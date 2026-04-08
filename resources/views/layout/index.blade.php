<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('page-title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    @include('layout.top-script');
  </head>
  <body >

   <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar  ">
   <div class="layout-container">

    @include('layout.sidebar')
       <div class="menu-mobile-toggler d-xl-none rounded-1">
           <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
               <i class="ti tabler-menu icon-base"></i>
               <i class="ti tabler-chevron-right icon-base"></i>
           </a>
       </div>

    <!-- Layout container -->
    <div class="layout-page">
        <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
                <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="icon-base ti tabler-menu-2 icon-md"></i>
                </a>
            </div>


            <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">

                <!-- Search -->
                <div class="navbar-nav align-items-center">
                    <div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">
                        <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                        <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
                        </a>
                    </div>
                </div>

                @include('layout.header')

            </div>
        </nav>
        <!-- Content wrapper -->
        <div class="content-wrapper">
              <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
          </div>


            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>



        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>


        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

    </div>
    <!-- / Layout wrapper -->


    @include('layout.notification')
    @include('layout.modal')
    @include('layout.script')
  </body>
</html>
