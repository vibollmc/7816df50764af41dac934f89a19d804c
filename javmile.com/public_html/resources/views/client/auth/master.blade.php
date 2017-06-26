@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            <div class="sidebar-content">
                <!-- Sidebar Navigation -->
                <ul class="sidebar-nav">
                    <li class="auth-title active">
                        <a>
                            <i class="fa fa-navicon sidebar-nav-icon"></i>
                            <span class="sidebar-nav-mini-hide hidden-xs">Menu</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('user')}}">
                            <i class="gi gi-stopwatch sidebar-nav-icon"></i>
                            <span class="sidebar-nav-mini-hide hidden-xs">Thông tin cá nhân</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('user_bookmark')}}">
                            <i class="gi gi-stopwatch sidebar-nav-icon"></i>
                            <span class="sidebar-nav-mini-hide hidden-xs">Bộ sưu tập</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('user_notifi')}}">
                            <i class="gi gi-stopwatch sidebar-nav-icon"></i>
                            <span class="sidebar-nav-mini-hide hidden-xs">Thông báo</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('user_edit')}}">
                            <i class="gi gi-stopwatch sidebar-nav-icon"></i>
                            <span class="sidebar-nav-mini-hide hidden-xs">Sửa thông tin</span>
                        </a>
                    </li>
                </ul>
                <!-- END Sidebar Navigation -->
            </div>
            <div id="auth-container">
                <div class="auth-title">
                {{isset($title)? $title: 'Thông tin cá nhân'}}
                </div>
                @section('content')
                @show
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.sidebar-item').each(function(){
            var str = $(location).attr('href');
            var n = str.indexOf($(this).find('a').attr('href'));
            if (n==0) {
                $(this).addClass('curent');
            }
        });
    })
</script>
@stop