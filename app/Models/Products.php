<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kategori_id',
        'nama',
        'harga_beli',
        'harga_jual',
        'stok',
        'image_path'
    ];

    public function kategori() {
        return $this->belongsTo(Categories::class, 'kategori_id');
    }

    use HasFactory;
}
