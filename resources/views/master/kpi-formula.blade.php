@extends('layouts.app')
@php
    $hideCentralToast = true;
@endphp

@section('title', 'KPI Formula Master')

@section('content')
@include('layouts.sidebar')
@include('components.toast')

<div class="lg:ml-20 min-h-screen flex flex-col bg-slate-50">
    @include('layouts.header')

    <!-- Page Content -->
    <main class="flex-1 p-6">
        <!-- Page Title -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-slate-800">KPI Formula</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">Manage definitions and calculation formulas of KPIs.</p>
            </div>
            <!-- Add Button -->
            <button onclick="openCreateModal()" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-xs sm:text-sm font-semibold shadow-sm shadow-blue-200">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>New Formula</span>
            </button>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <!-- Filter Section -->
            <div class="p-4 sm:p-6 border-b border-slate-200 bg-slate-50/50">
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search KPI Formula..."
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm outline-none bg-white">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="p-6 overflow-x-auto">
                <table id="kpiFormulaTable" class="qms-table w-full min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="w-[5%] text-center">No</th>
                            <th class="w-[25%] text-left">KPI</th>
                            <th class="w-[20%] text-left">Formula Logic</th>
                            <th class="w-[25%] text-left">Formula Expression</th>
                            <th class="w-[15%] text-left">Result</th>
                            <th class="w-[10%] text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400 text-sm">No data available.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 transition-opacity" onclick="closeCreateModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl flex flex-col transform transition-all overflow-hidden shadow-2xl">
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
                <h3 class="text-lg font-bold text-slate-800">New Formula</h3>
                <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <form id="createForm" action="{{ route('master.kpi-formula.store') }}" method="POST" class="p-6 space-y-4" 
                @formula-type-changed.window="formula_type = $event.detail.value; updateFormula()"
                x-data="{
                    locked: false,
                    vals: ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
                    formula: '',
                    formula_type: '',
                    selectedVars: [],
                    appendVar(index) {
                        const idx = this.selectedVars.indexOf(index);
                        if (idx > -1) {
                            this.selectedVars.splice(idx, 1);
                        } else {
                            this.selectedVars.push(index);
                        }
                        this.updateFormula();
                    },
                    updateFormula() {
                        const activeVars = this.selectedVars.map(i => 'val_' + i);
                        
                        if (activeVars.length === 0) {
                            this.formula = '';
                            return;
                        }

                        if (this.formula_type === 'sum') {
                            this.formula = activeVars.join(' + ');
                        } else if (this.formula_type === 'average') {
                            this.formula = '(' + activeVars.join(' + ') + ') / ' + activeVars.length;
                        } else if (this.formula_type === 'percent') {
                            if (activeVars.length >= 2) {
                                this.formula = '(' + activeVars[0] + ' / ' + activeVars[1] + ') * 100';
                            } else {
                                this.formula = activeVars[0] + ' * 100';
                            }
                        } else {
                            this.formula = activeVars.join(' ');
                        }
                    },
                calculateResult() {
                    let expr = this.formula;
                    if (!expr) return '';
                    
                    for (let i = 0; i < 15; i++) {
                        const val = this.vals[i];
                        const numVal = (val !== null && val !== '') ? parseFloat(val) : 0;
                        const regex = new RegExp('\\bval_' + (i + 1) + '\\b', 'g');
                        expr = expr.replace(regex, numVal);
                    }

                    try {
                        const sanitized = expr.replace(/[^0-9+\-*/().\s]/g, '');
                        const res = Function('return (' + sanitized + ')')();
                        if (isFinite(res)) {
                            return Number(res.toFixed(4));
                        }
                        return 'Error (Invalid Calculation)';
                    } catch (e) {
                        return '';
                    }
                }
            }">
                @csrf
                <!-- KPI Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">KPI <span class="text-red-500">*</span></label>
                    <x-searchable-select
                        name="kpi_list_id"
                        id="create_kpi_list_id"
                        label="KPI"
                        hideLabel="true"
                        required="true"
                        apiUrl="{{ route('master.kpi_list.options') }}"
                        :initialOptions="$kpiList->map(fn($item) => ['id' => $item->id, 'name' => $item->no_kpi . ' - ' . $item->objective])->toArray()"
                    />
                </div>

                <!-- 15 Numeric Input Fields -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5 font-semibold">Value Inputs (15 Columns)</label>
                    
                    <!-- When NOT locked, show inputs -->
                    <div x-show="!locked" class="grid grid-cols-5 gap-2 transition-all">
                        @for($i = 1; $i <= 15; $i++)
                            <div class="relative">
                                <input type="number" step="any" name="val_{{ $i }}" x-model="vals[{{ $i - 1 }}]" placeholder="Val {{ $i }}" class="w-full px-2 py-2.5 border border-slate-200 rounded-xl text-center text-xs outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        @endfor
                    </div>

                    <!-- When locked, show buttons -->
                    <div x-show="locked" class="grid grid-cols-5 gap-2 transition-all">
                        @for($i = 1; $i <= 15; $i++)
                            <button type="button" 
                                x-show="vals[{{ $i - 1 }}]"
                                @click="appendVar({{ $i }})" 
                                :class="selectedVars.includes({{ $i }}) 
                                    ? 'bg-blue-100 text-blue-700 border-blue-200 font-bold' 
                                    : 'bg-slate-50 hover:bg-slate-100 text-slate-400 border-slate-200'"
                                class="w-full px-2 py-2.5 border rounded-xl text-center text-xs transition-all truncate" 
                                title="Click to insert val_{{ $i }} into formula">
                                val_{{ $i }} <span :class="selectedVars.includes({{ $i }}) ? 'text-blue-600' : 'text-blue-500'" class="font-bold" x-text="vals[{{ $i - 1 }}] ? '(' + vals[{{ $i - 1 }}] + ')' : ''"></span>
                            </button>
                        @endfor
                    </div>

                    <!-- Lock/Save Button -->
                    <div class="mt-3 flex justify-end">
                        <button type="button" @click="locked = !locked; if(locked) updateFormula();" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition-colors flex items-center gap-1.5">
                            <i class="fa-solid" :class="locked ? 'fa-lock-open' : 'fa-lock'"></i>
                            <span x-text="locked ? 'Edit Inputs' : 'Save & Convert to Buttons'"></span>
                        </button>
                    </div>
                </div>

                <!-- Formula Logic & Result side by side -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Formula Logic -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Formula Logic <span class="text-red-500">*</span></label>
                        <x-searchable-select
                            name="formula_type"
                            id="create_formula_type"
                            label="Formula Logic"
                            hideLabel="true"
                            required="true"
                            position="up"
                            changeEvent="formula-type-changed"
                            :initialOptions="[
                                ['id' => 'sum', 'name' => 'SUM (Total)'],
                                ['id' => 'average', 'name' => 'AVERAGE (Rata-rata)'],
                                ['id' => 'percent', 'name' => 'PERCENTAGE (%)'],
                                ['id' => 'custom', 'name' => 'CUSTOM']
                            ]"
                        />
                    </div>

                    <!-- Formula Expression -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Formula Expression <span class="text-red-500">*</span></label>
                        <input type="text" name="result" x-model="formula" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm" placeholder="e.g. val_1 + val_2">
                    </div>
                </div>

                <!-- Calculation Result -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Result (Real-time Calculation)</label>
                    <input type="text" readonly :value="calculateResult()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none text-sm font-bold text-slate-700 cursor-not-allowed" placeholder="Waiting for inputs and formula...">
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 shrink-0">
                    <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors text-sm font-semibold shadow-sm shadow-blue-200">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 transition-opacity" onclick="closeEditModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl flex flex-col transform transition-all overflow-hidden shadow-2xl">
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
                <h3 class="text-lg font-bold text-slate-800">Edit Formula</h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <form id="editForm" action="{{ route('master.kpi-formula.update') }}" method="POST" class="p-6 space-y-4" 
                @formula-type-changed-edit.window="formula_type = $event.detail.value; updateFormula()"
                x-data="{
                    locked: false,
                    vals: ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
                    formula: '',
                    formula_type: '',
                    selectedVars: [],
                    appendVar(index) {
                        const idx = this.selectedVars.indexOf(index);
                        if (idx > -1) {
                            this.selectedVars.splice(idx, 1);
                        } else {
                            this.selectedVars.push(index);
                        }
                        this.updateFormula();
                    },
                    updateFormula() {
                        const activeVars = this.selectedVars.map(i => 'val_' + i);
                        
                        if (activeVars.length === 0) {
                            this.formula = '';
                            return;
                        }

                        if (this.formula_type === 'sum') {
                            this.formula = activeVars.join(' + ');
                        } else if (this.formula_type === 'average') {
                            this.formula = '(' + activeVars.join(' + ') + ') / ' + activeVars.length;
                        } else if (this.formula_type === 'percent') {
                            if (activeVars.length >= 2) {
                                this.formula = '(' + activeVars[0] + ' / ' + activeVars[1] + ') * 100';
                            } else {
                                this.formula = activeVars[0] + ' * 100';
                            }
                        } else {
                            this.formula = activeVars.join(' ');
                        }
                    },
                    calculateResult() {
                        let expr = this.formula;
                        if (!expr) return '';
                        
                        for (let i = 0; i < 15; i++) {
                            const val = this.vals[i];
                            const numVal = (val !== null && val !== '') ? parseFloat(val) : 0;
                            const regex = new RegExp('\\bval_' + (i + 1) + '\\b', 'g');
                            expr = expr.replace(regex, numVal);
                        }

                        try {
                            const sanitized = expr.replace(/[^0-9+\-*/().\s]/g, '');
                            const res = Function('return (' + sanitized + ')')();
                            if (isFinite(res)) {
                                  return Number(res.toFixed(4));
                            }
                            return 'Error (Invalid Calculation)';
                        } catch (e) {
                            return '';
                        }
                    }
                }">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                
                <!-- KPI Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">KPI <span class="text-red-500">*</span></label>
                    <x-searchable-select
                        name="kpi_list_id"
                        id="edit_kpi_list_id"
                        label="KPI"
                        hideLabel="true"
                        required="true"
                        updateEvent="edit-kpi-changed"
                        apiUrl="{{ route('master.kpi_list.options') }}"
                        :initialOptions="$kpiList->map(fn($item) => ['id' => $item->id, 'name' => $item->no_kpi . ' - ' . $item->objective])->toArray()"
                    />
                </div>

                <!-- 15 Numeric Input Fields -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5 font-semibold">Value Inputs (15 Columns)</label>
                    
                    <!-- When NOT locked, show inputs -->
                    <div x-show="!locked" class="grid grid-cols-5 gap-2 transition-all">
                        @for($i = 1; $i <= 15; $i++)
                            <div class="relative">
                                <input type="number" step="any" name="val_{{ $i }}" x-model="vals[{{ $i - 1 }}]" placeholder="Val {{ $i }}" class="w-full px-2 py-2.5 border border-slate-200 rounded-xl text-center text-xs outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        @endfor
                    </div>

                    <!-- When locked, show buttons -->
                    <div x-show="locked" class="grid grid-cols-5 gap-2 transition-all">
                        @for($i = 1; $i <= 15; $i++)
                            <button type="button" 
                                x-show="vals[{{ $i - 1 }}]"
                                @click="appendVar({{ $i }})" 
                                :class="selectedVars.includes({{ $i }}) 
                                    ? 'bg-blue-100 text-blue-700 border-blue-200 font-bold' 
                                    : 'bg-slate-50 hover:bg-slate-100 text-slate-400 border-slate-200'"
                                class="w-full px-2 py-2.5 border rounded-xl text-center text-xs transition-all truncate" 
                                title="Click to insert val_{{ $i }} into formula">
                                val_{{ $i }} <span :class="selectedVars.includes({{ $i }}) ? 'text-blue-600' : 'text-blue-500'" class="font-bold" x-text="vals[{{ $i - 1 }}] ? '(' + vals[{{ $i - 1 }}] + ')' : ''"></span>
                            </button>
                        @endfor
                    </div>

                    <!-- Lock/Save Button -->
                    <div class="mt-3 flex justify-end">
                        <button type="button" @click="locked = !locked; if(locked) updateFormula();" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition-colors flex items-center gap-1.5">
                            <i class="fa-solid" :class="locked ? 'fa-lock-open' : 'fa-lock'"></i>
                            <span x-text="locked ? 'Edit Inputs' : 'Save & Convert to Buttons'"></span>
                        </button>
                    </div>
                </div>

                <!-- Formula Logic & Result side by side -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Formula Logic -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Formula Logic <span class="text-red-500">*</span></label>
                        <x-searchable-select
                            name="formula_type"
                            id="edit_formula_type"
                            label="Formula Logic"
                            hideLabel="true"
                            required="true"
                            position="up"
                            updateEvent="edit-formula-logic-changed"
                            changeEvent="formula-type-changed-edit"
                            :initialOptions="[
                                ['id' => 'sum', 'name' => 'SUM (Total)'],
                                ['id' => 'average', 'name' => 'AVERAGE (Rata-rata)'],
                                ['id' => 'percent', 'name' => 'PERCENTAGE (%)'],
                                ['id' => 'custom', 'name' => 'CUSTOM']
                            ]"
                        />
                    </div>

                    <!-- Formula Expression -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Formula Expression <span class="text-red-500">*</span></label>
                        <input type="text" name="result" x-model="formula" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm" placeholder="e.g. val_1 + val_2">
                    </div>
                </div>

                <!-- Calculation Result -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Result (Real-time Calculation)</label>
                    <input type="text" readonly :value="calculateResult()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none text-sm font-bold text-slate-700 cursor-not-allowed" placeholder="Waiting for inputs and formula...">
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 shrink-0">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors text-sm font-semibold shadow-sm shadow-blue-200">Update</button>
                </div>
            </form>
        </div>
    </div>
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
                <p class="text-slate-500 text-sm">Are you sure you want to delete this Formula? This action cannot be undone.</p>
            </div>
            <div class="p-6 pt-0 flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors">Cancel</button>
                <button type="button" onclick="executeDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">Delete</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let table;

    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal() {
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function handleEdit(btn) {
        const row = JSON.parse(btn.getAttribute('data-row'));
        
        // Populate edit ID
        $('#edit_id').val(row.id);
        
        // Set searchable-select KPI option
        window.dispatchEvent(new CustomEvent('edit-kpi-changed', {
            detail: {
                id: row.kpi_list_id,
                name: row.no_kpi + ' - ' + row.objective
            }
        }));

        // Set searchable-select Formula Logic option
        window.dispatchEvent(new CustomEvent('edit-formula-logic-changed', {
            detail: {
                id: row.formula_type,
                name: row.formula_type ? row.formula_type.toUpperCase() : ''
            }
        }));

        // Populate Alpine state of Edit Form
        const formEl = document.getElementById('editForm');
        if (formEl && window.Alpine) {
            const alpineData = window.Alpine.$data(formEl);
            if (alpineData) {
                // Populate vals 1-15
                for (let i = 1; i <= 15; i++) {
                    alpineData.vals[i - 1] = row['val_' + i] !== null ? row['val_' + i] : '';
                }
                alpineData.formula = row.result || '';
                alpineData.formula_type = row.formula_type || '';
                
                // Parse selected variables based on the result formula string
                alpineData.selectedVars = [];
                for (let i = 1; i <= 15; i++) {
                    const regex = new RegExp('\\bval_' + i + '\\b');
                    if (regex.test(row.result)) {
                        alpineData.selectedVars.push(i);
                    }
                }
                
                // Lock inputs to show buttons if a formula exists
                alpineData.locked = alpineData.formula ? true : false;
            }
        }

        openEditModal();
    }

    $(document).ready(function() {
        // Initialize Datatable
        table = $('#kpiFormulaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('master.kpi-formula.table') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            },
            columns: [
                { data: 'no', name: 'no', className: 'text-center py-4' },
                { data: 'kpi', name: 'kpi', className: 'text-slate-700 py-4' },
                { data: 'formula_type', name: 'formula_type', className: 'text-slate-700 py-4 font-semibold' },
                { data: 'result', name: 'result', className: 'text-slate-700 py-4 text-sm' },
                { data: 'evaluated_result', name: 'evaluated_result', className: 'text-slate-700 py-4 text-sm' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center py-4' }
            ],
            language: {
                emptyTable: '<div class="flex flex-col items-center justify-center py-8 text-slate-500"><i class="fa-regular fa-folder-open text-4xl mb-3 text-slate-300"></i><p>No data available</p></div>',
            },
            dom: 'r<"overflow-x-auto"t><"flex flex-col sm:flex-row items-center justify-between p-4 border-t border-slate-200 gap-4"ip>',
            pagingType: "simple_numbers",
        });

        // Search trigger
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Create form submit
        $('#createForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = $(form).find('button[type="submit"]');
            
            submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...');
            
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    closeCreateModal();
                    showToast('KPI Formula added successfully.', 'success');
                    table.ajax.reload();
                    form.reset();
                    // Reset Alpine state
                    const formEl = document.getElementById('createForm');
                    if (formEl && window.Alpine) {
                        const data = window.Alpine.$data(formEl);
                        if (data) {
                            data.locked = false;
                            data.vals = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
                            data.formula = '';
                            data.formula_type = '';
                            data.selectedVars = [];
                        }
                    }
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Failed to add KPI Formula.', 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('Save');
                }
            });
        });

        // Edit form submit
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = $(form).find('button[type="submit"]');
            
            submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Updating...');
            
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    closeEditModal();
                    showToast('KPI Formula updated successfully.', 'success');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Failed to update KPI Formula.', 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('Update');
                }
            });
        });
    });

    let deleteId = null;
    let deleteNo = null;

    function handleDelete(id, rowNo) {
        deleteId = id;
        deleteNo = rowNo;
        $('#deleteModal').removeClass('hidden');
    }

    function closeDeleteModal() {
        $('#deleteModal').addClass('hidden');
        deleteId = null;
        deleteNo = null;
    }

    function executeDelete() {
        if (!deleteId) return;
        const id = deleteId;
        const no = deleteNo;

        const icon = $(`#icon_delete_${no}`);
        const loader = $(`#loader_delete_${no}`);
        const btn = $(`#btn_delete_${no}`);
        
        btn.prop('disabled', true);
        icon.addClass('hidden');
        loader.removeClass('hidden');

        closeDeleteModal();
        
        $.ajax({
            url: "{{ route('master.kpi-formula.delete') }}",
            type: 'POST',
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                showToast('KPI Formula deleted successfully.', 'success');
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.message || 'Failed to delete KPI Formula.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false);
                icon.removeClass('hidden');
                loader.addClass('hidden');
            }
        });
    }
</script>
@endpush
@endsection
