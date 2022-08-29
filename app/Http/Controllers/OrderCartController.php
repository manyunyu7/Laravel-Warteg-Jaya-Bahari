<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Food;
use App\Models\OrderCart;
use App\Models\OrderHistory;
use App\Models\Restoran;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderCartController extends Controller
{
    public function createCart(Request $request,$restoId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'address' => 'required|string|min:10|max:100',
                'lat' => 'required|between:-90,90',
                'long' => 'required|between:-180,180',
            ],
            [
                'address.string' => 'address must be a string',
                'lat.between' => 'The latitude must be in range between -90 and 90',
                'long.between' => 'The longitude must be in range between -100 and 100',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $dataOrder = array();
        $priceOrder = array();
        $user = Auth::id();
        $order = OrderHistory::where([
            'user_id' => $user,
            'resto_id' => $restoId
        ])->get();

        if (!$order) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found',
                'data' => null
            ],404);
        }


        foreach($order as $data)
        {
            $customer = User::where('id', $data->user_id)->first();
            $food = Food::where('id', $data->food_id)->first();
            $dataOrder[] = [
                'customer_id' => $data->user_id,
                'customer' => $customer->name,
                'food_id' => $data->food_id,
                'food' => $food->name,
                'quantity' => $data->quantity,
                'notes' => $data->notes,
            ];
            $priceOrder[] = $food->price * $data->quantity;
        }
        $tot = array_sum($priceOrder);

        $cart = new OrderCart();
        $cart->user_id = $user;
        $cart->resto_id = $restoId;
        $cart->orders =$dataOrder;
        $cart->address = $request->address;
        $cart->total_price = $tot;
        $cart->lat = $request->lat;
        $cart->long = $request->long;
        $cart->status_id = 1;

        $history = OrderHistory::where([
            'user_id' => $user,
            'resto_id' => $restoId
        ]);

        if ($history->delete()) {
            if ($cart->save()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success create order',
                    'data' => [
                        'order' => $cart,
                        'totalPrice' => $tot,
                    ]
                ],200);
            }
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed create order',
                'data' => null
            ],400);
        }
    }

    public function myCart()
    {
        $user = Auth::id();
        $orders = OrderCart::with(
            ["restoran","order_status"]
        )->where('user_id', $user)->first();

        if (!$orders) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'order not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get my order cart',
            'data' => $orders
        ],200);
    }

    public function myCarts()
    {
        $user = Auth::id();
        $orders = OrderCart::where('user_id', $user)->get();

        if (!$orders) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'order not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get my order cart',
            'data' => $orders
        ],200);
    }

    public function getDetailOrder($orderId)
    {
        $order = OrderCart::find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get order detail',
            'data' => $order
        ],200);
    }

    public function getAllOrderByResto($retoId)
    {
        $orders = OrderCart::where('resto_id', $retoId)->get();

        if (!$orders) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get order detail',
            'data' => $orders
        ],200);
    }

    public function rejectOrder(Request $request,$orderId)
    {
        $order = OrderCart::find($orderId);
        if (!$order) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found',
                'data' => null
            ],404);
        }

        $order->status_id = 5;
        $order->reject_reason = $request->reject_reason;

        if ($order->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success reject order',
                'data' => $order
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'reject order failed',
                'data' => $order
            ],500);
        }

    }

    public function approvedOrder($orderId)
    {
        $order = OrderCart::find($orderId);
        $dataOrder = (object) $order->orders;

        if (!$order) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found',
                'data' => null
            ],404);
        }

        $order->status_id = 2;

        if ($order->save()) {
            foreach($dataOrder as $key)
            {
                $foodName = $key['food'];
                $qty = $key['quantity'];

                $food = Food::where([
                    'name' => $foodName,
                    'restoran_id' => $order->resto_id,
                ])->first();
                $minQty = $food->quantity-$qty;
                $food->quantity = $minQty;
                $food->save();
            }
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success approved order',
                'data' => $order
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed approved order',
                'data' => null
            ],400);
        }
    }

    public function orderDelivered(Request $request,$orderId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'driver_id' => 'required|integer',
            ],
            [
                'driver_id.integer' => 'driver_id must be a number',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $order = OrderCart::where('id', $orderId)->first();


        if (!$order) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found',
                'data' => null
            ],404);
        }

        $driver = Driver::where('id', $request->driver_id)->first();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Driver not found',
                'data' => null
            ],404);
        }

        if ($driver->is_available == 0) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Driver not available',
                'data' => null
            ],400);
        }

        $order->driver_id = $request->driver_id;
        $order->status_id = 3;

        if ($order->save()) {
            $loc = Restoran::where('id', $driver->resto_id)->first();
            $driver->lat = $loc->lat;
            $driver->long = $loc->long;
            $driver->save();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Driver on the way',
                'data' => [
                    'order' => $order,
                    'driver' => $driver->user->name
                ]
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed delivered order',
                'data' => null
            ],400);
        }
    }

    public function uploadSign(Request $request,$orderId)
    {
        $validator = Validator::make($request->all(),
        [
            'sign' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            'sign.required' => 'Sign cannot be empty',
            'sign.image' => 'Sign must be and image',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $order = OrderCart::where('id', $orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found',
                'data' => null,
            ],404);
        }

        if ($order->status_id !== 3) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Order not yet processed',
                'data' => null,
            ],400);
        }

        if ($request->hasFile('sign')) {
            $img = $request->file('sign');
            $ekstension = $img->getClientOriginalExtension();
            $name = Auth::user()->name.'_'.'Sign_'.uniqid().'.'.$ekstension;

            if ($request->sign->move(public_path('storage/'),$name)) {
                $order->user_sign = $name;

                if ($order->save()) {
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'Success update order',
                        'data' => $order,
                    ],200);
                }else{
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => 'Failed update order',
                        'data' => null,
                    ],400);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Failed upload sign',
                    'data' => null,
                ],400);
            }
        }else{
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'File is null',
                'data' => null,
            ],404);
        }
    }

    public function completedOrder($orderId)
    {
        $order = OrderCart::where('id', $orderId)->first();

        if ($order == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found',
                'data' => null,
            ],404);
        }

        if($order->user_sign == null)
        {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'User sign not uploaded yet',
                'data' => null,
            ],400);
        }

        $order->status_id = 4;

        if ($order->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success completed order',
                'data' => $order,
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed completed order',
                'data' => null,
            ],400);
        }
    }
}
