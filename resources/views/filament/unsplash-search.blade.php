<div x-data="{
    search: '',
    images: [],
    loading: false,
    loadingMore: false,
    page: 1,
    apiKey: '{{ env('UNSPLASH_ACCESS_KEY', 'pUz_a8UeXxN00wBiIsgp1unFy_ja3Z7BKVeBYd_Likg') }}',
    async fetchImages() {
        if (!this.search.trim()) return;
        this.loading = true;
        this.page = 1;
        this.images = [];
        try {
            let res = await fetch(`https://api.unsplash.com/search/photos?query=${encodeURIComponent(this.search)}&per_page=12&page=${this.page}&client_id=${this.apiKey}`);
            if (res.ok) {
                let data = await res.json();
                this.images = data.results || [];
            } else {
                throw new Error('API request failed');
            }
        } catch (e) {
            console.error(e);
            // Fallback mock images if API fails or rate limited
            let normalized = this.search.toLowerCase().trim();
            if (normalized.includes('sushi') || normalized.includes('shushi')) {
                this.images = [
                    'https://images.unsplash.com/photo-1579871494447-9811cf80d66c',
                    'https://images.unsplash.com/photo-1611143669185-af224c5e3252',
                    'https://images.unsplash.com/photo-1583623025817-d180a2221d0a',
                    'https://images.unsplash.com/photo-1617196034796-73dfa7b1fd56',
                    'https://images.unsplash.com/photo-1553621042-f6e147245754',
                    'https://images.unsplash.com/photo-1563612116625-3012372fccbc'
                ].map(url => ({
                    urls: {
                        regular: `${url}?auto=format&fit=crop&q=80&w=600`,
                        thumb: `${url}?auto=format&fit=crop&q=80&w=200`
                    }
                }));
            } else {
                this.images = [1, 2, 3, 4, 5, 6, 7, 8, 9].map(i => ({
                    urls: {
                        regular: `https://loremflickr.com/600/450/${encodeURIComponent(normalized)}?random=${i}`,
                        thumb: `https://loremflickr.com/200/150/${encodeURIComponent(normalized)}?random=${i}`
                    }
                }));
            }
        }
        this.loading = false;
    },
    async loadMore() {
        if (!this.search.trim() || this.loadingMore) return;
        this.loadingMore = true;
        this.page++;
        try {
            let res = await fetch(`https://api.unsplash.com/search/photos?query=${encodeURIComponent(this.search)}&per_page=12&page=${this.page}&client_id=${this.apiKey}`);
            if (res.ok) {
                let data = await res.json();
                let newImages = data.results || [];
                this.images = [...this.images, ...newImages];
            } else {
                throw new Error('API request failed');
            }
        } catch (e) {
            console.error(e);
            // Append mock images for next page
            let normalized = this.search.toLowerCase().trim();
            let start = (this.page - 1) * 9 + 1;
            let mockNew = [0, 1, 2, 3, 4, 5, 6, 7, 8].map(i => ({
                urls: {
                    regular: `https://loremflickr.com/600/450/${encodeURIComponent(normalized)}?random=${start + i}`,
                    thumb: `https://loremflickr.com/200/150/${encodeURIComponent(normalized)}?random=${start + i}`
                }
            }));
            this.images = [...this.images, ...mockNew];
        }
        this.loadingMore = false;
    },
    selectImage(url) {
        $wire.set('{{ $statePath }}', url);
        // Find the active Filament modal wrapper and click the close button
        let modal = this.$el.closest('.fi-modal');
        if (modal) {
            let closeBtn = modal.querySelector('[x-on\\:click*=\'close\']') || 
                           modal.querySelector('[x-on\\:click*=\'isOpen\']') ||
                           modal.querySelector('button[type=button]') ||
                           modal.querySelector('button');
            if (closeBtn) {
                closeBtn.click();
            }
        }
    }
}" x-init="search = '{{ e($name ?? '') }}'; fetchImages();" class="p-2">
    
    <!-- Search Bar -->
    <div class="flex items-center gap-2 mb-4">
        <input 
            type="text" 
            x-model="search" 
            @keydown.enter.prevent="fetchImages()" 
            placeholder="Search Unsplash photos..." 
            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
        />
        <button 
            type="button" 
            @click="fetchImages()" 
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-warning-600 hover:bg-warning-500 focus:outline-none"
            style="background-color: #eab308;"
        >
            Search
        </button>
    </div>

    <!-- Loading Spinner -->
    <div x-show="loading" class="flex justify-center my-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-500"></div>
    </div>

    <!-- Results Area -->
    <div x-show="!loading" class="flex flex-col gap-4">
        <!-- Image Grid -->
        <div class="grid grid-cols-3 gap-3 overflow-y-auto max-h-[300px]">
            <template x-for="img in images" :key="img.urls.regular">
                <div 
                    @click="selectImage(img.urls.regular)" 
                    class="group relative cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-yellow-500 transition duration-150 ease-in-out bg-gray-100 dark:bg-gray-900"
                >
                    <img :src="img.urls.thumb" class="w-full h-24 object-cover group-hover:scale-105 transition duration-150" />
                </div>
            </template>
        </div>

        <!-- Load More Button -->
        <div x-show="images.length > 0" class="flex justify-center mt-2">
            <button 
                type="button" 
                @click="loadMore()" 
                class="inline-flex items-center gap-2 text-sm font-semibold text-yellow-500 hover:text-yellow-400 focus:outline-none"
            >
                <span x-show="loadingMore" class="animate-spin rounded-full h-4 w-4 border-b-2 border-yellow-500 inline-block"></span>
                <span x-show="!loadingMore">🔄 Load More</span>
            </button>
        </div>
        
        <div x-show="images.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
            No images found. Try another search.
        </div>
    </div>
</div>
