@extends('layouts.sidebar')

@section('title', 'Produk')

@section('content')
<div class="bg-white min-h-screen flex flex-col w-screen p-12">
    <div>
        <form action="{{ route('export.products') }}" method="post" class="flex flex-wrap justify-between items-center mb-4 gap-2">
            @csrf
            <div class="flex flex-wrap gap-2">
                <div class="relative w-full md:w-auto">
                    <input type="text" placeholder="Cari barang" id="search" class="border p-2 pl-10 rounded w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                </div>
                <select name="category" id="category" class="border p-2 rounded w-full md:w-auto">
                    <option value="">Semua</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2 ml-auto">
                <button type="submit" class="bg-green-700 text-white text-sm px-3 py-2 rounded hover:bg-green-600">
                    <i class="fa-regular fa-file-excel lg:mr-2"></i>
                    <span class="hidden md:inline">Export Excel</span>
                </button>
                <a href="/products/add" class="bg-red-600 text-white text-sm px-3 py-2 rounded hover:bg-red-500">
                    <i class="fas fa-plus lg:mr-2"></i>
                    <span class="hidden md:inline">Tambah Produk</span>
                </a>
            </div>
        </form>
    </div>
    <div class="flex-grow overflow-x-auto">
        <table class="min-w-full bg-white border"id="productTable" >
            <thead>
                <tr class="bg-gray-100">
                    <th class="w-16 px-6 py-3 text-left text-sm lg:text-md leading-4 text-gray-500 tracking-wider">No</th>
                    <th class="w-32 px-6 py-3 text-left text-sm lg:text-md leading-4 text-gray-500 tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-sm lg:text-md leading-4 text-gray-500 tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-sm lg:text-md leading-4 text-gray-500 tracking-wider">Kategori Produk</th>
                    <th class="px-6 py-3 text-left text-sm lg:text-md leading-4 text-gray-500 tracking-wider">Harga Beli</th>
                    <th class="px-6 py-3 text-left text-sm lg:text-md leading-4 text-gray-500 tracking-wider">Harga Jual</th>
                    <th class="px-6 py-3 text-left text-sm lg:text-md leading-4 text-gray-500 tracking-wider">Sisa Produk</th>
                    <th class="w-24 px-6 py-3 text-left text-sm lg:text-md leading-4 text-gray-500 tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($produks as $index => $produk)
                    <tr id="product-row-{{ $produk->id }}" class="border-b border-gray-200 hover:bg-gray-100 transition duration-300 ease-in-out">
                        <td class="px-6 py-4 whitespace-no-wrap">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            <img src="{{ $produk->image_path ? asset('storage/' . $produk->image_path) : asset('img/no-image.jpg') }}"
                            alt="Product Image" class="w-16 h-16 object-cover">
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">{{ $produk->nama }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">{{ $produk->kategori->name }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">{{ $produk->harga_beli }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">{{ $produk->harga_jual }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">{{ $produk->stok }}</td>
                        <td class="flex items-center mt-4 px-6 py-4 whitespace-no-wrap">
                            <button onclick="window.location.href='/products/edit/{{ $produk->id }}'" class="text-blue-500 hover:text-blue-600 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="{{ $produk->id }}" type="button" class="text-red-500 hover:text-red-600 mr-2 focus:outline-none delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="flex justify-between items-center mt-4">
            <div>
                <label for="show-data" class="mr-2">Show:</label>
                <select id="show-data" class="border p-2 rounded">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="ml-2 text-sm text-gray-500" id="total-data">of {{ $produks->count() }}</span>
            </div>
            <div class="flex items-end justify-center">
                <div id="pagination" class="flex">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

{{-- Catch success message --}}
@if (session('success'))
    <script>
        // Display Sweet Alert toast for success message
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productTable = document.querySelector('#productTable');
        let products = {!! json_encode($produks) !!};
        let currentPage = 1;
        let itemsPerPage = parseInt(document.getElementById('show-data').value);
        const totalDataSpan = document.getElementById('total-data');

        // Delete button
        function attachDeleteEventListeners() {
            const deleteButtons = document.querySelectorAll('.delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const productId = event.currentTarget.getAttribute('data-id');
                    console.log(productId);

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post(`/products/delete/${productId}`, {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            })
                            .then(response => {
                                Swal.fire(
                                    'Deleted!',
                                    'Your product has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the product.',
                                    'error'
                                );
                            });
                        }
                    });
                });
            });
        }

        // Update table data shown
        function updateTable() {
            const searchTerm = document.getElementById('search').value.trim().toLowerCase();
            const categoryId = document.getElementById('category').value;

            const filteredProducts = products.filter(product => {
                const matchesSearchTerm = searchTerm === '' || product.nama.toLowerCase().includes(searchTerm);
                const matchesCategory = categoryId === '' || product.kategori_id == categoryId;
                return matchesSearchTerm && matchesCategory;
            });

            totalDataSpan.textContent = `of ${filteredProducts.length}`;

            const paginatedProducts = filteredProducts.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

            renderTable(paginatedProducts);
            attachDeleteEventListeners();
            generatePaginationButtons(filteredProducts.length);
        }

        // Render data into table
        function renderTable(products) {
            const tbody = productTable.querySelector('tbody');
            tbody.innerHTML = '';

            products.forEach((product, index) => {
                const formattedHargaBeli = formatCurrency(product.harga_beli);
                const formattedHargaJual = formatCurrency(product.harga_jual);

                const row = `
                    <tr id="product-row-${product.id}" class="border-b border-gray-200 hover:bg-gray-100 transition duration-300 ease-in-out">
                        <td class="px-6 py-4 whitespace-no-wrap">${(currentPage - 1) * itemsPerPage + index + 1}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            <img src="{{ asset('storage/') }}/${product.image_path}" alt="Product Image" class="w-16 h-16 object-cover">
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">${product.nama}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">${product.kategori.name}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">${formattedHargaBeli}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">${formattedHargaJual}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">${product.stok}</td>
                        <td class="flex items-center mt-4 px-6 py-4 whitespace-no-wrap">
                            <button onclick="window.location.href='/products/edit/${product.id}'" class="text-blue-500 hover:text-blue-600 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="${product.id}" type="button" class="text-red-500 hover:text-red-600 mr-2 focus:outline-none delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        // Price Currency Formatter
        function formatCurrency(amount) {
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });
            return formatter.format(amount);
        }

        // Pagination Function
        function generatePaginationButtons(totalItems) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';

            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const maxVisibleButtons = 5;
            const halfVisible = Math.floor(maxVisibleButtons / 2);
            let startPage = Math.max(1, currentPage - halfVisible);
            let endPage = Math.min(totalPages, currentPage + halfVisible);

            if (currentPage <= halfVisible) {
                endPage = Math.min(totalPages, maxVisibleButtons);
            }
            if (totalPages - currentPage < halfVisible) {
                startPage = Math.max(1, totalPages - maxVisibleButtons + 1);
            }

            if (currentPage > 1) {
                const prevButton = document.createElement('button');
                prevButton.classList.add('px-4', 'py-2', 'text-gray-700', 'rounded-l-md', 'mr-1');
                prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
                prevButton.addEventListener('click', previousPage);
                paginationContainer.appendChild(prevButton);
            }

            for (let i = startPage; i <= endPage; i++) {
                const button = document.createElement('button');
                button.classList.add('px-4', 'py-2', 'text-gray-700', 'rounded-md', 'mr-1');
                button.innerText = i;
                if (i === currentPage) {
                    button.classList.add('bg-gray-200', 'font-bold');
                } else {
                    button.addEventListener('click', () => goToPage(i));
                }
                paginationContainer.appendChild(button);
            }

            if (currentPage < totalPages) {
                const nextButton = document.createElement('button');
                nextButton.classList.add('px-4', 'py-2', 'text-gray-700', 'rounded-r-md', 'ml-1');
                nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
                nextButton.addEventListener('click', nextPage);
                paginationContainer.appendChild(nextButton);
            }
        }

        function goToPage(page) {
            currentPage = page;
            updateTable();
        }

        function previousPage() {
            if (currentPage > 1) {
                goToPage(currentPage - 1);
            }
        }

        function nextPage() {
            const totalItems = products.filter(product => {
                const searchTerm = document.getElementById('search').value.trim().toLowerCase();
                const categoryId = document.getElementById('category').value;
                const matchesSearchTerm = searchTerm === '' || product.nama.toLowerCase().includes(searchTerm);
                const matchesCategory = categoryId === '' || product.kategori_id == categoryId;
                return matchesSearchTerm && matchesCategory;
            }).length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        }

        document.getElementById('search').addEventListener('input', updateTable);
        document.getElementById('category').addEventListener('change', updateTable);
        document.getElementById('show-data').addEventListener('change', function(event) {
            itemsPerPage = parseInt(event.target.value);
            currentPage = 1;
            updateTable();
        });

        updateTable(); // Initial table rendering
    });
</script>
@endsection
