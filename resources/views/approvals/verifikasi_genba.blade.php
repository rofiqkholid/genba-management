@extends('layouts.app')

@section('title', 'Execution Genba - QMS')

@section('content')
@include('layouts.sidebar')
@include('components.toast')

<!-- Main Content -->
<div class="lg:ml-20 min-h-screen flex flex-col bg-slate-50">
    @include('layouts.header')

    <!-- Page Content -->
    <main class="flex-1 p-6">
        <!-- Page Title -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Execution Genba</h1>
            <p class="text-slate-500 mt-1">Verifikasi Genba (Approval)</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg border border-slate-200">
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
                            <th class="w-[10%]">DocDate</th>
                            <th class="w-[23%]">Findings</th>
                            <th class="w-[9%]">Asign to Dept</th>
                            <th class="w-[12%]">Auditor</th>
                            <th class="w-[5%]">PICT. Before</th>
                            <th class="w-[5%]">PICT. After</th>
                            <th class="w-[14%]">Status</th>
                            <th class="w-[8%]">Approve</th>
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

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="closeImageModal()"></div>

    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all">
            <!-- Header -->
            <div class="flex flex-col p-4 border-b border-slate-200">
                <div class="flex items-center justify-between mb-2">
                    <h3 id="modalTitle" class="text-lg font-semibold text-slate-800">Picture Before / Findings</h3>
                    <button type="button" onclick="closeImageModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Caption -->
                <p id="modalCaption" class="text-sm text-slate-600 font-medium"></p>
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
<script>
    $(document).ready(function() {
        var table = $('#findingsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('execution_genba.table') }}",
                type: 'POST',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.search = $('#searchInput').val(); // Corrected param name for controller
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
                    data: 'date',
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
                    data: 'asign_to_dept',
                    className: 'text-slate-700',
                    render: function(data, type, row) {
                        return '<span class="text-sm">' + data + '</span>';
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
                    data: 'path',
                    orderable: false,
                    className: 'text-left',
                    render: function(data, type, row) {
                        if (data) {
                            // Escape single quotes and double quotes for the onclick handler HTML attribute
                            const findings = (row.findings || '').replace(/'/g, "\\'").replace(/"/g, "&quot;");
                            return '<button class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" onclick="viewImage(\'' + data + '\', \'findings\', \'' + findings + '\')" title="View Before Image"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></button>';
                        }
                        return '-';
                    }
                },
                {
                    data: 'execution_path',
                    orderable: false,
                    className: 'text-left',
                    render: function(data, type, row) {
                        if (data) {
                            // Escape single quotes and double quotes for the onclick handler HTML attribute
                            const comment = (row.execution_comment || '').replace(/'/g, "\\'").replace(/"/g, "&quot;");
                            return '<button class="w-9 h-9 flex items-center justify-center text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors" onclick="viewImage(\'' + data + '\', \'evidence\', \'' + comment + '\')" title="View After Image"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg></button>';
                        }
                        return '-';
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

        // Show/hide page loader on DataTables AJAX
        table.on('preXhr.dt', function() {
            $('body').addClass('data-loading');
            $('#page-loader').removeClass('hidden');
        });

        table.on('xhr.dt', function() {
            $('body').removeClass('data-loading');
            $('#page-loader').addClass('hidden');
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

        // Mobile sidebar toggle
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
    });

    function document_preview(id, no) {
        // Redirect to preview page - Reusing existing preview
        window.location.href = "{{ route('genba.preview', '') }}/" + id;
    }

    // Viewer instance
    var galleryViewer = null;

    const findingPhotoBaseUrl = "{{ asset('findings-photo') }}";
    const evidencePhotoBaseUrl = "{{ asset('evidence-photo') }}";

    function viewImage(path, type = 'findings', caption = '') {
        // Reset state
        $('#imageContainer').empty().removeClass('hidden');
        $('#noImageContainer').addClass('hidden').removeClass('flex');

        // Set Title based on type
        var title = (type === 'evidence') ? 'Picture After / Evidence' : 'Picture Before / Findings';
        $('#modalTitle').text(title);

        // Set Caption
        $('#modalCaption').text(caption);

        if (!path) {
            $('#imageContainer').addClass('hidden');
            $('#noImageContainer').removeClass('hidden').addClass('flex');
            $('#imagePreviewModal').removeClass('hidden');
            return;
        }

        // Split path by comma to handle multiple images
        var paths = path.split(',');
        var baseUrl = (type === 'evidence') ? evidencePhotoBaseUrl : findingPhotoBaseUrl;

        paths.forEach(function(imgName) {
            imgName = imgName.trim();
            if (imgName) {
                var imagePath = baseUrl + '/' + imgName;
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
</script>
<script>
    let currentAction = ''; // 'approve' or 'rollback'

    function approveGenba(id) {
        currentAction = 'approve';
        document.getElementById('confirmationId').value = id;

        // Update Modal UI for Approval
        const modal = document.getElementById('confirmationModal');
        modal.querySelector('#modalTitle').innerText = 'Approve Finding?';
        modal.querySelector('#modalMessage').innerHTML = 'Are you sure you want to approve this finding?<br>This action cannot be undone.';

        // Icon (Green Check)
        const iconContainer = modal.querySelector('#modalIcon');
        iconContainer.className = 'w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-5';
        iconContainer.innerHTML = `<svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;

        // Confirm Button (Green)
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.className = 'px-5 py-2.5 bg-emerald-50 text-emerald-600 font-medium rounded-xl hover:bg-emerald-700 transition-colors border border-emerald-200';
        confirmBtn.innerText = 'Yes, Approve';

        modal.classList.remove('hidden');
    }

    function rollbackGenba(id) {
        currentAction = 'rollback';
        document.getElementById('confirmationId').value = id;

        // Update Modal UI for Rollback
        const modal = document.getElementById('confirmationModal');
        modal.querySelector('#modalTitle').innerText = 'Rollback Finding?';
        modal.querySelector('#modalMessage').innerHTML = 'Are you sure you want to rollback this finding?<br>The status will be reset.';

        // Icon (Amber Undo/Refresh)
        const iconContainer = modal.querySelector('#modalIcon');
        iconContainer.className = 'w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-5';
        iconContainer.innerHTML = `<svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 0 1 8 8v2M3 10l6 6m-6-6l6-6"></path></svg>`;

        // Confirm Button (Amber)
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.className = 'px-5 py-2.5 bg-amber-50 text-amber-600 font-medium rounded-xl hover:bg-amber-700 transition-colors border border-amber-200';
        confirmBtn.innerText = 'Yes, Rollback';

        modal.classList.remove('hidden');
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
        document.getElementById('confirmationId').value = '';
    }

    function submitConfirmation() {
        const id = document.getElementById('confirmationId').value;
        const confirmBtn = document.getElementById('confirmBtn');
        const originalText = confirmBtn.innerText;

        let url = '';
        if (currentAction === 'approve') {
            url = "{{ route('execution_genba.approve') }}";
        } else if (currentAction === 'rollback') {
            url = "{{ route('execution_genba.rollback') }}";
        }

        // Show loading state
        confirmBtn.disabled = true;
        confirmBtn.innerText = 'Processing...';

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                closeConfirmationModal();
                if (response.status === 'success') {
                    showToast(response.message, 'success');
                    $('#findingsTable').DataTable().ajax.reload();
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                closeConfirmationModal();
                showToast('Something went wrong. Please try again.', 'error');
            },
            complete: function() {
                confirmBtn.disabled = false;
                confirmBtn.innerText = originalText;
            }
        });
    }
</script>
<!-- Generic Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 z-[60] hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="closeConfirmationModal()"></div>

    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all p-6 text-center border border-slate-100">

            <div id="modalIcon" class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <!-- Icon injected by JS -->
            </div>

            <h3 id="modalTitle" class="text-xl font-bold text-slate-800 mb-2"></h3>
            <p id="modalMessage" class="text-base text-slate-600 mb-6 leading-relaxed"></p>

            <input type="hidden" id="confirmationId">

            <div class="flex gap-3 justify-center">
                <button onclick="closeConfirmationModal()"
                    class="px-5 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmBtn" onclick="submitConfirmation()"
                    class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors shadow-lg shadow-green-200">
                    Yes
                </button>
            </div>
        </div>
    </div>
</div>
@endpush