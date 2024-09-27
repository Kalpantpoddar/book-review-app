@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-4">
        @include('layouts.sidebar')
            
        <div class="col-md-9">
            @include('layouts.message')
            <div class="card border-0 shadow profile_upgrade_card">
                <div class="card-header  text-dark">
                    Profile
                </div>
                <div class="card-body">
                    <form action="{{ route('account.updateProfile')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" name="name" id="" />
                            @error('name')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email"  name="email" id="email"/>
                            @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Image</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            @if(!@empty(Auth::user()->image))
                            <img src="{{asset('uploads/profile/'.Auth::user()->image) }}" class="img-fluid mt-4 profile_img" alt="{{ $user->name }}" >
                            @endempty
                        </div>   
                        <button class="btn btn-primary mt-2">Update</button>  
                    </form>                   
                </div>
            </div>                
        </div>
    </div>       
</div>
@endsection