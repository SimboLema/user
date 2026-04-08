<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SURETECH Systems</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- vendor1 CSS Files -->
    <link href="assets/vendor1/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor1/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor1/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor1/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/vendor1/glightbox/css/glightbox.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css1/main.css" rel="stylesheet">
    <style>
        @media (max-width: 576px) {
            .logo img {
                height: 50px !important;
            }
        }
    </style>

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top shadow-sm bg-white">
        <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between flex-wrap">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="logo d-flex align-items-center text-decoration-none">
                <img src="{{ asset('assets/dash/board_files/logo.png') }}" alt="Logo" class="img-fluid d-block"
                     style="height: 80px;">
            </a>

            <!-- Navigation -->
            <nav id="navmenu" class="navmenu d-flex flex-wrap align-items-center justify-content-end ms-auto">

                <ul class="nav justify-content-center mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link active fw-bold">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link fw-bold">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link fw-bold">Core Value</a>
                    </li>
                </ul>

                <!-- Action Buttons -->
                <div class="d-flex flex-row flex-md-row align-items-center gap-2">
                    <a href="{{ route('login') }}" class="btn px-4 py-2 text-white fw-bold"
                       style="background-color: #003153; border-radius: 30px;">
                        Login
                    </a>
                    <a href="{{ route('insuarer.login') }}" class="btn px-4 py-2 text-white fw-bold"
                       style="background-color: #006699; border-radius: 30px;">
                        Insurer Login
                    </a>
                </div>

                <!-- Mobile menu toggle -->
                <i class="mobile-nav-toggle d-xl-none bi bi-list ms-3 fs-3"></i>
            </nav>
        </div>
    </header>



    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">

            <div class="hero-wrapper">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 hero-content" data-aos="fade-right" data-aos-delay="100">
                            <h1><span class="fs-8">Driving The Digital Transformation of the insurance industry</span>
                            </h1>
                            <p>by delivering smart, efficient, and transparent solutions that put customer needs first
                                and simplify every step of the insurance journey.</p>
                            <div class="stats-row">
                                <div class="stat-item">
                                    <span class="stat-number">10+</span>
                                    <span class="stat-label">Insurer</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">1000+</span>
                                    <span class="stat-label">Agents</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">5000+</span>
                                    <span class="stat-label">Customers</span>
                                </div>
                            </div>
                            <div class="action-buttons">
                                {{-- <a href="#" class="btn-primary">Agent Register</a> --}}
                                <a href="{{ route('login') }}" class="btn-secondary">Login</a>

                            </div>

                        </div>
                        <div class="col-lg-6 hero-media" data-aos="zoom-in" data-aos-delay="200">
                            <img src="{{ asset('assets/dash/board_files/isurancePic.jpg') }}" alt="Education"
                                class="img-fluid main-image">
                            <div class="image-overlay">
                                <div class="badge-accredited">
                                    <i class="bi bi-patch-check-fill"></i>
                                    <span>Simplifying insurance through smart technology</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="feature-cards-wrapper" data-aos="fade-up" data-aos-delay="300">
                <div class="container">
                    <div class="row gy-4">
                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-bar-chart-fill"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Automation</h3>
                                    <p>Digitizing and automating policy management, claims handling, and underwriting to
                                        reduce manual errors, speed up operations, and improve accuracy.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="feature-card active">
                                <div class="feature-icon">
                                    <i class="bi bi-laptop-fill"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Data Driven Insights</h3>
                                    <p>Utilizing advanced analytics by provide insurers with actionable insights for
                                        risk assessment, fraud detection, and personalized customer experiences.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Customer-Centric Digital Platforms</h3>
                                    <p>User-friendly web and mobile platforms that allow customers to easily access
                                        services, track claims, and engage with their insurers in real-time.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- About Section -->
        <section id="about" class="about section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row mt-5">
                    <div class="col-lg-12">
                        <div class="core-values" data-aos="fade-up" data-aos-delay="500">
                            <h3 class="text-center mb-4">Core Values</h3>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                                <div class="col">
                                    <div class="value-card">
                                        <div class="value-icon">
                                            <i class="bi bi-lightbulb"></i>
                                        </div>
                                        <h4>Innovation</h4>
                                        <p>We embrace cutting-edge technology to create forward-thinking solutions that
                                            transform the insurance experience.</p>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="value-card">
                                        <div class="value-icon">
                                            <i class="bi bi-bounding-box-circles"></i>
                                        </div>
                                        <h4>Integrity</h4>
                                        <p>We conduct our business with honesty, transparency, and a strong sense of
                                            responsibility to our partners and clients.</p>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="value-card">
                                        <div class="value-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h4>Customer Focus</h4>
                                        <p>Our solutions are designed with the end-user in mind—ensuring accessibility,
                                            satisfaction, and long-term trust.</p>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="value-card">
                                        <div class="value-icon">
                                            <i class="bi bi-award"></i>
                                        </div>
                                        <h4>Excellence</h4>
                                        <p>We are committed to delivering high-quality products and services that meet
                                            the highest industry standards.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section><!-- /About Section -->
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>Partners</h2>
        </div>

        <div class="container d-flex flex-wrap justify-content-center align-items-center gap-4 py-3">
            <img src="{{ asset('assets/dash/board_files/tra-logo-2.png') }}" alt="TRA Logo"
                style="width: 50px; height: auto;">
            <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}" alt="TIRA Logo"
                style="width: 50px; height: auto;">
            <img src="{{ asset('assets/dash/board_files/mualogo.png') }}" alt="MUA Logo"
                style="width: 150px; height: auto;">
            <img src="{{ asset('assets/dash/board_files/monexlogo.jpg') }}" alt="Monex Logo"
                style="width: 100px; height: auto;">
        </div>

        <!-- Featured Programs Section -->
        <section id="featured-programs" class="featured-programs section">


            <section id="stats" class="stats section">

                <footer id="footer" class="footer position-relative light-background">

                    <div class="container footer-top">
                        <div class="row gy-4">
                            <div class="col-lg-4 col-md-6 footer-about">
                                <div class="footer-contact pt-3">
                                    <p>Jamhuri Street</p>
                                    <p>Dar es Salaam, Tanzania</p>
                                    <p class="mt-3"><strong>Phone:</strong> <span>+255 22 2120432 </span><br>
                                        <span>+255 712 467873</span></p>
                                    <p><strong>Email:</strong> <span>admin@kmjinsurance.co.tz</span></p>
                                </div>
                                <div class="social-links d-flex mt-4">
                                    <a href=""><i class="bi bi-twitter-x"></i></a>
                                    <a href=""><i class="bi bi-facebook"></i></a>
                                    <a href=""><i class="bi bi-instagram"></i></a>
                                    <a href=""><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-3 footer-links">
                                <h4>Useful Links</h4>
                                <ul>
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">About</a></li>
                                    <li><a href="#">Core Value</a></li>

                                </ul>
                            </div>

                            <div class="col-lg-2 col-md-3 footer-links">
                                <h4>Join Now</h4>
                                <ul>
                                    {{-- <li><a href="#">Register</a></li> --}}
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                </ul>
                            </div>

                            <div class="col-lg-2 col-md-3 footer-links">
                                <h4>Our Core Value</h4>
                                <ul>
                                    <li><a href="#">Innovation</a></li>
                                    <li><a href="#">Integrity</a></li>
                                    <li><a href="#">Customer Focus</a></li>
                                    <li><a href="#">Excellence</a></li>
                                </ul>
                            </div>

                            <div class="col-lg-2 col-md-3 footer-links">
                                <h4>We Offer</h4>
                                <ul>
                                    <li><a href="#">Automation</a></li>
                                    <li><a href="#">Data Driven Insights</a></li>
                                    <li><a href="#">Customer-Centric </a></li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="container copyright text-center mt-4">
                        <p>© <span>Copyright</span> <strong class="px-1 sitename">SURETECH Systems</strong> <span>All
                                Rights Reserved</span></p>
                        <div class="credits">
                            Designed by <a href="#">Humtech</a>
                        </div>
                    </div>

                </footer>

                <!-- Scroll Top -->
                <a href="#" id="scroll-top"
                    class="scroll-top d-flex align-items-center justify-content-center"><i
                        class="bi bi-arrow-up-short"></i></a>

                <!-- Preloader -->
                <div id="preloader"></div>

                <!-- vendor1 JS Files -->
                <script src="assets/vendor1/bootstrap/js/bootstrap.bundle.min.js"></script>
                <script src="assets/vendor1/php-email-form/validate.js"></script>
                <script src="assets/vendor1/aos/aos.js"></script>
                <script src="assets/vendor1/swiper/swiper-bundle.min.js"></script>
                <script src="assets/vendor1/purecounter/purecounter_vanilla.js"></script>
                <script src="assets/vendor1/imagesloaded/imagesloaded.pkgd.min.js"></script>
                <script src="assets/vendor1/isotope-layout/isotope.pkgd.min.js"></script>
                <script src="assets/vendor1/glightbox/js/glightbox.min.js"></script>

                <!-- Main JS File -->
                <script src="assets/js1/main.js"></script>

</body>

</html>
