@php
    // $event akan ada saat form dipakai di halaman edit, null saat create.
    $event = $event ?? null;
@endphp

@if ($errors->any())
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
        <p class="text-[13px] font-semibold text-red-700 mb-1">Periksa kembali input Anda:</p>
        <ul class="text-[12.5px] text-red-600 list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <!-- Judul -->
    <div class="md:col-span-2">
        <label for="title" class="block text-[12.5px] font-semibold text-gray-700 mb-1.5">
            Judul Kegiatan <span class="text-red-500">*</span>
        </label>
        <input
            type="text"
            id="title"
            name="title"
            value="{{ old('title', $event?->title) }}"
            required
            class="w-full h-[42px] px-3.5 bg-gray-50/60 border border-gray-200/80 rounded-lg text-[13.5px] text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-900/5 focus:border-gray-300 transition-all duration-200"
            placeholder="Contoh: Rapat Koordinasi Bulanan"
        >
    </div>

    <!-- Kategori -->
    <div>
        <label for="category" class="block text-[12.5px] font-semibold text-gray-700 mb-1.5">
            Kategori <span class="text-red-500">*</span>
        </label>
        <select
            id="category"
            name="category"
            required
            class="w-full h-[42px] px-3.5 bg-gray-50/60 border border-gray-200/80 rounded-lg text-[13.5px] text-gray-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-900/5 focus:border-gray-300 transition-all duration-200"
        >
            @php
                $categories = ['Rapat', 'Agenda Kantor', 'Kegiatan', 'Layanan', 'Lainnya'];
                $selectedCategory = old('category', $event?->category);
            @endphp
            <option value="" disabled {{ !$selectedCategory ? 'selected' : '' }}>Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" {{ $selectedCategory === $category ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Warna Label -->
    <div>
        <label for="color" class="block text-[12.5px] font-semibold text-gray-700 mb-1.5">
            Warna Label
        </label>
        <select
            id="color"
            name="color"
            class="w-full h-[42px] px-3.5 bg-gray-50/60 border border-gray-200/80 rounded-lg text-[13.5px] text-gray-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-900/5 focus:border-gray-300 transition-all duration-200"
        >
            @php
                $colors = [
                    '#3B82F6' => 'Biru',
                    '#6366F1' => 'Indigo',
                    '#F59E0B' => 'Amber',
                    '#10B981' => 'Hijau',
                    '#F43F5E' => 'Rose',
                    '#6B7280' => 'Netral',
                ];
                $selectedColor = old('color', $event?->color ?? '#3B82F6');
            @endphp
            @foreach ($colors as $hex => $label)
                <option value="{{ $hex }}" {{ $selectedColor === $hex ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Mulai -->
    <div>
        <label for="start_at" class="block text-[12.5px] font-semibold text-gray-700 mb-1.5">
            Waktu Mulai <span class="text-red-500">*</span>
        </label>
        <input
            type="datetime-local"
            id="start_at"
            name="start_at"
            value="{{ old('start_at', $event?->start_at?->format('Y-m-d\TH:i')) }}"
            required
            class="w-full h-[42px] px-3.5 bg-gray-50/60 border border-gray-200/80 rounded-lg text-[13.5px] text-gray-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-900/5 focus:border-gray-300 transition-all duration-200"
        >
    </div>

    <!-- Selesai -->
    <div>
        <label for="end_at" class="block text-[12.5px] font-semibold text-gray-700 mb-1.5">
            Waktu Selesai
            <span class="text-gray-400 font-normal">(opsional)</span>
        </label>
        <input
            type="datetime-local"
            id="end_at"
            name="end_at"
            value="{{ old('end_at', $event?->end_at?->format('Y-m-d\TH:i')) }}"
            class="w-full h-[42px] px-3.5 bg-gray-50/60 border border-gray-200/80 rounded-lg text-[13.5px] text-gray-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-900/5 focus:border-gray-300 transition-all duration-200"
        >
    </div>

    <!-- Lokasi -->
    <div class="md:col-span-2">
        <label for="location" class="block text-[12.5px] font-semibold text-gray-700 mb-1.5">
            Lokasi
            <span class="text-gray-400 font-normal">(opsional)</span>
        </label>
        <input
            type="text"
            id="location"
            name="location"
            value="{{ old('location', $event?->location) }}"
            class="w-full h-[42px] px-3.5 bg-gray-50/60 border border-gray-200/80 rounded-lg text-[13.5px] text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-900/5 focus:border-gray-300 transition-all duration-200"
            placeholder="Contoh: Ruang Rapat Lt. 3"
        >
    </div>

    <!-- Deskripsi -->
    <div class="md:col-span-2">
        <label for="description" class="block text-[12.5px] font-semibold text-gray-700 mb-1.5">
            Deskripsi
            <span class="text-gray-400 font-normal">(opsional)</span>
        </label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="w-full px-3.5 py-3 bg-gray-50/60 border border-gray-200/80 rounded-lg text-[13.5px] text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-900/5 focus:border-gray-300 transition-all duration-200 resize-none"
            placeholder="Catatan tambahan mengenai kegiatan ini..."
        >{{ old('description', $event?->description) }}</textarea>
    </div>

</div>