<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
            <p class="text-xl sm:text-2xl md:text-4xl text-center text-gray-600 mb-6">Masuk untuk memulai</p>
            <!-- Form Login -->
            <form class="w-full" method="POST" action="{{ route('login') }}">
                @csrf
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
                </div>
                <!-- Submit button -->
                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Masuk</button>

                <div class="flex justify-between w-full py-4">
                    <div>
                        <input type="checkbox" name="ch" id="show-password" class="mr-1" />
                        <span class="text-md">Show Password</span>
                    </div>
                    <a href="/register" class="font-regular text-md">Register</a>
                </div>
            </form>
            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Side -->
    <div class="w-full h-full lg:w-1/2 lg:flex bg-white hidden lg:block">
        <img src="{{ asset('img/Frame 98699.png') }}" alt="Image" class="w-full h-full object-cover">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showPasswordCheckbox = document.getElementById('show-password');
            const passwordField = document.getElementById('password');

            showPasswordCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            });
        });
    </script>
</body>
</html>
