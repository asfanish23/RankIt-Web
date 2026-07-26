<div class="min-h-screen bg-[#0F0E17] flex flex-col items-center justify-center relative overflow-hidden px-4">
    <!-- Top-right purple circle decoration -->
    <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-[#240046] opacity-30 blur-2xl pointer-events-none"></div>
    
    <!-- Bottom-left teal circle decoration -->
    <div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full bg-[#00F5D4] opacity-10 blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-[480px] z-10 flex flex-col items-center">
        <!-- Glowing Logo -->
        <div class="mb-3 flex items-center justify-center text-[#00F5D4]">
            <svg class="w-16 h-16 filter drop-shadow-[0_0_10px_rgba(0,245,212,0.5)]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 17L9 11L13 15L21 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="3" cy="17" r="1.5" fill="currentColor"/>
                <circle cx="9" cy="11" r="1.5" fill="currentColor"/>
                <circle cx="13" cy="15" r="1.5" fill="currentColor"/>
                <circle cx="21" cy="7" r="1.5" fill="currentColor"/>
            </svg>
        </div>

        <h1 class="text-3xl font-extrabold text-white tracking-tight">RankeIt</h1>
        <p class="text-xs text-[#a7a9be] mt-1 mb-8 tracking-wide font-medium">Community-Based Ranking Aggregation</p>

        <!-- Login Card -->
        <div class="w-full bg-[#240046]/20 border border-white/10 rounded-3xl p-8 backdrop-blur-md shadow-2xl">
            <h2 class="text-xl font-bold text-white text-center mb-6">Welcome Back</h2>

            <form wire:submit.prevent="authenticate" class="space-y-6">
                {{ $this->form }}

                <button type="submit" class="w-full bg-[#9d4edd] hover:bg-[#8332c7] text-white font-bold py-3.5 px-4 rounded-2xl transition duration-200 uppercase tracking-wider text-sm shadow-lg shadow-[#9d4edd]/30">
                    Log In
                </button>
            </form>
        </div>
    </div>
</div>
