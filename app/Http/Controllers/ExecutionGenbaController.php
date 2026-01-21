<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GenbaManagement;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExecutionGenbaController extends Controller
{
    public function index()
    {
        return view('execution_genba.index');
    }

    public function table(Request $request)
    {
        $search = $request->search;
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        // For Verification/Approval, we might want to see all or filter by specific status
        // Reusing logic from GenbaManagementController for consistency

        $columns = array(
            0 => 'a.SysID',
            1 => 'DocNum',
            2 => 'a.Path',
            3 => 'b.Date',
            4 => 'a.asign_to_dept',
            5 => 'a.findings',
            6 => 'b.Auditor',
            7 => 'a.status',
            8 => 'a.SysID'
        );

        // Using the same model method for now. 
        // We might need to filter for 'Proccess Verification' status specifically if this is strictly for approval.
        // But usually management wants to see everything or filter.
        // Let's assume standard list first.
        // Filter: corrective_action = 1 AND evidence = 1 (Proccess Verification)
        $query = GenbaManagement::get_genba_approval_list($search, $date_from, $date_to, null);
        $query->where('a.corrective_action', 1)->where('a.evidence', 1);

        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = ($request->input('order.0.column') == 0 ? $columns[0] : $columns[$request->input('order.0.column')]);
        $dir = ($request->input('order.0.column') == 0 ? 'desc' : $request->input('order.0.dir'));

        $postsQuery = GenbaManagement::get_genba_approval_list($search, $date_from, $date_to, null);
        $postsQuery->where('a.corrective_action', 1)->where('a.evidence', 1);

        $posts = $postsQuery->offset($start)
            ->limit($limit)
            ->reorder($order, $dir)
            ->get();

        $data = array();
        if (!empty($posts)) {
            $no = $start;
            foreach ($posts as $post) {
                $no++;
                $trc_id = Crypt::encryptString($post->SysID);
                $sys_id = "'" . str_replace("=", "-", $trc_id) . '_' . $no . "'";

                $execution_comment = $post->execution_comment;
                $verification_result = $post->verification_result;
                $execution_path = $post->execution_path;
                $date = Carbon::parse($post->Date)->format('d M Y');

                // Action Buttons - customized for Execution/Approval
                if ($verification_result == 1) {
                    // Rollback Button
                    $button = '<div class="flex items-center justify-center gap-2">
                                <button type="button" title="Rollback" class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 hover:text-amber-700 transition-all duration-200" onclick="rollbackGenba(' . $sys_id . ')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                        <path d="M3 3v5h5"></path>
                                    </svg>
                                </button>
                           </div>';
                } else {
                    // Approve Button
                    $button = '<div class="flex items-center justify-center gap-2">
                                <button type="button" title="Approve" class="w-10 h-10 flex items-center justify-center rounded-xl bg-green-50 text-green-600 border border-green-200 hover:bg-green-100 hover:text-green-700 transition-all duration-200" onclick="approveGenba(' . $sys_id . ')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </button>
                           </div>';
                }

                if ($execution_comment == '' || $execution_comment == null) {
                    $status = 'Need Action Plan';
                    $badge = 'inline-flex items-center px-2.5 font-semibold py-1 bg-amber-50 text-amber-600 rounded-md text-sm font-base border border-amber-200';
                } else if ($execution_path == '' || $execution_path == null) {
                    $status = 'Need Evidence';
                    $badge = 'inline-flex items-center px-2.5 font-semibold py-1 bg-amber-50 text-amber-700 rounded-md text-sm font-base border border-amber-200';
                } else if ($verification_result == '' || $verification_result == null) {
                    $status = 'Proccess Verification';
                    $badge = 'inline-flex items-center px-2.5 font-semibold py-1 bg-blue-50 text-blue-700 rounded-md text-sm font-base border border-blue-200';
                } else {
                    $status = "Close";
                    $badge = 'inline-flex items-center px-2.5 font-semibold py-1 bg-emerald-50 text-emerald-700 rounded-md text-sm font-base border border-emerald-200';
                }

                $nestedData['no'] = $no;
                $nestedData['DocNum'] = $post->DocNum;
                $nestedData['path'] = $post->Path;
                $nestedData['execution_path'] = $post->execution_path;
                $nestedData['date'] = $date;
                $nestedData['asign_to_dept'] = $post->asign_to_dept;
                $nestedData['findings'] = $post->findings;
                $nestedData['status'] = '<span class="' . $badge . '">' . $status . '</span>';
                $nestedData['action'] = $button;
                $nestedData['auditor'] = $post->Auditor;

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }
    public function approve(Request $request)
    {
        try {
            $id = $request->id;
            // ... (decryption logic same as before, simplified for this snippet)
            $parts = explode('_', $id);
            if (count($parts) > 1 && is_numeric(end($parts))) {
                array_pop($parts);
                $encrypted_id = implode('_', $parts);
            } else {
                $encrypted_id = $id;
            }
            $encrypted_id = str_replace("-", "=", $encrypted_id);
            $decrypted_id = Crypt::decryptString($encrypted_id);

            DB::connection('sqlsrv')->table('GenbaProcAuditDtl')
                ->where('SysID', $decrypted_id)
                ->update([
                    'verification_result' => 1,
                    'updated_at' => Carbon::now()
                ]);

            return response()->json(['status' => 'success', 'message' => 'Approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function rollback(Request $request)
    {
        try {
            $id = $request->id;

            // Decryption logic
            $parts = explode('_', $id);
            if (count($parts) > 1 && is_numeric(end($parts))) {
                array_pop($parts);
                $encrypted_id = implode('_', $parts);
            } else {
                $encrypted_id = $id;
            }
            $encrypted_id = str_replace("-", "=", $encrypted_id);
            $decrypted_id = Crypt::decryptString($encrypted_id);

            // Update the record: Set verification_result to NULL
            DB::connection('sqlsrv')->table('GenbaProcAuditDtl')
                ->where('SysID', $decrypted_id)
                ->update([
                    'verification_result' => null,
                    'updated_at' => Carbon::now()
                ]);

            return response()->json(['status' => 'success', 'message' => 'Rollback successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
