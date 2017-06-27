<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>Admin</title>

        <meta name="description" content="website management.">
        <meta name="author" content="static team">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
        <!-- END Icons -->
        <!-- Stylesheets -->
        @include('admin.block.header-css')
        <!-- END Stylesheets -->

        @include('admin.block.header-js')
    </head>
    <body>
        <div id="page-wrapper">
            <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">
                @include('admin.partials.sidebar')
                <!-- Main Container -->
                <div id="main-container">
                    @include('admin.partials.header')
                    <!-- Page content -->
                    <div id="page-content">
                        @section('content')
                        @show
                    </div>
                    <!-- END Page Content -->
                    @include('admin.partials.footer')
                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>
        <!-- END Page Wrapper -->
        @include('admin.layouts.load-custom-page-js')
        <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
        <script src="{{asset('themes/admin/js/vendor/bootstrap.min.js')}}"></script>
        <script src="{{asset('themes/admin/js/plugins.js')}}"></script>
        <script src="{{asset('themes/admin/js/app.js')}}"></script>
    </body>
</html>