<div class="admin-login-wrapper">
    <!-- Top-right purple circle decoration -->
    <div class="bg-circle-purple"></div>
    
    <!-- Bottom-left teal circle decoration -->
    <div class="bg-circle-teal"></div>

    <div class="login-container">
        <!-- Glowing Logo -->
        <div class="logo-wrapper">
            <svg class="logo-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 17L9 11L13 15L21 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="3" cy="17" r="1.5" fill="currentColor"/>
                <circle cx="9" cy="11" r="1.5" fill="currentColor"/>
                <circle cx="13" cy="15" r="1.5" fill="currentColor"/>
                <circle cx="21" cy="7" r="1.5" fill="currentColor"/>
            </svg>
        </div>

        <h1 class="title-text">RankeIt</h1>
        <p class="subtitle-text">Community-Based Ranking Aggregation</p>

        <!-- Login Card -->
        <div class="login-card">
            <h2 class="card-title">Welcome Back</h2>

            <form wire:submit.prevent="authenticate">
                {{ $this->form }}

                <button type="submit" class="submit-btn">
                    Log In
                </button>
            </form>
        </div>

    <style>
        .admin-login-wrapper {
            min-height: 100vh;
            width: 100vw;
            background-color: #0F0E17 !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 1.5rem;
            box-sizing: border-box;
        }
        
        .bg-circle-purple {
            position: absolute;
            top: -10rem;
            right: -10rem;
            width: 25rem;
            height: 25rem;
            border-radius: 9999px;
            background-color: #240046;
            opacity: 0.4;
            filter: blur(80px);
            pointer-events: none;
        }
        
        .bg-circle-teal {
            position: absolute;
            bottom: -10rem;
            left: -10rem;
            width: 25rem;
            height: 25rem;
            border-radius: 9999px;
            background-color: #00F5D4;
            opacity: 0.12;
            filter: blur(100px);
            pointer-events: none;
        }
        
        .login-container {
            width: 100%;
            max-width: 440px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
        }
        
        .logo-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .logo-svg {
            width: 4.5rem;
            height: 4.5rem;
            color: #00F5D4;
            filter: drop-shadow(0 0 10px rgba(0, 245, 212, 0.5));
            margin-bottom: 0.75rem;
        }
        
        .title-text {
            font-size: 2rem !important;
            font-weight: 800 !important;
            color: #FFFFFF !important;
            letter-spacing: -0.025em;
            margin: 0 !important;
            line-height: 1.2;
        }
        
        .subtitle-text {
            font-size: 0.75rem !important;
            color: #A7A9BE !important;
            margin-top: 0.35rem !important;
            margin-bottom: 2rem !important;
            letter-spacing: 0.05em;
            font-weight: 500;
            text-align: center;
        }
        
        .login-card {
            width: 100%;
            background-color: rgba(36, 0, 70, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 1.5rem !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(16px);
            box-sizing: border-box;
        }
        
        .card-title {
            font-size: 1.35rem !important;
            font-weight: 700 !important;
            color: #FFFFFF !important;
            text-align: center;
            margin-top: 0 !important;
            margin-bottom: 1.75rem !important;
        }
        
        .submit-btn {
            width: 100%;
            background-color: #9D4EDD !important;
            color: #FFFFFF !important;
            font-weight: 700 !important;
            padding: 0.95rem 1rem !important;
            border-radius: 1rem !important;
            border: none !important;
            cursor: pointer;
            transition: all 0.2s ease !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.85rem !important;
            box-shadow: 0 10px 15px -3px rgba(157, 78, 221, 0.3) !important;
            margin-top: 1.5rem !important;
        }
        
        .submit-btn:hover {
            background-color: #8332C7 !important;
            box-shadow: 0 10px 20px -3px rgba(157, 78, 221, 0.5) !important;
        }

        /* Style Filament inputs slightly to match user inputs */
        .fi-fo-text-input input {
            border-radius: 1rem !important;
            background-color: rgba(255, 255, 255, 0.04) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #FFFFFF !important;
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }

        .fi-fo-text-input input:focus {
            border-color: #9D4EDD !important;
            box-shadow: 0 0 0 2px rgba(157, 78, 221, 0.2) !important;
        }
    </style>
</div>
