<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request){
        $reviews = Review::with('book')->orderBy('created_at', 'desc');
        if(!empty($request->keyword)){
            $reviews->where('review', 'like', '%'.$request->keyword.'%');
        }
        $reviews = $reviews->paginate(10);
        return view('account.reviews.list', ['reviews' => $reviews]);
    }

    public function edit($id){
        $review = Review::findOrFail($id);
        return view('account.reviews.edit', ['review' => $review]);
    }

    public function update($id, Request $request){
        $review = Review::findOrFail($id);
        // dd($review);
        $validation = Validator::make($request->all(), [
            'review' => 'required|min:10|max:3000',
            'status' => 'required',
            'rating' => 'required|min:1|max:5'
        ]);

        if($validation->fails()){
            return redirect()->route('review.edit', $review->id)->withInput()->withErrors($validation);
        }
        $review->review = $request->review;
        $review->status = $request->status;
        $review->rating = $request->rating;
        $review->save();

        return redirect()->route('reviews.index')->with('success', "Review has been updated.");
    }

    public function deleteReview(Request $request){
        $id = $request->id;
        $review = Review::find($id);
        //dd($review);
        if($review == null){
            session()->flash('error', "review not found.");
            return response()->json([
                'status' => false
            ]);
        }
        else{
            $review->delete();
            session()->flash('success', "review deleted.");
            return response()->json([
                'status' => true
            ]);
        }
    }
}
