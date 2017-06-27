@extends('admin.master')

@section('content')
<script src="{{asset('themes/admin/js/fileuploader.js')}}"></script>
<!-- Table Styles Header -->
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- END Table Styles Header -->

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Payment</h2>
    </div>
    <!-- END Table Styles Title -->
    <div class="btn-group btn-group-sm">
        <a class="btn btn-primary {{(isset($_GET['status']) and $_GET['status'] == 'pending')? 'active' :''}}" href="{{route('admin_payment', ['status' => 'pending'])}}">Đang chờ</a>
        <a class="btn btn-primary {{(isset($_GET['status']) and $_GET['status'] == 'complete')? 'active' :''}}" href="{{route('admin_payment', ['status' => 'complete'])}}">Đã chuyển</a>
        <a class="btn btn-primary {{!isset($_GET['status'])? 'active' :''}}" href="{{route('admin_payment')}}">Tất cả</a>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter table-striped table-hover">
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Thành viên</th>
                    <th>Số lượt xem</th>
                    <th>Số tiền/1000 lượt</th>
                    <th class="text-center">Tổng(VND)</th>
                    <th class="text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result as $key => $item)
                <tr>
                    <td>
                        {{is_null($item->time_pay)? date('d/m/Y H:i:s', $item->time_pending): date('d/m/Y H:i:s', $item->time_pay)}}
                    </td>
                    <td><a href="{{route('admin_payment', ['customer_id' => $item->customer_id])}}">{{$item->customer->username}}</a></td>
                    <td>{{number_format($item->viewed)}}</td>
                    <td>{{$item->price}}</td>
                    <td class="text-center">
                        {{number_format($item->viewed*$item->price/1000)}}
                    </td>
                    <td class="text-center">
                        @if($item->status == 'pending')
                            <a href="{{route('edit_payment', $item->id)}}" class="label label-warning">Đang chờ</a>
                        @else
                            <a href="{{route('edit_payment', $item->id)}}" class="label label-success">Đã thanh toán</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop