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

                <div class="flex items-center gap-1.5">
                    <button class="p-2 hover:bg-slate-100 rounded-xl text-slate-500 transition-colors" title="Clear History" onclick="clearChatHistory()">
                        <i class="fa-solid fa-trash-can text-base text-red-500 hover:text-red-600"></i>
                    </button>
                    <button class="p-2 hover:bg-slate-100 rounded-xl text-slate-500 transition-colors" title="Agent Info" onclick="toggleRightPanel()">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                    </button>
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
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-slate-500"></i>
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
                    <div class="mt-2.5 flex justify-center items-center gap-1.5">
                        <span class="text-[10px] font-bold text-slate-500">Ready to assist</span>
                    </div>
                </div>

                <!-- Quick Template Prompts -->
                <div>
                    <h5 class="text-[10px] font-bold text-slate-400 mb-3">Quick Prompts</h5>
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
        if (confirm('Apakah Anda yakin ingin menghapus seluruh riwayat percakapan?')) {
            localStorage.removeItem('grace_chat_messages');
            chatMessages = [];
            loadChatHistory();
        }
    }

    // Load and render history from localStorage
    function loadChatHistory() {
        const container = document.getElementById('messagesContainer');
        
        // Define welcome banner HTML template
        const welcomeBannerHtml = `
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 text-center max-w-xl mx-auto my-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-database text-lg"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 mb-1">Welcome to Asisten GRACE</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Saya dapat membantu menyajikan data temuan audit, status persetujuan, dan statistik audit internal secara langsung dari database sistem secara real-time.
                </p>
            </div>
        `;

        container.innerHTML = welcomeBannerHtml;

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
