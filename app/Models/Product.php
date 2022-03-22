<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id','keyword_id', 'name', 'description', 'producent', 'img'];

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class);
    }
}
