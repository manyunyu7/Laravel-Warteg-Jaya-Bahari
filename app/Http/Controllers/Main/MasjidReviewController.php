<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use App\Models\MasjidReview;
use App\Models\MasjidReviewImage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MasjidReviewController extends Controller
{
    public function store(Request $request, $masjidId)
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

        $image = $request->file('img');
        $files = array();

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'code' => 401,
                'message' => 'Login First',
            ],400);
        }else{

            if ($request->hasFile('img')) {
                $dataFile = array();

                if($image != null)
                {
                    foreach($image as $files)
                    {
                        $destination = 'uploads/img/masjid_reviews/';
                        $name = Auth::user()->name."_".uniqid().'.'.$files->getClientOriginalExtension();
                        if ($files->move($destination, $name)) {
                            $dataFile[] = $destination.$name;
                        }
                    }
                }
    
                $review = new MasjidReview();
    
                $review->masjid_id = $masjidId;
                $review->user_id = Auth::id();
                $review->rating_id = $request->rating_id;
                $review->comment = $request->comment;
    
                if ($review->save()) {
    
                    foreach($dataFile as $file)
                    {
                        $reviewImage = new MasjidReviewImage();
                        $reviewImage->masjid_review_id = $review->id;
                        $reviewImage->path = $file;
                        $reviewImage->save();
                    }
    
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'success add review masjid',
                        'data' => $review
                    ],200);
                } else {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => 'failed add review masjid',
                        'data' => null
                    ],400);
                }
            }else{
                $review = new MasjidReview();
    
                $review->masjid_id = $masjidId;
                $review->user_id = Auth::id();
                $review->rating_id = $request->rating_id;
                $review->comment = $request->comment;
    
                if ($review->save()) {
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'success add review masjid',
                        'data' => $review
                    ],200);
                } else {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => 'failed add review masjid',
                        'data' => null
                    ],400);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($masjidId, Request $request)
    {
        $page = $request->page;
        $perPage = $request->perPage;
        $isPaginate = $request->isPaginate === 'true'? true: false;
        $review = MasjidReview::where('masjid_id', $masjidId)->paginate($perPage, ['*'], 'page', $page);
        $reviews = MasjidReview::where('masjid_id', $masjidId)->get();
        $tot = $reviews->count();
        $rating1 = MasjidReview::where('rating_id', 1)->count();
        $rating2 = MasjidReview::where('rating_id', 2)->count();
        $rating3 = MasjidReview::where('rating_id', 3)->count();
        $rating4 = MasjidReview::where('rating_id', 4)->count();
        $rating5 = MasjidReview::where('rating_id', 5)->count();
        $avg = ($rating1+$rating2+$rating3+$rating4+$rating5)/5.0;

        if (!$isPaginate) {
            if ($reviews == null) {
                return response()->json([
                    'success' => false,
                    'code' => 404,
                    'message' => 'review not found',
                    'data' => null
                ],404);
            }

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get data review',
                'data' => [
                    'dataReview' => $reviews,
                    'rating1' => $rating1,
                    'rating2' => $rating2,
                    'rating3' => $rating3,
                    'rating4' => $rating4,
                    'rating5' => $rating5,
                    'totalReview' => $tot,
                    'averageReview' => $avg
                ]
            ],200);
        } else {
            if ($review == null) {
                return response()->json([
                    'success' => false,
                    'code' => 404,
                    'message' => 'review not found',
                    'data' => null
                ],404);
            }

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get data review',
                'data' => [
                    'dataReview' => $review,
                    'rating1' => $rating1,
                    'rating2' => $rating2,
                    'rating3' => $rating3,
                    'rating4' => $rating4,
                    'rating5' => $rating5,
                    'totalReview' => $tot,
                    'averageReview' => $avg
                ]
            ],200);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($reviewId)
    {
        $review = MasjidReview::find($reviewId);
        $image = MasjidReviewImage::where('restoran_review_id', $reviewId)->pluck('path')->all();
        if ($review == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => "masjid review not found"
            ],404);
        }else{
            // if ($image != null) {
            //     foreach ($image as $img) {
            //         $path = $img;
            //         if (file_exists($path)) {
            //             try {
            //                 unlink($path);
            //                 MasjidReviewImage::where('path', $img)->delete();
            //             } catch (\Throwable $th) {
            //                 return response()->json([
            //                     'success' => false,
            //                     'code' => 400,
            //                     'message' => $th->getMessage(),
            //                 ],400);
            //             }
            //         }
            //     }
            // }

            if ($review->delete()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success delete review masjid',
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed delete review masjid',
                ],400);
            }
        }
    }
}
