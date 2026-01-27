@extends('layouts.app')

@section('title', 'Dashboard - QMS')

@section('content')
@include('layouts.sidebar')
@include('components.toast')

<!-- Main Content -->
<div class="lg:ml-20 min-h-screen flex flex-col bg-slate-50">
    @include('layouts.header')

    <!-- Page Content -->
    <main class="flex-1 p-6">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-slate-500 mt-1">Welcome back! Here's what's happening today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Card 0: All Findings -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">All Findings</p>
                        <h3 class="text-2xl font-bold text-slate-800" id="val_allFindings">...</h3>
                    </div>
                </div>
            </div>

            <!-- Card 1: Findings Open -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Findings Open</p>
                        <h3 class="text-2xl font-bold text-slate-800" id="val_findingsOpen">...</h3>
                    </div>
                </div>
            </div>

            <!-- Card 2: Need Approve -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Need Approve</p>
                        <h3 class="text-2xl font-bold text-slate-800" id="val_needApprove">...</h3>
                    </div>
                </div>
            </div>

            <!-- Card 3: Due Date (Overdue) -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Due Date</p>
                        <h3 class="text-2xl font-bold text-slate-800" id="val_dueDateCount">...</h3>
                    </div>
                </div>
            </div>

            <!-- Card 4: Closed -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Closed</p>
                        <h3 class="text-2xl font-bold text-slate-800" id="val_findingsClose">...</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Findings Genba Table Section (Ported) -->
        <div class="bg-white rounded-lg border border-slate-200 mb-8">
            <!-- Filter Section -->
            <div class="p-6 border-b border-slate-200 bg-slate-50/50">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Search -->
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search findings..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm outline-none">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Date From -->
                    <div>
                        <input type="date" id="dateFrom"
                            class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm outline-none">
                    </div>

                    <!-- Date To -->
                    <div>
                        <input type="date" id="dateTo"
                            class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm outline-none">
                    </div>

                    <!-- Filter Button -->
                    <button type="button" id="btnFilter"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-base transition-colors">
                        <i class="fa-solid fa-filter text-sm"></i>
                        Filter
                    </button>

                    <!-- Reset Button -->
                    <button type="button" id="btnReset"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 text-sm font-base transition-colors">
                        <i class="fa-solid fa-rotate-right text-sm"></i>
                        Reset
                    </button>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto p-6">
                <table id="findingsTable" class="qms-table w-full">
                    <thead>
                        <tr>
                            <th class="w-[4%] text-center">No</th>
                            <th class="w-[8%]">DocNum</th>
                            <th class="w-[5%]">Picture</th>
                            <th class="w-[10%]">DocDate</th>
                            <th class="w-[9%]">Area Checked</th>
                            <th class="w-[23%]">Findings</th>
                            <th class="w-[12%]">Auditor</th>
                            <th class="w-[14%]">Status</th>
                            <th class="w-[8%]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    @include('layouts.footer')
</div>

<!-- Mobile Sidebar Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-30 hidden lg:hidden"></div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>

    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
            <!-- Header -->
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Konfirmasi Hapus</h3>
                <p class="text-slate-500">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>

            <!-- Footer -->
            <div class="flex gap-3 p-6 pt-0">
                <button type="button" id="btnCancelDelete"
                    class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                    Tidak
                </button>
                <button type="button" id="btnConfirmDelete"
                    class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-colors">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="closeImageModal()"></div>

    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">Findings</h3>
                <button type="button" onclick="closeImageModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto max-h-[80vh]">
                <div id="imageContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Images will be inserted here dynamically -->
                </div>

                <!-- Fallback for no images -->
                <div id="noImageContainer" class="hidden flex-col items-center justify-center min-h-[300px] text-slate-400">
                    <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18" />
                    </svg>
                    <p class="text-sm">Image not available</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end p-4 border-t border-slate-200">
                <button type="button" onclick="closeImageModal()"
                    class="px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-medium hover:bg-slate-200 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fetch Data
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('dashboard.data_cards') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                $('#val_allFindings').text(new Intl.NumberFormat().format(response.allFindings));
                $('#val_findingsOpen').text(new Intl.NumberFormat().format(response.findingsOpen));
                $('#val_needApprove').text(new Intl.NumberFormat().format(response.needApprove));
                $('#val_dueDateCount').text(new Intl.NumberFormat().format(response.dueDateCount));
                $('#val_findingsClose').text(new Intl.NumberFormat().format(response.findingsClose));
            },
            error: function(xhr, status, error) {
                console.error(error);
                $('#val_allFindings').text('Error');
                $('#val_findingsOpen').text('Error');
                $('#val_needApprove').text('Error');
                $('#val_dueDateCount').text('Error');
                $('#val_findingsClose').text('Error');
            }
        });
    });

    // Mobile Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    }

    // --- Findings Table Logic (Ported) ---

    $(document).ready(function() {
        var table = $('#findingsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('genba.table') }}", // Uses GenbaManagementController@front_mng_table
                type: 'POST',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.front_table_search = $('#searchInput').val();
                    d.date_from = $('#dateFrom').val();
                    d.date_to = $('#dateTo').val();
                }
            },
            columns: [{
                    data: 'no',
                    orderable: false,
                    className: 'text-center font-base text-slate-700',
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'DocNum',
                    className: 'font-base text-slate-900',
                    render: function(data, type, row) {
                        return '<span class="inline-flex items-center rounded-md text-sm font-base text-slate-800 font-mono">' + data + '</span>';
                    }
                },
                {
                    data: 'path',
                    orderable: false,
                    className: 'text-left',
                    render: function(data, type, row) {
                        if (data) {
                            return '<button class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" onclick="viewImage(\'' + data + '\')" title="View Image"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></button>';
                        }
                        return '<button class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" onclick="viewImage(\'' + data + '\')" title="View Image"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></button>';
                    }
                },
                {
                    data: 'date',
                    className: 'text-slate-700',
                    render: function(data, type, row) {
                        return '<span class="text-sm">' + data + '</span>';
                    }
                },
                {
                    data: 'area_checked',
                    className: 'text-slate-700',
                    render: function(data, type, row) {
                        return '<span class="text-sm">' + data + '</span>';
                    }
                },
                {
                    data: 'findings',
                    render: function(data, type, row) {
                        return '<div class="text-sm text-slate-600">' + (data || '') + '</div>';
                    }
                },
                {
                    data: 'auditor',
                    className: 'text-slate-700',
                    render: function(data, type, row) {
                        return '<span class="text-sm">' + (data || '') + '</span>';
                    }
                },
                {
                    data: 'status',
                    orderable: false,
                    className: 'text-left',
                },
                {
                    data: 'action',
                    orderable: false,
                    className: 'text-left',
                    render: function(data, type, row) {
                        return '<div class="flex items-center gap-2">' + data + '</div>';
                    }
                }
            ],
            order: [
                [3, 'desc']
            ],
            pageLength: 10,
            language: {
                emptyTable: '<div class="text-center py-8 text-slate-500">No data available</div>',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                paginate: {
                    previous: '<i class="fa-solid fa-chevron-left"></i>',
                    next: '<i class="fa-solid fa-chevron-right"></i>'
                }
            }
        });

        // Filter button
        $('#btnFilter').click(function() {
            table.ajax.reload();
        });

        // Reset button
        $('#btnReset').click(function() {
            $('#searchInput').val('');
            $('#dateFrom').val('');
            $('#dateTo').val('');
            table.ajax.reload();
        });

        // Search on enter
        $('#searchInput').keypress(function(e) {
            if (e.which == 13) {
                table.ajax.reload();
            }
        });
    });

    function document_preview(id, no) {
        // Redirect to preview page
        window.location.href = "{{ route('genba.preview', '') }}/" + id;
    }

    // Viewer instance
    var galleryViewer = null;

    const findingPhotoBaseUrl = "{{ asset('findings-photo') }}";

    function viewImage(path) {
        // Reset state
        $('#imageContainer').empty().removeClass('hidden');
        $('#noImageContainer').addClass('hidden').removeClass('flex');

        if (!path) {
            $('#imageContainer').addClass('hidden');
            $('#noImageContainer').removeClass('hidden').addClass('flex');
            $('#imagePreviewModal').removeClass('hidden');
            return;
        }

        // Split path by comma to handle multiple images
        var paths = path.split(',');

        paths.forEach(function(imgName) {
            imgName = imgName.trim();
            if (imgName) {
                var imagePath = findingPhotoBaseUrl + '/' + imgName;
                var imgHtml = `
                    <div class="relative group cursor-pointer">
                        <img src="${imagePath}" 
                             class="w-full h-auto rounded-lg object-contain border border-slate-200 hover:opacity-90 transition-opacity" 
                             alt="Finding Image"
                             onerror="this.parentElement.style.display='none'">
                    </div>
                `;
                $('#imageContainer').append(imgHtml);
            }
        });

        // Initialize Viewer.js
        if (galleryViewer) {
            galleryViewer.destroy();
        }

        var container = document.getElementById('imageContainer');
        // Check if Viewer is defined to avoid errors if script is missing
        if (typeof Viewer !== 'undefined') {
            galleryViewer = new Viewer(container, {
                toolbar: {
                    zoomIn: 1,
                    zoomOut: 1,
                    oneToOne: 1,
                    reset: 1,
                    prev: 1,
                    play: 1,
                    next: 1,
                    rotateLeft: 1,
                    rotateRight: 1,
                    flipHorizontal: 1,
                    flipVertical: 1,
                },
                title: false,
                transition: true,
            });
        } else {
            console.warn('Viewer.js not loaded');
        }

        // Show modal
        $('#imagePreviewModal').removeClass('hidden');
    }

    function closeImageModal() {
        $('#imagePreviewModal').addClass('hidden');
        $('#imageContainer').empty();

        if (galleryViewer) {
            galleryViewer.destroy();
            galleryViewer = null;
        }
    }

    // Delete confirmation variables
    var deleteTargetSysId = null;
    var deleteTargetNo = null;

    function f_genba_conform_delete(sysId, no) {
        deleteTargetSysId = sysId;
        deleteTargetNo = no;
        $('#deleteConfirmModal').removeClass('hidden');
    }

    function closeDeleteModal() {
        $('#deleteConfirmModal').addClass('hidden');
        deleteTargetSysId = null;
        deleteTargetNo = null;
    }

    function executeDelete() {
        if (!deleteTargetSysId) return;

        var sysId = deleteTargetSysId;
        var no = deleteTargetNo;

        // Show loader on button
        $('#icon_f_genba_conform_delete_' + no).addClass('hidden');
        $('#loader_f_genba_conform_delete_' + no).removeClass('hidden');

        closeDeleteModal();

        $.ajax({
            url: "{{ route('genba.delete') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                sys_id: sysId
            },
            success: function(response) {
                $('#icon_f_genba_conform_delete_' + no).removeClass('hidden');
                $('#loader_f_genba_conform_delete_' + no).addClass('hidden');

                if (response.success) {
                    showToast('Data berhasil dihapus', 'success');
                    $('#findingsTable').DataTable().ajax.reload();
                    // Also reload stats if possible, or just table
                    // updateStats(); // function not defined yet but would be nice
                } else {
                    showToast('Gagal menghapus data', 'error');
                }
            },
            error: function() {
                $('#icon_f_genba_conform_delete_' + no).removeClass('hidden');
                $('#loader_f_genba_conform_delete_' + no).addClass('hidden');
                showToast('Terjadi kesalahan', 'error');
            }
        });
    }

    // Modal button handlers
    $(document).ready(function() {
        $('#btnCancelDelete').click(function() {
            closeDeleteModal();
        });

        $('#btnConfirmDelete').click(function() {
            executeDelete();
        });

        // Close modal on backdrop click
        $('#deleteConfirmModal').click(function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    });
</script>
@endpush