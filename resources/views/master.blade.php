<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GTI619 LAB5</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">GTI619 LAB5</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        @if(auth()->user()->role === 'administrateur')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.security') }}">Security Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.security-logs.index') }}">Security Logs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clients.residential') }}">Residential Clients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clients.business') }}">Business Clients</a>
                            </li>
                            
                                    
                        @elseif(auth()->user()->role === 'prepose_residentiel')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clients.residential') }}">Residential Clients</a>
                            </li>
                        @elseif(auth()->user()->role === 'prepose_affaire')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clients.business') }}">Business Clients</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('password.change') }}">Change Password</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link" style="display:inline; padding:0;">Logout</button>
                            </form>
                        </li>
                        
                    @endauth
                </ul>
                @auth
                    <span class="navbar-text">Logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }})</span>
                @endauth
            </div>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
    <!-- Bootstrap JS for navbar toggling -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
