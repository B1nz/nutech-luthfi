@extends('layouts.sidebar')

@section('title', 'Profile')

@section('content')
    <div class="bg-gray-100 h-screen w-screen flex items-center justify-center">
        <div class="w-full bg-white p-8 h-screen flex flex-col items-start justify-start">
            <form id="profileForm" class="w-full" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="relative mb-8">
                    <div class="relative inline-block">
                        <img id="profileImage" src="{{ $profile->avatar ? asset('storage/' . $profile->avatar) : asset('img/def-avatar.png') }}" alt="Profile Picture" class="w-42 h-42 rounded-full" style="width: 156px; height: 156px;">
                        <label for="image" class="absolute bottom-0 right-0 bg-white rounded-full px-2 py-1 border border-gray-400 cursor-pointer">
                            <i class="fa-solid fa-pencil-alt text-black"></i>
                            <input type="file" id="image" name="image" class="hidden" accept="image/png, image/jpeg">
                        </label>
                    </div>
                    <h2 class="text-2xl font-semibold mt-4">{{ $profile->name }}</h2>
                </div>
                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="col-span-1">
                        <div class="mb-4 relative">
                            <label for="candidateName" class="block text-sm font-medium text-gray-700">Nama Kandidat</label>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center mt-6 pointer-events-none">
                                <i class="fa-solid fa-user text-gray-500"></i>
                            </div>
                            <input type="text" id="candidateName" name="candidateName" class="mt-1 block w-full pl-10 p-2 border border-gray-300 rounded-md"
                                placeholder="Masukkan nama kandidat" value="{{ $profile->name }}">
                        </div>
                    </div>
                    <div class="col-span-1">
                        <div class="mb-4 relative">
                            <label for="candidatePosition" class="block text-sm font-medium text-gray-700">Posisi Kandidat</label>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center mt-6 pointer-events-none">
                                <i class="fa-solid fa-code text-gray-500"></i>
                            </div>
                            <input type="text" id="candidatePosition" name="candidatePosition" class="mt-1 block w-full pl-10 p-2 border border-gray-300 rounded-md"
                                placeholder="Masukkan posisi kandidat" value="{{ $profile->role }}">
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button id="resetButton" type="button" class="hidden bg-white border border-red-500 text-red-500 hover:bg-red-500 hover:border-red-500 hover:text-white px-8 py-2 rounded-md">
                        Reset
                    </button>
                    <button id="saveButton" type="submit" class="hidden bg-blue-500 border border-blue-500 text-white px-8 py-2 rounded-md">
                        Save
                    </button>
                </div>
            </form>

            <hr class="my-8 w-full border-gray-300">
            <!-- Candidate Container -->
            <div class="w-full p-8 mt-8 rounded-lg shadow-lg bg-gradient-to-r from-red-600 to-yellow-500">
                <h3 class="text-xl font-semibold mb-8 text-center text-left">Get to know about me!</h3>
                <div class="flex flex-col items-center mb-4">
                    <img src="{{ asset('img/static_avatar.png') }}" alt="Candidate Profile Picture" class="w-24 h-24 rounded-full mr-0 md:mr-6 mb-4 md:mb-0">
                    <div class="text-center">
                        <h4 class="text-2xl font-bold">Luthfi Goldiansyah Kusumajadi</h4>
                        <p class="text-gray-300">PHP Developer</p>
                        <div class="flex justify-center space-x-4 mt-4 md:mt-8">
                            <a href="https://www.linkedin.com/in/luthfigk/" class="text-white">
                                <i class="fab fa-linkedin fa-2x hover:bg-opacity-50 transform transition-transform hover:scale-105"></i>
                            </a>
                            <a href="https://www.instagram.com/_gluthfi/" class="text-white">
                                <i class="fab fa-instagram fa-2x hover:bg-opacity-50 transform transition-transform hover:scale-105"></i>
                            </a>
                            <a href="https://github.com/b1nz" class="text-white">
                                <i class="fab fa-github fa-2x hover:bg-opacity-50 transform transition-transform hover:scale-105"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <p class="mt-8 text-center">
                    "The only way to do great work is to love what you do." - Steve Jobs
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileImage = document.getElementById('profileImage');
            const imageInput = document.getElementById('image');
            const candidateName = document.getElementById('candidateName');
            const candidatePosition = document.getElementById('candidatePosition');
            const saveButton = document.getElementById('saveButton');
            const resetButton = document.getElementById('resetButton');
            const profileForm = document.getElementById('profileForm');

            const originalImageSrc = profileImage.src;
            const originalName = candidateName.value;
            const originalPosition = candidatePosition.value;

            function showButtons() {
                saveButton.classList.remove('hidden');
                resetButton.classList.remove('hidden');
            }

            function hideButtons() {
                saveButton.classList.add('hidden');
                resetButton.classList.add('hidden');
            }

            imageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file && (file.type === 'image/png' || file.type === 'image/jpeg') && file.size <= 100 * 1024) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            canvas.width = 156;
                            canvas.height = 156;
                            ctx.drawImage(img, 0, 0, 156, 156);
                            profileImage.src = canvas.toDataURL(file.type);
                            showButtons();
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a PNG or JPG image smaller than 100KB.');
                }
            });

            candidateName.addEventListener('input', function() {
                if (candidateName.value !== originalName || candidatePosition.value !== originalPosition || profileImage.src !== originalImageSrc) {
                    showButtons();
                } else {
                    hideButtons();
                }
            });

            candidatePosition.addEventListener('input', function() {
                if (candidateName.value !== originalName || candidatePosition.value !== originalPosition || profileImage.src !== originalImageSrc) {
                    showButtons();
                } else {
                    hideButtons();
                }
            });

            resetButton.addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reset it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        profileImage.src = originalImageSrc;
                        candidateName.value = originalName;
                        candidatePosition.value = originalPosition;
                        hideButtons();
                        Swal.fire(
                            'Reset!',
                            'Your changes have been reverted.',
                            'success'
                        )
                    }
                });
            });
        });
    </script>
@endsection
