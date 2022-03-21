<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\MasjidReview;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MasjidReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = MasjidReview::all();

        if ($reviews->count() > 0) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add review masjid', 
                'data' => $reviews
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'data not found', 
                'data' => null
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $masjidId)
    {
        $validator = Validator::make($request->all(), 
            [
                'rating_id' => 'required|integer', Rule::in([1,2]),
                'comment' => 'required|string|min:3|max:1000',
                'img' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'rating_id.required' => 'rating_id cannot be empty',
                'comment.required' => 'comment cannot be empty',
                'img.image' => 'Image must be and image',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $review = new MasjidReview();

            $review->masjid_id = $masjidId;
            $review->user_id = Auth::id();
            $review->rating_id = $request->rating_id;
            $review->comment = $request->comment;
            
            if ($request->hasFile('img')) {

                $file = $request->file('img');
                $ekstension = $file->getClientOriginalExtension();
                $name = time().'_masjidReviews'.'.'.$ekstension;
                $request->img->move(public_path('uploads/img/masjid_reviews'), $name);
    
                $review->img = $name;
            }

            if ($review->save()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success add review masjid', 
                    'data' => $review
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed add review masjid', 
                    'data' => null
                ]);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($reviewId)
    {
        $review = MasjidReview::findOrFail($reviewId);

        if (! $review->exists()) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'review not found', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get data review', 
                'data' => $review
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $reviewId)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
