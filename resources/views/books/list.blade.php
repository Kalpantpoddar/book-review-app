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
                    <div class="d-flex justify-content-between">
                        <form action="" method="get">
                            <div class="d-flex">
                                <input type="text" class="form-control serach_input" name="keyword" value="{{ Request::get('keyword')}}" placeholder="Search">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <a href="{{ route('books.index')}}" class="btn btn-secondary ms-2"><i class="fa-solid fa-xmark"></i></a>
                            </div>
                        </form>
                        <a href="{{ route('books.create')}}" class="btn btn-primary">Add Book</a>
                    </div>      
                                
                    <table class="table table-striped mt-3 books_list">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                            <tbody>
                                @if ($books->isNotEmpty())
                                    @foreach ($books as $book)
                                    <tr>
                                        <td>{{ $book->name }}</td>
                                        <td>{{ $book->author }}</td>
                                        <td>3.0 (3 Reviews)</td>
                                        <td>
                                            @if ($book->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Block</span>  
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm"><i class="fa-regular fa-star"></i></a>
                                            <a href="{{ route('books.edit', $book->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            <a href="#" onclick="deleteBook({{ $book->id }})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Books Found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </thead>
                    </table>   
                    @if ($books->isNotEmpty())
                        {{ $books->links() }}
                    @endif
                    
                </div>
                
            </div>                 
        </div>
    </div>       
</div>
@endsection

@section('script')
<script>
function deleteBook(id) {
    if (confirm('Are you sure you want to delete this book?')) {
        $.ajax({
            url: "{{ route('books.destroy')}}",
            type: "delete",
            data: {id:id},
            headers: {'X-CSRF-TOKEN': '{{ csrf_token()}}'},
            success: function(response){
                window.location.href = '{{route("books.index")}}';
            }

        });
    }
}
</script>
@endsection