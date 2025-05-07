@include('layout.header')

<div class="container">
    @yield('content')
    @stack('scripts')
</div>

@include('layout.footer')
