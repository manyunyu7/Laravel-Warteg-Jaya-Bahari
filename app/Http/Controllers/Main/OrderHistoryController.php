<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\OrderHistory;
use App\Models\Restoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderHistoryController extends Controller
{
    public function store(Request $request, $restoId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'food_id' => 'required',
                'quantity' => 'required|integer',
            ],
            [
                'food_id.required' => 'food_id cannot be empty',
                'quantity.required' => 'quantity cannot be empty',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $cekResto = Restoran::where('id', $restoId)->first();

        if (!$cekResto) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Restoran not found', 
                'data' => null
            ],404);
        }

        $cekFood = Food::where('id', $request->food_id)->first();
        if (!$cekFood) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Food not found', 
                'data' => null
            ],404);
        }
        
        if($cekFood['restoran_id'] != $restoId)
        {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Food not registered on restoran', 
                'data' => null
            ],400);
        }

        $isOrderExist = OrderHistory::where(
            [
                'food_id' => $request->food_id,
                'user_id' => Auth::id()
            ]
        )->first();

        if ($isOrderExist) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Order already exists', 
                'data' => null
            ],400);
        }
            
        $draft = new OrderHistory();

        $draft->user_id = Auth::id();
        $draft->resto_id = $restoId;
        $draft->food_id = $request->food_id;
        $draft->quantity = $request->quantity;
        $draft->notes = $request->notes;

        if ($draft->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add order',
                'data' => $draft
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add order',
                'data' => null
            ],400);
        }
    }
    
    public function myOrder()
    {
        $userId = Auth::id();

        $orders = OrderHistory::where('user_id', $userId)->get();

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
            'message' => 'success get my order',
            'data' => $orders
        ],200);
    }

    public function getOrderById($orderId)
    {
        $order = OrderHistory::where('id', $orderId)->first();

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
            'message' => 'success get order',
            'data' => $order
        ],200);
    }

    public function editOrder(Request $request, $restoId,$orderId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'food_id' => 'required',
                'quantity' => 'required|integer',
            ],
            [
                'food_id.required' => 'food_id cannot be empty',
                'quantity.required' => 'quantity cannot be empty',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        } 

        $cekResto = Restoran::where('id', $restoId)->first();

        if (!$cekResto) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Restoran not found', 
                'data' => null
            ],404);
        }

        $order = OrderHistory::find($orderId);
        if (!$order) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found', 
                'data' => null
            ],404);
        }

        $cekFood = Food::where('id', $request->food_id)->first();
        if (!$cekFood) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Food not found', 
                'data' => null
            ],404);
        }

        $order->user_id = Auth::id();
        $order->resto_id = $restoId;
        $order->food_id = $request->food_id;
        $order->quantity = $request->quantity;
        $order->notes = $request->notes;

        if ($order->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update order data', 
                'data' => $order
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update order data', 
                'data' => null
            ],400);
        }

    }

    public function deleteOrder($orderId)
    {
        $order = OrderHistory::find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order not found', 
                'data' => null
            ],404);
        }

        if ($order->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success delete order'
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed delete order'
            ],400);
        }
    }
}
