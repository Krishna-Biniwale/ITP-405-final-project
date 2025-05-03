<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>@yield('title')</title>
</head>
<body>
  <div class="container">
    @if (session('error'))
        <div class="alert alert-danger mt-3" role="alert">
            {{ session('error')}}
        </div>
    @endif
    <ul class="nav d-flex justify-content-end">
        <li class="nav-item">
            <a href="/events" class="nav-link">Events</a>
        </li>
        @if (Auth::check())
            <li class="nav-item">
                <a href="{{ route('event.user') }}" class="nav-link">My created events</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('event.bookmark') }}" class="nav-link">Bookmarks</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('eventCreateForm') }}" class="nav-link">Create new event</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.index') }}" class="nav-link">Profile</a>
            </li>
            <li class="nav-item">
                <form method="post" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none">Logout</button>
                </form>
            </li>
        @else
            <li class="nav-item">
                <a href="{{ route('registration.index') }}" class="nav-link">Register</a>
            </li>
            <li class="nav-item">
                <a href="/login" class="nav-link">Login</a>
            </li>
        @endif
    </ul>
    @yield('main')
  </div>
</body>
</html>