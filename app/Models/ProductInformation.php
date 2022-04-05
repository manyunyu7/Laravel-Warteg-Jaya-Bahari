<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInformation extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'product_id','allergy', 'environment', 'ingredient', 'summary'];

    protected $casts = [
        'allergy' => 'array',
        'environment' => 'array',
        'ingredient' => 'array',
        'summary' => 'array',
    ];

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
