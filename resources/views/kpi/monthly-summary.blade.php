@extends('layouts.app')

@section('title', 'Monthly Summary')

@section('content')
@include('layouts.sidebar')
@include('components.toast')

<!-- Main Content -->
<div class="lg:ml-20 min-h-screen flex flex-col bg-slate-50">
    @include('layouts.header')

    <!-- Page Content -->
    <main class="flex-1 p-6">
        <!-- Page Title & Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Monthly Summary</h1>
                <p class="text-slate-500 mt-1">Detailed track record of quality metrics and KPI progress across months.</p>
            </div>
            <div class="flex items-center gap-3">
                <select class="text-sm font-semibold text-slate-700 border border-slate-300 rounded-xl px-4 py-2 bg-white outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Year 2026</option>
                    <option>Year 2025</option>
                </select>
            </div>
        </div>

        <!-- Monthly Trends Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-lg">Metric Comparison (2026)</h3>
                <span class="text-xs text-slate-400">Values in percentage (%) or absolute units</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">Metric Name</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">Target</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">Jan</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">Feb</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">Mar</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">Apr</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">May</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">Jun</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">Jul</th>
                            <th class="p-4 text-xs font-semibold text-slate-500 uppercase">YTD Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <!-- Row 1 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-medium text-slate-800">QMS Compliance Rate</td>
                            <td class="p-4 text-slate-500 font-medium">95.0%</td>
                            <td class="p-4 text-slate-700">94.8%</td>
                            <td class="p-4 text-slate-700">95.2%</td>
                            <td class="p-4 text-slate-700">96.0%</td>
                            <td class="p-4 text-slate-700">95.9%</td>
                            <td class="p-4 text-slate-700">97.2%</td>
                            <td class="p-4 text-slate-700">97.8%</td>
                            <td class="p-4 text-slate-800 font-semibold text-blue-600">98.2%</td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Met
                                </span>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-medium text-slate-800">Audit Finding Closure</td>
                            <td class="p-4 text-slate-500 font-medium">90.0%</td>
                            <td class="p-4 text-slate-700">89.2%</td>
                            <td class="p-4 text-slate-700">90.5%</td>
                            <td class="p-4 text-slate-700">91.4%</td>
                            <td class="p-4 text-slate-700">92.0%</td>
                            <td class="p-4 text-slate-700">93.5%</td>
                            <td class="p-4 text-slate-700">94.1%</td>
                            <td class="p-4 text-slate-800 font-semibold text-blue-600">94.5%</td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Met
                                </span>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-medium text-slate-800">Product Defect Rate</td>
                            <td class="p-4 text-slate-500 font-medium">< 0.50%</td>
                            <td class="p-4 text-slate-700">0.34%</td>
                            <td class="p-4 text-slate-700">0.30%</td>
                            <td class="p-4 text-slate-700">0.42%</td>
                            <td class="p-4 text-slate-700">0.39%</td>
                            <td class="p-4 text-slate-700">0.28%</td>
                            <td class="p-4 text-slate-700">0.26%</td>
                            <td class="p-4 text-slate-800 font-semibold text-blue-600">0.24%</td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Met
                                </span>
                            </td>
                        </tr>

                        <!-- Row 4 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-medium text-slate-800">Unplanned Downtime</td>
                            <td class="p-4 text-slate-500 font-medium">< 10 hrs</td>
                            <td class="p-4 text-slate-700">8.5 hrs</td>
                            <td class="p-4 text-slate-700">9.2 hrs</td>
                            <td class="p-4 text-slate-700">11.4 hrs</td>
                            <td class="p-4 text-slate-700">10.8 hrs</td>
                            <td class="p-4 text-slate-700">9.5 hrs</td>
                            <td class="p-4 text-slate-700">13.2 hrs</td>
                            <td class="p-4 text-slate-800 font-semibold text-blue-600">12.5 hrs</td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-semibold bg-rose-100 text-rose-800 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Critical
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
