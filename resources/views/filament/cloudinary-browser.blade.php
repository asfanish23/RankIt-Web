<div x-data="{
    loaded: false,
    init() {
        if (!window.cloudinary) {
            let script = document.createElement('script');
            script.src = 'https://media-library.cloudinary.com/global/all.js';
            script.onload = () => {
                this.loaded = true;
                this.openWidget();
            };
            document.body.appendChild(script);
        } else {
            this.loaded = true;
            this.openWidget();
        }
    },
    openWidget() {
        cloudinary.openMediaLibrary({
            cloud_name: '{{ $cloudName }}',
            api_key: '{{ $apiKey }}',
            timestamp: {{ $timestamp }},
            signature: '{{ $signature }}',
            multiple: false
        }, {
            insertHandler: (data) => {
                if (data.assets && data.assets.length > 0) {
                    let asset = data.assets[0];
                    $wire.set('{{ $statePath }}', asset.secure_url);
                }
                // Traverses up to close the active Filament modal
                let modal = this.$el.closest('.fi-modal');
                if (modal) {
                    let closeBtn = modal.querySelector('[x-on\\:click*=\'close\']') || 
                                   modal.querySelector('[x-on\\:click*=\'isOpen\']') ||
                                   modal.querySelector('button[type=button]') ||
                                   modal.querySelector('button');
                    if (closeBtn) closeBtn.click();
                }
            }
        });
    }
}" class="p-4 text-center">
    <div x-show="!loaded" class="flex flex-col items-center gap-2">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-500"></div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Loading Cloudinary Media Library...</p>
    </div>
    <div x-show="loaded" class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">The Cloudinary Media Library has opened in a popup window.</p>
        <button type="button" @click="openWidget()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-warning-600 hover:bg-warning-500 focus:outline-none" style="background-color: #eab308;">
            Re-open Media Library
        </button>
    </div>
</div>
