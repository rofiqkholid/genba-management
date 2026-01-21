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
}
