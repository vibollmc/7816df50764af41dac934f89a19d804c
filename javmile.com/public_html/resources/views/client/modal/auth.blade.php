<div class="modal fade" id="modal-auth" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul class="nav nav-tabs" data-toggle="tabs">
                    <li class="tab-switch active"><a href="login-tab">Đăng nhập</a></li>
                    <li class="tab-switch"><a href="register-tab">Đăng ký</a></li>
                </ul>
            </div>
            <div class="modal-body container-fluid">
                <div class="tab-pane" id="login-tab">
                    <form id="loginForm" class="form-horizontal {{in_array($errors->first('email'),["Thông tin đăng nhập không chính xác!", "Đăng nhập sai vượt số lần cho phép. Vui lòng thử lại sau 1 phút"])? 'login-error': ''}} {{$errors->first('password')=="The password field is required."? 'login-error': ''}}" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="to_url" value="{{\URL::current()}}" />
                        <div class="form-group">
                            <label class="col-md-3 control-label">E-Mail</label>

                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Mật khẩu</label>

                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox text-center">
                                <label>
                                    <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                                </label>
                            </div>
                        </div>
                        <input type="hidden" id="has-login" value="{{old('login')}}">
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" name="login" value="1" class="btn btn-success">
                                    Đăng nhập
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane hide" id="register-tab">
                    <form id="registerForm" class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">Tên</label>

                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">Username</label>

                            <div class="col-md-9">
                                <input type="text" class="form-control" name="username" placeholder="viết thường, viết liền, không dấu" value="{{ old('username') }}">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">Email</label>

                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">Mật khẩu</label>

                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">Xác nhận mật khẩu</label>

                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" id="has-register" value="{{old('register')}}">
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" name="register" value="1" class="btn btn-success">
                                    Đăng ký
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="text-center">
                    <div class="btn-group">
                        <a class="btn btn-primary" href="{{route('social', 'facebook')}}"><span class="fa fa-facebook"></span> Đăng nhập bằng Facebook</a>
                        <a class="btn btn-danger" href="{{route('social', 'google')}}"><span class="fa fa-google"></span> Đăng nhập bằng Google</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>