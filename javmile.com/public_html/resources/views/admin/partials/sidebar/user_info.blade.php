<!-- User Info -->
@if(\Session::has('admin'))
<?php $admin = \Session::get('admin'); ?>
<div class="sidebar-section sidebar-user clearfix sidebar-nav-mini-hide">
    <div class="sidebar-user-avatar">
        <a href="#">
            <img src="{{ asset('themes/admin/img/placeholders/avatars/avatar2.jpg') }}" alt="avatar">
        </a>
    </div>
    <div class="sidebar-user-name">{{ $admin->name }}</div>
    <div class="sidebar-user-links">
        <!-- <a href="{{ route('show_user',$admin->id) }}" data-toggle="tooltip" data-placement="bottom" title="Profile"><i class="gi gi-user"></i></a> -->
        <a href="#" data-toggle="tooltip" data-placement="bottom" title="Messages"><i class="gi gi-envelope"></i></a>
        <!-- Opens the user settings modal that can be found at the bottom of each page (page_footer.html in PHP version) -->
        <a href="{{ route('edit_user',$admin->id) }}" class="enable-tooltip" data-placement="bottom" title="Settings"><i class="gi gi-cogwheel"></i></a>
        <a href="{{ route('admin_logout') }}" data-toggle="tooltip" data-placement="bottom" title="Logout"><i class="gi gi-exit"></i></a>
    </div>
</div>
@endif
<!-- END User Info -->