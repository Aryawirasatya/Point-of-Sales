<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>TOKO KELONTONG</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="{{ asset('assets/img/warcokheader.png') }}" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="{{ asset('assets/js')}}/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: [
          "Font Awesome 5 Solid",
          "Font Awesome 5 Regular",
          "Font Awesome 5 Brands",
          "simple-line-icons",
        ],
        urls: ["{{ asset('assets/css/fonts.min.css') }}"],
      },
      active: function () {
        sessionStorage.fonts = true;
      },
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
  <style>
  /* 1) Ubah warna dasar halaman (body) */
  body {
    background-color:rgba(49, 54, 63, 0.26) !important; /* ganti dengan hex pilihanmu */
  }

  /* 2) Jika konten dibungkus oleh wrapper/main-panel, override juga */
  .main-panel, nav {
    background-color: #222831 !important;
  }
  .wrapper {
    background-color: #222831 !important;
  }

  /* 3) Jika container-card atau elemen lain yang masih putih, override lebih spesifik */
  .card, .container {
  }

  .sidebar{
    background-color:rgb(23, 28, 33) !important;

  }
  .navbar{
           background-color:rgb(23, 28, 33) !important;
 /* ganti dengan hex pilihanmu */

  }

  .container {
    background-color: #222831 !important;
  }
  .footer{
    background-color: #222831 !important;

  }
 
</style>


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    crossorigin="anonymous" />




  <!-- CSS Just for demo purpose, don't include it in your project -->
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap JS (for dismissing alert) -->



</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" data-background-color="#222831">
      <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="222831">
          <a href=" " class="logo px-5 ">
            <img src="{{ asset('assets/img/warcok1.png') }}" alt="navbar brand" class="navbar-brand "
              height="50px"   />
          </a>
          <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar" >
              <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
              <i class="gg-menu-left"></i>
            </button>
          </div>
          <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
          </button>
        </div>
        <!-- End Logo Header -->
      </div>
      <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
          <ul class="nav nav-secondary">
            {{-- Dashboard: arahnya beda sesuai role --}}
            <li class="nav-item">
              <a href="
                {{ auth()->user()->role === 'admin'
  ? route('admin.dashboard')
  : route('cashier.dashboard') }}
              ">
                <i class="fas fa-home"></i>
                <p>Dashboard</p>
              </a>
            </li>

            {{-- Hanya untuk Admin --}}
        @if(auth()->user()->role === 'admin')
          <li class="nav-item">
            <a href="{{ route('products.index') }}">
            <i class="fas fa-box"></i>
            <p>Product</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('categories.index') }}">
            <i class="fas fa-tags"></i>
            <p>Kategori</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.users.index') }}">
            <i class="fas fa-users"></i>
            <p>User Management</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.reports.index') }}">
            <i class="fas fa-file-excel"></i>
            <p>laporan</p>
            </a>
          </li>
        @endif

            {{-- Hanya untuk Cashier --}}
            @if(auth()->user()->role === 'cashier')
        <li class="nav-item">
          <a href="{{ route('cashier.transactions.create') }}">
          <i class="fas fa-cash-register"></i>
          <p>Transaksi</p>
          </a>
        </li>
        <li class="nav-item">

          <a href="{{ route('cashier.transactions.index') }}">
          <i class="fas fa-file-invoice-dollar"></i>
          <p>INVOICE</p>
          </a>
        </li>

      @endif
          </ul>
        </div>
      </div>


    </div>
    <!-- End Sidebar -->

    <div class="main-panel">
      <div class="main-header">
        <div class="main-header-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="{{ route('home') }}" class="logo">
              <img src="{{ asset('assets/img/warcok1.png') }}" alt="navbar brand" class="navbar-brand"
                height="20" />
            </a>

            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar ">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <!-- Navbar Header -->
        <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search ps-3 pe-3 d-none d-lg-flex">

          <div class="container-fluid">
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">

            </nav>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
              <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                  aria-expanded="false" aria-haspopup="true">
                  <i class="fa fa-search"></i>
                </a>
                <ul class="dropdown-menu dropdown-search animated fadeIn">
                  <form class="navbar-left navbar-form nav-search">
                    <div class="input-group">
                      <input type="text" placeholder="Search ..." class="form-control" />
                    </div>
                  </form>
                </ul>
              </li>


              {{-- <li class="nav-item topbar-icon dropdown hidden-caret">
                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                  <i class="fas fa-layer-group"></i>
                </a>
                <div class="dropdown-menu quick-actions animated fadeIn">
                  <div class="quick-actions-header">
                    <span class="title mb-1">Quick Actions</span>
                    <span class="subtitle op-7">Shortcuts</span>
                  </div>
                  <div class="quick-actions-scroll scrollbar-outer">
                    <div class="quick-actions-items">
                      <div class="row m-0">
                        <a class="col-6 col-md-4 p-0" href="#">
                          <div class="quick-actions-item">
                            <div class="avatar-item bg-danger rounded-circle">
                              <i class="far fa-calendar-alt"></i>
                            </div>
                            <span class="text">Calendar</span>
                          </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                          <div class="quick-actions-item">
                            <div class="avatar-item bg-warning rounded-circle">
                              <i class="fas fa-map"></i>
                            </div>
                            <span class="text">Maps</span>
                          </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                          <div class="quick-actions-item">
                            <div class="avatar-item bg-info rounded-circle">
                              <i class="fas fa-file-excel"></i>
                            </div>
                            <span class="text">Reports</span>
                          </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                          <div class="quick-actions-item">
                            <div class="avatar-item bg-success rounded-circle">
                              <i class="fas fa-envelope"></i>
                            </div>
                            <span class="text">Emails</span>
                          </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                          <div class="quick-actions-item">
                            <div class="avatar-item bg-primary rounded-circle">
                              <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <span class="text">Invoice</span>
                          </div>
                        </a>
                        <a class="col-6 col-md-4 p-0" href="#">
                          <div class="quick-actions-item">
                            <div class="avatar-item bg-secondary rounded-circle">
                              <i class="fas fa-credit-card"></i>
                            </div>
                            <span class="text">Payments</span>
                          </div>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </li> --}}
              <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
 
                  <span class="profile-username">
                    <span class="op-7" style="color:white">Hi,</span>
                    <span class="fw-bold" style="color:white">  {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                  <div class="dropdown-user-scroll scrollbar-outer">
                    <li>
                      <div class="user-box">
 
                        <div class="u-text">
                          <h4>{{ auth()->user()->name }}</h4>
                          <p class="text-muted">{{ auth()->user()->email }}</p>
                        </div>
                      </div>
                    </li>
                   <li>
                    <button 
                      type="button" 
                      class="dropdown-item text-danger d-flex align-items-center gap-2" 
                      data-bs-toggle="modal" 
                      data-bs-target="#logoutModal"
                    >
                      <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                  </li>
                  </div>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>