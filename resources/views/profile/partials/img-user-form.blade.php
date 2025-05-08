<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your profile picture.') }}
        </p>
    </header>

    <form id="profile-image-form" method="post" action="{{ route('profile.img') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf

        <div class="space-y-2">
            <div class="flex flex-col items-center mb-4">
                <div id="current-profile-image" class="mb-4">
                    @if(auth()->user()->img)
                        <div class="w-32 h-32 rounded-lg overflow-hidden border-2 border-blue-200 shadow-md">
                            <img src="{{ asset('storage/profile_pictures/' . auth()->user()->img) }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-full h-full object-cover object-center">
                        </div>
                    @else
                        <div class="w-32 h-32 rounded-lg flex items-center justify-center bg-gray-200 border-2 border-gray-300 shadow-sm">
                            <span class="text-gray-500">{{ __('No profile picture') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <label for="img"
                class="cursor-pointer flex items-center justify-center w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md border border-gray-300 hover:bg-gray-300 focus:bg-gray-300 transition duration-200 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span id="file-name">{{ __('Choose a file') }}</span>
                <input id="img" name="img" type="file" class="hidden" accept="image/jpeg,image/png,image/jpg,image/gif">
            </label>

            <div id="image-preview-container" class="hidden flex flex-col items-center mt-4">
                <img id="preview-img" class="rounded-md mx-auto object-cover object-center w-32 h-32 border-2 border-blue-200 shadow-md" alt="Preview Image">
                <div id="image-preview-actions" class="mt-2 flex space-x-2">
                    <button type="button" id="change-image-btn" class="text-sm text-blue-500 hover:text-blue-700">
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Change
                    </button>
                    <button type="button" id="remove-preview-btn" class="text-sm text-red-500 hover:text-red-700">
                        <i class="fa-solid fa-xmark mr-1"></i> Remove
                    </button>
                </div>
            </div>

            <div id="image-validation-error" class="hidden mt-2 text-sm text-red-600"></div>
            <x-form.error :messages="$errors->get('img')" />
        </div>

        <div class="flex items-center gap-4">
            <x-button id="submit-profile-image" type="submit" disabled>
                <span class="mr-2"><i class="fa-regular fa-floppy-disk" style="color: #ffffff;"></i></span>{{ __('Save') }}
            </x-button>
            
            <span id="upload-status" class="text-sm"></span>
            
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Profile picture updated.') }}
                </p>
            @endif
        </div>
    </form>

    @if(auth()->user()->img)
    <form action="{{route('profile.img.delete')}}" method="POST" class="mt-4">
        @csrf
        @method('delete')
        <x-button variant="danger">
            <span class="mr-2"><i class="fa-solid fa-trash" style="color: #ffffff;"></i></span>{{ __('Delete Picture') }}
        </x-button>
    </form>
    @endif
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profile-image-form');
        const input = document.getElementById('img');
        const fileNameDisplay = document.getElementById('file-name');
        const imgPreview = document.getElementById('preview-img');
        const previewContainer = document.getElementById('image-preview-container');
        const submitBtn = document.getElementById('submit-profile-image');
        const validationError = document.getElementById('image-validation-error');
        const changeImageBtn = document.getElementById('change-image-btn');
        const removePreviewBtn = document.getElementById('remove-preview-btn');
        const uploadStatus = document.getElementById('upload-status');

        // Reset the form when the page loads
        input.value = '';
        
        // Check if browser supports FileReader API
        if (!window.FileReader) {
            validationError.textContent = "Your browser doesn't support file preview. Please update your browser.";
            validationError.classList.remove('hidden');
            return;
        }

        // Handle file selection
        input.addEventListener('change', function() {
            validationError.classList.add('hidden');
            validationError.textContent = '';
            submitBtn.disabled = true;
            
            if (input.files.length > 0) {
                const file = input.files[0];
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    validationError.textContent = "Please select a valid image file (JPEG, PNG, or GIF).";
                    validationError.classList.remove('hidden');
                    resetFileInput();
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    validationError.textContent = "Image size should be less than 5MB.";
                    validationError.classList.remove('hidden');
                    resetFileInput();
                    return;
                }
                
                const reader = new FileReader();

                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    submitBtn.disabled = false;
                    fileNameDisplay.textContent = file.name;
                }
                
                reader.onerror = function() {
                    validationError.textContent = "Error reading file. Please try another image.";
                    validationError.classList.remove('hidden');
                    resetFileInput();
                };

                reader.readAsDataURL(file);
            } else {
                resetFileInput();
            }
        });
        
        // Change image button
        changeImageBtn.addEventListener('click', function() {
            input.click();
        });
        
        // Remove preview button
        removePreviewBtn.addEventListener('click', function() {
            resetFileInput();
        });
        
        // Form submission
        form.addEventListener('submit', function(event) {
            if (input.files.length === 0) {
                event.preventDefault();
                validationError.textContent = "Please select an image to upload.";
                validationError.classList.remove('hidden');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Uploading...';
            uploadStatus.textContent = 'Uploading your profile picture...';
            uploadStatus.className = 'text-sm text-blue-500';
        });
        
        function resetFileInput() {
            input.value = '';
            fileNameDisplay.textContent = "{{ __('Choose a file') }}";
            previewContainer.classList.add('hidden');
            imgPreview.src = '';
            submitBtn.disabled = true;
        }
    });
</script>
