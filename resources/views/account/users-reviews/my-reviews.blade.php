@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-4">
        @include('layouts.sidebar')
            
        <div class="col-md-9">
                
            <div class="card border-0 shadow">
                <div class="card-header  text-black">
                    My Reviews
                </div>
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <form action="" method="get">
                            <div class="d-flex">
                                <input type="text" class="form-control serach_input" name="keyword" value="{{ Request::get('keyword')}}" placeholder="Search">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <a href="{{ route('reviews.myReviews')}}" class="btn btn-secondary ms-2"><i class="fa-solid fa-xmark"></i></a>
                            </div>
                        </form>
                    </div>             
                    <table class="table  table-striped mt-3 review_list">
                        <thead class="table-dark">
                            <tr>
                                <th>Book</th>
                                <th>Review</th>
                                <th>Rating</th>
                                <th>Status</th>                                  
                                <th width="100">Action</th>
                            </tr>
                            <tbody>
                                @if ($reviews->isNotEmpty())
                                @foreach ($reviews as $review)  
                                <tr>
                                    <td><h6>{{ $review->book->name}}</h6></td>
                                    <td>
                                        <p class="review_para">{{ $review->review}}</p>
                                        At: <span class="text-primary">{{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y')}}</span>
                                    </td>                                        
                                    <td>
                                        @for ($i = 1; $i <= $review->rating; $i++)
                                        <i class="fa-solid fa-star"></i> 
                                        @endfor
                                    </td>
                                    <td>
                                        @if ($review->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Block</span>  
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('reviews.editMyReview', $review->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="#" onclick="deleteReview({{ $review->id }})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5"> No Reviews Found.</td>
                                </tr>
                                @endif
                                
                                                                   
                            </tbody>
                        </thead>
                    </table>   
                    {{ $reviews->links()}}                
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
        url: "{{ route('reviews.destroyMyReview') }}",
        data: {id:id},
        type: 'delete',
        headers: {
            'X-CSRF-TOKEN' : '{{ csrf_token() }}'
        },
        success: function(response){
            window.location.href = '{{route("reviews.myReviews")}}';
        }
    });
}
}
</script>
@endsection