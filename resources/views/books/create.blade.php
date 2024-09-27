@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-4">
        @include('layouts.sidebar')
            
        <div class="col-md-9">
            @include('layouts.message')
            <div class="card border-0 shadow">
                <div class="card-header text-black">
                    Books
                </div>
                <div class="card-body pb-0">            
                    <form action="{{route('books.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="create_books">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" value="{{old('title')}}" class="form-control @error('title') is-invalid @enderror" placeholder="Title" name="title" id="title" />
                                @error('title')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" value="{{old('author')}}" class="form-control @error('author') is-invalid @enderror" placeholder="Author"  name="author" id="author"/>
                                @error('author')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div class="mb-3">
                                <label for="author" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description" cols="30" rows="5">{{old('description')}}</textarea>
                                @error('description')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div class="mb-3">
                                <label for="Image" class="form-label">Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"  name="image" id="image"/>
                                @error('image')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div class="mb-3">
                                <label for="author" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                                @error('status')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary mt-2">Create Book</button> 
                            </div>
                            
                        </div>
                    </form>          
                                 
                </div>
                
            </div>                 
        </div>
    </div>       
</div>
@endsection