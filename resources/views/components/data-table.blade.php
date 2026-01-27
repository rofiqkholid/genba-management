@props(['tableId' => 'findingsTable'])

<!-- Data Count Dropdown (Hidden, moved via JS) -->
<div id="rowCountFooter_{{ $tableId }}" class="hidden">
    <div class="flex items-center gap-2 mr-6 text-slate-600 font-medium z-20"
        x-data="{ 
            open: false, 
            selected: 10,
            init() {
                // Default 10
            },
            select(val) {
                this.selected = val;
                this.open = false;
                $('#{{ $tableId }}').DataTable().page.len(val).draw();
            }
        }"
        @click.outside="open = false">

        <div class="relative">
            <button @click="open = !open"
                class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:border-slate-300 transition-all outline-none min-w-[70px] justify-between group">
                <span x-text="selected" class="text-sm font-semibold text-slate-700"></span>
                <i class="fa-solid fa-chevron-up text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open"
                style="display: none;"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute bottom-full left-0 mb-2 w-full min-w-[70px] bg-white border border-slate-100 rounded-lg overflow-hidden z-50">
                <template x-for="val in [10, 50, 100]">
                    <button @click="select(val)"
                        class="block w-full text-left px-3 py-2 text-sm transition-colors border-b border-slate-50 last:border-0"
                        :class="selected === val ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800'">
                        <span x-text="val"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Move Custom Dropdown to DataTables Footer
        setTimeout(function() {
            var $tableInfo = $('#{{ $tableId }}_info');
            if ($tableInfo.length) {
                var $dropdown = $('#rowCountFooter_{{ $tableId }}').children().first();

                // Check if already moved to avoid duplication if scripts run twice
                if (!$dropdown.parent().hasClass('flex')) {
                    // Create a wrapper
                    var $wrapper = $('<div class="flex items-center gap-4 float-left"></div>');

                    $tableInfo.before($wrapper);
                    $wrapper.append($dropdown);
                    $wrapper.append($tableInfo);
                }
            }
        }, 500); // 500ms to be safe after DT init
    });
</script>
@endpush