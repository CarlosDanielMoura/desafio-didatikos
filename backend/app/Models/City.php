<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'city';
    protected $primaryKey = 'COD_CITY';

    protected $fillable = [
        'NAME_CITY',
    ];
    //Relacionamento com a tabela Produto
    public function products()
    {
        return $this->hasMany(Product::class, 'CITY', 'COD_CITY');
    }
}
