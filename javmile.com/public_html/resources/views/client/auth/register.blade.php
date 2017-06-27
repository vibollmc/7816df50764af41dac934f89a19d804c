@extends('client.master')
@section('content')

<section class="site-content site-section site-section-top">
    @include('client.block.breadcrumb')
    <div class="container">
        <div class="col-md-9">
            <div class="row">
                <div class="block-title">
                    <span class="">Đăng Ký</span>
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <p><?php echo Session::has('container_mes')? Session::get('container_mes'): ''; ?></p>
                    <form class="form-horizontal" method="POST" action="{{ route('register_post')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="txt_fullname" class="col-sm-4 control-label">User name</label>
                            <div class="col-sm-8 col-md-8">
                                <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}">
                                <span class="help-block">{!! $errors->first('username') !!}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txt_email" class="col-sm-4 control-label">Email</label>
                            <div class="col-sm-8 col-md-8">
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email ..." value="{{ old('email') }}">
                                <span class="help-block">{!! $errors->first('email') !!}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txt_password" class="col-sm-4 control-label">Password</label>
                            <div class="col-sm-8 col-md-8">
                                <input type="password" name="password" class="form-control" id="password">
                                <span class="help-block">{!! $errors->first('password') !!}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txt_password2" class="col-sm-4 control-label">Confirm Password</label>
                            <div class="col-sm-8 col-md-8">
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                <span class="help-block">{!! $errors->first('repassword') !!}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-success">Đăng ký</button>
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="{{ route('register_get') }}">Tạo tài khoản mới</a>
                        <a class="btn btn-primary" href="{{route('social', 'facebook')}}"><span class="fa fa-facebook"></span> Đăng nhập bằng Facebook</a>
                        <a class="btn btn-danger" href="{{route('social', 'google')}}"><span class="fa fa-google"></span> Đăng nhập bằng Google</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @include('client.block.facebook')
        </div>
        <!-- END Portfolio Items -->
    </div>
</section>
@stop