<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request){
        $books = Books::withCount('reviews')->withSum('reviews', 'rating')->orderBy('created_at', 'desc');
        if(!empty($request->keyword)){
            $books->where('name', 'like', '%'.$request->keyword.'%');
        }
        $books = $books->where('status', 1)->paginate(8);
        return view('home', ['books' => $books]);
    }
    public function details($id){
        $book = Books::with(['reviews.user', 'reviews' => function($query){
            $query->where('status', 1);
        }])->withCount('reviews')->withSum('reviews', 'rating')->findOrFail($id);
        // dd($book);
        if($book->status == 0){
            abort(404);
        }

        $relatedbooks = Books::where('status', 1)
            ->withCount('reviews')->withSum('reviews', 'rating')
            ->take(3)->where('id', '!=', $id)->inRandomOrder()->get();
        // dd($relatedbooks);
        return view('book-details', ['book' => $book, 'relatedBook' => $relatedbooks]);
    }

    public function saveReview(Request $request){
        $rules = [
            'review' => 'required|min:10|max:3000',
            'rating' => 'required|min:1|max:5'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors'=> $validator->errors() 
            ]);
        }

        $checkReview = Review::where('user_id', Auth::user()->id)->where('book_id', $request->book_id)->count();
        if($checkReview > 0){
            session()->flash('error', "You already submitted a review.");
            return response()->json([
                'status' => true,
            ]);
        }


        $review = new Review();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->book_id = $request->book_id;
        $review->user_id = Auth::user()->id;
        $review->save();

        session()->flash('success', "Review saved successfully");
        return response()->json([
            'status' => true,
        ]);

    }

}
