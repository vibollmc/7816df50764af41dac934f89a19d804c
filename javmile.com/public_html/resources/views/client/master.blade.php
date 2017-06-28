<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ MetaTag::get('title')}}</title>
        <meta name="title" content="{{ MetaTag::get('title') }}">
        <meta name="keywords" content="{{ MetaTag::get('keyword') }}">
        <meta name="description" content="{{ MetaTag::get('description') }}">
        <meta name="author" content="{{ MetaTag::get('author') }}">
        <meta name="csrf_token" content="{{ csrf_token() }}">
        <meta name="robots" content="index,follow" />
        <meta name="agent_device" content="{{\Session::get('user_agent')['device']}}" />
        <meta content="index, follow" name="googlebot">
        <meta property="og:title" content="{{ MetaTag::get('title') }}" />
        <meta property="og:description" content="{{ MetaTag::get('description') }}" />
        <meta property="og:image" content="{{ MetaTag::get('image_src') }}" />
        <meta property="og:url" content="{{ \URL::current() }}" />
        <meta property="og:type" content="{{ MetaTag::get('og_type') }}" />
        <meta property="og:site_name" content="{{ MetaTag::get('title') }}" />
        <meta property="fb:app_id" content="{{ env('SOCIAL_FB_ID') }}" />
        
        <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
        <link rel="image_src" type="{{ MetaTag::get('image_type') }}" href="{{ MetaTag::get('image_src') }}"/>
        <link rel="canonical" href="{{ \URL::current() }}" />
        <link rel="shortlink" href="{{ \URL::current() }}" />

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="{{asset('themes/client/css/bootstrap.min.css')}}">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="{{asset('themes/client/css/plugins.css')}}">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="{{asset('themes/client/css/main-4.9.css')}}">

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        {{-- <link rel="stylesheet" href="{{asset('themes/client/css/themes/spring.css')}}"> --}}
        <!-- END Stylesheets -->
        {{-- <link rel="stylesheet" href="{{asset('themes/client/css/owl.theme.css')}}"> --}}
        <link rel="stylesheet" href="{{asset('themes/client/css/owl.carousel.css')}}">
        {{-- <link rel="stylesheet" type="text/css" href="{{asset('themes/client/css/tooltipster.bundle.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('themes/client/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-shadow.min.css')}}" /> --}}

        <script src="{{asset('themes/client/js/vendor/modernizr-respond.min.js')}}"></script>
        <script src="{{asset('themes/client/js/vendor/jquery-1.12.0.min.js')}}"></script>
        <script src="{{asset('themes/client/js/vendor/bootstrap.min.js')}}"></script>
        <script src="{{asset('themes/client/js/owl.carousel.min.js')}}"></script>
        {{-- <script type="text/javascript" src="{{asset('themes/client/js/tooltipster.bundle.min.js')}}"></script> --}}

 	</head>
    <body>
        <!-- Page Container -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!-- 'boxed' class for a boxed layout -->
        <div id="page-wrapper">
            <div id="page-container" class="sidebar-no-animations">
                <!-- Main Sidebar -->
                @include('client.layouts.sidebar')
                <!-- END Main Sidebar -->

                <!-- Main Container -->
                <div id="main-container">
                    @include('client.layouts.header')
                    <div id="page-content">
                        <!-- No Sidebars Header -->
                        @include('client.block.breadcrumb')
                        <div class="content-header">
                            <div class="header-section">
                            @if(\URL::current() == route('home'))
                                <div class="col-xs-12">
                                    <div class="mini-slide-box">
                                        @include('client.block.slider')
                                    </div>
                                    <div class="facebook-box hidden-xs">
                                        <script type="text/javascript">
                                        var ad_idzone = "2673506",
                                            ad_width = "300",
                                            ad_height = "250";
                                        </script>
                                        <script type="text/javascript" src="https://ads.exoclick.com/ads.js"></script>
                                        <noscript><a href="https://main.exoclick.com/img-click.php?idzone=2673506" target="_blank"><img src="https://syndication.exoclick.com/ads-iframe-display.php?idzone=2673506&output=img&type=300x250"></a></noscript>
                                    </div>
                                </div>
                                @include('client.block.slide-bottom')
                                @include('client.block.mini-slider')
                            @endif
                            </div>
                            @section('content')
                            @show
                        </div>
                        <!-- END No Sidebars Header -->

                        <!-- END Dummy Content -->
                    </div>
                    <!-- END Page Content -->

                    <!-- Footer -->
                    @include('client.layouts.footer')
                    <!-- END Footer -->
                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>
        <!-- END Page Container -->
        @if(!\Session::has('user'))
            @include('client.modal.auth')
        @endif
        @include('client.layouts.js')
        <!-- Scroll to top link, initialized in - scrollToTop() -->
        <a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>
        <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->

        <script src="{{asset('themes/client/js/plugins.1.0.js')}}"></script>
        <script src="{{asset('themes/client/js/app-1.7.js')}}"></script>
    </body>
</html>
