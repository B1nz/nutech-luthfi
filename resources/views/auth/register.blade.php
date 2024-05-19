<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
</head>
<body class="flex flex-col lg:flex-row justify-center items-center h-screen bg-white">
    <!-- Left Side -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center bg-white rounded-md p-8 xl:p-24">
        <div class="p-4 sm:p-8 md:p-12">
            <div class="flex justify-center items-center mb-4">
                <img src="{{ asset('img/shopping-bag.jpg') }}" alt="Logo" class="w-6 h-6 mr-2">
                <h2 class="text-xl font-semibold">SIMS Web App</h2>
            </div>
            <p class="text-xl sm:text-2xl md:text-4xl text-center text-gray-600 mb-6">Buat akun untuk memulai</p>
            <!-- Form Register -->
            <form class="w-full" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="py-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-gray-400"></i>
                    </div>
                    <input type="text" class="w-full pl-10 p-2 border border-gray-300 rounded-md placeholder:font-light placeholder:text-gray-500"
                        name="name" id="name" placeholder="Masukkan nama anda" required autofocus>
                </div>
                <div class="py-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-at text-gray-400"></i>
                    </div>
                    <input type="text" class="w-full pl-10 p-2 border border-gray-300 rounded-md placeholder:font-light placeholder:text-gray-500"
                        name="email" id="email" placeholder="Masukkan email anda" required autofocus>
                </div>
                <div class="py-2 mb-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password"
                        class="w-full pl-10 p-2 border border-gray-300 rounded-md placeholder:font-light placeholder:text-gray-500"
                        placeholder="Masukkan password anda" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                        <i class="fa-solid fa-eye-slash text-gray-400" id="toggle-password"></i>
                    </div>
                </div>
                <div class="py-2 mb-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full pl-10 p-2 border border-gray-300 rounded-md placeholder:font-light placeholder:text-gray-500"
                        placeholder="Konfirmasi password anda" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                        <i class="fa-solid fa-eye-slash text-gray-400" id="toggle-password_confirmation"></i>
                    </div>
                </div>
                <!-- Submit button -->
                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Daftar</button>

                <div class="flex justify-between w-full py-4">
                    <a href="/login" class="font-regular text-md">Login</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side -->
    <div class="w-full h-full lg:w-1/2 lg:flex bg-white hidden lg:block">
        <img src="{{ asset('img/Frame 98699.png') }}" alt="Image" class="w-full h-full object-cover">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Catch success message --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
        </script>
    @endif

    {{-- Catch error message --}}
    @if ($errors->any())
        <script>
            var errorMessage = '';
            @foreach ($errors->all() as $error)
                errorMessage += '{{ $error }}<br>';
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: errorMessage,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordIcon = document.getElementById('toggle-password');
            const passwordField = document.getElementById('password');
            const togglePasswordIconConf = document.getElementById('toggle-password_confirmation');
            const passwordFieldConf = document.getElementById('password_confirmation');

            togglePasswordIcon.addEventListener('click', function() {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    togglePasswordIcon.classList.remove('fa-eye-slash');
                    togglePasswordIcon.classList.add('fa-eye');
                } else {
                    passwordField.type = 'password';
                    togglePasswordIcon.classList.remove('fa-eye');
                    togglePasswordIcon.classList.add('fa-eye-slash');
                }
            });

            togglePasswordIconConf.addEventListener('click', function() {
                if (passwordFieldConf.type === 'password') {
                    passwordFieldConf.type = 'text';
                    togglePasswordIconConf.classList.remove('fa-eye-slash');
                    togglePasswordIconConf.classList.add('fa-eye');
                } else {
                    passwordFieldConf.type = 'password';
                    togglePasswordIconConf.classList.remove('fa-eye');
                    togglePasswordIconConf.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
</body>
</html>

