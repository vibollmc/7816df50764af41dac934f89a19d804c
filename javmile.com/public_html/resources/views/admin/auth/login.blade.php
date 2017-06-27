@extends('admin.auth.master')

@section('content')
<!-- Login Container -->
<div id="login-container" class="animation-fadeIn">
    <!-- Login Title -->
    <div class="login-title text-center">
        <h1><i class="gi gi-flash"></i> <strong>Admin</strong><br><small>Please <strong>Login</strong></small></h1>
    </div>
    <!-- END Login Title -->

    <!-- Login Block -->
    <div class="block push-bit">
        <!-- Login Form -->
        <form action="{{ route('admin_postlogin') }}" method="post" id="form-login" class="form-horizontal form-bordered form-control-borderless">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <?php echo \Session::has('message')? \Session::get('message'):'' ?>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                        <input type="text" id="login-email" name="username" value="{{ old('username') }}" class="form-control input-lg" placeholder="Email">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                        <input type="password" id="login-password" name="password" class="form-control input-lg" placeholder="Password">
                    </div>
                </div>
            </div>
            <div class="form-group form-actions">
                <div class="col-xs-4">
                    <label class="switch switch-primary" data-toggle="tooltip" title="Remember Me?">
                        <input type="checkbox" id="login-remember-me" name="remember" checked>
                        <span></span>
                    </label>
                </div>
                <div class="col-xs-8 text-right">
                    <button type="submit" name="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Login to Dashboard</button>
                </div>
            </div>
        </form>
        <!-- END Login Form -->

        <!-- Reminder Form -->
        <form action="login.php#reminder" method="post" id="form-reminder" class="form-horizontal form-bordered form-control-borderless display-none">
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                        <input type="text" id="reminder-email" name="reminder-email" class="form-control input-lg" placeholder="Email">
                    </div>
                </div>
            </div>
            <div class="form-group form-actions">
                <div class="col-xs-12 text-right">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Reset Password</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12 text-center">
                    <small>Did you remember your password?</small> <a href="javascript:void(0)" id="link-reminder"><small>Login</small></a>
                </div>
            </div>
        </form>
        <!-- END Reminder Form -->
    </div>
    <!-- END Login Block -->

    <!-- Footer -->
    <footer class="text-muted text-center">
        <small><span id="year-copy"></span> &copy; <a href="" target="_blank"></a></small>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Login Container -->
@stop