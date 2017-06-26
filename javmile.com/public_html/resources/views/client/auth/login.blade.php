@extends('client.master')
@section('content')

<section class="site-content site-section site-section-top">
    @include('client.block.breadcrumb')
    <div class="container">
        <div class="col-md-9">
            <div class="row">
                <div class="block-title">
                    <span class="">Đăng nhập</span>
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <p><?php echo Session::has('container_mes')? Session::get('container_mes'): ''; ?></p>
                    <form method="POST" action="{{ route('login_post') }}" class="<?php echo Session::has('container_mes')? 'has-error': ''; ?>">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="email" class="col-sm-3 col-md-3 no-padding-vertical control-label">Email</label>
                            <div class="col-sm-8 col-md-8">
                                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-3 col-md-3 no-padding-vertical control-label">Password</label>
                            <div class="col-sm-8 col-md-8">
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-md-3 control-label"></label>
                            <div class="col-sm-8 col-md-8">
                                <div class="pull-right"><button type="submit" class="btn btn-success">Đăng nhập</button></div>
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="{{ route('register_get') }}">Tạo tài khoản mới</a>
                        <a class="btn btn-primary" href="{{route('social', 'facebook')}}"><span class="fa fa-facebook"></span> Đăng nhập Facebook</a>
                        <a class="btn btn-danger" href="{{route('social', 'google')}}"><span class="fa fa-google"></span> Đăng nhập Google</a>
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