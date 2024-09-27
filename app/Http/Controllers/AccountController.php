<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    //Display register page
    public function register()
    {
        return view('account.register');
    }

    //Register a new user
    public function processRegister(Request $request){
        $rules = [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.login')->with('success', "Your account has been created. Please login.");
    }

    public function login(){
        return view('account.login');
    }

    //user login with authentication
    public function authenticate(Request $request){
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $validation = Validator::make($request->all(), $rules);
        if($validation->fails()){
            return redirect()->route('account.login')->withInput()->withErrors($validation);
        }
        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            return redirect()->route('account.profile');
        }
        else{
            return redirect()->route('account.login')->withInput()->with('error', 'Email or Password is incorrect');
        }

    }

    //display user profile
    public function profile(){
        $user = User::find(Auth::user()->id);
        
        return view('account.profile', ['user' => $user]);
    }

    //update user profile
    public function updateProfile(Request $request){
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id.',id',
        ];
        if(!empty($request->image)){
            $rules['image'] = 'image|max:2048';
        }
        $validation = Validator::make($request->all(), $rules);
        if($validation->fails()){
            return redirect()->route('account.profile')->withInput()->withErrors($validation);
        }

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        if(!empty($request->image)){
            //delete old image
            File::delete(public_path('uploads/profile/'.$user->image));

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $image->move(public_path('uploads/profile'), $imageName);

        $user->image =$imageName;
        $user->save();
        }

        return redirect()->route('account.profile')->with('success', "Profile updated successfully");

    }

    //logout user
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function myReviews(Request $request){
        $reviews = Review::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC');
        if(!empty($request->keyword)){
            $reviews->where('review', 'like', '%'.$request->keyword.'%');
        }
        $reviews = $reviews->paginate(10);
        // dd($reviews);
        return view('account.users-reviews.my-reviews', ['reviews' => $reviews]);
    }
    public function editReview($id){
        $review = Review::where([
            'id' => $id,
            'user_id' => Auth::user()->id
        ])->with('book')->first();
        return view('account.users-reviews.edit-review', ['review' => $review]);
    }

    public function updateReview(Request $request){
        $review = Review::findOrFail($request->id);
        $validation = Validator::make($request->all(), [
            'review' => 'required|min:10|max:3000',
            'rating' => 'required|min:1|max:5'
        ]);
        if($validation->fails()){
            return redirect()->route('reviews.editMyReview', $review->id)->withInput()->withErrors($validation);
        }

        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        return redirect()->route('reviews.myReviews')->with('success', "Review has been updated.");
    }

    public function destroyReview(Request $request){
        $id = $request->id;
        $review = Review::find($id);
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
