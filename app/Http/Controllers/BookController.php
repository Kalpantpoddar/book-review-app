<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request){
        $books = Books::orderBy('created_at', 'desc');
        if(!empty($request->keyword)){
            $books->where('name', 'like', '%'.$request->keyword.'%');
        }
        $books = $books->paginate(10);
        return view('books.list', ['books' => $books]);
    }
    
    public function create(){
        return view('books.create');
    }
    
    public function store(Request $request){
        $rules = [
            'title' => 'required|min:5|max:255',
            'author' => 'required|min:5|max:255',
            'description' => 'required|min:10|max:10000',
            'status' => 'required',
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image|max:2048|mimes:jpeg,png,jpg';
        }

        $validation = Validator::make($request->all(), $rules);

        if($validation->fails()){
            return redirect()->route('books.create')->withInput()->withErrors($validation);
        }

        $book = new Books();
        $book->name = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        if(!empty($request->image)){
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/books'), $imageName);
            $book->image = $imageName;
            $book->save();
        }

        return redirect()->route('books.index')->with('success', "Book has been created.");
    }
    
    public function edit($id){
        $book = Books::findOrFail($id);
        // dd($book);
        return view('books.edit', ['book' => $book]);
    }
    
    public function update($id, Request $request){
        $book = Books::findOrFail($id);
        $rules = [
            'title' => 'required|min:5|max:255',
            'author' => 'required|min:5|max:255',
            'description' => 'required|min:10|max:10000',
            'status' => 'required',
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image|max:2048|mimes:jpeg,png,jpg';
        }

        $validation = Validator::make($request->all(), $rules);

        if($validation->fails()){
            return redirect()->route('books.edit', $book->id)->withInput()->withErrors($validation);
        }

        $book->name = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        if(!empty($request->image)){
            File::delete(public_path('uploads/books'.$book->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/books'), $imageName);
            $book->image = $imageName;
            $book->save();
        }

        return redirect()->route('books.index')->with('success', "Book has been updated.");
    }

    public function destroy(Request $request){
        $book = Books::find($request->id);
        if($book == null){
            session()->flash('error', 'Book not found');
            return response()->json([
                'status' => false,
                'message' => 'Book not found'
            ]);
        }else{

            File::delete(public_path('uploads/books'.$book->image));
            $book->delete();
            
            session()->flash('success', 'Book deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'Book deleted successfully'
            ]);
        }
    }
}
