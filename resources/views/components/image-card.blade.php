@props([
    'imageUrl',
    'title',
    'alt' => null,
])
<div
    class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
    <div class="aspect-square overflow-hidden">
        <img src="{{ $imageUrl }}" alt="{{ $alt ?? $title }}"
            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
    </div>
    <div class="p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $title }}</h3>
    </div>
</div>
