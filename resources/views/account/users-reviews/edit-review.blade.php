@extends('layouts.app')

@section('main')
    <div class="container">
        <div class="row my-5">
             @include('layouts.sidebar')
                
            <div class="col-md-9">
                
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Update Review
                    </div>
                    <div class="card-body pb-0"> 
                         
                        <form action="{{route('reviews.updateMyReview', $review->id)}}" method="POST">
                            @csrf
                            
                            <div class="create_books">
                                <div class="mb-3">
                                    <label for="" class="form-label">Book</label>
                                    <h5 class="text-black"><strong>{{ $review->book->name }}</strong></h5>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Review</label>
                                    <textarea name="review" class="form-control @error('review') is-invalid @enderror" cols="5" rows="5" placeholder="Review">{{ old('review', $review->review) }}</textarea>
                                    @error('review')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="rating"  class="form-label">Rating</label>
                                    <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" >
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($review->rating == $i)
                                            <option value="{{$i}}" selected>{{$i}}</option>
                                            @else
                                            <option value="{{$i}}">{{$i}}</option>
                                            @endif
                                        @endfor
                                    </select>
                                    @error('rating')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
    
                                <div class="mb-3">
                                    <button class="btn btn-primary mt-2">Update Book</button> 
                                </div>
                                
                            </div>
                        </form>  
                       
                    </div>
                </div>                
            </div>
        </div>       
    </div>
@endsection