<x-app-layout>
   <div class="mx-auto max-w-2xl" x-data="documentUpload()">

    <!-- Header -->
    <div class="mb-8 flex items-center gap-4">
        <a
            href="{{ route('documents.index') }}"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-200/80 bg-white text-gray-400 shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900"
        >
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                Upload Document
            </h1>

            <p class="mt-1 text-[14px] text-gray-500">
                Add a new document to your organization's AI knowledge base.
            </p>
        </div>
    </div>


    <!-- Upload Form -->
    <form
        action="{{ route('documents.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="relative overflow-hidden rounded-[24px] border border-gray-200/80 bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]"
        @submit.prevent="submitForm"
    >

        @csrf

        <!-- Decorative Background Glow -->
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-50/50 blur-3xl"></div>


        <div class="relative space-y-8 p-6 sm:p-10">

            <!-- Title Input -->
            <div>
                <label
                    for="title"
                    class="mb-2.5 block text-[13px] font-semibold text-gray-900"
                >
                    Document Title
                </label>

                <input
                    type="text"
                    name="title"
                    id="title"
                    x-model="title"
                    class="block h-[44px] w-full rounded-[12px] border border-gray-200/80 bg-gray-50/50 px-4 text-[14px] text-gray-900 placeholder-gray-400 shadow-sm transition-all focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="e.g., Q3 Financial Report 2026"
                    value="{{ old('title') }}"
                    required
                >

                @error('title')
                    <p class="mt-2 text-[13px] text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            <!-- File Input -->
            <div>

                <label
                    class="mb-2.5 block text-[13px] font-semibold text-gray-900"
                >
                    Document File
                </label>


                <div
                    class="rounded-[16px] border border-gray-200/80 bg-gray-50/50 p-5 transition-all"
                    :class="file ? 'border-indigo-200 bg-indigo-50/30' : 'hover:border-indigo-200 hover:bg-indigo-50/30'"
                >

                    <div class="flex items-start gap-4">

                        <!-- File Icon -->
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[10px] border border-gray-200 bg-white text-gray-400 shadow-sm"
                        >

                            <template x-if="fileType === 'pdf'">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </template>

                            <template x-if="fileType !== 'pdf'">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 3h6m-9 6h12a2 2 0 002-2V7.828a2 2 0 00-.586-1.414l-4.828-4.828A2 2 0 0012.172 1H6a2 2 0 00-2 2v16a2 2 0 002 2z" />
                                </svg>
                            </template>

                        </div>


                        <div class="min-w-0 flex-1">

                            <!-- Input -->
                            <input
                                type="file"
                                name="document"
                                id="document"
                                required
                                accept=".pdf,.docx,.xlsx,.csv,.pptx,.txt,.jpg,.jpeg,.png,.webp"
                                @change="handleFile"
                                class="block w-full cursor-pointer text-[13px] text-gray-500 transition-all
                                       file:mr-4 file:cursor-pointer file:rounded-[8px]
                                       file:border-0 file:bg-white file:px-4 file:py-2.5
                                       file:text-[13px] file:font-semibold
                                       file:text-indigo-600 file:shadow-sm
                                       file:ring-1 file:ring-gray-200
                                       hover:file:bg-gray-50 hover:file:text-indigo-700"
                            >


                            <!-- Empty State -->
                            <template x-if="!file">
                                <p class="mt-2.5 text-[12px] text-gray-500">
                                    PDF, DOCX, XLSX, CSV, PPTX, TXT, JPG, PNG, or WEBP.
                                </p>
                            </template>


                            <!-- Selected File -->
                            <template x-if="file">

                                <div class="mt-3 flex items-center justify-between gap-3 rounded-[10px] border border-gray-200 bg-white px-3 py-2.5">

                                    <div class="min-w-0">

                                        <p
                                            class="truncate text-[12px] font-semibold text-gray-800"
                                            x-text="file.name"
                                        ></p>

                                        <p
                                            class="mt-0.5 text-[11px] text-gray-400"
                                            x-text="fileType.toUpperCase() + ' · ' + formatSize(file.size)"
                                        ></p>

                                    </div>

                                    <button
                                        type="button"
                                        @click="clearFile"
                                        class="shrink-0 text-[11px] font-medium text-gray-400 transition-colors hover:text-red-500"
                                    >
                                        Remove
                                    </button>

                                </div>

                            </template>


                            <p class="mt-2.5 text-[12px] text-gray-500">
                                INAMOR will extract and process this document into organizational knowledge.
                            </p>

                        </div>

                    </div>

                </div>


                @error('document')
                    <p class="mt-2 text-[13px] text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>


        <!-- Form Actions -->
        <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-5 sm:flex sm:flex-row-reverse sm:px-10">

            <button
                type="submit"
                :disabled="uploading"
                class="flex h-[42px] w-full items-center justify-center gap-2 rounded-[12px] bg-[#0F172A] px-8 text-[14px] font-medium text-white shadow-[0_1px_2px_rgba(0,0,0,0.1)] transition-all hover:bg-[#1E293B] active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60 sm:ml-3 sm:w-auto"
            >

                <template x-if="!uploading">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                    </svg>
                </template>

                <template x-if="uploading">
                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </template>

                <span x-text="uploading ? 'Processing...' : 'Upload to Knowledge Base'"></span>

            </button>


            <a
                href="{{ route('documents.index') }}"
                class="mt-3 flex h-[42px] w-full items-center justify-center rounded-[12px] border border-gray-200/80 bg-white px-6 text-[14px] font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900 sm:mt-0 sm:w-auto"
                :class="uploading ? 'pointer-events-none opacity-50' : ''"
            >
                Cancel
            </a>

        </div>

    </form>


    <!-- Processing Modal -->
    <template x-if="uploading">

        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/20 px-5 backdrop-blur-[3px]">

            <div class="w-full max-w-md overflow-hidden rounded-[24px] border border-gray-200/80 bg-white shadow-[0_20px_60px_-15px_rgba(0,0,0,0.2)]">

                <div class="p-7 sm:p-8">

                    <!-- Icon -->
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-[16px] bg-indigo-50 text-indigo-600">

                        <svg
                            class="h-7 w-7"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9H3" />
                        </svg>

                    </div>


                    <div class="mt-5 text-center">

                        <h2 class="text-[17px] font-semibold text-gray-900">
                            <span x-show="uploadPercent < 100">
                                Uploading document
                            </span>

                            <span x-show="uploadPercent >= 100">
                                Processing knowledge
                            </span>
                        </h2>

                        <p class="mt-1.5 text-[13px] leading-5 text-gray-500">
                            <span x-show="uploadPercent < 100">
                                Uploading your file securely...
                            </span>

                            <span x-show="uploadPercent >= 100">
                                INAMOR is extracting and organizing the document.
                                This may take a moment.
                            </span>
                        </p>

                    </div>


                    <!-- Progress -->
                    <div class="mt-7">

                        <div class="mb-2 flex items-center justify-between text-[11px] font-medium">

                            <span class="text-gray-500">
                                <span x-show="uploadPercent < 100">
                                    Upload progress
                                </span>

                                <span x-show="uploadPercent >= 100">
                                    Processing
                                </span>
                            </span>

                            <span
                                x-show="uploadPercent < 100"
                                class="text-gray-700"
                                x-text="uploadPercent + '%'"
                            ></span>

                        </div>


                        <!-- Real upload progress -->
                        <div
                            x-show="uploadPercent < 100"
                            class="h-2 overflow-hidden rounded-full bg-gray-100"
                        >
                            <div
                                class="h-full rounded-full bg-indigo-600 transition-all duration-200"
                                :style="'width: ' + uploadPercent + '%'"
                            ></div>
                        </div>


                        <!-- Processing indicator -->
                        <div
                            x-show="uploadPercent >= 100"
                            class="h-2 overflow-hidden rounded-full bg-gray-100"
                        >
                            <div class="h-full w-1/3 rounded-full bg-indigo-600 animate-[progress_1.4s_ease-in-out_infinite]"></div>
                        </div>

                    </div>


                    <!-- Steps -->
                    <div class="mt-7 space-y-3">

                        <div class="flex items-center gap-3 text-[12px]">

                            <div
                                class="flex h-5 w-5 items-center justify-center rounded-full"
                                :class="uploadPercent >= 100 ? 'bg-indigo-100 text-indigo-600' : 'bg-indigo-600 text-white'"
                            >
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            <span
                                :class="uploadPercent >= 100 ? 'text-gray-400' : 'font-medium text-gray-900'"
                            >
                                Upload document
                            </span>

                        </div>


                        <div class="flex items-center gap-3 text-[12px]">

                            <div
                                class="flex h-5 w-5 items-center justify-center rounded-full"
                                :class="uploadPercent >= 100 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-300'"
                            >
                                <svg
                                    x-show="uploadPercent >= 100"
                                    class="h-3 w-3"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            <span
                                :class="uploadPercent >= 100 ? 'font-medium text-gray-900' : 'text-gray-400'"
                            >
                                Extract and process knowledge
                            </span>

                        </div>


                        <div class="flex items-center gap-3 text-[12px]">

                            <div class="flex h-5 w-5 items-center justify-center rounded-full bg-gray-100 text-gray-300">
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            <span class="text-gray-400">
                                Ready for AI
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </template>

</div>


<style>
@keyframes progress {
    0% {
        transform: translateX(-120%);
    }

    100% {
        transform: translateX(420%);
    }
}
</style>


<script>
function documentUpload() {
    return {
        title: @json(old('title', '')),
        file: null,
        fileType: '',
        uploading: false,
        uploadPercent: 0,

        handleFile(event) {
            const selected = event.target.files[0];

            if (!selected) {
                this.clearFile();
                return;
            }

            this.file = selected;

            const extension = selected.name
                .split('.')
                .pop()
                .toLowerCase();

            this.fileType = extension;
        },

        clearFile() {
            this.file = null;
            this.fileType = '';

            const input = document.getElementById('document');

            if (input) {
                input.value = '';
            }
        },

        formatSize(bytes) {
            if (bytes < 1024) {
                return bytes + ' B';
            }

            if (bytes < 1024 * 1024) {
                return (bytes / 1024).toFixed(1) + ' KB';
            }

            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        },

        submitForm() {
            if (!this.file || !this.title) {
                return;
            }

            this.uploading = true;
            this.uploadPercent = 0;

            const form = this.$el;
            const formData = new FormData(form);

            const xhr = new XMLHttpRequest();

            xhr.open('POST', form.action, true);

            xhr.setRequestHeader(
                'X-Requested-With',
                'XMLHttpRequest'
            );

            xhr.upload.addEventListener('progress', (event) => {

                if (!event.lengthComputable) {
                    return;
                }

                this.uploadPercent = Math.round(
                    (event.loaded / event.total) * 100
                );

            });

            xhr.addEventListener('load', () => {

                if (xhr.status >= 200 && xhr.status < 400) {

                    this.uploadPercent = 100;

                    /*
                     * The backend is still processing here.
                     * We intentionally keep the processing state visible
                     * until the server finishes.
                     */

                    setTimeout(() => {

                        if (xhr.responseURL) {
                            window.location.href = xhr.responseURL;
                        } else {
                            window.location.href = "{{ route('documents.index') }}";
                        }

                    }, 500);

                    return;
                }

                this.uploading = false;

                /*
                 * Let the browser handle validation/error responses.
                 * Reloading preserves Laravel's normal error flow.
                 */
                window.location.reload();

            });

            xhr.addEventListener('error', () => {

                this.uploading = false;

                alert('Upload failed. Please try again.');

            });

            xhr.addEventListener('abort', () => {

                this.uploading = false;

            });

            xhr.send(formData);
        }
    };
}
</script>
</x-app-layout>