<!-- resources/views/exports/products.blade.php -->
<table>
    <thead>
        <!-- Title Row -->
        <tr>
            <th colspan="6">DATA PRODUK</th>
        </tr>
        <!-- Empty Row -->
        <tr></tr>
        <!-- Header Row -->
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Kategori Produk</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $index => $product)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $product->nama }}</td>
                <td>{{ $product->kategori->name }}</td>
                <td>Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                <td style="text-align: left">{{ $product->stok }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
