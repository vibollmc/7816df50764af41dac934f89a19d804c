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
                <i class="gi gi-user sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Thông tin cá nhân</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('user_bookmark')}}">
                <i class="gi gi-bookmark sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Bộ sưu tập</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('member_films')}}">
                <i class="gi gi-film sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Phim của tôi</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('member_create_film')}}">
                <i class="gi gi-plus sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Đăng phim mới</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('member_drive_tool')}}">
                <i class="gi gi-flash sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Drive tool</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('member_error_film')}}">
                <i class="gi gi-warning_sign sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Phim bị lỗi</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('user_static')}}">
                <i class="gi gi-charts sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Thống kê</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('user_notifi')}}">
                <i class="gi gi-message_plus sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Thông báo</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('user_edit')}}">
                <i class="gi gi-settings sidebar-nav-icon"></i>
                <span class="sidebar-nav-mini-hide hidden-xs">Sửa thông tin</span>
            </a>
        </li>
    </ul>
    <!-- END Sidebar Navigation -->
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