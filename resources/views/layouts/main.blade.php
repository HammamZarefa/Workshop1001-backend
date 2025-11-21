<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- Tailwind -->

{{--    <style>--}}
{{--        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');--}}
{{--        .font-family-karla { font-family: karla; }--}}
{{--        .bg-sidebar { background: #3d68ff; }--}}
{{--        .cta-btn { color: #3d68ff; }--}}
{{--        .upgrade-btn { background: #1947ee; }--}}
{{--        .upgrade-btn:hover { background: #0038fd; }--}}
{{--        .active-nav-link { background: #1947ee; }--}}
{{--        .nav-item:hover { background: #1947ee; }--}}
{{--        .account-link:hover { background: #3d68ff; }--}}
{{--    </style>--}}

    @stack('styles')
</head>

<body class="bg-gray-100 font-family-karla flex">

    @include('components.sidebar')

    <div class="w-full flex flex-col h-screen overflow-y-hidden">

        @include('components.navbar')

        <div class="w-full overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                @yield('content')
            </main>

            @include('components.footer')
        </div>
    </div>

{{--    <!-- AlpineJS -->--}}
{{--    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>--}}
{{--    <!-- Font Awesome -->--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>--}}
{{--    <!-- ChartJS -->--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

    @stack('scripts')

</body>
</html>

