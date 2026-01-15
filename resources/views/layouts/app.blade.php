<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Parkir')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; margin-bottom: 20px; }
        .header h1 { display: inline-block; }
        .header .user-info { float: right; }
        .header a { color: white; text-decoration: none; margin-left: 15px; }
        .content { background: white; padding: 20px; border-radius: 5px; }
        .nav { background: #34495e; padding: 10px; margin-bottom: 20px; }
        .nav a { color: white; text-decoration: none; padding: 10px 15px; display: inline-block; }
        .nav a:hover { background: #2c3e50; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button, .btn { padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        table th { background: #34495e; color: white; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .card { background: white; padding: 20px; border-radius: 5px; border: 1px solid #ddd; }
        .card h3 { margin-bottom: 10px; color: #2c3e50; }
        .card .value { font-size: 24px; font-weight: bold; color: #3498db; }
    </style>
    @yield('styles')
</head>
<body>
    @auth
    <div class="header">
        <h1>Sistem Parkir Internal</h1>
        <div class="user-info">
            <span>{{ Auth::user()->nama_lengkap }} ({{ Auth::user()->role }})</span>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>
    @endif

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
