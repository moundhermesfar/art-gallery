<div x-data="{ 
  imageError: false,
  showModal: false,
  downloadImage(imageId, title) {
    if (!imageId) {
      return;
    }
    
    const downloadUrl = `/download-image?image_id=${encodeURIComponent(imageId)}&title=${encodeURIComponent(title)}`;
    
    // Create a temporary link element and trigger download
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
}" class="overflow-hidden rounded-lg transition-transform duration-300 hover:shadow-lg">
  <div class="relative w-full cursor-pointer" @click="showModal = true">
    @if ($getRecord()['image_url'])
      <img class="w-full h-[350px] transition-transform duration-300 ease-in-out hover:scale-105"
        src="{{ $getRecord()['image_url'] }}" alt="Artwork"
        onerror="this.onerror=null; this.src='{{ asset('default.png') }}'; this.imageError=true;" />
    @else
      <div class="w-full h-[350px] bg-gray-200 flex items-center justify-center">
        <span>No Image Available</span>
      </div>
    @endif
    <div
      class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 opacity-0 hover:opacity-100 transition-opacity duration-300">
      <h3 class="text-white text-sm font-medium truncate">
        {!! Str::limit(strip_tags($getRecord()['description'] ?? 'No description available'), 40) !!}
      </h3>
    </div>
  </div>
  <div class="flex justify-around items-center p-4">
    <x-filament::icon-button icon="heroicon-o-heart" />
    <x-filament::icon-button icon="heroicon-o-arrow-down-tray"
      x-bind:disabled="imageError || '{{ empty($getRecord()['image_id']) ? 'true' : 'false' }}' === 'true'"
      x-bind:class="{ 'opacity-50 cursor-not-allowed': imageError || '{{ empty($getRecord()['image_id']) ? 'true' : 'false' }}' === 'true' }"
      @click.stop="if (!imageError && '{{ !empty($getRecord()['image_id']) ? 'true' : 'false' }}' === 'true') downloadImage('{{ $getRecord()['image_id'] ?? '' }}', '{{ $getRecord()['title'] ?? 'artwork' }}')" />
  </div>

  <div x-show="showModal" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showModal = false"
    @keydown.escape.window="showModal = false">
    <div class="relative max-w-4xl max-h-[90vh] mx-4">
      <!-- Close button -->
      <button @click="showModal = false"
        class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors duration-200 z-10">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      @if ($getRecord()['image_url'])
        <!-- Modal image -->
        <img class="max-h-[600px] object-contain rounded-lg shadow-2xl" src="{{ $getRecord()['image_url'] }}"
          alt="{{ $getRecord()['title'] ?? 'Artwork' }}"
          onerror="this.onerror=null; this.src='{{ asset('default.png') }}';"
          x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
          x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
          x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" @click.stop>
      @else
        <div class="w-[500px] h-[400px] bg-gray-200 flex items-center justify-center">
          <h2 class="text-2xl font-semibold">No Image Available</h2>
        </div>
      @endif

      <!-- Image description -->
      <div
        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 via-black/50 to-transparent p-4 opacity-0 hover:opacity-100 transition-opacity duration-300 max-h-48 overflow-hidden">
        <div
          class="text-white text-md font-semibold prose prose-invert max-w-none max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-white/30 scrollbar-track-transparent">
          {!! $getRecord()['description'] ?? 'No description available' !!}
        </div>
      </div>
    </div>
  </div>
</div>