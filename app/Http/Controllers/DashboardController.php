<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function data_cards()
    {
        // 1. Findings Open: evidence IS NULL AND status IS NULL, IsDelete = 0
        $findingsOpen = DB::connection('sqlsrv')->table('GenbaProcAuditDtl as a')
            ->leftJoin('GenbaProcAudit as b', 'b.SysID', '=', 'a.genba_id')
            ->where('b.IsDelete', 0)
            ->whereNotNull('a.asign_to_dept')
            ->whereNotNull('a.findings')
            ->where(function ($q) {
                $q->whereNull('a.evidence')->orWhere('a.evidence', '0');
            })
            ->where(function ($q) {
                $q->whereNull('a.corrective_action')->orWhere('a.corrective_action', '0');
            })
            ->count();

        // 2. Need Approve: evidence = '1' AND status = '1' AND verification_result IS NULL, IsDelete = 0
        $needApprove = DB::connection('sqlsrv')->table('GenbaProcAuditDtl as a')
            ->leftJoin('GenbaProcAudit as b', 'b.SysID', '=', 'a.genba_id')
            ->where('b.IsDelete', 0)
            ->whereNotNull('a.findings')
            ->where('a.evidence', '1')
            ->where('a.corrective_action', '1')
            ->where(function ($q) {
                $q->whereNull('a.verification_result')->orWhere('a.verification_result', '0');
            })
            ->count();

        // 3. Due Date (Overdue): due_date < today AND (evidence is null/0 OR corrective_action is null/0)
        $dueDateCount = DB::connection('sqlsrv')->table('GenbaProcAuditDtl as a')
            ->leftJoin('GenbaProcAudit as b', 'b.SysID', '=', 'a.genba_id')
            ->where('b.IsDelete', 0)
            ->whereNotNull('a.findings')
            ->whereDate('a.due_date', '<', now())
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNull('a.evidence')->orWhere('a.evidence', '0');
                })->orWhere(function ($sub) {
                    $sub->whereNull('a.corrective_action')->orWhere('a.corrective_action', '0');
                });
            })
            ->count();

        // 4. Closed: evidence='1' AND corrective_action='1' AND verification_result='1'
        $findingsClose = DB::connection('sqlsrv')->table('GenbaProcAuditDtl as a')
            ->leftJoin('GenbaProcAudit as b', 'b.SysID', '=', 'a.genba_id')
            ->where('b.IsDelete', 0)
            ->whereNotNull('a.findings')
            ->where('a.evidence', '1')
            ->where('a.corrective_action', '1')
            ->where('a.verification_result', '1')
            ->count();

        // 5. All Findings: findings is not null, IsDelete = 0
        $allFindings = DB::connection('sqlsrv')->table('GenbaProcAuditDtl as a')
            ->leftJoin('GenbaProcAudit as b', 'b.SysID', '=', 'a.genba_id')
            ->where('b.IsDelete', 0)
            ->whereNotNull('a.findings')
            ->count();

        return response()->json([
            'findingsOpen' => $findingsOpen,
            'needApprove' => $needApprove,
            'dueDateCount' => $dueDateCount,
            'findingsClose' => $findingsClose,
            'allFindings' => $allFindings
        ]);
    }
    public function table(Request $request)
    {
        $search = $request->front_table_search;
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $auditor = $request->auditor;
        $columns = array(
            0 => 'a.SysID',
            1 => 'DocNum',
            2 => 'a.Path',
            3 => 'b.Date',
            4 => 'b.Area_Checked',
            5 => 'a.findings',
            6 => 'b.Auditor',
            7 => 'a.status',
            8 => 'a.SysID'
        );

        $totalData = \App\Models\GenbaManagement::get_genba_mng_activity_list($search, $date_from, $date_to, $auditor)->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = ($request->input('order.0.column') == 0 ? $columns[0] : $columns[$request->input('order.0.column')]);
        $dir = ($request->input('order.0.column') == 0 ? 'desc' : $request->input('order.0.dir'));

        if (empty($search)) {
            $posts = \App\Models\GenbaManagement::get_genba_mng_activity_list($search, $date_from, $date_to, $auditor)
                ->offset($start)
                ->limit($limit)
                ->reorder($order, $dir)
                ->get();
        } else {
            $posts = \App\Models\GenbaManagement::get_genba_mng_activity_list($search, $date_from, $date_to, $auditor)
                ->offset($start)
                ->limit($limit)
                ->reorder($order, $dir)
                ->get();
            $totalFiltered = \App\Models\GenbaManagement::get_genba_mng_activity_list($search, $date_from, $date_to, $auditor)->count();
        }

        $data = array();
        if (!empty($posts)) {
            $no = $start;
            foreach ($posts as $post) {
                $no++;
                $trc_id = \Illuminate\Support\Facades\Crypt::encryptString($post->SysID);
                $sys_id = "'" . str_replace("=", "-", $trc_id) . '_' . $no . "'";

                $verification_result = $post->verification_result;

                // Action Button Logic
                if ($verification_result != '' && $verification_result != null) {
                    // CLOSED -> Show Photo Icon for Before/After View
                    $findingsEnc = rawurlencode($post->findings); // URL encode to safely pass to JS
                    $commentEnc = rawurlencode($post->execution_comment);
                    $pathBefore = $post->Path;
                    $pathAfter = $post->execution_path;

                    $button = '<div class="flex items-center justify-center gap-2">
                                <button type="button" title="View Photos" class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-100 hover:text-blue-600 transition-all duration-200" 
                                    onclick="viewGenbaImages(\'' . $pathBefore . '\', \'' . $pathAfter . '\', \'' . $findingsEnc . '\', \'' . $commentEnc . '\')">
                                    <span class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </span>
                                </button>
                           </div>';
                } else {
                    // NOT CLOSED -> Show Link/Preview Icon (Existing Logic)
                    $button = '<div class="flex items-center justify-center gap-2">
                                <button type="button" title="Preview" class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-100 hover:text-blue-600 transition-all duration-200" id="btn_form_view_doc_' . $no . '" onclick="document_preview(' . $sys_id . ',' . $no . ')">
                                    <span id="svg_form_view_doc_' . $no . '" class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                        </svg>
                                    </span>
                                    <span id="spinner_form_view_doc_' . $no . '" class="hidden animate-spin rounded-full h-4 w-4 border-b-2 border-current"></span>
                                </button>
                           </div>';
                }

                $date = \Carbon\Carbon::parse($post->Date)->format('d M Y');
                $corrective_action = $post->corrective_action;
                $execution_comment = $post->execution_comment;
                $verification_result = $post->verification_result;
                $execution_path = $post->execution_path;

                // Stepper Logic
                $line = '<div class="w-8 h-0.5 bg-gray-200"></div>';
                $activeLine = '<div class="w-8 h-0.5 bg-blue-200"></div>';

                // Icons
                $emptyStep = '<div class="w-10 h-10 rounded-full border border-gray-200 bg-white"></div>';
                $activeStep = '<div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                               </div>';

                if ($execution_comment == '' || $execution_comment == null) {
                    // Need Action Plan
                    $steps = $emptyStep . $line . $emptyStep . $line . $emptyStep;
                } else if ($execution_path == '' || $execution_path == null) {
                    // Need Evidence
                    $steps = $activeStep . $line . $emptyStep . $line . $emptyStep;
                } else if ($verification_result == '' || $verification_result == null) {
                    // Process Verification
                    $steps = $activeStep . $activeLine . $activeStep . $line . $emptyStep;
                } else {
                    // Closed
                    $steps = $activeStep . $activeLine . $activeStep . $activeLine . $activeStep;
                }

                $status = '<div class="flex items-center gap-0.5">' . $steps . '</div>';

                $nestedData['no'] = $no;
                $nestedData['DocNum'] = $post->DocNum;
                $nestedData['date'] = $date;

                $nestedData['area_checked'] = $post->Area_Checked;
                $nestedData['path'] = $post->Path;
                $nestedData['findings'] = $post->findings;
                $nestedData['due_date'] = $post->due_date;
                $nestedData['execution_comment'] = $post->execution_comment;
                $nestedData['execution_path'] = '<button class="btn btn-sm w-9 h-9 flex items-center justify-center bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors" id="btn_corrective_path_' . $no . '" onclick="btn_corrective(' . $sys_id . ',' . $no . ')"><i class="fa fa-camera"></i></button>';
                $nestedData['status'] = $status;
                $nestedData['action'] = $button;
                $nestedData['auditor'] = $post->Auditor;
                $data[] = $nestedData;
            }
        } else {

            $nestedData['no'] = '';
            $nestedData['area_checked'] = '';
            $nestedData['path'] = '';
            $nestedData['date'] = '';
            $nestedData['status'] = '';
            $nestedData['action'] = '';
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }
}
