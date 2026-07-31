@props(['title', 'description'])

<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6">
    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mb-4 text-indigo-600">
        {{ $slot }}
    </div>
    <h3 class="text-[15px] font-bold text-gray-900 mb-1.5">{{ $title }}</h3>
    <p class="text-[13px] text-gray-500 leading-relaxed">{{ $description }}</p>
</div>