<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail, FilamentUser, HasAvatar
{
    use  HasApiTokens,HasFactory, Notifiable;

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->photo;
    }

    public function canAccessFilament(): bool
    {
        return true;
//        return str_ends_with($this->roles_id, '1') && $this->hasVerifiedEmail();
    }

    protected static function booted()
    {
        static::deleted(function ($product) {
            unlink(public_path('storage/'.$product->img));
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'photo',
        'roles_id',
        'phone_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(){
        return [];
    }

    public function role()
    {
        return $this->hasOne('user_roles');
    }

    public function masjidReviews()
    {
        return $this->hasMany(MasjidReview::class);
    }

    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

    public function comments()
    {
        return $this->hasMany(ForumComment::class);
    }

    public function likes()
    {
        return $this->hasMany(ForumLike::class);
    }

    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function restoFavorites()
    {
        return $this->hasMany(FavoriteRestoran::class);
    }

    public function masjidFavorites()
    {
        return $this->hasMany(FavoriteMasjid::class);
    }

    public function restoranReviews()
    {
        return $this->hasMany(RestoranReview::class);
    }

    public function otp()
    {
        return $this->hasOne(UserOTP::class);
    }

    public function restoran()
    {
        return $this->hasOne(Restoran::class);
    }
}
