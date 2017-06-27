<!-- Main Sidebar -->
<div id="sidebar">
    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <a href="{{ route('admin_home') }}" class="sidebar-brand">
                <i class="gi gi-home"></i><span class="sidebar-nav-mini-hide">HOME</span>
            </a>
            @include('admin.partials.sidebar.user_info')

            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                <li <?php echo strstr(URL::current(),route('admin_film')) != ''? 'class="active"':''; ?>>
                    <a href="javascript:void(0)" class="sidebar-nav-menu" ><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-film sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Film</span></a>
                    <ul>
                        <li><a href="{{ route('admin_film') }}" <?php echo URL::current()==route('admin_film')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Films</span></a></li>
                        <li><a href="{{ route('admin_member_film') }}" <?php echo URL::current()==route('admin_member_film')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Member films</span></a></li>
                        <li><a href="{{ route('backend_drive') }}" <?php echo URL::current()==route('backend_drive')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Tool Drive</span></a></li>
                        <li><a href="{{ route('admin_film_genres') }}" <?php echo URL::current()==route('admin_film_genres')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Genres</span></a></li>
                        <li><a href="{{ route('admin_category') }}" <?php echo URL::current()==route('admin_category')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Categories</span></a></li>
                        <li><a href="{{ route('admin_quality') }}" <?php echo URL::current()==route('admin_quality')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Qualities</span></a></li>
                        <li><a href="{{ route('admin_country') }}" <?php echo URL::current()==route('admin_country')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Countries</span></a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin_payment') }}" <?php echo strstr(URL::current(),route('admin_payment')) != ''? 'class="active"':''; ?> ><i class="gi gi-usd sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Pay</span></a>
                </li>
                <li>
                    <a href="{{ route('admin_calendar') }}" <?php echo strstr(URL::current(),route('admin_calendar')) != ''? 'class="active"':''; ?> ><i class="gi gi-calendar sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Calendar</span></a>
                </li>
                <li>
                    <a href="{{ route('admin_article') }}" <?php echo strstr(URL::current(),route('admin_article')) != ''? 'class="active"':''; ?> ><i class="gi gi-sort sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Articles</span></a>
                </li>
                <li>
                    <a href="{{ route('admin_server') }}" <?php echo strstr(URL::current(),route('admin_server')) != ''? 'class="active"':''; ?> ><i class="gi gi-server sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Servers</span></a>
                </li>
                <li>
                    <a href="{{ route('admin_actor') }}" <?php echo strstr(URL::current(),route('admin_actor')) != ''? 'class="active"':''; ?> ><i class="gi gi-star sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Actors</span></a>
                </li>
                <li>
                    <a href="{{ route('admin_tag') }}" <?php echo strstr(URL::current(),route('admin_tag')) != ''? 'class="active"':''; ?> ><i class="gi gi-tags sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Tags</span></a>
                </li>
                <li <?php echo strstr(URL::current(),route('admin_user')) != ''? 'class="active"':''; ?>>
                    <a href="javascript:void(0)" class="sidebar-nav-menu" ><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-user sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Users</span></a>
                    <ul>
                        <li>
                            <a href="{{ route('admin_user') }}" <?php echo URL::current()==route('admin_user')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Staf</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin_customer') }}" <?php echo URL::current()==route('admin_customer')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Customer</span></a>
                        </li>
                    </ul>
                </li>
                <li <?php echo strstr(URL::current(),route('admin_setting')) != ''? 'class="active"':''; ?>>
                    <a href="javascript:void(0)" class="sidebar-nav-menu" ><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-cogwheels sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Settings</span></a>
                    <ul>
                        <li>
                            <a href="{{ route('edit_slide') }}" <?php echo URL::current()==route('edit_slide')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Slide</span></a>
                        </li>
                        <li>
                            <a href="{{ route('edit_seo') }}" <?php echo URL::current()==route('edit_seo')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Seo</span></a>
                        </li>
                        <li>
                            <a href="{{ route('edit_interface') }}" <?php echo URL::current()==route('edit_interface')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Etc Interface</span></a>
                        </li>
                        <li>
                            <a href="{{route('backend_analytic')}}" <?php echo URL::current()==route('backend_analytic')? 'class="active"':''; ?>><span class="sidebar-nav-mini-hide">Script</span></a>
                        </li>
                        <li>
                            <a href="{{ route('social_setting') }}" <?php echo URL::current()==route('social_setting')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Social</span></a>
                        </li>
                        <li>
                            <a href="{{ route('price_setting') }}" <?php echo URL::current()==route('price_setting')? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Thành viên kiếm tiền</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin_reporter') }}" <?php echo strstr(URL::current(),route('admin_reporter')) != ''? 'class="active"':''; ?> ><i class="gi gi-circle_exclamation_mark sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Reporter</span></a>
                </li>
                <li>
                    <a href="{{ route('admin_fixing') }}" <?php echo strstr(URL::current(),route('admin_fixing')) != ''? 'class="active"':''; ?> ><i class="gi gi-circle_exclamation_mark sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Fixing</span></a>
                </li>
                <li>
                    <a href="{{ route('admin_error') }}" <?php echo strstr(URL::current(),route('admin_error')) != ''? 'class="active"':''; ?> ><i class="gi gi-circle_exclamation_mark sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Error</span></a>
                </li>
                <li <?php echo strstr(URL::current(),route('admin_home').'/tool/') != ''? 'class="active"':''; ?>>
                    <a href="javascript:void(0)" class="sidebar-nav-menu" ><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-wrench sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Tools</span></a>
                    <ul>
                        <li>
                            <a href="{{ route('die_episode') }}" <?php echo strstr(URL::current(),route('die_episode')) != ''? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Died Episode</span></a>
                        </li>
                        <li>
                            <a href="{{ route('null_episode') }}" <?php echo strstr(URL::current(),route('null_episode')) != ''? 'class="active"':''; ?> ><span class="sidebar-nav-mini-hide">Null episode</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin_seo_block') }}" <?php echo strstr(URL::current(),route('admin_seo_block')) != ''? 'class="active"':''; ?> ><i class="fa fa-bars sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Seo block</span></a>
                </li>
            </ul>
            <!-- END Sidebar Navigation -->
        </div>
        <!-- END Sidebar Content -->
    </div>

    <!-- END Wrapper for scrolling functionality -->
</div>
<!-- END Main Sidebar -->