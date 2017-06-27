@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            @include('client.auth.sidebar')
            <div id="auth-container">
                <div class="auth-title">Thống kê</div>
                    <div>
                    {!!\Session::has('message')? \Session::get('message'): ''!!}
                    <?php $user = Session::get('user'); $bank = json_decode($user->data);?>
                    @if((isset($bank->id) and strlen($bank->id) > 0))
                    @else
                    <div class="alert alert-warning">
                        Bạn chưa cập nhật thông tin thanh toán trong thông tin cá nhân, vui lòng cập nhật để có thể được thanh toán
                    </div>
                    @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Số lượt xem</th>
                                    <th>Số tiền/1000 lượt</th>
                                    <th class="text-center">Tổng(VND)</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($payments->count() > 0)
                                    @foreach($payments as $key => $item)
                                    <tr>
                                        <td>
                                            {{is_null($item->time_pay)? date('d/m/Y H:i:s', $item->time_pending): date('d/m/Y H:i:s', $item->time_pay)}}
                                        </td>
                                        <td>{{number_format($item->viewed)}}</td>
                                        <td>{{$item->price}}</td>
                                        <td class="text-center">
                                            {{!is_null($price->data)? number_format($price->data*$item->viewed/1000): number_format($item->viewed)}}
                                        </td>
                                        <td class="text-center">
                                            @if(is_null($item->time_pay))
                                                <div class="label label-warning">Đang thanh toán</div>
                                            @else
                                                <div class="label label-success">Đã thanh toán</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            {{date('d/m/Y H:i:s', time())}}
                                        </td>
                                        <?php
                                            $complete = $payments->pluck('viewed')->toArray(); $pending_view = array_sum($viewed) - array_sum($complete);
                                            $monney = !is_null($price->data)? $price->data*$pending_view/1000: $pending_view;
                                        ?>
                                        <td>{{number_format($pending_view)}}</td>
                                        <td>{{!is_null($price->data)? $price->data: 1000}}</td>
                                        <td class="text-center">
                                            {{number_format($monney)}}
                                        </td>
                                        <td class="text-center">
                                            @if($monney >= 200000)
                                            <a href="{{route('checkout')}}" class="btn btn-info">Rút tiền</a>
                                            @else
                                            chỉ thanh toán > 200,000 đ
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            {{date('d/m/Y H:i:s', time())}}
                                        </td>
                                        <?php
                                            $pending_view = array_sum($viewed);
                                            $monney = !is_null($price->data)? $price->data*$pending_view/1000: $pending_view;
                                        ?>
                                        <td>{{number_format($pending_view)}}</td>
                                        <td>{{!is_null($price->data)? $price->data: 1000}}</td>
                                        <td class="text-center">
                                            {{number_format($monney)}}
                                        </td>
                                        <td class="text-center">
                                            @if($monney >= 200000)
                                            <a href="{{route('checkout')}}" class="btn btn-info">Rút tiền</a>
                                            @else
                                            chỉ thanh toán > 200,000 đ
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop
