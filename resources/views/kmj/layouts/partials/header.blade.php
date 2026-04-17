 <!--begin::Header-->
 <div id="kt_app_header" class="app-header " data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}"
     data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}">

     <!--begin::Header container-->
     <div class="app-container  container-xxl d-flex align-items-stretch justify-content-between "
         id="kt_app_header_container">
         <!--begin::Header mobile toggle-->

         <!--end::Header mobile toggle-->

         <!--begin::Logo-->
         <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
             <a href="#">
                 <img alt="Logo" src="{{ asset('assets/dash/board_files/logo.png') }}"
                     class="h-150px h-lg-150px app-sidebar-logo-default theme-light-show" />
         </div>
         <!--end::Logo-->

         <!--begin::Header wrapper-->
         <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
             <!--begin::Menu wrapper-->
             <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
                 data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
                 data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
                 data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
                 data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                 data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                 <!--begin::Menu-->

                 <!--end::Menu-->
             </div>
             <!--end::Menu wrapper-->

             <!--begin::Navbar-->
             <div class="app-navbar flex-shrink-0">
                 <!--begin::Search-->
                 <div class="app-navbar-item align-items-stretch ms-1 ms-lg-3">

                     <!--begin::Search-->
                     <div id="kt_header_search" class="header-search d-flex align-items-stretch"
                         data-kt-search-keypress="true" data-kt-search-min-length="2" data-kt-search-enter="enter"
                         data-kt-search-layout="menu" data-kt-menu-trigger="auto" data-kt-menu-overflow="false"
                         data-kt-menu-permanent="true" data-kt-menu-placement="bottom-end">
                         <!--end::Menu-->
                     </div>
                     <!--end::Search-->
                 </div>

                 <!--begin::User menu-->
                 <div class="app-navbar-item ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                     <!--begin::Menu wrapper-->
                     <div class="d-flex justify-content-center ms-4 me-15 ">
                         <div class="d-flex fw-bold text-primary ms-20 me-10 menu-link px-2"
                             style="color: rgb(15, 15, 83) !important;">
                             <a href="#" class="btn btn-sm me-3" style="text-decoration: none; color: inherit;">
                                 <span></span></a>
                         </div>
                         <div class="d-flex fw-bold text-primary ms-0 menu-link"
                             style="color: rgb(15, 15, 83) !important;">
                             <form method="GET" action="{{ route('logout') }}">
                                 @csrf
                                 <button type="submit"
                                     style="border: none; background: none; color: inherit; font-weight: bold; cursor: pointer;">
                                     Logout
                                 </button>
                             </form>
                         </div>

                         {{-- <div class="d-flex fw-bold text-primary ms-4 menu-link" style="color: red !important;">
                             <a href="#" style="text-decoration: none; color: inherit;"><span style="color: #003153">Messages |
                                     </span> <span>10081</span></a>
                         </div> --}}
                         <!--
                                    <div class="d-flex fw-bold text-primary ms-4 menu-link">
                                        <a href="../index.html">
                                        <button class="btn btn-primary btn-sm">Logout</button>
                                        </a>

                                    </div>
                                    -->
                     </div>
                     <div class="cursor-pointer symbol symbol-35px symbol-md-40px"
                         data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                         data-kt-menu-placement="bottom-end">

                         <!-- Begin: User Initials as Avatar -->
                         <!-- Your initials div with popover attributes -->
                         @php
                            // 1. Get the authenticated user safely
                            // We try the default web guard first, then fall back to insuarer
                            $user = Auth::user() ?? Auth::guard('insuarer')->user();

                            if ($user) {
                                // 2. Generate Initials (e.g., "Kuku Chima" -> "KC")
                                $nameParts = explode(' ', trim($user->name));
                                $firstInitial = substr($nameParts[0], 0, 1);
                                $lastInitial = count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : '';
                                $initials = strtoupper($firstInitial . $lastInitial);

                                // 3. Determine Role Label safely
                                $roleLabel = 'User';

                                // Check if the user is from the insuarer guard
                                if (Auth::guard('insuarer')->check()) {
                                    $roleLabel = 'Insurer Partner';
                                }
                                // If they are logged in via the default guard, they might be an Admin
                                // (Adjust this condition if your admin check logic is different)
                                elseif (Auth::check()) {
                                    $roleLabel = 'System Admin';
                                }
                            }
                        @endphp

                        @if($user)
                        <div class="symbol-label1 text-white fw-bold fs-4 d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 50px; height: 50px; cursor: pointer; background-color: #003153;"
                             data-bs-toggle="popover"
                             data-bs-trigger="hover"
                             data-bs-placement="right"
                             data-bs-html="true"
                             data-bs-content='
                                <div class="popover-profile">
                                    <div class="d-flex align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-primary fw-bold">{{ $roleLabel }}</small>
                                        </div>
                                    </div>
                                    <hr class="my-2 opacity-10">
                                    <div class="row g-2">
                                        <div class="col-12 text-start">
                                            <small class="text-muted d-block">Email Address</small>
                                            <small class="fw-medium">{{ $user->email }}</small>
                                        </div>
                                        <div class="col-6 text-start">
                                            <small class="text-muted d-block">Joined</small>
                                            <small class="fw-medium">{{ $user->created_at ? $user->created_at->format("M Y") : "N/A" }}</small>
                                        </div>
                                        @if(isset($user->Insuarer_code))
                                        <div class="col-6 text-start">
                                            <small class="text-muted d-block">Code</small>
                                            <small class="fw-medium">{{ $user->Insuarer_code }}</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                             '>
                            {{ $initials }}
                        </div>
                        @endif

                         <!-- Optional: Status dot -->
                         <div
                             class="position-absolute translate-middle bottom-0 start-100 ms-n1 mb-7 bg-danger rounded-circle h-10px w-10px">
                         </div>
                     </div>


                     <!--begin::User account menu-->
                     <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                         data-kt-menu="true">
                         <!--begin::Menu item-->
                         <div class="menu-item px-3">

                             <div class="menu-content d-flex align-items-center px-3">
                                 <!--begin::Avatar (Initials instead of image)-->
                                 <div class="cursor-pointer symbol symbol-35px symbol-md-40px"
                                     data-kt-menu-trigger="{default: &#39;click&#39;, lg: &#39;hover&#39;}"
                                     data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">

                                     <!-- Begin: User Initials as Avatar -->
                                     <div class="symbol-label1 bg-primary text-white fw-bold fs-4 d-flex align-items-center justify-content-center rounded-circle"
                                         style="width: 50px; height: 50px;">
                                         IM
                                     </div>

                                     <!-- Optional: Status dot -->
                                     <div
                                         class="position-absolute translate-middle bottom-0 start-100 ms-n1 mb-7 bg-danger rounded-circle h-10px w-10px">
                                     </div>
                                 </div>
                                 <!--end::Avatar-->

                                 <!--begin::Username-->
                                 <div class="d-flex flex-column">
                                     <div class="fw-bold d-flex align-items-center fs-5">
                                         SURETECH Systems
                                         <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                             admin
                                         </span>
                                     </div>

                                     <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                         h.zuheri@SURETECH Systems.co.tz
                                     </a>
                                 </div>
                                 <!--end::Username-->
                             </div>

                         </div>
                         <!--end::Menu item-->

                         <!--begin::Menu separator-->
                         <div class="separator my-2"></div>
                         <!--end::Menu separator-->

                         <!--begin::Menu item-->
                         <div class="menu-item px-5">
                             <a href="../account/overview.html" class="menu-link px-5">
                                 My Profile
                             </a>
                         </div>
                         <!--end::Menu item-->


                         <!--begin::Menu separator-->
                         <div class="separator my-2"></div>
                         <!--end::Menu separator-->




                         <!--begin::Menu item-->
                         <div class="menu-item px-5 my-1">
                             <a href="../account/settings.html" class="menu-link px-5">
                                 Account Settings
                             </a>
                         </div>
                         <!--end::Menu item-->

                         <!--begin::Menu item-->
                         <div class="menu-item px-5">
                             <form method="POST" action="https:/.SURETECH Systems.co.tz/logout">
                                 <input type="hidden" name="_token" value="9IWdQgSdUy3b0mZ2V7oBOo0xJS9Hl9JxOilhlyoA"
                                     autocomplete="off">
                                 <a href="#" class="menu-link px-5"
                                     onclick="event.preventDefault(); this.closest('form').submit();">
                                     Sign Out
                                 </a>
                             </form>
                         </div>
                         <!--end::Menu item-->
                     </div>
                     <!--end::User account menu-->

                     <!--end::Menu wrapper-->
                 </div>
                 <!--end::User menu-->

                 <!--begin::Header menu toggle-->
                 <!--end::Header menu toggle-->
             </div>
             <!--end::Navbar-->
         </div>
         <!--end::Header wrapper-->
     </div>
     <!--end::Header container-->
 </div>
 <!--end::Header-->
