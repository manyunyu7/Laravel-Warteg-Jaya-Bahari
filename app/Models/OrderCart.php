<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCart extends Model
{
    use HasFactory;

    protected $casts = [
        'orders' => 'array',
    ];

    protected $appends = ["status_desc","resto_obj","user_obj"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restorans()
    {
        return $this->belongsTo(Restoran::class);
    }

    public function orderStatus()
    {
        $this->belongsTo(OrderStatu::class);
    }

    public function getStatusDescAttribute(){
        $status = OrderStatu::where('id','=',$this->status_id)->first()->name;
        return $status;
    }

    public function getRestoObjAttribute(){
        $status = Restoran::find($this->resto_id);
        return $status;
    }

    public function getUserObjAttribute(){
        $status = User::find($this->user_id);
        return $status;
    }


}
