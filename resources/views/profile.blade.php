<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="bg-gray-100 h-screen w-screen flex items-center justify-center">
        <div class="w-full bg-white p-8 h-screen flex flex-col items-start justify-start">
            <form id="profileForm" class="w-full" action="{{ route('profileUpdate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="relative mb-8">
                    <div class="relative inline-block">
                        <img id="profileImage" src="{{ $profile->avatar ? asset('storage/'.$profile->avatar) : asset('img/def-avatar.png') }}" alt="Profile Picture" class="w-42 h-42 rounded-full" style="width: 156px; height: 156px;">
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
                                <i class="fa-solid fa-at text-gray-500"></i>
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
                <button id="saveButton" type="submit" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                    Save
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileImage = document.getElementById('profileImage');
            const imageInput = document.getElementById('image');
            const candidateName = document.getElementById('candidateName');
            const candidatePosition = document.getElementById('candidatePosition');
            const saveButton = document.getElementById('saveButton');
            const profileForm = document.getElementById('profileForm');

            function showSaveButton() {
                saveButton.classList.remove('hidden');
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
                            showSaveButton();
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a PNG or JPG image smaller than 100KB.');
                }
            });

            candidateName.addEventListener('input', showSaveButton);
            candidatePosition.addEventListener('input', showSaveButton);
        });
    </script>
</body>
</html>
