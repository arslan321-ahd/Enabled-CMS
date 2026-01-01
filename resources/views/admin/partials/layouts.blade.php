<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head_css')
</head>
<body>
    @include('admin.partials.navbar')
    @include('admin.partials.sidenav')
    <div class="page-wrapper">
        <div class="page-content">
            @yield('content')
            @include('admin.partials.footer')
        </div>
    </div>
    @include('admin.partials.script')
</body>
</html>