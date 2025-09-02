<div x-data="{ 
  showModal: false,
}" class="overflow-hidden rounded-lg transition-transform duration-300 hover:shadow-lg">
  <div class="relative w-full cursor-pointer" @click="if(!imageError) { showModal = true; }">
    @if (!empty($getRecord()['image_id']))
      <img class="w-full h-[350px] transition-transform duration-300 ease-in-out hover:scale-105"
        src="{{ $this->getImageURL($getRecord()['image_id']) }}" alt="Artwork"
        onerror="this.onerror=null; this.src='{{ asset('default.png') }}';" />
    @else
      <div class="w-full h-[350px] bg-gray-200 flex items-center justify-center">
        <span>No Image Available</span>
      </div>
    @endif
    <div
      class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 opacity-0 hover:opacity-100 transition-opacity duration-300">
      <h3 class="text-white text-sm font-medium truncate">
        {{ Str::limit($getRecord()['title'] ?? 'Untitled', 30) }}
      </h3>
    </div>
  </div>
  <div class="flex justify-around items-center p-4">
    <x-filament::icon-button icon="heroicon-o-heart" />
  </div>

  <div x-show="showModal && !imageError" x-cloak
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

      <!-- Modal image -->
      <img class="max-h-[600px] object-contain rounded-lg shadow-2xl"
        src="{{ $this->getImageURL($getRecord()['image_id']) }}" alt="{{ $getRecord()['title'] ?? 'Artwork' }}"
        onerror="this.onerror=null; this.src='{{ asset('default.png') }}';"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" @click.stop>

      <!-- Image title -->
      <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-6">
        <h3 class="text-white text-xl font-semibold">
          {{ Str::limit($getRecord()['title'] ?? 'Untitled', 30) }}
        </h3>
      </div>
    </div>
  </div>
</div>