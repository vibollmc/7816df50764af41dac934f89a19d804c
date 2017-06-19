<div id="sidebar">
    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Brand -->
            <a href="{{route('home')}}" class="sidebar-brand">
                <img src="{{asset('themes/client/img/logo-javmile.png')}}" alt="javmile.com">
            </a>
            <!-- END Brand -->

            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                @foreach($categories as $key => $value)
                    <li>
                        <a href="{{route('category', $value->slug)}}" class="{{route('category', $value->slug) == \URL::current()? 'active': ''}}">
                            <i class="hi hi-film sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">{{$value->title}}</span>
                        </a>
                    </li>
                @endforeach
                <li>
                    <a href="#" class="sidebar-nav-menu">
                        <i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                        <i class="gi gi-albums sidebar-nav-icon"></i>
                        <span class="sidebar-nav-mini-hide">Category</span>
                    </a>
                    <ul>
                        @foreach ($menu_genres as $item)
                        <li><a href="{{route('genre', $item->slug)}}" class="{{route('genre', $item->slug) == \URL::current()? 'active': ''}}">{{$item->title}}</a></li>
                        @endforeach
                        <li><a href="{{route('genres')}}">>> view more</a></li>
                    </ul>
                </li>
                <li>
                    <form action="{{ route('search') }}" method="get" class="navbar-form-custom-mobile">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default btn-search"><span class="fa fa-search"></span></button>
                            </span>
                            <input type="text" id="top-search" name="key" class="form-control" placeholder="Search..." value="<?php echo isset($_GET['key'])? $_GET['key']: ''; ?>">
                        </div>
                    </form>
                    <!-- END Search Form -->
                </li>
            </ul>
            <!-- END Sidebar Navigation -->
        </div>
        <!-- END Sidebar Content -->
    </div>
    <!-- END Wrapper for scrolling functionality -->
</div>