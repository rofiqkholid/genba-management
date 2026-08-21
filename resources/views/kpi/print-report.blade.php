@extends('layouts.app')

@section('title', 'Print Report')

@section('content')
@include('layouts.sidebar')
@include('components.toast')

<!-- Main Content -->
<div class="lg:ml-20 min-h-screen flex flex-col bg-slate-50">
    @include('layouts.header')

    <!-- Page Content -->
    <main class="flex-1 p-6">
        <!-- Page Title & Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Print KPI Report</h1>
            <p class="text-slate-500 mt-1">Export, generate, and print official performance reports.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Settings Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm lg:col-span-1 h-fit">
                <h3 class="font-bold text-slate-800 text-lg mb-4">Report Configurations</h3>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Report Template</label>
                        <select class="w-full text-sm font-semibold text-slate-700 border border-slate-300 rounded-xl px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Annual KPI Summary (2026)</option>
                            <option>Monthly Department Performance</option>
                            <option>Quality & Compliance Index</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Target Department</label>
                        <select class="w-full text-sm font-semibold text-slate-700 border border-slate-300 rounded-xl px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-blue-500">
                            <option>All Departments</option>
                            <option>Production</option>
                            <option>Quality Assurance (QA)</option>
                            <option>Maintenance</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Start Month</label>
                            <select class="w-full text-sm font-semibold text-slate-700 border border-slate-300 rounded-xl px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-blue-500">
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">End Month</label>
                            <select class="w-full text-sm font-semibold text-slate-700 border border-slate-300 rounded-xl px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-blue-500">
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                        <button type="button" onclick="window.print()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-medium transition-colors shadow-sm shadow-blue-200">
                            <i class="fa-solid fa-print"></i>
                            Print Document
                        </button>
                        <button type="button" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-white text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 text-sm font-medium transition-colors">
                            <i class="fa-solid fa-file-pdf"></i>
                            Export to PDF
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview Card -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm lg:col-span-2">
                <h3 class="font-bold text-slate-800 text-lg mb-4 pb-2 border-b border-slate-100">Live Preview</h3>
                
                <div class="border border-dashed border-slate-200 rounded-2xl p-6 bg-slate-50/50">
                    <div class="bg-white p-8 border border-slate-200 shadow-lg rounded-lg max-w-2xl mx-auto space-y-6 text-slate-800">
                        <!-- Report Header -->
                        <div class="flex items-center justify-between border-b-2 border-slate-800 pb-4">
                            <div>
                                <h2 class="text-xl font-bold tracking-wide uppercase">QEMS GRACE SYSTEM</h2>
                                <p class="text-xs text-slate-500">Key Performance Indicator Official Report</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-400">Printed: 2026-08-10</span>
                            </div>
                        </div>

                        <!-- Report Summary Info -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="font-semibold text-slate-500 uppercase">Report Type</p>
                                <p class="text-sm font-bold text-slate-800">Annual KPI Summary (2026)</p>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-500 uppercase">Target Audience</p>
                                <p class="text-sm font-bold text-slate-800">Board of Directors & Management</p>
                            </div>
                        </div>

                        <!-- Mini Preview Table -->
                        <table class="w-full text-left text-xs border-collapse mt-4">
                            <thead>
                                <tr class="bg-slate-100 border-b border-slate-300">
                                    <th class="p-2 font-bold uppercase">KPI Category</th>
                                    <th class="p-2 font-bold uppercase">Target</th>
                                    <th class="p-2 font-bold uppercase">Actual</th>
                                    <th class="p-2 font-bold text-right uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="p-2 font-medium">QMS Compliance Rate</td>
                                    <td class="p-2">95.0%</td>
                                    <td class="p-2">98.2%</td>
                                    <td class="p-2 text-right text-emerald-600 font-bold">PASSED</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-medium">Audit Finding Closure</td>
                                    <td class="p-2">90.0%</td>
                                    <td class="p-2">94.5%</td>
                                    <td class="p-2 text-right text-emerald-600 font-bold">PASSED</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-medium">Product Defect Rate</td>
                                    <td class="p-2">&lt; 0.50%</td>
                                    <td class="p-2">0.24%</td>
                                    <td class="p-2 text-right text-emerald-600 font-bold">PASSED</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-medium">Unplanned Downtime</td>
                                    <td class="p-2">&lt; 10 hrs</td>
                                    <td class="p-2">12.5 hrs</td>
                                    <td class="p-2 text-right text-rose-600 font-bold">FAILED</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
