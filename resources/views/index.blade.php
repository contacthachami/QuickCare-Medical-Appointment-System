<!doctype html>
<html>

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Site keywords here">
    <meta name="description" content="">
    <meta name='copyright' content=''>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Title -->
    <title>MA | QuickCare</title>


    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/bootstrap.min.css') }}">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href=" {{ asset('css/landingpage/nice-select.css') }}">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/font-awesome.min.css') }}">
    <!-- icofont CSS -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/icofont.css') }}">
    <!-- Slicknav -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/slicknav.min.css') }}">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/owl-carousel.css') }}">
    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/datepicker.css') }}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/animate.min.css') }}">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/magnific-popup.css') }}">

    <!-- Medipro CSS -->
    <link rel="stylesheet" href="{{ asset('css/landingpage/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landingpage/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landingpage/responsive.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('img/app-logo.png') }}">

    <style>
        .fun-facts {
            background: url({{ asset('img/fun-bg.jpg') }});
        }
        
        /* Enhanced Footer Styles */
        .footer {
            background-color: #1A76D1;
            color: #fff;
        }
        
        .footer .footer-top {
            background-color: #1A76D1;
            padding: 80px 0 50px;
        }
        
        .footer .footer-top:before {
            display: none;
        }
        
        .footer .single-footer h2 {
            color: #fff;
            font-size: 22px;
            font-weight: 600;
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }
        
        .footer .single-footer h2::before {
            content: "";
            position: absolute;
            left: 0;
            width: 40px;
            height: 2px;
            bottom: 0;
            background: #fff;
        }
        
        .footer .single-footer p {
            color: #fff;
            line-height: 26px;
        }
        
        .footer .social {
            margin-top: 25px;
        }
        
        .footer .social li {
            display: inline-block;
            margin-right: 10px;
        }
        
        .footer .social li a {
            width: 38px;
            height: 38px;
            line-height: 38px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: block;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .footer .social li a:hover {
            background: #fff;
            color: #1A76D1;
            border-color: transparent;
            transform: translateY(-5px);
        }
        
        .footer .time-sidual li {
            color: #fff;
            margin-bottom: 15px;
            position: relative;
        }
        
        .footer .time-sidual li:last-child {
            margin-bottom: 0;
        }
        
        .footer .time-sidual li span {
            float: right;
            font-weight: 500;
        }
        
        .footer .time-sidual li i {
            margin-right: 10px;
            color: #fff;
            font-size: 18px;
        }
        
        .footer .newsletter-inner {
            position: relative;
            margin-top: 20px;
            border-radius: 0;
            overflow: hidden;
            max-width: 100%;
        }
        
        .footer .newsletter-inner input {
            width: 100%;
            height: 45px;
            padding: 0 15px;
            background: #fff;
            border: none;
            color: #333;
            border-radius: 0;
            font-size: 15px;
            outline: none;
        }
        
        .footer .newsletter-inner input::placeholder {
            color: #666;
        }
        
        .footer .newsletter-inner .button {
            position: absolute;
            right: 0;
            top: 0;
            height: 45px;
            width: 45px;
            text-align: center;
            line-height: 45px;
            font-size: 18px;
            background: #2c3e50;
            color: #fff;
            border: none;
            border-radius: 0;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .footer .newsletter-inner .button:hover {
            background: #0d5eb9;
        }
        
        .footer .copyright {
            background: #0d5eb9;
            padding: 25px 0;
        }
        
        .footer .copyright-content p {
            color: #fff;
            margin: 0;
            font-size: 14px;
        }
        
        .footer .copyright-content p a {
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer .copyright-content p a:hover {
            color: #f0f0f0;
            text-decoration: underline;
        }
        
        @media only screen and (max-width: 767px) {
            .footer .single-footer {
                margin-bottom: 40px;
            }
        }
        
        /* Enhanced Schedule Area Styles */
        .schedule {
            background: #f8f9fa;
            margin: 0;
            padding: 80px 0 50px;
            height: auto;
        }
        
        .schedule .schedule-inner {
            position: relative;
            transform: none;
            z-index: 9;
        }
        
        .schedule .single-schedule {
            position: relative;
            text-align: left;
            z-index: 3;
            border-radius: 8px;
            background: #5da3f2;
            -webkit-transition: all .3s ease-out 0s;
            -moz-transition: all .3s ease-out 0s;
            -ms-transition: all .3s ease-out 0s;
            -o-transition: all .3s ease-out 0s;
            transition: all .3s ease-out 0s;
            margin-bottom: 30px;
            height: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .schedule .single-schedule .inner {
            overflow: hidden;
            position: relative;
            padding: 30px;
            z-index: 2;
            height: 100%;
        }
        
        .schedule .single-schedule:before {
            position: absolute;
            z-index: -1;
            content: '';
            bottom: -10px;
            left: 0;
            right: 0;
            margin: 0 auto;
            width: 80%;
            height: 90%;
            background: #5da3f2;
            opacity: 0;
            filter: blur(10px);
            -webkit-transition: all .3s ease-out 0s;
            -moz-transition: all .3s ease-out 0s;
            -ms-transition: all .3s ease-out 0s;
            -o-transition: all .3s ease-out 0s;
            transition: all .3s ease-out 0s;
        }
        
        .schedule .single-schedule:hover:before {
            opacity: 0.8;
        }
        
        .schedule .single-schedule:hover {
            transform: translateY(-5px);
            background: #4a96ec;
        }
        
        .schedule .single-schedule .icon i {
            position: absolute;
            font-size: 80px;
            color: #fff;
            -webkit-transition: all .3s ease-out 0s;
            -moz-transition: all .3s ease-out 0s;
            -ms-transition: all .3s ease-out 0s;
            -o-transition: all .3s ease-out 0s;
            transition: all .3s ease-out 0s;
            z-index: -1;
            visibility: visible;
            opacity: 0.2;
            right: -15px;
            bottom: -15px;
        }
        
        .schedule .single-schedule span {
            display: inline-block;
            color: #fff;
            margin-bottom: 5px;
            font-size: 16px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.15);
            padding: 5px 15px;
            border-radius: 30px;
        }
        
        .schedule .single-schedule h4 {
            font-size: 22px;
            font-weight: 600;
            display: block;
            text-transform: capitalize;
            color: #fff;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        
        .schedule .single-schedule p {
            color: #fff;
            margin-top: 0;
            font-size: 15px;
            line-height: 24px;
        }
        
        .schedule .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .schedule .section-title h2 {
            font-size: 32px;
            font-weight: 700;
            color: #1A76D1;
            position: relative;
            margin-bottom: 15px;
            display: inline-block;
        }
        
        .schedule .section-title p {
            font-size: 16px;
            color: #666;
        }
        
        .schedule .section-title h2:before {
            position: absolute;
            content: "";
            left: 50%;
            bottom: -15px;
            height: 2px;
            width: 50px;
            background: #5da3f2;
            margin-left: -25px;
        }
        
        .schedule .row {
            display: flex;
            flex-wrap: wrap;
        }
        
        .schedule .col-lg-4 {
            display: flex;
        }
    </style>

</head>

<body>

    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>

            <div class="indicator">
                <svg width="16px" height="12px">
                    <polyline id="back" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                    <polyline id="front" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                </svg>
            </div>
        </div>
    </div>
    <!-- End Preloader -->

    <div>
        <x-home.home-navbar></x-home.home-navbar>
    </div>

    <!-- Slider Area -->
    <section class="slider">
        <div class="hero-slider">
            <!-- Start Single Slider -->
            <div class="single-slider" style="background-image:url('img/slider2.jpg')">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="text">
                                <h1>We Provide <span>Medical</span> Services That You Can <span>Trust!</span></h1>
                                <p>Book your appointment today and experience top-notch medical care tailored to your
                                    needs.
                                    Our dedicated team of healthcare professionals is committed to your well-being.</p>
                                <div class="button">
                                    <a href="{{ route('login') }}" class="btn">Get Appointment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Single Slider -->
            <!-- Start Single Slider -->
            <div class="single-slider" style="background-image:url('img/slider.jpg')">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="text">
                                <h1>We Provide <span>Medical</span> Services That You Can <span>Trust!</span></h1>
                                <p>Discover the difference with our comprehensive medical services.
                                    From routine check-ups to specialized treatments, we're here to meet all your
                                    healthcare needs.</p>
                                <div class="button">
                                    <a href="{{ route('login') }}" class="btn">Get Appointment</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start End Slider -->
            <!-- Start Single Slider -->
            <div class="single-slider" style="background-image:url('img/slider3.jpg')">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="text">
                                <h1>We Provide <span>Medical</span> Services That You Can <span>Trust!</span></h1>
                                <p>Ready to take charge of your health? Register today to schedule your appointment and
                                    embark on a journey towards better health and wellness.</p>
                                <div class="button">
                                    <a href="{{ route('login') }}" class="btn">Get Appointment</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Single Slider -->
        </div>
    </section>
    <!--/ End Slider Area -->

    <!-- Start Schedule Area -->
    <section class="schedule">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Our Services</h2>
                        <p>Explore the comprehensive healthcare services we offer</p>
                    </div>
                </div>
            </div>
            <div class="schedule-inner">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- single-schedule -->
                        <div class="single-schedule first">
                            <div class="inner">
                                <div class="icon">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <div class="single-content">
                                    <span>Doctor's Timetable</span>
                                    <h4>View And Book Appointments</h4>
                                    <p>Check out our doctors' schedules and book appointments at your convenience.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- single-schedule -->
                        <div class="single-schedule first">
                            <div class="inner">
                                <div class="icon">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <div class="single-content">
                                    <span>Specialized Services</span>
                                    <h4>Expert Care For Complex Cases</h4>
                                    <p>Learn about our specialized services catering to complex medical conditions and treatments.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- single-schedule -->
                        <div class="single-schedule first">
                            <div class="inner">
                                <div class="icon">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div class="single-content">
                                    <span>Appointment Availability</span>
                                    <h4>Flexible Booking Options</h4>
                                    <p>Discover our wide range of available appointment slots tailored to your convenience. Whether you need a last-minute booking or prefer to plan ahead, our flexible scheduling options ensure you can find the perfect time for your appointment with ease.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/End Start schedule Area -->

    <!-- Start Feautes -->
    <section class="Feautes section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>We Are Always Ready to Help You & Your Family</h2>

                        <p>
                        <p>At our web application, we prioritize your health and well-being.
                            Here are some of the features we offer to ensure you and your family receive the best
                            possible care:</p>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-12">
                    <!-- Start Single features -->
                    <div class="single-features">
                        <div class="signle-icon">
                            <i class="fa-solid fa-stethoscope"></i>
                        </div>
                        <h3>Comprehensive Health Services</h3>
                        <p>Access a wide range of healthcare services including primary care, specialist consultations,
                            diagnostic tests, and preventive screenings all in one place.</p>

                    </div>
                    <!-- End Single features -->
                </div>
                <div class="col-lg-4 col-12">
                    <!-- Start Single features -->
                    <div class="single-features">
                        <div class="signle-icon">
                            <i class="fa-solid fa-book"></i>
                        </div>
                        <h3>Convenient Booking</h3>
                        <p>Book appointments online at your convenience, hassle-free and with flexible scheduling
                            options.</p>

                    </div>
                    <!-- End Single features -->
                </div>
                <div class="col-lg-4 col-12">
                    <!-- Start Single features -->
                    <div class="single-features last">
                        <div class="signle-icon">
                            <i class="fa-solid fa-hospital-user"></i>
                        </div>
                        <h3>Expert Consultations</h3>
                        <p>Receive personalized care from experienced healthcare professionals, tailored to your
                            specific needs.</p>

                    </div>
                    <!-- End Single features -->
                </div>
            </div>
        </div>
    </section>
    <!--/ End Feautes -->

    <!-- Start Fun-facts -->
    <div id="fun-facts" class="fun-facts section overlay">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Fun -->
                    <div class="single-fun">
                        <i class="fa-solid fa-user-doctor"></i>
                        <div class="content">
                            <span class="counter">{{ $doctors ? count($doctors) : 0 }}</span>
                            <p>Specialist Doctors</p>
                        </div>
                    </div>
                    <!-- End Single Fun -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Fun -->
                    <div class="single-fun">
                        <i class="fa-solid fa-bed-pulse"></i>
                        <div class="content">
                            <span class="counter">{{ $patients ? count($patients) : 0 }}</span>

                            <p>Happy Patients</p>
                        </div>
                    </div>
                    <!-- End Single Fun -->
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Fun -->
                    <div class="single-fun">
                        <i class="fa-solid fa-star"></i>
                        <div class="content">
                            <span class="counter">{{ $ratings ? count($ratings) : 0 }}</span>
                            <p>Satisfaction Ratings</p>
                        </div>
                    </div>
                    <!-- End Single Fun -->
                </div>
            </div>
        </div>
    </div>
    <!--/ End Fun-facts -->

    <!-- Start Why choose -->
    <section class="why-choose section">
        <div class="container">

            <div class="row">
                <div class="container">
                    <div class="row justify-content-around">

                        <!-- Start Choose Left -->
                        <div class="col-lg-5 mb-4">
                            <div class="choose-left">
                                <h3>Who Are We ?</h3>
                                <p>We are your digital gateway to healthcare convenience and peace of mind. Our web
                                    application
                                    revolutionizes the way you connect with healthcare professionals. Whether you're
                                    seeking a
                                    routine check-up or specialized care, we're here to simplify the process.</p>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list">
                                            <li><i class="fa fa-caret-right"></i>Effortless appointment booking with
                                                doctors of
                                                various specialties</li>
                                            <li><i class="fa fa-caret-right"></i>User-friendly interface for convenient
                                                scheduling
                                                and appointment management</li>
                                            <li><i class="fa fa-caret-right"></i>Comprehensive doctor profiles
                                                including credentials,
                                                specialties, and patient reviews</li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list">
                                            <li><i class="fa fa-caret-right"></i>Prioritized security and privacy
                                                measures for
                                                protecting personal information</li>
                                            <li><i class="fa fa-caret-right"></i>Trusted companion on your healthcare
                                                journey</li>
                                            <li><i class="fa fa-caret-right"></i>Dedicated to making healthcare
                                                accessible,
                                                transparent, and hassle-free</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 mb-4">
                            <div class="choose-left">
                                <h3 cla>You are a healthcare professional ?</h3>
                                <p>Join our <strong>MA | QuickCare</strong> to manage your appointments,
                                    patients, and more!</p>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list">
                                            <li><i class="fa fa-caret-right"></i>Appointment Management</li>
                                            <li><i class="fa fa-caret-right"></i>Patient Management</li>
                                            <li><i class="fa fa-caret-right"></i>Secure Data Storage</li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list">
                                            <li><i class="fa fa-caret-right"></i>Mobile Access</li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('home.apply') }}" target="_blank"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Read More &
                                    Apply !!!</a>
                            </div>



                        </div>
                        <!-- End Choose Left -->
                    </div>
                </div>

                <div class="col-lg-6 col-12">

                </div>
            </div>
        </div>
    </section>
    <!--/ End Why choose -->





    <!-- Start service -->
    <section class="services section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>We Offer Various Medical Services for Your Convenience</h2>

                        <p>Explore the range of healthcare services we provide to ensure your well-being.</p>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="fa-solid fa-user-nurse"></i>
                        <h4>Doctor Consultation</h4>
                        <p>Book appointments with doctors of various specialties for consultations.</p>
                    </div>
                    <!-- End Single Service -->
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="fa-regular fa-calendar-check"></i>
                        <h4>Health Check-ups</h4>
                        <p>Schedule routine health check-ups for preventive care and early detection of health issues.
                        </p>
                    </div>
                    <!-- End Single Service -->
                </div>


                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="fa-solid fa-flask"></i>
                        <h4>Specialized Treatments</h4>
                        <p>Access specialized medical treatments for specific health conditions.</p>
                    </div>
                    <!-- End Single Service -->
                </div>
            </div>
        </div>
    </section>
    <!--/ End service -->





    <!-- Start Appointment -->
    <section class="appointment">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>We Are Always Ready to Help You. Book An Appointment</h2>

                        <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Get Appointment</a>
                    </div>
                </div>
            </div>

        </div>


    </section>
    <!-- End Appointment -->



    <!-- Footer Area -->
    <footer id="footer" class="footer ">
        <!-- Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>About Us</h2>
                            <p>We are committed to revolutionizing healthcare accessibility and convenience through our
                                innovative web application.
                                With a focus on user experience and transparency, we strive to ensure that every
                                interaction with our platform enhances the well-being of our users. Join us in our
                                journey towards a healthier, happier future.</p>
                            <!-- Social -->
                            <ul class="social">
                                <li><a href="#" class="facebook"><i class="fa-brands fa-facebook"></i></a></li>
                                <li><a href="#" class="google"><i class="fa-brands fa-google-plus-g"></i></a></li>
                                <li><a href="#" class="twitter"><i class="fa-brands fa-twitter"></i></a></li>
                                <li><a href="#" class="instagram"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                            <!-- End Social -->
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>Doctor Availability</h2>
                            <p>Find the perfect time for your appointment by browsing through our doctors' schedules.
                                Select a convenient time and date from the available slots to book your consultation.
                            </p>
                            <ul class="time-sidual">
                                <li class="day">Monday - Friday <span>8.00-20.00</span></li>
                                <li class="day">Saturday <span>9.00-18.30</span></li>
                                <li class="day">Sunday <span>9.00-15.00</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>Contact Information</h2>
                            <ul class="time-sidual">
                                <li class="day"><i class="fa fa-map-marker-alt"></i> Morocco <span></span></li>
                                <li class="day"><i class="fa fa-phone"></i> +212 672457782 <span></span></li>
                                <li class="day"><i class="fa fa-envelope"></i> support@quickcare.com <span></span></li>
                            </ul>
                            <div class="newsletter-inner mt-4">
                                <input type="email" name="EMAIL" placeholder="Enter Your Email" required="">
                                <button type="submit" class="button"><i class="fa fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->
        <!-- Copyright -->
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="copyright-content">
                            <p>© Copyright 2025 | All Rights Reserved by <strong>MA | QuickCare</strong> | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Copyright -->
    </footer>
    <!--/ End Footer Area -->
    @include('modals.apply-modal')



    <!-- jquery Min JS -->
    <script src="{{ asset('js/landingpage/jquery.min.js') }}"></script>
    <!-- jquery Migrate JS -->
    <script src="{{ asset('js/landingpage/jquery-migrate-3.0.0.js') }}"></script>
    <!-- jquery Ui JS -->
    <script src="{{ asset('js/landingpage/jquery-ui.min.js') }}"></script>
    <!-- Easing JS -->
    <script src="{{ asset('js/landingpage/easing.js') }}"></script>
    <!-- Color JS -->
    <script src="{{ asset('js/landingpage/colors.js') }}"></script>
    <!-- Popper JS -->
    <script src="{{ asset('js/landingpage/popper.min.js') }}"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="{{ asset('js/landingpage/bootstrap-datepicker.js') }}"></script>
    <!-- Jquery Nav JS -->
    <script src="{{ asset('js/landingpage/jquery.nav.js') }}"></script>
    <!-- Slicknav JS -->
    <script src="{{ asset('js/landingpage/slicknav.min.js') }}"></script>
    <!-- ScrollUp JS -->
    <script src="{{ asset('js/landingpage/jquery.scrollUp.min.js') }}"></script>
    <!-- Niceselect JS -->
    <script src="{{ asset('js/landingpage/niceselect.js') }}"></script>
    <!-- Tilt Jquery JS -->
    <script src="{{ asset('js/landingpage/tilt.jquery.min.js') }}"></script>
    <!-- Owl Carousel JS -->
    <script src="{{ asset('js/landingpage/owl-carousel.js') }}"></script>
    <!-- counterup JS -->
    <script src="{{ asset('js/landingpage/jquery.counterup.min.js') }}"></script>
    <!-- Steller JS -->
    <script src="{{ asset('js/landingpage/steller.js') }}"></script>
    <!-- Wow JS -->
    <script src="{{ asset('js/landingpage/wow.min.js') }}"></script>
    <!-- Magnific Popup JS -->
    <script src="{{ asset('js/landingpage/jquery.magnific-popup.min.js') }}"></script>
    <!-- Counter Up CDN JS -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/landingpage/bootstrap.min.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('js/landingpage/main.js') }}"></script>
</body>

</html>
