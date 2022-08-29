<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restorans()
    {
        return $this->belongsTo(Restoran::class);
    }

    protected $appends = ["is_driver_have_order", "user_driver"];

    public function getIsDriverHaveOrderAttribute()
    {
        $orders = OrderCart::where([
            ['driver_id', '=', $this->id],
            ['status_id', '=', 3],
        ])->count();

        if ($orders == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getUserDriverAttribute()
    {
        return User::findOrFail($this->user_id);
    }


}
