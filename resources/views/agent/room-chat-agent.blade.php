@extends('layouts.app')

@section('title', 'Asisten GRACE')

@section('content')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="lg:ml-20 h-screen flex flex-col bg-slate-50 overflow-hidden">
    @include('layouts.header')

    <!-- Chat App Layout (No left sidebar, only Chat Area & Right Info Panel) -->
    <main class="flex-1 flex overflow-hidden h-[calc(100vh-4rem)]">
        <!-- Center Panel: Chat conversation -->
        <div class="flex-1 flex flex-col bg-slate-50 relative" id="chatAreaPanel">
            <!-- Active Chat Header -->
            <div class="h-16 px-6 bg-white border-b border-slate-200 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-robot text-base"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-800 truncate">Asisten GRACE</h3>
                        <p class="text-[10px] text-slate-400 font-medium truncate">Audit Query Helper | Real-time database checker</p>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 relative">
                    <button id="menuDropdownBtn" class="p-2 hover:bg-slate-100 rounded-xl text-slate-500 transition-colors" title="Menu" onclick="toggleMenuDropdown(event)">
                        <i class="fa-solid fa-ellipsis-vertical text-lg"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="menuDropdown" class="hidden absolute right-0 top-11 w-48 bg-white border border-slate-200 rounded-xl shadow-lg z-50 py-1.5 transform origin-top-right transition-all">
                        <button onclick="triggerClearChat(event)" class="w-full text-left px-4 py-2 text-[14px] text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-trash-can text-[14px]"></i>
                            <span>Hapus Percakapan</span>
                        </button>
                        <button onclick="triggerTogglePanel(event)" class="w-full text-left px-4 py-2 text-[14px] text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-circle-info text-[14px] text-slate-500"></i>
                            <span>Info Asisten</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages Stream -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4 flex flex-col" id="messagesContainer">
                <!-- Chat history will be loaded dynamically here -->
            </div>

            <!-- Chat Footer Input -->
            <div class="p-4 bg-white border-t border-slate-200 flex-shrink-0">
                <form id="chatForm" onsubmit="sendChatMessage(event)" class="flex items-center gap-3">
                    <div class="flex-1 relative">
                        <input type="text" id="messageInput" placeholder="Ask GRACE Helper..."
                            class="w-full py-2.5 pl-4 pr-12 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" autocomplete="off">
                    </div>
                    <button type="submit" class="p-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Panel: AI Capabilities & Prompts (Width: 20% on desktop) -->
        <div class="hidden xl:flex w-80 flex-col bg-white border-l border-slate-200 flex-shrink-0" id="infoDetailPanel">
            <!-- Right Panel Header (Aligned with Center Panel Header) -->
            <div class="h-16 px-6 border-b border-slate-200 flex items-center flex-shrink-0">
                <h3 class="text-md font-semibold text-slate-700 flex items-center gap-2">
                    <span>Helper Options</span>
                </h3>
            </div>

            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Agent Overview Card -->
                <div class="text-center pb-6 border-b border-slate-100 mb-6">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-2xl mx-auto shadow-md mb-3">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <h4 class="font-bold text-slate-800">Asisten GRACE</h4>
                    <p class="text-xs text-slate-500">Local Database Engine</p>
                </div>

                <!-- Quick Template Prompts -->
                <div>
                    <h5 class="text-xs font-semibold text-slate-400 mb-3">Quick Prompts</h5>
                    <div class="flex flex-col gap-2">
                        <button onclick="usePrompt('Ada berapa temuan yang overdue?')" class="text-left text-xs bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 p-3 rounded-xl border border-slate-200/60 transition-colors">
                            "Ada berapa temuan yang overdue?"
                        </button>
                        <button onclick="usePrompt('Temuan yang belum di-approve ada berapa?')" class="text-left text-xs bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 p-3 rounded-xl border border-slate-200/60 transition-colors">
                            "Temuan yang belum di-approve ada berapa?"
                        </button>
                        <button onclick="usePrompt('Berapa total temuan saat ini?')" class="text-left text-xs bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 p-3 rounded-xl border border-slate-200/60 transition-colors">
                            "Berapa total temuan saat ini?"
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Custom Professional Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="closeDeleteModal()"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md transform transition-all shadow-xl">
            <!-- Header -->
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Riwayat Chat</h3>
                <p class="text-slate-500 text-sm">Apakah Anda yakin ingin menghapus seluruh riwayat percakapan? Tindakan ini tidak dapat dibatalkan.</p>
            </div>

            <!-- Footer Buttons -->
            <div class="flex gap-3 p-6 pt-0">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors text-sm">
                    Batal
                </button>
                <button type="button" onclick="executeDelete()"
                    class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-colors text-sm">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Page Style for Smooth Flex Layout -->
<style>
    body {
        overflow: hidden;
    }
</style>
@endsection

@push('scripts')
<script>
    // In-memory chat messages history array
    let chatMessages = [];

    // Toggle Right Detail Panel
    function toggleRightPanel() {
        const panel = document.getElementById('infoDetailPanel');
        panel.classList.toggle('hidden');
        panel.classList.toggle('xl:flex');
    }

    // Toggle Menu Dropdown
    function toggleMenuDropdown(e) {
        e.stopPropagation();
        const menu = document.getElementById('menuDropdown');
        menu.classList.toggle('hidden');
    }

    function triggerClearChat(e) {
        e.stopPropagation();
        document.getElementById('menuDropdown').classList.add('hidden');
        clearChatHistory();
    }

    function triggerTogglePanel(e) {
        e.stopPropagation();
        document.getElementById('menuDropdown').classList.add('hidden');
        toggleRightPanel();
    }

    // Close dropdown on click outside
    document.addEventListener('click', (e) => {
        const menu = document.getElementById('menuDropdown');
        const btn = document.getElementById('menuDropdownBtn');
        if (menu && !menu.classList.contains('hidden') && !menu.contains(e.target) && e.target !== btn && !btn.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // Quick prompt template helper
    function usePrompt(text) {
        const input = document.getElementById('messageInput');
        input.value = text;
        input.focus();
    }

    // Save history to localStorage
    function saveToLocalStorage() {
        localStorage.setItem('grace_chat_messages', JSON.stringify(chatMessages));
    }

    // Clear chat history
    function clearChatHistory() {
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }

    // Close Delete Modal
    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
    }

    // Execute deletion of chat history
    function executeDelete() {
        localStorage.removeItem('grace_chat_messages');
        chatMessages = [];
        loadChatHistory();
        closeDeleteModal();
    }

    // Load and render history from localStorage
    function loadChatHistory() {
        const container = document.getElementById('messagesContainer');
        
        // Define welcome banner HTML template
        const welcomeBannerHtml = `
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 text-center max-w-xl mx-auto my-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-robot text-lg"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 mb-1">Welcome to Asisten GRACE</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Saya dapat membantu menyajikan data temuan audit, status persetujuan, dan statistik audit internal secara langsung dari database sistem secara real-time.
                </p>
            </div>
        `;

        // Fetch stored messages or initialize with default message
        const stored = localStorage.getItem('grace_chat_messages');
        if (stored) {
            chatMessages = JSON.parse(stored);
        } else {
            // Setup default starting greeting message
            chatMessages = [{
                sender: 'left',
                text: 'Halo! Ada yang bisa saya bantu terkait pencarian data audit internal hari ini? Coba tanyakan hal-hal seperti jumlah temuan overdue atau persetujuan dokumen CAR.'
            }];
            saveToLocalStorage();
        }

        // Only show welcome banner if there are no real user conversations yet (only default welcome greeting exists)
        if (chatMessages.length <= 1) {
            container.innerHTML = welcomeBannerHtml;
        } else {
            container.innerHTML = '';
        }

        // Render each message
        chatMessages.forEach(msg => {
            appendMessageToContainer(msg.sender, msg.text, false);
        });

        // Auto Scroll to bottom
        container.scrollTop = container.scrollHeight;
    }

    // Helper function to append message html to container
    function appendMessageToContainer(sender, text, animate = true) {
        const container = document.getElementById('messagesContainer');
        const animationClass = animate ? 'animate-fade-in' : '';
        let msgHtml = '';

        if (sender === 'left') {
            msgHtml = `
                <div class="flex items-start gap-3 max-w-[85%] self-start ${animationClass}">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-xs flex-shrink-0">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="bg-white px-4 py-3 rounded-[20px] rounded-bl-none border border-slate-200 shadow-sm text-sm text-slate-800 leading-relaxed space-y-2">
                            ${text}
                        </div>
                    </div>
                </div>
            `;
        } else {
            msgHtml = `
                <div class="flex items-start gap-3 max-w-[85%] self-end flex-row-reverse ${animationClass}">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 text-xs flex-shrink-0 border border-slate-300">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="flex flex-col gap-1 items-end">
                        <div class="bg-blue-600 px-4 py-3 rounded-[20px] rounded-br-none shadow-md text-sm text-white leading-relaxed">
                            ${text}
                        </div>
                    </div>
                </div>
            `;
        }

        container.insertAdjacentHTML('beforeend', msgHtml);
    }

    // Send chat message
    function sendChatMessage(e) {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const text = input.value.trim();
        if (!text) return;

        // Clear welcome banner and initial welcome message when user sends their first message
        if (chatMessages.length === 1 && chatMessages[0].sender === 'left') {
            const container = document.getElementById('messagesContainer');
            container.innerHTML = '';
            chatMessages = [];
        }

        // Push User Message to local state and save
        chatMessages.push({
            sender: 'right',
            text: text
        });
        saveToLocalStorage();

        // Render to container immediately
        appendMessageToContainer('right', text, true);
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;

        // Reset input field
        input.value = "";

        // Trigger AI Agent Thinking Indicator and Response
        triggerAiResponse(text);
    }

    // Real AI response generator via backend proxy
    function triggerAiResponse(userText) {
        const container = document.getElementById('messagesContainer');

        // Thinking Indicator
        const loadingId = "ai-thinking-indicator";
        const loadingHtml = `
            <div class="flex items-start gap-3 max-w-[85%] self-start animate-pulse" id="${loadingId}">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-xs flex-shrink-0">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="flex flex-col gap-1">
                    <div class="bg-white px-4 py-2.5 rounded-[20px] rounded-bl-none border border-slate-200 shadow-sm text-xs font-semibold text-slate-400">
                        Checking database...
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', loadingHtml);
        container.scrollTop = container.scrollHeight;

        // Perform actual HTTP fetch request to the Laravel backend
        fetch("{{ route('agent.chat.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({
                message: userText
            })
        })
        .then(res => res.json())
        .then(data => {
            // Remove Thinking Indicator
            const indicator = document.getElementById(loadingId);
            if (indicator) indicator.remove();

            const responseText = data.response;

            // Push to local state and save
            chatMessages.push({
                sender: 'left',
                text: responseText
            });
            saveToLocalStorage();

            // Render to container
            appendMessageToContainer('left', responseText, true);
            container.scrollTop = container.scrollHeight;
        })
        .catch(err => {
            console.error(err);
            const indicator = document.getElementById(loadingId);
            if (indicator) indicator.remove();

            const errMsg = 'Maaf, terjadi kesalahan koneksi saat memproses permintaan Anda.';
            
            // Render error message to container (no saving to localStorage for errors)
            const aiMsgHtml = `
                <div class="flex items-start gap-3 max-w-[85%] self-start">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs flex-shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="bg-red-50 border border-red-200 px-4 py-3 rounded-[20px] rounded-bl-none text-sm text-red-800 leading-relaxed">
                            ${errMsg}
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', aiMsgHtml);
            container.scrollTop = container.scrollHeight;
        });
    }

    // Auto Scroll to bottom of message container on first load
    document.addEventListener('DOMContentLoaded', () => {
        loadChatHistory();
    });
</script>
@endpush
