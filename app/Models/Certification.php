<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id','name'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
