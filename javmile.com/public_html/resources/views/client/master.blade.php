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
        @if(is_null(MetaTag::get('canonical')))
        <meta property="og:url" content="{{ \URL::current() }}" />
        @else
        <meta property="og:url" content="{{ MetaTag::get('canonical') }}" />
        @endif
        <meta property="og:type" content="{{ MetaTag::get('og_type') }}" />
        <meta property="og:site_name" content="{{ MetaTag::get('title') }}" />
        <meta property="fb:app_id" content="{{ env('SOCIAL_FB_ID') }}" />
		<meta name="clckd" content="ecfef9bb103ada7bf4f0d45e7a2b313e" />
        
        <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
        <link rel="image_src" type="{{ MetaTag::get('image_type') }}" href="{{ MetaTag::get('image_src') }}"/>
		
		<meta name="juicyads-site-verification" content="d601afb78e1f2126eea3e7183960de0c">
        
        @if(is_null(MetaTag::get('canonical')))
        <link rel="canonical" href="{{ \URL::current() }}" />
        <link rel="shortlink" href="{{ \URL::current() }}" />
        @else
        <link rel="canonical" href="{{ MetaTag::get('canonical') }}" />
        <link rel="shortlink" href="{{ MetaTag::get('canonical') }}" />
        @endif

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

<!--
The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
In case you use the code multiple times then be sure that every id is unique!
-->
<div id="ea_3553950_node">&nbsp;</div>

<script type="text/javascript" language="javascript" charset="utf-8">
/*
code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
*/ 

if (typeof eaCtrl =="undefined"){ 
	var eaCtrlRecs=[];
	var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
	var js = document.createElement('script');
	js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=3553950");
	document.head.appendChild(js);
}
/*
End load eactrl 

Command eaCtrl to load ads
*/
eaCtrl.add({"display":"ea_3553950_node","sid":3553950,"plugin":"banner","traffic_type":"all","subid":""});
</script>


<!--
The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
In case you use the code multiple times then be sure that every id is unique!
-->
<div id="ea_3553952_node">&nbsp;</div>

<script type="text/javascript" language="javascript" charset="utf-8">
/*
code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
*/ 

if (typeof eaCtrl =="undefined"){ 
	var eaCtrlRecs=[];
	var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
	var js = document.createElement('script');
	js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=3553952");
	document.head.appendChild(js);
}
/*
End load eactrl 

Command eaCtrl to load ads
*/
eaCtrl.add({"display":"ea_3553952_node","sid":3553952,"plugin":"banner","traffic_type":"all","subid":""});
</script>
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
		
		<!-- JuicyAds PopUnders v3 Start -->
		<!-- <script type="text/javascript" src="https://js.juicyads.com/jp.php?c=3474z2w2s274u4q2q29403b444&u={{ \URL::current() }}"></script> -->
		<!-- JuicyAds PopUnders v3 End -->
		
        @include('client.layouts.js')
        <!-- Scroll to top link, initialized in - scrollToTop() -->
        <a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>
        <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->

        <script src="{{asset('themes/client/js/plugins.1.0.js')}}"></script>
        <script src="{{asset('themes/client/js/app-1.7.js')}}"></script>
    </body>
</html>
