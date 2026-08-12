@extends('layouts.app')

@php
    $hideCentralToast = true;
@endphp

@section('title', 'Detail KPI Company')

@section('content')
@include('layouts.sidebar')
@include('components.toast')
@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast("{{ session('info') }}", 'info');
    });
</script>
@endif

<!-- Main Content -->
<div class="lg:ml-20 min-h-screen flex flex-col bg-slate-50">
    @include('layouts.header')

    <!-- Page Content -->
    <main class="flex-1 p-6">
        <!-- Back Button & Page Title -->
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('kpi.company') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-slate-600"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail KPI Company</h1>
                <p class="text-slate-500 mt-1">Detailed performance tracking, objective details and monthly actual trend.</p>
            </div>
        </div>

        <!-- Detail Content -->
        <div class="space-y-6">
            <!-- Unified Detail Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
                <!-- Summary Info -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 pb-6 border-b border-slate-200">
                    <div class="lg:col-span-3">
                        <span class="text-slate-500 text-[10px] sm:text-xs tracking-wider block mb-1">KPI Objective</span>
                        <span class="text-sm font-semibold text-slate-700 block">{{ $kpi->no_kpi }} - {{ $kpi->objective }}</span>
                    </div>
                    <div class="lg:col-span-2">
                        <span class="text-slate-500 text-[10px] sm:text-xs tracking-wider block mb-1">Pillar / Dept</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $kpi->pillar ?? '-' }} / {{ $kpi->department_code }}</span>
                    </div>
                    <div class="lg:col-span-2">
                        <span class="text-slate-500 text-[10px] sm:text-xs tracking-wider block mb-1">Period / Target</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $kpi->periode }} / {{ $kpi->operator }} {{ $kpi->target }} {{ $kpi->unit }}</span>
                    </div>
                    <div class="lg:col-span-5 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-slate-500 text-[10px] sm:text-xs tracking-wider block mb-1">Calculation Method</span>
                            <span class="text-sm font-semibold text-slate-700">{{ $kpi->calculation_method }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Print PDF Button -->
                            <button type="button" class="inline-flex items-center justify-center gap-2 w-28 py-2 text-xs font-medium text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-colors shadow-sm">
                                <i class="fa-solid fa-file-pdf text-sm"></i>
                                Print
                            </button>
                            <button type="button" class="inline-flex items-center justify-center gap-2 w-28 py-2 text-xs font-medium text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-colors shadow-sm">
                                <i class="fa-solid fa-file-excel text-sm"></i>
                                Export Excel
                            </button>
                            <div class="w-px h-6 bg-slate-200 mx-1"></div>
                            <button type="button" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fa-solid fa-list-check text-sm"></i>
                                Manage Activity Plan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chart & Table section -->
                <div>
                <!-- Title Header -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-700">12-Month Performance Summary</h3>
                </div>

                <!-- Flex container for indicator and chart -->
                <div class="flex flex-col lg:flex-row items-center gap-6">
                    <!-- Target direction indicator (Left) -->
                    <div class="flex items-center gap-3 border-r border-slate-200 pr-6 lg:h-32">
                        <div class="flex items-center gap-2 border border-green-500 rounded-xl px-4 py-2 bg-green-50/30">
                            @if(in_array($kpi->operator, ['<=', '<']))
                                <!-- Down Arrow -->
                                <svg class="w-6 h-6 text-green-600 fill-current" viewBox="0 0 24 24">
                                    <path d="M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.58-5.59L4 12l8 8 8-8z"/>
                                </svg>
                            @else
                                <!-- Up Arrow -->
                                <svg class="w-6 h-6 text-green-600 fill-current" viewBox="0 0 24 24">
                                    <path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"/>
                                </svg>
                            @endif
                            <span class="text-xl font-normal text-green-600">Good</span>
                        </div>
                    </div>

                    <div class="flex-1 w-full min-w-0 relative h-[280px]">
                        @php
                            $actualList = [];
                            foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m) {
                                $act = $activities->firstWhere('bulan', $m);
                                $actualList[] = ($act && $act->actual !== null) ? (float) filter_var($act->actual, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : null;
                            }
                        @endphp
                        <canvas id="kpiPerformanceChart" data-actual='{!! json_encode($actualList) !!}' data-target="{{ $kpi->target }}" data-operator="{!! $kpi->operator !!}"></canvas>
                    </div>
                </div>

                <!-- Divider Line -->
                <div class="border-t border-slate-200 my-6"></div>

                <!-- Data Table -->
                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-sm text-left text-slate-700">
                        <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-3 border-r border-slate-200 whitespace-nowrap">Bulan / Component</th>
                                @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                                    <th class="p-3 text-center border-r border-slate-200">{{ $m }}</th>
                                @endforeach
                                <th class="p-3 text-center font-bold">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-200">
                                <td class="p-3 font-semibold border-r border-slate-200 whitespace-nowrap">Target ({{ $kpi->unit }})</td>
                                @php
                                    $targetSum = 0;
                                @endphp
                                @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                                    @php
                                        $targetSum += (float) filter_var($kpi->target, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    @endphp
                                    <td class="p-3 text-center border-r border-slate-200">{{ $kpi->target }}</td>
                                @endforeach
                                <td class="p-3 text-center font-bold">{{ $targetSum }}</td>
                            </tr>
                            <tr class="border-b border-slate-200">
                                <td class="p-3 font-semibold border-r border-slate-200 whitespace-nowrap">Actual ({{ $kpi->unit }})</td>
                                @php
                                    $actualSum = 0;
                                    $hasActual = false;
                                @endphp
                                @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                                    @php
                                        $act = $activities->firstWhere('bulan', $m);
                                    @endphp
                                    @if($act && $act->actual !== null)
                                        @php
                                            $val = (float) filter_var($act->actual, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                            $actualSum += $val;
                                            $hasActual = true;
                                        @endphp
                                        <td class="p-3 text-center border-r border-slate-200 font-semibold text-slate-800">{{ $act->actual }}</td>
                                    @else
                                        <td class="p-3 text-center text-slate-400 border-r border-slate-200">-</td>
                                    @endif
                                @endforeach
                                <td class="p-3 text-center font-bold">{{ $hasActual ? $actualSum : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-semibold border-r border-slate-200 whitespace-nowrap">Status</td>
                                @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                                    @php
                                        $act = $activities->firstWhere('bulan', $m);
                                    @endphp
                                    @if($act && $act->actual !== null)
                                        <td class="p-3 text-center border-r border-slate-200">
                                            @if($act->status === 'Achieved')
                                                <div class="inline-flex w-8 h-8 rounded-lg items-center justify-center bg-green-50 border border-slate-100 text-green-500" title="Achieved">
                                                    <i class="fas fa-circle text-[16px]"></i>
                                                </div>
                                            @else
                                                <div class="inline-flex w-8 h-8 rounded-lg items-center justify-center bg-red-50 border border-slate-100 text-red-500" title="Not Achieved">
                                                    <i class="fas fa-times text-xs font-bold"></i>
                                                </div>
                                            @endif
                                        </td>
                                    @else
                                        <td class="p-3 text-center text-slate-400 border-r border-slate-200">-</td>
                                    @endif
                                @endforeach
                                <td class="p-3 text-center">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Legend under Table -->
                <div class="flex items-center justify-center gap-6 mt-4 text-xs font-medium text-slate-500">
                    <div class="flex items-center gap-2">
                        <div class="inline-flex w-6 h-6 rounded-md items-center justify-center bg-green-50 border border-slate-100 text-green-500">
                            <i class="fas fa-circle text-[12px]"></i>
                        </div>
                        <span>Achieved</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="inline-flex w-6 h-6 rounded-md items-center justify-center bg-red-50 border border-slate-100 text-red-500">
                            <i class="fas fa-times text-xs"></i>
                        </div>
                        <span>Not Achieved</span>
                    </div>
                </div>
            </div>
            </div>

            <!-- Monthly Performance Detail Card -->
            <div id="activity-section" class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
                <!-- Title Header -->
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-700">KPI Company Activity</h3>
                </div>

                <!-- Data Table -->
                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-sm text-left text-slate-700">
                        <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-3 whitespace-nowrap">Tahun</th>
                                <th class="p-3 whitespace-nowrap">Bulan</th>
                                <th class="p-3">Target</th>
                                <th class="p-3">Actual</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Problem Solve</th>
                                <th class="p-3 text-right pr-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($activities as $activity)
                                <tr class="odd:bg-white even:bg-slate-100/70 hover:bg-slate-50 transition-colors">
                                    <!-- Tahun -->
                                    <td class="p-3 font-medium text-slate-700 whitespace-nowrap">
                                        {{ $activity->tahun }}
                                    </td>

                                    <!-- Bulan -->
                                    <td class="p-3 font-medium text-slate-700 whitespace-nowrap">
                                        {{ $activity->bulan }}
                                    </td>

                                    <!-- Target -->
                                    <td class="p-3 text-slate-600">
                                        {{ $kpi->operator }} {{ $kpi->target }} {{ $kpi->unit }}
                                    </td>

                                    <!-- Actual -->
                                    <td class="p-3 text-slate-600">
                                        {{ $activity->actual ?? '' }}
                                    </td>

                                    <!-- Status -->
                                    <td class="p-3">
                                        @if($activity->status == 'Achieved')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-none text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                                Achieved
                                            </span>
                                        @elseif($activity->status == 'Not Achieved')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-none text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                Not achieved
                                            </span>
                                        @else
                                        @endif
                                    </td>

                                    <!-- Problem Solve -->
                                    <td class="p-3">
                                        @if($activity->status === 'Not Achieved' && $activity->problem)
                                            <a href="{{ route('kpi.company.activity.edit', $activity->hash_id) }}?mode=view"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-100 hover:text-blue-600 transition-all duration-200" title="Preview Problem Solving">
                                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                            </a>
                                        @elseif($activity->status === 'Not Achieved' && !$activity->problem)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-none text-xs font-semibold bg-red-50 text-red-600 border border-red-200">
                                                Belum diisi
                                            </span>
                                        @elseif($activity->problem_solve)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-none text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                Corrective action
                                            </span>
                                        @else
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="p-3 text-right pr-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Send Button -->
                                            <button type="button" class="w-10 h-10 flex items-center justify-center rounded-xl bg-green-50 text-green-500 hover:bg-green-100 hover:text-green-600 transition-all duration-200" title="Send">
                                                <i class="fa-solid fa-paper-plane text-sm"></i>
                                            </button>
                                            <!-- Edit Button -->
                                            <a href="{{ route('kpi.company.activity.edit', $activity->hash_id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-100 hover:text-blue-600 transition-all duration-200" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"></path>
                                                    <path opacity="0.3" d="M10.3 15.3L11 14.6L8.70002 12.3C8.30002 11.9 7.7 11.9 7.3 12.3C6.9 12.7 6.9 13.3 7.3 13.7L10.3 16.7C9.9 16.3 9.9 15.7 10.3 15.3Z" fill="currentColor"></path>
                                                    <path d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM11.7 16.7L16.7 11.7C17.1 11.3 17.1 10.7 16.7 10.3C16.3 9.89999 15.7 9.89999 15.3 10.3L11 14.6L8.70001 12.3C8.30001 11.9 7.69999 11.9 7.29999 12.3C6.89999 12.7 6.89999 13.3 7.29999 13.7L10.3 16.7C10.5 16.9 10.8 17 11 17C11.2 17 11.5 16.9 11.7 16.7Z" fill="currentColor"></path>
                                                </svg>
                                            </a>
                                            @if($hasDeletePermission)
                                            <button type="button" 
                                                data-route="{{ route('kpi.company.activity.cancel', $activity->hash_id) }}"
                                                onclick="openDeleteModal(this)"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 transition-all duration-200" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                    <path d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V7H5V5Z" fill="currentColor"/>
                                                    <path d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footer')
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-sm transform transition-all shadow-xl">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Confirm Delete</h3>
                <p class="text-slate-500 text-sm">Apakah Anda yakin ingin menghapus/mereset data actual dan status bulan ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="p-6 pt-0 flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors">Cancel</button>
                <button type="button" onclick="executeDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Problem Solving Preview Modal -->
<div id="problemPreviewModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 transition-opacity" onclick="closeProblemPreview()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl my-4">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-clipboard-list text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Problem Solving Detail</h3>
                </div>
                <button onclick="closeProblemPreview()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                <!-- Problem Description -->
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Problem Description</p>
                    <p id="prev_problem_description" class="text-sm text-slate-700 bg-slate-50 rounded-lg p-3">-</p>
                </div>
                <!-- Root Cause -->
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Root Cause</p>
                    <p id="prev_root_cause" class="text-sm text-slate-700 bg-slate-50 rounded-lg p-3">-</p>
                </div>
                <!-- 4M1E -->
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Root Cause Factor (4M1E)</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Machine</p>
                            <p id="prev_machine" class="text-sm font-medium text-slate-700">-</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Material</p>
                            <p id="prev_material" class="text-sm font-medium text-slate-700">-</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Man</p>
                            <p id="prev_man" class="text-sm font-medium text-slate-700">-</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Method</p>
                            <p id="prev_method" class="text-sm font-medium text-slate-700">-</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Money</p>
                            <p id="prev_money" class="text-sm font-medium text-slate-700">-</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3">
                            <p class="text-xs text-slate-400 mb-0.5">Environment</p>
                            <p id="prev_environment" class="text-sm font-medium text-slate-700">-</p>
                        </div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Temporary Action</p>
                        <p id="prev_temporary_action" class="text-sm text-slate-700 bg-slate-50 rounded-lg p-3">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Permanent Action</p>
                        <p id="prev_permanent_action" class="text-sm text-slate-700 bg-slate-50 rounded-lg p-3">-</p>
                    </div>
                </div>
                <!-- Timeline & PIC -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="bg-slate-50 rounded-lg p-3">
                        <p class="text-xs text-slate-400 mb-0.5">Start Date</p>
                        <p id="prev_start_date" class="text-sm font-medium text-slate-700">-</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-3">
                        <p class="text-xs text-slate-400 mb-0.5">Finish Date</p>
                        <p id="prev_finish_date" class="text-sm font-medium text-slate-700">-</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-3">
                        <p class="text-xs text-slate-400 mb-0.5">PIC Dept</p>
                        <p id="prev_pic_dept" class="text-sm font-medium text-slate-700">-</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-3">
                        <p class="text-xs text-slate-400 mb-0.5">Follow Up By</p>
                        <p id="prev_follow_up_by" class="text-sm font-medium text-slate-700">-</p>
                    </div>
                </div>
                <!-- Closed Status -->
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Closed Status</p>
                    <p id="prev_closed_status" class="text-sm font-medium text-slate-700">-</p>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="p-6 pt-0 border-t border-slate-100 mt-2">
                <button onclick="closeProblemPreview()" class="w-full px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('kpiPerformanceChart');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const actualData = JSON.parse(canvas.dataset.actual || '[]');
        const targetVal = parseFloat(canvas.dataset.target) || 0;
        const operator = canvas.dataset.operator || '';
        
        const isNegativeTarget = ['<=', '<'].includes(operator);
        const actualColor = isNegativeTarget ? '#FF4560' : '#22c55e';
        const targetColor = isNegativeTarget ? '#22c55e' : '#FF4560';
        const actualBgColor = isNegativeTarget ? 'rgba(255, 69, 96, 0.05)' : 'rgba(34, 197, 94, 0.05)';

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const targetData = new Array(12).fill(targetVal);

        if (window.kpiChart) {
            window.kpiChart.destroy();
        }

        window.kpiChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Actual',
                        data: actualData,
                        borderColor: actualColor,
                        backgroundColor: actualBgColor,
                        borderWidth: 2,
                        pointStyle: 'circle',
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: actualColor,
                        spanGaps: true,
                        fill: false
                    },
                    {
                        label: 'Target',
                        data: targetData,
                        borderColor: targetColor,
                        borderDash: [5, 5],
                        borderWidth: 1.5,
                        pointStyle: 'circle',
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: targetColor,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                family: 'Outfit, sans-serif',
                                size: 12
                            },
                            color: '#475569'
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            maxTicksLimit: 5,
                            font: {
                                family: 'Outfit, sans-serif',
                                size: 11
                            }
                        },
                        suggestedMax: targetVal + 1
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Outfit, sans-serif',
                                size: 13
                            }
                        }
                    }
                }
            }
        });
    });

    // Scroll to activity section if URL has #activity-section anchor
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#activity-section') {
            const el = document.getElementById('activity-section');
            if (el) {
                setTimeout(function() {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 200);
            }
        }
    });
    let deleteUrl = null;

    function openDeleteModal(el) {
        deleteUrl = el.dataset.route;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteUrl = null;
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function executeDelete() {
        if (deleteUrl) {
            window.location.href = deleteUrl;
        }
    }

    function openProblemPreview(problem) {
        const fields = ['problem_description', 'root_cause', 'machine', 'material', 'man', 'method', 'money', 'environment', 'temporary_action', 'permanent_action', 'start_date', 'finish_date', 'pic_dept', 'follow_up_by', 'closed_status'];
        fields.forEach(function(f) {
            const el = document.getElementById('prev_' + f);
            if (el) el.textContent = problem[f] || '-';
        });
        document.getElementById('problemPreviewModal').classList.remove('hidden');
    }

    function closeProblemPreview() {
        document.getElementById('problemPreviewModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
