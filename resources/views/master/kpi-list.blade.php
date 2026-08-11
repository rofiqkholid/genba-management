@php
    $hideCentralToast = true;
@endphp
@extends('layouts.app')

@section('title', 'KPI Master List')

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
                <h1 class="text-lg sm:text-2xl font-bold text-slate-800">KPI Master List</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">Manage definitions, targets, objectives, and categories of KPIs.</p>
            </div>
            <!-- Add Button -->
            <button onclick="openCreateModal()" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-xs sm:text-sm font-semibold shadow-sm shadow-blue-200">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add KPI</span>
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
                            <input type="text" id="searchInput" placeholder="Search No. KPI, Objective, or Category..."
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm outline-none bg-white">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="p-6">
                <table id="kpiTable" class="qms-table w-full min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="w-[5%] text-center">No</th>
                            <th class="w-[10%]">No. KPI</th>
                            <th class="w-[20%]">Objective</th>
                            <th class="w-[30%]">Definition</th>
                            <th class="w-[10%]">Pillar</th>
                            <th class="w-[10%]">Category</th>
                            <th class="w-[10%]">Target</th>
                            <th class="w-[10%]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                    </tbody>
                </table>
            </div>
            
            <!-- Data Count Component -->
            <x-data-table tableId="kpiTable" />
        </div>
    </main>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 transition-opacity" onclick="closeCreateModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Add KPI</h3>
                <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="createForm" action="{{ route('master.kpi_list.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">No. KPI</label>
                        <input type="text" name="no_kpi" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all uppercase" placeholder="Enter KPI number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Parent Objective</label>
                        <x-searchable-select
                            name="parent_objective_id"
                            id="create_parent_objective_id"
                            label="Parent Objective"
                            apiUrl="{{ route('master.kpi_list.options') }}"
                            :initialOptions="$kpiList->map(fn($item) => ['id' => $item->id, 'name' => $item->no_kpi . ' - ' . $item->objective])->toArray()"
                            valueField="id"
                            updateEvent="set-create-parent-objective"
                            hideLabel="true" />
                    </div>
                </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Objective</label>
                        <input type="text" name="objective" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Enter objective">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Definition</label>
                        <textarea name="definition" id="create_definition" rows="3" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none overflow-y-auto max-h-40" placeholder="Enter definition"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pillar</label>
                            <input type="text" name="pillar" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Enter pillar">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                            <input type="text" name="category" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Enter category">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Target</label>
                        <input type="text" name="target" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Enter target">
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-slate-700 font-medium hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 transition-opacity" onclick="closeEditModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Edit KPI</h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="editForm" action="{{ route('master.kpi_list.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">No. KPI</label>
                        <input type="text" name="no_kpi" id="edit_no_kpi" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Parent Objective</label>
                        <x-searchable-select
                            name="parent_objective_id"
                            id="edit_parent_objective_id"
                            label="Parent Objective"
                            apiUrl="{{ route('master.kpi_list.options') }}"
                            :initialOptions="$kpiList->map(fn($item) => ['id' => $item->id, 'name' => $item->no_kpi . ' - ' . $item->objective])->toArray()"
                            valueField="id"
                            updateEvent="set-edit-parent-objective"
                            hideLabel="true" />
                    </div>
                </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Objective</label>
                        <input type="text" name="objective" id="edit_objective" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Definition</label>
                        <textarea name="definition" id="edit_definition" rows="3" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none overflow-y-auto max-h-40"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pillar</label>
                            <input type="text" name="pillar" id="edit_pillar" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                            <input type="text" name="category" id="edit_category" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Target</label>
                        <input type="text" name="target" id="edit_target" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-slate-700 font-medium hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-sm transform transition-all">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Confirm Delete</h3>
                <p class="text-slate-500 text-sm">Are you sure you want to delete this specific KPI? This action cannot be undone.</p>
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
    $(document).ready(function() {
        var table = $('#kpiTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('master.kpi_list.table') }}",
                type: 'POST',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.search.value = $('#searchInput').val();
                }
            },
            columns: [
                {
                    data: 'no',
                    name: 'no',
                    orderable: false,
                    searchable: false,
                    className: 'text-center font-base text-slate-700'
                },
                {
                    data: 'no_kpi',
                    name: 'no_kpi',
                    className: 'text-slate-700'
                },
                {
                    data: 'objective',
                    name: 'objective',
                    className: 'text-slate-700'
                },
                {
                    data: 'definition',
                    name: 'definition',
                    className: 'text-slate-700',
                    render: function(data, type, row) {
                        if (data && data.length > 100) {
                            return data.substring(0, 100) + '...';
                        }
                        return data;
                    }
                },
                {
                    data: 'pillar',
                    name: 'pillar',
                    className: 'text-slate-700'
                },
                {
                    data: 'category',
                    name: 'category',
                    className: 'text-slate-700'
                },
                {
                    data: 'target',
                    name: 'target',
                    className: 'text-slate-700'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-left'
                }
            ],
            language: {
                emptyTable: '<div class="flex flex-col items-center justify-center py-8 text-slate-500"><i class="fa-regular fa-folder-open text-4xl mb-3 text-slate-300"></i><p>No data available</p></div>',
            },
            dom: 'r<"overflow-x-auto"t><"flex flex-col sm:flex-row items-center justify-between p-4 border-t border-slate-200 gap-4"ip>',
            pagingType: "simple_numbers",
        });

        // Search keyup debounced
        var searchTimer;
        $(document).on('keyup', '#searchInput', function() {
            clearTimeout(searchTimer);
            const self = this;
            searchTimer = setTimeout(function() {
                table.search($(self).val()).draw();
            }, 500);
        });

        // Parent Objective is optional, so remove the required attribute from searchable-select hidden inputs
        $('#create_parent_objective_id').removeAttr('required');
        $('#edit_parent_objective_id').removeAttr('required');

        function autoResizeTextarea(el) {
            if (!el) return;
            el.style.height = 'auto';
            let scrollHeight = el.scrollHeight;
            if (scrollHeight > 160) {
                el.style.height = '160px';
                el.style.overflowY = 'auto';
            } else {
                el.style.height = scrollHeight + 'px';
                el.style.overflowY = 'hidden';
            }
        }

        $('#create_definition, #edit_definition').on('input', function() {
            autoResizeTextarea(this);
        });

        window.autoResizeTextarea = autoResizeTextarea;

        // Submit Create Form
        $('#createForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = $(form).find('button[type="submit"]');
            const originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...');
            
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    closeCreateModal();
                    showToast('Data added successfully.', 'success');
                    table.ajax.reload();
                    form.reset();
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Failed to add data.', 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Submit Edit Form
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = $(form).find('button[type="submit"]');
            const originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Updating...');
            
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    closeEditModal();
                    showToast('Data updated successfully.', 'success');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Failed to update data.', 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    });

    function openCreateModal() {
        window.dispatchEvent(new CustomEvent('set-create-parent-objective', {
            detail: { id: "", name: "" }
        }));
        $('#createModal').removeClass('hidden');
        setTimeout(() => {
            if (window.autoResizeTextarea) {
                window.autoResizeTextarea(document.getElementById('create_definition'));
            }
        }, 50);
    }

    function closeCreateModal() {
        $('#createModal').addClass('hidden');
    }

    function handleEdit(btn) {
        const id = btn.getAttribute('data-id');
        const parent_objective_id = btn.getAttribute('data-parent_objective_id');
        const no_kpi = btn.getAttribute('data-no_kpi');
        const objective = btn.getAttribute('data-objective');
        const definition = btn.getAttribute('data-definition');
        const pillar = btn.getAttribute('data-pillar');
        const category = btn.getAttribute('data-category');
        const target = btn.getAttribute('data-target');

        const parent_objective_name = btn.getAttribute('data-parent_objective_name') || "";

        $('#edit_id').val(id);
        
        // Dispatch event to Alpine component of searchable select
        window.dispatchEvent(new CustomEvent('set-edit-parent-objective', {
            detail: { id: parent_objective_id || "", name: parent_objective_id ? parent_objective_name : "" }
        }));

        $('#edit_no_kpi').val(no_kpi);
        $('#edit_objective').val(objective);
        $('#edit_definition').val(definition);
        $('#edit_pillar').val(pillar);
        $('#edit_category').val(category);
        $('#edit_target').val(target);

        // Auto-resize Definition textarea on load
        setTimeout(() => {
            if (window.autoResizeTextarea) {
                window.autoResizeTextarea(document.getElementById('edit_definition'));
            }
        }, 50);

        $('#editModal').removeClass('hidden');
    }

    function closeEditModal() {
        $('#editModal').addClass('hidden');
    }

    let deleteId = null;
    let deleteNo = null;

    function handleDelete(id, no) {
        deleteId = id;
        deleteNo = no;
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

        $('#icon_delete_' + no).addClass('hidden');
        $('#loader_delete_' + no).removeClass('hidden');

        closeDeleteModal();

        $.ajax({
            url: "{{ route('master.kpi_list.delete') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                $('#icon_delete_' + no).removeClass('hidden');
                $('#loader_delete_' + no).addClass('hidden');

                if (response.success) {
                    showToast('Data deleted successfully', 'success');
                    $('#kpiTable').DataTable().ajax.reload();
                } else {
                    showToast('Failed to delete item', 'error');
                }
            },
            error: function() {
                $('#icon_delete_' + no).removeClass('hidden');
                $('#loader_delete_' + no).addClass('hidden');
                showToast('An error occurred', 'error');
            }
        });
    }
</script>
@endpush
@endsection