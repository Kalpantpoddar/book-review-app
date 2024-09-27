<div class="col-md-3">
    <div class="card border-0 shadow-lg profile_card">
        <div class="card-header  text-dark">
            Welcome, {{ Auth::user()->name }} 
        </div>
        <div class="card-body">
            <div class="text-center mb-3">
                <img src="{{asset('uploads/profile/'.Auth::user()->image) }}" class="img-fluid rounded-circle profile_img" alt="{{ Auth::user()->name }}">                            
            </div>
            <div class="h5 text-center">
                <strong>{{ Auth::user()->name }}</strong>
                @php
                    $reviewsCount = Auth::user()->reviews->count();
                @endphp
                <p class="h6 mt-2 text-muted">{{ ($reviewsCount > 1) ? $reviewsCount.' Reviews' : $reviewsCount.' Review' }}</p>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-lg mt-3">
        <div class="card-header  text-dark">
            Navigation
        </div>
        <div class="card-body sidebar">
            <ul class="nav flex-column">
            @if (Auth::user()->role == 'admin')
                <li class="nav-item">
                    <a href="{{ route('books.index')}}">Books</a>                               
                </li>
                <li class="nav-item">
                    <a href="{{ route('reviews.index')}}">Reviews</a>                               
                </li>
            @endif
                
                <li class="nav-item">
                    <a href="{{ route('account.profile')}}">Profile</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reviews.myReviews')}}">My Reviews</a>
                </li>
                {{-- <li class="nav-item">
                    <a href="change-password.html">Change Password</a>
                </li>  --}}
                <li class="nav-item">
                    <a href="{{ route('account.logout') }}">Logout</a>
                </li>                           
            </ul>
        </div>
    </div>
</div>