<x-filament::page>
    @vite('resources/css/filament/admin/theme.css')
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($this->images as $item)
            <x-image-card :image-url="$item['image_url']" :title="$item['title']" />
        @endforeach
    </div>
</x-filament::page>