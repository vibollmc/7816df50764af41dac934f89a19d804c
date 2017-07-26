<header class="navbar navbar-default">
    <div class="text-center desktop-menu">
        <div class="nav-top">
            <div class="menu-item">
                <a href="{{route('home')}}">
                    <!--<div class="menu-icon"><i class="gi gi-home"></i></div>-->
                    <img src="{{asset('themes/client/img/logo-javmile.png')}}" alt="javmile.com" style="margin-top:10px; margin-left:10px;">
                </a>
            </div>
            @foreach($categories as $key => $value)
            <div class="menu-item">
                <a href="{{route('category', $value->slug)}}">
                    <div class="menu-icon"><i class="{{$value->id == 1? 'fa fa': 'hi hi'}}-film"></i></div>
                    <div>{{$value->title}}</div>
                </a>
            </div>
            @endforeach
            <div class="menu-item">
                <a class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        <div class="menu-icon"><i class="hi hi-th-large"></i></div>
                        <div>Categories</div>
                    </a>
                    <ul class="dropdown-menu multi-column columns-3">
                        <div class="row">
                        @foreach ($menu_genres->chunk(ceil(count($menu_genres)/3)) as $chunk)
                            <div class="col-sm-4">
                                <ul class="multi-column-dropdown">
                                @foreach ($chunk as $item)
                                    <li><a href="{{route('genre', $item->slug)}}">{{$item->title}}</a></li>
                                @endforeach
                                </ul>
                            </div>
                        @endforeach
                            <div class="col-sm-4">
                                <a class="menu-more" href="{{route('genres')}}">>> view more</a>
                            </div>
                        </div>
                    </ul>
                </a>
            </div>

            <div class="menu-item pull-right" style="width: 300px;margin-top: 20px;">
                <form action="{{ route('search') }}" method="get">
                    <div class="input-group stylish-input-group">
                        <input name="key" type="text" class="form-control" placeholder="Search" value="<?php echo isset($_GET['key'])? $_GET['key']: ''; ?>">
                        <span class="input-group-addon">
                            <button type="submit">
                                <span class="fa fa-search"></span>
                            </button>  
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="mobile-menu">
        <ul class="nav navbar-nav-custom mobile-menu">
            <li>
                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                    <i class="fa fa-bars fa-fw"></i>
                </a>
            </li>
        </ul>
        
        <div class="menu-item pull-right" style="width:200px; margin-top:5px;">
            <form action="{{ route('search') }}" method="get">
                <div class="input-group stylish-input-group">
                    <input name="key" type="text" class="form-control" placeholder="Search" value="<?php echo isset($_GET['key'])? $_GET['key']: ''; ?>">
                    <span class="input-group-addon">
                        <button type="submit">
                            <span class="fa fa-search"></span>
                        </button>  
                    </span>
                </div>
            </form>
        </div>
    </div>
</header>
<!-- END Header -->