<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'user_id', 'title', 'body', 'img'];

    protected static function booted()
    {
        static::deleted(function ($forum) {
            unlink(public_path('storage/'.$forum->img));
        });
    }

    public function category()
    {
        return $this->belongsTo(ForumCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(ForumComment::class);
    }

    public function likes()
    {
        return $this->hasMany(ForumLike::class);
    }

    protected $appends = ["img_full_path"];

    public function getImgFullPathAttribute(){
        if($this->img==null){
            return "https://polteksahid.ac.id/wp-content/uploads/2021/12/placeholder.png";
        }else{
            return asset("").$this->img;
        }
    }
}
