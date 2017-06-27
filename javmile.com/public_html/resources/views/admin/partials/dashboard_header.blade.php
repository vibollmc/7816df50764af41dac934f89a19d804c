<div class="content-header content-header-media">
    <div class="header-section">
        <div class="row">
            <!-- Main Title (hidden on small devices for the statistics to fit) -->
            <div class="col-md-4 col-lg-6 hidden-xs hidden-sm">
                <h1>Wellcome <strong>Admin</strong><br><small></small></h1>
            </div>
            <!-- END Main Title -->

            <!-- Top Stats -->
            <div class="col-md-8 col-lg-6">
                <div class="row text-center">
                    <div class="col-xs-4 col-sm-3">
                        <h2 class="animation-hatch">
                            <strong>{{ $users->count() }}</strong><br>
                            <small>Users</small>
                        </h2>
                    </div>
                </div>
            </div>
            <!-- END Top Stats -->
        </div>
    </div>
    <img src="{{asset('themes/admin/img/placeholders/headers/profile_header.jpg')}}" alt="header image" class="animation-pulseSlow">
    </div>