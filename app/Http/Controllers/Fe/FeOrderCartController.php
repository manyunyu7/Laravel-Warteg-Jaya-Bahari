<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Food;
use App\Models\OrderCart;
use App\Models\OrderHistory;
use App\Models\Restoran;
use App\Models\RestoranReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeOrderCartController extends Controller
{
    public function getDriverOrder(Request $request)
    {
        $driverId = Driver::where("user_id",'=',Auth::id())->first()->id;
        $page = $request->page;
        $perPage = $request->perPage;
        $objs = OrderCart::where("driver_id", '=', $driverId)->paginate($perPage,['*'],'page',$page);
        return $objs;
    }

    public function orderByResto(Request $request, $id)
    {
        $perPage = $request->perPage;
        $page = $request->page;
        if ($request->status != null) {

            $data = OrderCart::where([
                ['resto_id', '=', $id],
                ['status_id', '=', $request->status],
            ])->paginate($perPage, ['*'], 'page', $page);
            return $data;
        } else {
            $data = OrderCart::where("resto_id", '=', $id)->paginate($perPage, ['*'], 'page', $page);
            return $data;
        }
    }

    public function createCart(Request $request, $restoId)
    {

        $dataOrder = array();
        $priceOrder = array();
        $user = Auth::id();
        $order = $request->orders;

        foreach ($order as $data) {
            $model = new OrderHistory($data);
            $customer = User::where('id', Auth::id())->first();
            $food = Food::where('id', $model->food_id)->first();
            $dataOrder[] = [
                'customer_id' => Auth::id(),
                'customer' => $customer->name,
                'food_id' => $model->food_id,
                'food' => $food->name,
                'food_price' => $food->price,
                'food_image' => $food->img_full_path,
                'quantity' => $model->quantity,
                'notes' => $model->notes,
            ];
            $priceOrder[] = $food->price * $model->quantity;
        }

        $tot = array_sum($priceOrder);

        $cart = new OrderCart();
        $cart->user_id = $user;
        $cart->resto_id = $restoId;
        $cart->orders = $dataOrder;
        $cart->address = $request->address;
        $cart->total_price = $tot;
        $cart->lat = $request->lat;
        $cart->long = $request->long;
        $cart->status_id = 1;


        if ($cart->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success checkout',
                'data' => [
                    'order' => $cart,
                    'totalPrice' => $tot,
                ]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed checkout',
                'data' => null
            ], 400);
        }
    }


}
