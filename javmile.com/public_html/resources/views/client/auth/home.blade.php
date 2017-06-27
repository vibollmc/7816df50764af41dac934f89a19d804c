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
                    <div class="form-group">
                        <strong>Tên đầy đủ:</strong>
                        <span>{!!strlen($result->full_name) > 0? $result->full_name: '<i>Chưa cập nhật</i>'!!}</span>
                    </div>
                    <div class="form-group">
                        <strong>Username: </strong>
                        <span>{!!strlen($result->username) > 0? $result->username: '<i>Chưa cập nhật</i>'!!}</span>
                    </div>
                    <div class="form-group">
                        <strong>Email: </strong>
                        <span>{!!strlen($result->email) > 0? $result->email: '<i>Chưa cập nhật</i>'!!}</span>
                    </div>
                    <div class="form-group">
                        <strong>Số điện thoại: </strong>
                        <span>{!!strlen($result->phone) > 0? $result->phone: '<i>Chưa cập nhật</i>'!!}</span>
                    </div>
                    <div class="form-group">
                        <strong>Loại thành viên: </strong>
                        <span>{!!$result->type->title!!}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop