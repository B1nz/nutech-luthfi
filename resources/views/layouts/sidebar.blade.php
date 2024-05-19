<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .sidebar {
            transition: width 0.5s;
        }

        .sidebar.collapsed {
            width: 64px;
        }

        .sidebar .menu {
            transition: opacity 0.5s;
        }

        .sidebar.collapsed .menu a span, .sidebar.collapsed .menu form span,
        .sidebar.collapsed .logo .menu-title {
            display: none;
        }

        .sidebar.collapsed .menu a, .sidebar.collapsed .menu form{
            padding-left: 24px;
        }

        .sidebar.collapsed #sidebarToggle {
            margin-left: 0;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar bg-red-600 w-64 flex flex-col">
            <div class="logo flex items-center justify-center py-5">
                <div class="flex items-center menu-title">
                    <i class="fas fa-bag-shopping text-white mr-2"></i>
                    <span class="text-lg font-bold text-white">SIMS Web App</span>
                </div>
                <button id="sidebarToggle" class="text-white ml-8 focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="menu mt-4">
                <a href="/" class="px-8 block py-3 text-white hover:bg-white hover:bg-opacity-50 transform transition-transform hover:scale-105">
                    <i class="fas fa-box mr-4"></i>
                    <span>Produk</span>
                </a>
                <a href="/profile" class="px-8 block py-3 text-white hover:bg-white hover:bg-opacity-50 transform transition-transform hover:scale-105">
                    <i class="fas fa-user mr-4"></i>
                    <span>Profile</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="px-8 block py-3 text-white hover:bg-white hover:bg-opacity-50 transform transition-transform hover:scale-105">
                    @csrf
                    <button
                    type="submit"
                    >
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
        <!-- ----------- -->

        @yield('content')

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
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        function checkScreenWidth() {
            if (window.innerWidth <= 640) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
        }
        checkScreenWidth();
        window.addEventListener('resize', checkScreenWidth);
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>

</html>
