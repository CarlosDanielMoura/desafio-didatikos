<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'COD_PRODUCT';
    use HasFactory;


    protected $fillable = [
        'NAME_PRODUCT',
        "PRICE",
        "BRAND_PRODUCT",
        "STOCK",
        "CITY",
    ];

    public function brands()
    {
        return $this->hasOne(Brand::class, 'BRAND_PRODUCT', 'COD_BRAND');
    }

    // Relacionamento com a tabela Cidade
    public function city()
    {
        return $this->hasOne(City::class, 'CITY', 'COD_CITY');
    }
}
