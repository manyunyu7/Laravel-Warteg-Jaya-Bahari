<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\RestoranReview;
use App\Models\RestoranReviewImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RestoranReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, $restoId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'rating_id' => 'required|integer', Rule::in([1, 2, 3, 4]),
                'comment' => 'required|string|min:3|max:1000',
                'img.*' => 'mimes:jpeg,png,jpg,gif,svg|max:12048',
            ],
            [
                'rating_id' => 'rating_id cannot be empty',
                'comment.required' => 'comment cannot be empty',
                'img.image' => 'image must be an image',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $review = new RestoranReview();

        if ($request->hasFile('image')) {
            $review->user_id = Auth::id();
            $review->restoran_id = $restoId;
            $review->rating_id = $request->rating_id;
            $review->comment = $request->comment;

            if ($review->save()) {
                $files = array();
        
                $destination = 'uploads/img/resto_reviews/';
                foreach($request->file('image') as $img)
                {
                    $ekstension = $img->getClientOriginalExtension();
                    $name = time().'resto_review'.'.'.$ekstension;
                    if ($img->move($destination, $name)) {
                        $files[] = $destination.$name;
                    }
                }

                foreach($files as $file) 
                {
                    $reviewImage = new RestoranReviewImage();
                    $reviewImage->restoran_review_id = $review->id;
                    $reviewImage->path = $file;
                    $reviewImage->save();
                }

                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success add review restoran',
                    'data' => $review
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed add review restoran',
                    'data' => null
                ]);
            }
        }else{
            $review->user_id = Auth::id();
            $review->restoran_id = $restoId;
            $review->rating_id = $request->rating_id;
            $review->comment = $request->comment;

            if ($review->save()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success add review restoran',
                    'data' => $review
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed add review restoran',
                    'data' => null
                ]);
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($restoId)
    {
        $restoReviews = RestoranReview::find($restoId);
        $rating1 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 1)->get()->count();
        $rating2= RestoranReview::where('restoran_id', $restoId)->where('rating_id', 2)->get()->count();
        $rating3 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 3)->get()->count();
        $rating4 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 4)->get()->count();
        $rating5 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 5)->get()->count();
        $totalReview = $rating1 + $rating2 + $rating3 + $rating4 + $rating5;
        
        if ($restoReviews == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'resto review not found',
                'data' => null
            ]);
        }

        if (!$restoReviews) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get resto review',
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get resto review',
                'data' => [
                    "detailReview" => $restoReviews,
                    "rating1" => $rating1,
                    "rating2" => $rating2,
                    "rating3" => $rating3,
                    "rating4" => $rating4,
                    "rating5" => $rating5,
                    "totalReview" => $totalReview,
                ]
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
    public function update(Request $request, $id)
    {
        //
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
