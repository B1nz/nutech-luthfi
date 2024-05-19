@extends('layouts.sidebar')

@section('title', 'Tambah Produk')

@section('content')
<div class="bg-gray-100 h-screen w-screen flex items-center justify-center">
    <div class="w-full bg-white p-8 h-screen flex flex-col items-start justify-start">
        <div class="lg:p-8 w-full">
            <form class="w-full" action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <div class="mb-4 relative">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select id="category_id" name="category_id" class="mt-1 block w-full pl-4 p-2 border border-gray-300 rounded-md">
                                <option value="" disabled hidden>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $product->kategori_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-span-1">
                        <div class="mb-4 relative">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <input type="text" id="nama" name="nama" class="mt-1 block w-full pl-4 p-2 border border-gray-300 rounded-md"
                                placeholder="Masukkan nama barang" value="{{ old('name', $product->nama) }}">
                        </div>
                    </div>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <div class="mb-4 relative">
                            <label for="harga_beli" class="block text-sm font-medium text-gray-700">Harga Beli</label>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center mt-6 pointer-events-none">
                                <i class="fa-solid fa-rupiah-sign text-gray-500"></i>
                            </div>
                            <input type="text" id="harga_beli" name="harga_beli" class="mt-1 block w-full pl-10 p-2 border border-gray-300 rounded-md"
                                placeholder="Masukkan harga beli">
                        </div>
                    </div>
                    <div class="col-span-1">
                        <div class="mb-4 relative">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stok Barang</label>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center mt-6 pointer-events-none">
                                <i class="fa-solid fa-box text-gray-500"></i>
                            </div>
                            <input type="number" id="stock" name="stock" class="mt-1 block w-full pl-10 p-2 border border-gray-300 rounded-md"
                                placeholder="Masukkan stok barang (cnth. 17)" min="0" value="{{ old('stock', $product->stok) }}">
                        </div>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-3 mb-4 relative">
                    <label for="image" class="block text-sm font-medium text-gray-700">Upload Gambar Produk</label>
                    <div class="flex items-center justify-center border-2 border-dashed border-blue-500 rounded-md py-12">
                        <div class="text-center">
                            <i id="imageIcon" class="fa-solid fa-image text-4xl text-blue-500 mb-4"></i>
                            <input type="file" id="image" name="image" class="opacity-0 absolute top-0 left-0 w-full h-full cursor-pointer"
                                accept=".png, .jpg">
                            <img id="imagePreview" src="{{ $product->image_path ? asset('storage/' . $product->image_path) : '' }}" alt="Preview" style="display: {{ $product->image_path ? 'block' : 'none' }}; max-width: 100%; max-height: 200px;">
                            <p id="uploadText" class="text-blue-500">Upload gambar disini.</p>
                        </div>
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2 lg:col-span-3 flex flex-col md:flex-row justify-end gap-2">
                    <a type="button" class="bg-white border border-red-500 text-red-500 hover:bg-red-500 hover:border-red-500 hover:text-white px-8 py-2 rounded-md"
                            href="/">
                        Batalkan
                    </a>
                    <button type="submit" class="bg-blue-500 text-white hover:bg-blue-600 px-8 py-2 rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const hargaJualInput = document.getElementById('harga_beli');
        const hargaJualValue = '{{ $product->harga_beli ? number_format($product->harga_beli, 0, ',', '.') : '' }}';
        hargaJualInput.value = hargaJualValue;
    });

    function formatPrice(input) {
        let value = input.value.replace(/\D/g, '');
        if (value) {
            value = parseInt(value, 10).toLocaleString('id-ID');
            input.value = value;
        }
    }

    function isNumber(event) {
        const charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    document.getElementById('image').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const img = document.getElementById('imagePreview');
            const uploadText = document.getElementById('uploadText');
            const imageIcon = document.getElementById('imageIcon');
            img.src = URL.createObjectURL(file);
            img.style.display = 'block';
            uploadText.style.display = 'none';
            imageIcon.style.display = 'none';
            img.onload = () => {
                URL.revokeObjectURL(img.src);
            };
        }
    });
</script>
@endsection
