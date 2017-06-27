@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            @include('client.auth.sidebar')
            <div id="auth-container">
                <div class="auth-title">
                {{isset($title)? $title: 'Thông tin cá nhân'}}
                </div>
                <div class="thumbnail-list auth-container">
                    <form method="post" action="{{route('user_update')}}" class="form-horizontal" enctype="multipart/form-data">
                        {!!csrf_field()!!}
                        <div class="form-group">
                            <label class="control-label col-sm-3">Tên đầy đủ: </label>
                            <div class="col-sm-9">
                                <input type="text" name="full_name" class="form-control" value="{{$result->full_name}}">
                                <span class="text-danger">{{ $errors->first('full_name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Điện thoại: </label>
                            <div class="col-sm-9">
                                <input type="text" name="phone" class="form-control" value="{{$result->phone}}">
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Mật khẩu mới: </label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control" value="">
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Nhập lại mật khẩu mới: </label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" class="form-control" value="">
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <h4 class="alert alert-success">THÔNG TIN THANH TOÁN</h4>
                        <?php $bank = json_decode($result->data); ?>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Tên ngân hàng: </label>
                            <div class="col-sm-9">
                                <input type="text" name="data[bank]" class="form-control" value="{{$bank->bank or ''}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Tên chủ tài khoản: </label>
                            <div class="col-sm-9">
                                <input type="text" name="data[bank_name]" class="form-control" value="{{$bank->bank_name or ''}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Số tài khoản: </label>
                            <div class="col-sm-9">
                                <input type="text" name="data[id]" class="form-control" value="{{$bank->id or ''}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Chi nhánh: </label>
                            <div class="col-sm-9">
                                <input type="text" name="data[bank_address]" class="form-control" value="{{$bank->bank_address or ''}}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- Bottom Navigation -->
                        <div class="block-section">
                            <div class="form-group form-actions text-center">
                                <input type="submit" class="btn btn-success active" value="Hoàn tất">
                                <input type="reset" class="btn btn-danger" value="Làm lại">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop