@extends('layouts.app')

@section('title', 'Department KPI')

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
                <h1 class="text-2xl font-bold text-slate-800">Department Key Performance Indicators</h1>
                <p class="text-slate-500 mt-1">Detailed metrics breakdown and performance scorecards by department.</p>
            </div>
            <div class="flex items-center gap-3">
                <select class="text-sm font-semibold text-slate-700 border border-slate-300 rounded-xl px-4 py-2 bg-white outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Departments</option>
                    <option>Production</option>
                    <option>Quality Assurance (QA)</option>
                    <option>Maintenance</option>
                    <option>Human Resources (HR)</option>
                </select>
            </div>
        </div>

        <!-- Grid of Department KPI Scorecards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Production Department Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-blue-50/50 to-indigo-50/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                                <i class="fa-solid fa-industry"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Production</h3>
                                <p class="text-xs text-slate-500">Target Achievement: 94.2%</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            On Track
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Overall Equipment Effectiveness (OEE)</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800 text-sm">88.5%</span>
                            <span class="text-xs text-slate-400">/ 85%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Production Plan Adherence</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800 text-sm">96.8%</span>
                            <span class="text-xs text-slate-400">/ 98%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Scrap & Waste Rate</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-emerald-600 text-sm">0.82%</span>
                            <span class="text-xs text-slate-400">/ < 1.2%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quality Assurance Department Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/50 to-teal-50/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center">
                                <i class="fa-solid fa-clipboard-check"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Quality Assurance (QA)</h3>
                                <p class="text-xs text-slate-500">Target Achievement: 97.5%</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            On Track
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Internal Audit Schedule Adherence</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800 text-sm">100%</span>
                            <span class="text-xs text-slate-400">/ 100%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">First Time Quality (FTQ)</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800 text-sm">99.1%</span>
                            <span class="text-xs text-slate-400">/ 98.5%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Avg Corrective Action Closure Time</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800 text-sm">4.2 days</span>
                            <span class="text-xs text-slate-400">/ < 5 days</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Department Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-amber-50/50 to-orange-50/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center">
                                <i class="fa-solid fa-wrench"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Maintenance</h3>
                                <p class="text-xs text-slate-500">Target Achievement: 86.4%</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            Attention Needed
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Mean Time To Repair (MTTR)</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-amber-600 text-sm">48 mins</span>
                            <span class="text-xs text-slate-400">/ < 40 mins</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Preventive Maintenance (PM) Adherence</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800 text-sm">92.4%</span>
                            <span class="text-xs text-slate-400">/ 95%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Unplanned Downtime Hours</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-amber-600 text-sm">12.5 hrs</span>
                            <span class="text-xs text-slate-400">/ < 10 hrs</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HSE / Human Resources Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-purple-50/50 to-indigo-50/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">HSE & Human Resources</h3>
                                <p class="text-xs text-slate-500">Target Achievement: 100%</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            Excellent
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Work-Related Accidents</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-emerald-600 text-sm">0 cases</span>
                            <span class="text-xs text-slate-400">/ 0 cases</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Safety Genba Adherence</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800 text-sm">100%</span>
                            <span class="text-xs text-slate-400">/ 100%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Employee Training Hours (HSE)</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800 text-sm">4.5 hrs/m</span>
                            <span class="text-xs text-slate-400">/ 4.0 hrs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
