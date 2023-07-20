<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\MasjidReviewImage;
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
                'rating_id' => 'required', Rule::in([1, 2, 3, 4]),
                'comment' => 'required|string|min:0|max:1000',
//                'img.*' => 'mimes:jpeg,png,jpg,gif,svg|max:12048',
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

            $image = $request->file('image');
            $files = array();

            if ($image != null) {
                foreach ($image as $img)
                {
                    $name = Auth::user()->name.'_'.uniqid().'.'.$img->getClientOriginalExtension();
                    if ($img->move(public_path('storage'), $name)) {
                        $files[] = $name;
                    }
                }
            }

            $review->user_id = Auth::id();
            $review->restoran_id = $restoId;
            $review->rating_id = $request->rating_id;
            $review->comment = $request->comment;

            if ($review->save()) {

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
                ],200);

            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed add review restoran',
                    'data' => null
                ],400);
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
                ],200);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed add review restoran',
                    'data' => null
                ],400);
            }

        }

    }

    public function show($restoId, Request $request)
    {
        $page = $request->page;
        $perPage = $request->perPage;
        $isPaginate = $request->isPaginate === 'true'? true: false;
        $restoPaginate = RestoranReview::where('restoran_id', $restoId)->paginate($perPage, ['*'], 'page', $page);
        $restoReviews = RestoranReview::where('restoran_id', $restoId)->get();
        $rating1 = RestoranReview::where('rating_id', 1)->count();
        $rating2= RestoranReview::where('rating_id', 2)->get()->count();
        $rating3 = RestoranReview::where('rating_id', 3)->get()->count();
        $rating4 = RestoranReview::where('rating_id', 4)->get()->count();
        $rating5 = RestoranReview::where('rating_id', 5)->get()->count();
        $totalReviews = $restoReviews->count();
        $avgReviews = ($rating1 + $rating2 + $rating3 + $rating4 + $rating5)/5.0;

        if ($restoReviews == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'resto review not found',
                'data' => null
            ],404);
        }


        if (!$isPaginate) {
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
                    "totalReview" => $totalReviews,
                    "averageReview" => $avgReviews
                ]
            ],200);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get resto review',
                'data' => [
                    "detailReview" => $restoPaginate,
                    "rating1" => $rating1,
                    "rating2" => $rating2,
                    "rating3" => $rating3,
                    "rating4" => $rating4,
                    "rating5" => $rating5,
                    "totalReview" => $totalReviews,
                    "averageReview" => $avgReviews
                ]
            ],200);
        }
    }

    public function destroy($reviewId)
    {
        $review = RestoranReview::find($reviewId);
        $reviewImage = RestoranReviewImage::where('restoran_review_id', $reviewId);
        if ($review == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => "restoran review not found"
            ],404);
        }else{
            if ($reviewImage->exists()) {
                try {
                    $reviewImage->delete();
                    if ($review->delete()) {
                        return response()->json([
                            'success' => true,
                            'code' => 200,
                            'message' => 'success delete review restoran',
                        ],200);
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => $th->getMessage(),
                    ],400);
                }
            }else{
                if ($review->delete()) {
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'success delete review restoran',
                    ],200);
                }
            }
        }
    }
}
