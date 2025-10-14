{{-- @extends("student.layouts.app")

@section("main_content")
    <x-flash-message type="message" />
    <x-flash-message type="error" />

    <!-- Profile Photo Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-gray-900 border border-gray-200 dark:border-gray-700 rounded-3xl mb-8 shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5 dark:from-blue-400/5 dark:to-purple-400/5"></div>
        <div class="relative p-8">
            <form id="profileForm" action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-2">
                        Profile Photo
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">Upload a professional photo to complete your profile</p>
                </div>

                <div class="flex flex-col gap-8 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex flex-col items-center w-full gap-8 xl:flex-row">
                        <!-- Enhanced Profile Picture Preview -->
                        <div class="relative group">
                            <div class="relative w-40 h-40">
                                <!-- Animated border -->
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-2xl p-1 animate-pulse">
                                    <div class="w-full h-full bg-white dark:bg-gray-800 rounded-2xl overflow-hidden">
                                        <img id="profilePreview" src="{{ $student->setAvatar() }}" alt="Profile picture of {{ $student->full_name }}"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    </div>
                                </div>

                                <!-- Enhanced Spinner -->
                                <div id="photoSpinner"
                                    class="absolute inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl hidden z-20">
                                    <div class="flex flex-col items-center space-y-2">
                                        <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Uploading...</span>
                                    </div>
                                </div>

                                <!-- Enhanced Camera Button -->
                                <div class="absolute -bottom-2 -right-2">
                                    <label for="avatar"
                                        class="flex items-center justify-center w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full cursor-pointer transition-all duration-200 hover:scale-110 shadow-lg hover:shadow-xl group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </label>

                                    <!-- Enhanced Tooltip -->
                                    <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 px-3 py-1 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                        Change photo
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Student Info -->
                        <div class="text-center xl:text-left">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $student->full_name }}
                            </h3>
                            <div class="flex flex-col items-center gap-2 xl:flex-row xl:gap-4">
                                <div class="flex items-center gap-2 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-4 0v1a2 2 0 104 0"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ $student->cne }}</span>
                                </div>
                                <div class="flex items-center gap-2 px-3 py-1 bg-green-100 dark:bg-green-900/30 rounded-full">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ $student->filiere }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Upload Input -->
                    <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" aria-label="Upload profile photo">

                    <!-- Enhanced Save Button -->
                    <div class="flex justify-center xl:justify-end">
                        <button id="uploadBtn" type="submit"
                            class="group relative px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-200 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Photo
                            </span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Enhanced Alert Messages -->
            @if (!$student->avatar)
                <div class="flex items-start gap-3 p-4 mt-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl" role="alert">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-amber-800 dark:text-amber-200">Photo Required</h4>
                        <p class="mt-1 text-sm text-amber-700 dark:text-amber-300">Please upload your profile photo to complete your registration.</p>
                    </div>
                </div>
            @endif

            <div class="flex items-start gap-3 p-4 mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-blue-800 dark:text-blue-200">Photo Requirements</h4>
                    <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Professional and clear image
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Maximum size: 2MB
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Accepted formats: JPG, PNG, GIF
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Student Information Section -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-8">
            <form action="{{ route('student.profile.update') }}" method="POST">
                @csrf
                <!-- Enhanced Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-2">
                        Student Information
                    </h2>
                    <div class="flex items-start gap-2 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-red-700 dark:text-red-300">
                            <span class="font-semibold">Important:</span> Please fill in missing information and contact administration immediately if there are any mistakes.
                        </p>
                    </div>
                </div>

                <!-- Enhanced Tabs Navigation -->
                <div x-data="{ tab: 'personal' }">
                    <div class="mb-8">
                        <nav class="flex space-x-1 bg-gray-100 dark:bg-gray-700 p-1 rounded-xl" aria-label="Profile tabs">
                            <button type="button"
                                class="flex-1 px-6 py-3 text-sm font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="tab === 'personal' ? 'bg-white dark:bg-gray-600 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'"
                                @click="tab = 'personal'"
                                :aria-selected="tab === 'personal'">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Personal
                                </span>
                            </button>
                            <button type="button"
                                class="flex-1 px-6 py-3 text-sm font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="tab === 'contact' ? 'bg-white dark:bg-gray-600 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'"
                                @click="tab = 'contact'"
                                :aria-selected="tab === 'contact'">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Address
                                </span>
                            </button>
                            <button type="button"
                                class="flex-1 px-6 py-3 text-sm font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="tab === 'additional' ? 'bg-white dark:bg-gray-600 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'"
                                @click="tab = 'additional'"
                                :aria-selected="tab === 'additional'">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Additional
                                </span>
                            </button>
                        </nav>
                    </div>

                    <!-- Personal Info Tab -->
                    <div x-show="tab === 'personal'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-8">
                        <div class="grid lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <x-input-field label="First Name" name="prenom" value="{{ $student->prenom }}" placeholder="John" icon="user" />
                                <x-input-field label="Last Name" name="nom" value="{{ $student->nom }}" placeholder="Doe" icon="user-group" />
                                <x-input-field label="الإسم الشخصي" name="prenom_ar" value="{{ $student->prenom_ar }}" placeholder="محمد" icon="language" />
                                <x-input-field label="الإسم العائلي" name="nom_ar" value="{{ $student->nom_ar }}" placeholder="العلوي" icon="language" />
                            </div>
                            <div class="space-y-6">
                                <x-input-field label="CIN" name="cin" value="{{ $student->cin }}" placeholder="AB123456" icon="identification" />
                                <x-input-field label="CNE" name="cne" value="{{ $student->cne }}" placeholder="P123456789" icon="academic-cap" />
                                <x-input-field type="date" label="Date of Birth" name="date_naissance" value="{{ $student->date_naissance ? \Carbon\Carbon::parse($student->date_naissance)->format('Y-m-d') : '' }}" icon="calendar" />

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="sexe" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gender</label>
                                        <select name="sexe" id="sexe" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <option value="m" {{ $student->sexe === 'm' ? 'selected' : '' }}>Male</option>
                                            <option value="f" {{ $student->sexe === 'f' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="nationalite" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nationality</label>
                                        <select name="nationalite" id="nationalite" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <option value="">Select Nationality</option>
                                            <option value="MA" {{ $student->nationalite === 'MA' ? 'selected' : '' }}>Moroccan</option>
                                            <option value="ET" {{ $student->nationalite === 'ET' ? 'selected' : '' }}>Foreign</option>
                                        </select>
                                        @error('nationalite')
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Tab -->
                    <div x-show="tab === 'contact'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-8">
                        <div class="grid lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <x-input-field label="Phone" name="tel" value="{{ $student->tel }}" placeholder="+212 600 000 000" icon="phone" />
                                <x-input-field label="Emergency Phone" name="tel_urgence" value="{{ $student->tel_urgence }}" placeholder="+212 600 000 000" icon="phone" />
                            </div>
                            <div class="space-y-6">
                                <x-input-field label="Address" name="adresse" value="{{ $student->adresse }}" placeholder="123 Main Street" icon="location-marker" />
                                <x-input-field label="العنوان" name="adresse_ar" value="{{ $student->adresse_ar }}" placeholder="شارع 123 الرئيسي" icon="translate" />
                            </div>
                        </div>
                    </div>

                    <!-- Additional Tab -->
                    <div x-show="tab === 'additional'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-8">
                        <div class="grid lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <x-input-field label="Place of Birth" name="lieu_naissance" value="{{ $student->lieu_naissance }}" placeholder="Casablanca" icon="map" />
                                <x-input-field label="مكان الإزدياد" name="lieu_naissance_ar" value="{{ $student->lieu_naissance_ar }}" placeholder="الدار البيضاء" icon="translate" />
                            </div>
                            <div class="space-y-6">
                                <x-input-field label="Marital Status" name="situation_familiale" value="{{ $student->situation_familiale }}" placeholder="Single/Married/..." icon="heart" />
                                <x-input-field label="Province of Birth" name="province_naissance" value="{{ $student->province_naissance }}" placeholder="Casablanca-Settat" icon="location-marker" />
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Form Actions -->
                    <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end">
                            <button type="submit" class="group relative px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl transition-all duration-200 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save Changes
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js for tab functionality -->
    <script src="https://unpkg.com/alpinejs" defer></script>

@endsection

@push('js')
    <script>
        // Enhanced file preview with validation
        document.getElementById("avatar").addEventListener("change", function (event) {
            const file = event.target.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

            if (file) {
                // Validate file size
                if (file.size > maxSize) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }

                // Validate file type
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, or GIF)');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById("profilePreview").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Enhanced form submission with better UX
        document.getElementById('profileForm').addEventListener('submit', function (e) {
            const fileInput = document.getElementById('avatar');

            if (!fileInput.files[0]) {
                e.preventDefault();
                alert('Please select a photo to upload');
                return;
            }

            // Show spinner and disable button
            document.getElementById('photoSpinner').classList.remove('hidden');
            document.getElementById('uploadBtn').disabled = true;
            document.getElementById('uploadBtn').innerHTML = `
                <span class="flex items-center gap-2">
                    <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    Uploading...
                </span>
            `;
        });

        // Add smooth transitions and hover effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effect to profile image
            const profileImage = document.getElementById('profilePreview');
            if (profileImage) {
                profileImage.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });

                profileImage.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            }

            // Add focus states for better accessibility
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
                });

                input.addEventListener('blur', function() {
                    this.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');
                });
            });

            // Add loading states for form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        submitButton.disabled = true;
                        setTimeout(() => {
                            if (submitButton) submitButton.disabled = false;
                        }, 3000); // Re-enable after 3 seconds to prevent permanent disable
                    }
                });
            });

            // Animate elements on page load
            const animatedElements = document.querySelectorAll('.bg-gradient-to-br, .bg-white');
            animatedElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    element.style.transition = 'all 0.6s ease-out';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });

        // Add real-time validation feedback
        function addValidationFeedback() {
            const requiredFields = document.querySelectorAll('input[required], select[required]');

            requiredFields.forEach(field => {
                field.addEventListener('blur', function() {
                    const value = this.value.trim();
                    const fieldContainer = this.closest('.space-y-6') || this.parentElement;

                    // Remove existing feedback
                    const existingFeedback = fieldContainer.querySelector('.validation-feedback');
                    if (existingFeedback) {
                        existingFeedback.remove();
                    }

                    // Add validation feedback
                    if (!value) {
                        const feedback = document.createElement('p');
                        feedback.className = 'validation-feedback mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1';
                        feedback.innerHTML = `
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            This field is required
                        `;
                        fieldContainer.appendChild(feedback);
                        this.classList.add('border-red-500', 'dark:border-red-400');
                    } else {
                        this.classList.remove('border-red-500', 'dark:border-red-400');
                        this.classList.add('border-green-500', 'dark:border-green-400');

                        const feedback = document.createElement('p');
                        feedback.className = 'validation-feedback mt-1 text-sm text-green-600 dark:text-green-400 flex items-center gap-1';
                        feedback.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Looks good!
                        `;
                        fieldContainer.appendChild(feedback);

                        // Remove success feedback after 2 seconds
                        setTimeout(() => {
                            if (feedback.parentElement) {
                                feedback.remove();
                                this.classList.remove('border-green-500', 'dark:border-green-400');
                            }
                        }, 2000);
                    }
                });
            });
        }

        // Initialize validation feedback
        addValidationFeedback();

        // Add keyboard navigation for tabs
        document.addEventListener('keydown', function(e) {
            if (e.target.matches('[role="tablist"] button')) {
                const tabs = Array.from(document.querySelectorAll('[role="tablist"] button'));
                const currentIndex = tabs.indexOf(e.target);

                switch(e.key) {
                    case 'ArrowRight':
                        e.preventDefault();
                        const nextIndex = (currentIndex + 1) % tabs.length;
                        tabs[nextIndex].click();
                        tabs[nextIndex].focus();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        const prevIndex = (currentIndex - 1 + tabs.length) % tabs.length;
                        tabs[prevIndex].click();
                        tabs[prevIndex].focus();
                        break;
                }
            }
        });
    </script>
@endpush --}}

@extends("student.layouts.app")

@section("main_content")
    <x-flash-message type="message" />
    <x-flash-message type="error" />

    <div class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Profile Photo Section -->
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-gray-900 border border-gray-200 dark:border-gray-700 rounded-3xl mb-8 shadow-lg">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5 dark:from-blue-400/5 dark:to-purple-400/5"></div>
                <div class="relative p-8">
                    <form id="profileForm" action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-2">
                                Profile Photo
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400">Upload a professional photo to complete your profile</p>
                        </div>

                        <div class="flex flex-col gap-8 xl:flex-row xl:items-center xl:justify-between">
                            <div class="flex flex-col items-center w-full gap-8 xl:flex-row">
                                <!-- Enhanced Profile Picture Preview -->
                                <div class="relative group">
                                    <div class="relative w-40 h-40">
                                        <!-- Animated border -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-2xl p-1 animate-pulse">
                                            <div class="w-full h-full bg-white dark:bg-gray-800 rounded-2xl overflow-hidden">
                                                <img id="profilePreview" src="{{ $student->setAvatar() }}" alt="Profile picture of {{ $student->full_name }}"
                                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            </div>
                                        </div>

                                        <!-- Enhanced Spinner -->
                                        <div id="photoSpinner"
                                            class="absolute inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl hidden z-20">
                                            <div class="flex flex-col items-center space-y-2">
                                                <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Uploading...</span>
                                            </div>
                                        </div>

                                        <!-- Enhanced Camera Button -->
                                        <div class="absolute -bottom-2 -right-2">
                                            <label for="avatar"
                                                class="flex items-center justify-center w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full cursor-pointer transition-all duration-200 hover:scale-110 shadow-lg hover:shadow-xl group">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </label>

                                            <!-- Enhanced Tooltip -->
                                            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 px-3 py-1 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                Change photo
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Student Info -->
                                <div class="text-center xl:text-left">
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $student->full_name }}
                                    </h3>
                                    <div class="flex flex-col items-center gap-2 xl:flex-row xl:gap-4">
                                        <div class="flex items-center gap-2 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-4 0v1a2 2 0 104 0"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ $student->cne }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 px-3 py-1 bg-green-100 dark:bg-green-900/30 rounded-full">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ $student->filiere }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Upload Input -->
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" aria-label="Upload profile photo">

                            <!-- Enhanced Save Button -->
                            <div class="flex justify-center xl:justify-end">
                                <button id="uploadBtn" type="submit"
                                    class="group relative px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-200 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Save Photo
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Enhanced Alert Messages -->
                    @if (!$student->avatar)
                        <div class="flex items-start gap-3 p-4 mt-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl" role="alert">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-800 dark:text-amber-200">Photo Required</h4>
                                <p class="mt-1 text-sm text-amber-700 dark:text-amber-300">Please upload your profile photo to complete your registration.</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start gap-3 p-4 mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-800 dark:text-blue-200">Photo Requirements</h4>
                            <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Professional and clear image
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Maximum size: 2MB
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Accepted formats: JPG, PNG, GIF
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Information Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <form action="{{ route('student.profile.update') }}" method="POST">
                    @csrf

                    <!-- Form Header -->
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Student Information</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your personal information and contact details</p>

                        @if(!$student->email_verified_at || !$student->avatar)
                        <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                            <div class="flex gap-2">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-amber-700 dark:text-amber-300">
                                    Please complete your profile and verify your email address
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Form Content with Tabs -->
                    <div x-data="{ tab: 'personal' }" class="p-6">

                        <!-- Tab Navigation -->
                        <div class="mb-6">
                            <nav class="flex space-x-1 bg-gray-100 dark:bg-gray-700/50 p-1 rounded-lg">
                                <button type="button"
                                        @click="tab = 'personal'"
                                        :class="tab === 'personal' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'"
                                        class="flex-1 px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Personal Info
                                    </span>
                                </button>
                                <button type="button"
                                        @click="tab = 'contact'"
                                        :class="tab === 'contact' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'"
                                        class="flex-1 px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        Contact & Address
                                    </span>
                                </button>
                            </nav>
                        </div>

                        <!-- Personal Info Tab -->
                        <div x-show="tab === 'personal'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="prenom"
                                           name="prenom"
                                           value="{{ old('prenom', $student->prenom) }}"
                                           required
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('prenom')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="nom"
                                           name="nom"
                                           value="{{ old('nom', $student->nom) }}"
                                           required
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('nom')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- First Name Arabic -->
                                <div>
                                    <label for="prenom_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                        الإسم الشخصي
                                    </label>
                                    <input type="text"
                                           id="prenom_ar"
                                           name="prenom_ar"
                                           value="{{ old('prenom_ar', $student->prenom_ar) }}"
                                           dir="rtl"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('prenom_ar')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name Arabic -->
                                <div>
                                    <label for="nom_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                        الإسم العائلي
                                    </label>
                                    <input type="text"
                                           id="nom_ar"
                                           name="nom_ar"
                                           value="{{ old('nom_ar', $student->nom_ar) }}"
                                           dir="rtl"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('nom_ar')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CIN -->
                                <div>
                                    <label for="cin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        CIN <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="cin"
                                           name="cin"
                                           value="{{ old('cin', $student->cin) }}"
                                           required
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('cin')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div>
                                    <label for="date_naissance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Date of Birth
                                    </label>
                                    <input type="date"
                                           id="date_naissance"
                                           name="date_naissance"
                                           value="{{ old('date_naissance', $student->date_naissance ? \Carbon\Carbon::parse($student->date_naissance)->format('Y-m-d') : '') }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('date_naissance')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="sexe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Gender
                                    </label>
                                    <select id="sexe"
                                            name="sexe"
                                            class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                        <option value="">Select Gender</option>
                                        <option value="M" {{ old('sexe', $student->sexe) === 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('sexe', $student->sexe) === 'F' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('sexe')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nationality -->
                                <div>
                                    <label for="nationalite" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nationality
                                    </label>
                                    <input type="text"
                                           id="nationalite"
                                           name="nationalite"
                                           value="{{ old('nationalite', $student->nationalite) }}"
                                           placeholder="MA"
                                           maxlength="5"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('nationalite')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Place of Birth -->
                                <div>
                                    <label for="lieu_naissance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Place of Birth
                                    </label>
                                    <input type="text"
                                           id="lieu_naissance"
                                           name="lieu_naissance"
                                           value="{{ old('lieu_naissance', $student->lieu_naissance) }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('lieu_naissance')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Place of Birth Arabic -->
                                <div>
                                    <label for="lieu_naissance_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                        مكان الإزدياد
                                    </label>
                                    <input type="text"
                                           id="lieu_naissance_ar"
                                           name="lieu_naissance_ar"
                                           value="{{ old('lieu_naissance_ar', $student->lieu_naissance_ar) }}"
                                           dir="rtl"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('lieu_naissance_ar')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Marital Status -->
                                <div>
                                    <label for="situation_familiale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Marital Status
                                    </label>
                                    <select id="situation_familiale"
                                            name="situation_familiale"
                                            class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                        <option value="">Select Status</option>
                                        <option value="Célibataire" {{ old('situation_familiale', $student->situation_familiale) === 'Célibataire' ? 'selected' : '' }}>Single</option>
                                        <option value="Marié" {{ old('situation_familiale', $student->situation_familiale) === 'Marié' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorcé" {{ old('situation_familiale', $student->situation_familiale) === 'Divorcé' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Veuf" {{ old('situation_familiale', $student->situation_familiale) === 'Veuf' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    @error('situation_familiale')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Professional Status -->
                                <div>
                                    <label for="situation_professionnelle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Professional Status
                                    </label>
                                    <select id="situation_professionnelle"
                                            name="situation_professionnelle"
                                            class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                        <option value="">Select Status</option>
                                        <option value="Étudiant" {{ old('situation_professionnelle', $student->situation_professionnelle) === 'Étudiant' ? 'selected' : '' }}>Student</option>
                                        <option value="Salarié" {{ old('situation_professionnelle', $student->situation_professionnelle) === 'Salarié' ? 'selected' : '' }}>Employed</option>
                                        <option value="Chômeur" {{ old('situation_professionnelle', $student->situation_professionnelle) === 'Chômeur' ? 'selected' : '' }}>Unemployed</option>
                                        <option value="Autre" {{ old('situation_professionnelle', $student->situation_professionnelle) === 'Autre' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('situation_professionnelle')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Address Tab -->
                        <div x-show="tab === 'contact'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Phone -->
                                <div>
                                    <label for="tel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel"
                                           id="tel"
                                           name="tel"
                                           value="{{ old('tel', $student->tel) }}"
                                           placeholder="+212 600 000 000"
                                           maxlength="20"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('tel')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Emergency Phone -->
                                <div>
                                    <label for="tel_urgence" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Emergency Contact
                                    </label>
                                    <input type="tel"
                                           id="tel_urgence"
                                           name="tel_urgence"
                                           value="{{ old('tel_urgence', $student->tel_urgence) }}"
                                           placeholder="+212 600 000 000"
                                           maxlength="20"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('tel_urgence')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label for="adresse" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Address
                                    </label>
                                    <textarea id="adresse"
                                              name="adresse"
                                              rows="3"
                                              placeholder="Enter your full address"
                                              class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none">{{ old('adresse', $student->adresse) }}</textarea>
                                    @error('adresse')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address Arabic -->
                                <div class="md:col-span-2">
                                    <label for="adresse_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" dir="rtl">
                                        العنوان
                                    </label>
                                    <textarea id="adresse_ar"
                                              name="adresse_ar"
                                              rows="3"
                                              dir="rtl"
                                              placeholder="أدخل عنوانك الكامل"
                                              class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none">{{ old('adresse_ar', $student->adresse_ar) }}</textarea>
                                    @error('adresse_ar')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div>
                                    <label for="pays" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Country
                                    </label>
                                    <input type="text"
                                           id="pays"
                                           name="pays"
                                           value="{{ old('pays', $student->pays) }}"
                                           maxlength="20"
                                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    @error('pays')
                                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between gap-4">
                            <a href="
                            {{-- {{ route('student.profile') }} --}}
                             "
                               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection

@push('js')
<script>
    // Profile photo preview with validation
    document.getElementById("avatar").addEventListener("change", function (event) {
        const file = event.target.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

        if (file) {
            // Validate file size
            if (file.size > maxSize) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }

            // Validate file type
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, or GIF)');
                this.value = '';
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("profilePreview").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Profile photo form submission
    document.getElementById('profileForm').addEventListener('submit', function (e) {
        const fileInput = document.getElementById('avatar');

        if (!fileInput.files[0]) {
            e.preventDefault();
            alert('Please select a photo to upload');
            return;
        }

        // Show spinner and disable button
        document.getElementById('photoSpinner').classList.remove('hidden');
        const uploadBtn = document.getElementById('uploadBtn');
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = `
            <span class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                Uploading...
            </span>
        `;
    });

    // Form validation on blur
    document.addEventListener('DOMContentLoaded', function() {
        const requiredFields = document.querySelectorAll('input[required]');

        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('border-red-500', 'dark:border-red-400');
                    this.classList.remove('border-green-500', 'dark:border-green-400');
                } else {
                    this.classList.remove('border-red-500', 'dark:border-red-400');
                    this.classList.add('border-green-500', 'dark:border-green-400');

                    // Remove green border after 2 seconds
                    setTimeout(() => {
                        this.classList.remove('border-green-500', 'dark:border-green-400');
                    }, 2000);
                }
            });
        });

        // Phone number formatting
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 0) {
                    // Format as +212 XXX XXX XXX
                    if (value.startsWith('212')) {
                        value = '+' + value;
                    } else if (!value.startsWith('+')) {
                        value = '+212' + value;
                    }
                }
                this.value = value;
            });
        });
    });

    // Prevent form double submission
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;

                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    });
</script>
@endpush