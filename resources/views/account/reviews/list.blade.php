@extends('layouts.app')

@section('main')
    <div class="container">
        <div class="row my-5">
             @include('layouts.sidebar')
                
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-black">
                        Reviews
                    </div>
                    <div class="card-body pb-0"> 
                         
                        <div class="d-flex justify-content-between">
                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" class="form-control serach_input" name="keyword" value="{{ Request::get('keyword')}}" placeholder="Search">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    <a href="{{ route('reviews.index')}}" class="btn btn-secondary ms-2"><i class="fa-solid fa-xmark"></i></a>
                                </div>
                            </form>
                        </div> 
                        <table class="table  table-striped mt-3 review_list">
                            <thead class="table-dark ">
                                <tr>
                                    <th>Book</th>
                                    <th>Review</th>
                                    <th>Rating</th>
                                    <th>Date</th>  
                                    <th>Status</th>                                  
                                    <th width="100">Action</th>
                                </tr>
                                <tbody>
                                    @if ($reviews->isNotEmpty())
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>{{ $review->book->name}}</td>
                                            <td>
                                                <p class="review_para">{{ $review->review}}</p>
                                                By: <span class="text-primary">{{ $review->user->name}}</span>
                                            </td>                                        
                                            <td>
                                                @for ($i = 1; $i <= $review->rating; $i++)
                                                <i class="fa-solid fa-star"></i> 
                                                @endfor
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y') }}</td>
                                            <td>
                                                @if ($review->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Block</span>  
                                                @endif
                                            </td>
                                            <td>
                                                {{-- <a href="view-review.html" class="btn btn-success btn-sm"><i class="fa-solid fa-eye"></i></a> --}}
                                                <a href="{{ route('review.edit', $review->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i></a>
                                                <a href="#" onclick="deleteReview({{ $review->id }})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                           <td colspan="5"> Reviews not found.</td> 
                                        </tr>
                                    @endif
                                    
                                    
                                </tbody>
                            </thead>
                        </table>   
                        @if ($reviews->isNotEmpty())
                        {{ $reviews->links()}}
                        @endif
                    
                    </div>
                </div>                
            </div>
        </div>       
    </div>
@endsection

@section('script')
<script>
function deleteReview(id){
if(confirm("Are you sure to delete review")){
    $.ajax({
        url: "{{ route('review.destroy') }}",
        data: {id:id},
        type: 'delete',
        headers: {
            'X-CSRF-TOKEN' : '{{ csrf_token() }}'
        },
        success: function(response){
            window.location.href = '{{route("reviews.index")}}';
        }
    });
}
}
</script>
@endsection