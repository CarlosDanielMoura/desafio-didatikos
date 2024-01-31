<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';
    protected $primaryKey = 'COD_BRAND';
    use HasFactory;

    protected $fillable = [
        'NAME_BRAND',
        "MANUFACTURER",
    ];

    // Relacionamento com a tabela Produto
    public function products()
    {
        return $this->hasMany(Product::class, 'BRAND_PRODUCT', 'COD_BRAND');
    }
}
