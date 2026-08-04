<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AgentChatController extends Controller
{
    public function index()
    {
        return view('agent.room-chat-agent');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = strtolower($request->input('message'));
        $today = Carbon::now()->toDateString();

        // 1. Dynamically retrieve unique department codes and detect all mentioned departments
        $detectedDepts = [];
        try {
            $dbDepts = DB::table('CsAuditCar')
                ->whereNotNull('department')
                ->where('department', '<>', '')
                ->distinct()
                ->pluck('department')
                ->toArray();
                
            foreach ($dbDepts as $dept) {
                $lowerDept = strtolower($dept);
                // Match word boundary
                if (preg_match('/\b' . preg_quote($lowerDept, '/') . '\b/i', $message)) {
                    $detectedDepts[] = $dept;
                }
            }
            
            // Fallback alias checking for HR
            if (empty($detectedDepts) && (str_contains($message, 'hr') || str_contains($message, 'human resource'))) {
                foreach ($dbDepts as $dept) {
                    if (str_starts_with(strtolower($dept), 'hr')) {
                        $detectedDepts[] = $dept;
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to hardcoded check if query fails
            $departments = ['qc', 'hrga', 'hr', 'mtc', 'sls', 'tmc', 'pe', 'pur', 'qms', 'stp', 'tmf', 'ppic', 'lh', 'fa', 'ict'];
            foreach ($departments as $dept) {
                if (preg_match('/\b' . preg_quote($dept, '/') . '\b/i', $message)) {
                    $actualDept = ($dept === 'hr') ? 'HRGA' : strtoupper($dept);
                    if (!in_array($actualDept, $detectedDepts)) {
                        $detectedDepts[] = $actualDept;
                    }
                }
            }
        }

        // 2. Check for OVERDUE query (supports common typos like "overude")
        if (str_contains($message, 'overdue') || str_contains($message, 'overude') || str_contains($message, 'lewat batas') || str_contains($message, 'terlambat')) {
            $query = DB::table('CsAuditCar as a')
                ->leftJoin('CsAuditDetail as b', 'b.id', '=', 'a.audit_detail_id')
                ->leftJoin('CsAuditHeader as c', 'c.id', '=', 'b.audit_header_id')
                ->where('a.status', '<>', 'Closed')
                ->whereDate('a.due_date', '<', $today)
                ->whereNotNull('a.clause_title')
                ->where('a.clause_title', '<>', '')
                ->whereNotExists(function($sub) {
                    $sub->select(DB::raw(1))
                        ->from('CsAuditAction as act')
                        ->whereColumn('act.audit_car_id', 'a.id')
                        ->whereIn('act.action_status', ['open_verif', 'approve_superior', 'verified']);
                });

            if (!empty($detectedDepts)) {
                $query->whereIn('a.department', $detectedDepts);
            }

            $overdueList = $query->select('a.id', 'a.req_number', 'a.finding_category', 'a.due_date', 'a.department', 'a.finding')
                ->get();

            $count = $overdueList->count();
            $deptSuffix = !empty($detectedDepts) ? " untuk departemen <strong>" . implode(', ', $detectedDepts) . "</strong>" : "";

            if ($count === 0) {
                $response = "Tidak ada temuan audit yang berstatus <strong>Overdue</strong> saat ini{$deptSuffix}. Semua tindakan berjalan sesuai jadwal!";
            } else {
                $response = "<p class='mb-2'>Terdapat <strong>{$count} temuan</strong> berstatus <strong>Overdue</strong>{$deptSuffix}:</p>";
                $response .= '<div class="overflow-x-auto border border-slate-200 rounded-xl">';
                $response .= '<table class="min-w-full divide-y divide-slate-200 text-[11px] text-left">';
                $response .= '  <thead class="bg-slate-50 sticky top-0">';
                $response .= '    <tr>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Doc No</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Dept</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Cat</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Finding</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Due Date</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Status</th>';
                $response .= '    </tr>';
                $response .= '  </thead>';
                $response .= '  <tbody class="divide-y divide-slate-100 bg-white">';
                foreach ($overdueList as $item) {
                    $formattedDate = $item->due_date ? Carbon::parse($item->due_date)->format('d/m/Y') : '-';
                    $encryptedId = $this->encryptCarId($item->id);
                    $link = route('internal_audit.action_report.preview', $encryptedId);
                    
                    $response .= "    <tr class='hover:bg-slate-50/50'>";
                    $response .= "      <td class='px-3 py-2 font-medium whitespace-nowrap'><a href='{$link}' target='_blank' class='text-blue-600 hover:underline'>{$item->req_number}</a></td>";
                    $response .= "      <td class='px-3 py-2 text-slate-600 font-medium'>{$item->department}</td>";
                    $response .= "      <td class='px-3 py-2 text-slate-600'>{$item->finding_category}</td>";
                    $response .= "      <td class='px-3 py-2 text-slate-600 min-w-[150px]'>{$item->finding}</td>";
                    $response .= "      <td class='px-3 py-2 text-slate-600 whitespace-nowrap'>{$formattedDate}</td>";
                    $response .= "      <td class='px-3 py-2 whitespace-nowrap'><span class='px-1.5 py-0.5 rounded text-[9px] font-bold bg-red-100 text-red-800'>Overdue</span></td>";
                    $response .= "    </tr>";
                }
                $response .= '  </tbody>';
                $response .= '</table>';
                $response .= '</div>';
            }

            return response()->json([
                'status' => 'success',
                'response' => $response
            ]);
        }

        // 3. Check for NEED VERIFICATION / BELUM APPROVE query
        if (str_contains($message, 'belum di approve') || str_contains($message, 'belum diapprove') || str_contains($message, 'belum di-approve') || str_contains($message, 'need verif') || str_contains($message, 'perlu verifikasi') || str_contains($message, 'verif')) {
            $query = DB::table('CsAuditCar as a')
                ->leftJoin('CsAuditDetail as b', 'b.id', '=', 'a.audit_detail_id')
                ->leftJoin('CsAuditHeader as c', 'c.id', '=', 'b.audit_header_id')
                ->whereNotNull('a.clause_title')
                ->where('a.clause_title', '<>', '')
                ->where(function($q) {
                    $q->where(function($q2) {
                        $q2->where('a.status', 'Closed')
                           ->whereNull('a.qmr_approved_at');
                    })->orWhere(function($q2) {
                        $q2->where('a.status', '<>', 'Closed')
                           ->whereExists(function($sub) {
                               $sub->select(DB::raw(1))
                                   ->from('CsAuditAction as act')
                                   ->whereColumn('act.audit_car_id', 'a.id')
                                   ->whereIn('act.action_status', ['open_verif', 'approve_superior']);
                           });
                    });
                });

            if (!empty($detectedDepts)) {
                $query->whereIn('a.department', $detectedDepts);
            }

            $needVerifList = $query->select('a.id', 'a.req_number', 'a.finding_category', 'a.department', 'a.finding')
                ->get();

            $count = $needVerifList->count();
            $deptSuffix = !empty($detectedDepts) ? " untuk departemen <strong>" . implode(', ', $detectedDepts) . "</strong>" : "";

            if ($count === 0) {
                $response = "Tidak ada temuan audit yang sedang menunggu persetujuan (<strong>Need Verification</strong>) saat ini{$deptSuffix}.";
            } else {
                $response = "<p class='mb-2'>Terdapat <strong>{$count} temuan</strong> menunggu persetujuan (Need Verification){$deptSuffix}:</p>";
                $response .= '<div class="overflow-x-auto border border-slate-200 rounded-xl">';
                $response .= '<table class="min-w-full divide-y divide-slate-200 text-[11px] text-left">';
                $response .= '  <thead class="bg-slate-50 sticky top-0">';
                $response .= '    <tr>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Doc No</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Dept</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Cat</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Finding</th>';
                $response .= '      <th class="px-3 py-2 font-semibold text-slate-500">Status</th>';
                $response .= '    </tr>';
                $response .= '  </thead>';
                $response .= '  <tbody class="divide-y divide-slate-100 bg-white">';
                foreach ($needVerifList as $item) {
                    $encryptedId = $this->encryptCarId($item->id);
                    $link = route('internal_audit.action_report.preview', $encryptedId);

                    $response .= "    <tr class='hover:bg-slate-50/50'>";
                    $response .= "      <td class='px-3 py-2 font-medium whitespace-nowrap'><a href='{$link}' target='_blank' class='text-blue-600 hover:underline'>{$item->req_number}</a></td>";
                    $response .= "      <td class='px-3 py-2 text-slate-600 font-medium'>{$item->department}</td>";
                    $response .= "      <td class='px-3 py-2 text-slate-600'>{$item->finding_category}</td>";
                    $response .= "      <td class='px-3 py-2 text-slate-600 min-w-[180px]'>{$item->finding}</td>";
                    $response .= "      <td class='px-3 py-2 whitespace-nowrap'><span class='px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800'>Need Verif</span></td>";
                    $response .= "    </tr>";
                }
                $response .= '  </tbody>';
                $response .= '</table>';
                $response .= '</div>';
            }

            return response()->json([
                'status' => 'success',
                'response' => $response
            ]);
        }

        // 4. Check for total / count findings query
        if (str_contains($message, 'jumlah temuan') || str_contains($message, 'total temuan') || str_contains($message, 'berapa temuan')) {
            $queryTotal = DB::table('CsAuditCar')
                ->where('status', '<>', 'Closed')
                ->whereNotNull('clause_title')
                ->where('clause_title', '<>', '');

            $queryGroup = DB::table('CsAuditCar')
                ->where('status', '<>', 'Closed')
                ->whereNotNull('clause_title')
                ->where('clause_title', '<>', '')
                ->select('finding_category', DB::raw('count(*) as total'))
                ->groupBy('finding_category');

            if (!empty($detectedDepts)) {
                $queryTotal->whereIn('department', $detectedDepts);
                $queryGroup->whereIn('department', $detectedDepts);
            }

            $totalActive = $queryTotal->count();
            $byCategory = $queryGroup->get();

            $catText = "";
            foreach ($byCategory as $cat) {
                $catText .= "<li><strong>{$cat->finding_category}:</strong> {$cat->total} temuan</li>";
            }

            $deptSuffix = !empty($detectedDepts) ? " untuk departemen <strong>" . implode(', ', $detectedDepts) . "</strong>" : "";

            $response = "<p>Jumlah temuan audit yang masih berstatus <strong>Open</strong> saat ini{$deptSuffix} adalah <strong>{$totalActive} temuan</strong>.</p>";
            if (!empty($catText)) {
                $response .= "<p class='mt-1.5'>Rincian berdasarkan kategori:</p><ul class='list-disc list-inside text-xs mt-1 space-y-0.5 text-slate-600'>{$catText}</ul>";
            }

            return response()->json([
                'status' => 'success',
                'response' => $response
            ]);
        }

        // 5. Default helpful guide
        $response = "
            <p>Halo! Saya adalah <strong>Asisten GRACE</strong>. Saya dapat membantu mencari informasi temuan audit langsung dari database sistem secara real-time.</p>
            <p class='mt-2'>Silakan coba tanyakan hal-hari seperti:</p>
            <ul class='list-disc list-inside text-xs mt-1.5 space-y-1 text-slate-600'>
                <li><button onclick=\"usePrompt('Ada berapa temuan yang overdue di departemen QC?')\" class='text-blue-600 hover:underline text-left font-semibold'>\"Ada berapa temuan yang overdue di departemen QC?\"</button></li>
                <li><button onclick=\"usePrompt('Temuan yang belum di-approve untuk HR?')\" class='text-blue-600 hover:underline text-left font-semibold'>\"Temuan yang belum di-approve untuk HR?\"</button></li>
                <li><button onclick=\"usePrompt('Berapa total temuan saat ini?')\" class='text-blue-600 hover:underline text-left font-semibold'>\"Berapa total temuan saat ini?\"</button></li>
            </ul>
        ";

        return response()->json([
            'status' => 'success',
            'response' => $response
        ]);
    }

    private function encryptCarId($id)
    {
        $s1 = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $s2 = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $mid = str_pad($id, 6, '0', STR_PAD_LEFT);
        $plain = $s1 . $mid . $s2;
        
        $appKey = config('app.key', 'qms_secret_fallback_key_123');
        $key = substr(md5($appKey), 0, 16);
        
        $encrypted = '';
        for ($i = 0; $i < 16; $i++) {
            $encrypted .= chr(ord($plain[$i]) ^ ord($key[$i]));
        }
        
        $hex = bin2hex($encrypted);
        
        return sprintf('%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }
}
