@include('layout.header')

<div class="container">
    @yield('content')
</div>
@stack('styles')

<!-- Load dependencies pertama -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<!-- Lalu inject semua inline/pushed scripts sekali saja -->
@stack('scripts')

@include('layout.footer')
