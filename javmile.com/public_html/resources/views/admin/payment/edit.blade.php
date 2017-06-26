@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Edit Payment</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('update_payment', $result->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Ngày yêu cầu</label>
                        <div class="col-md-9">
                            {{date('d/m/Y H:i:s', $result->time_pending)}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Số lượt xem</label>
                        <div class="col-md-9">
                            {{number_format($result->viewed)}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Giá 1000 view</label>
                        <div class="col-md-9">
                            {{$result->price}} đ
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Số tiền</label>
                        <div class="col-md-9">
                            {{number_format($result->viewed*$result->price/1000)}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-select">Trạng thái</label>
                        <div class="col-md-9">
                            <select name="status" class="form-control" size="1">
                                <option value="pending">Đang chờ</option>
                                <option value="complete" {{$result->status == 'complete'? 'selected' : ''}}>Đã thanh toán</option>
                            </select>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                <!-- Basic Form Elements Content -->
                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Username: </label>
                    <div class="col-md-9">
                        {{$result->customer->username}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Email: </label>
                    <div class="col-md-9">
                        {{$result->customer->email}}
                    </div>
                </div>
                <?php $data = json_decode($result->customer->data); ?>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Ngân hàng: </label>
                    <div class="col-md-9">
                        {{$data->bank or 'Chưa cập nhật'}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Tên chủ TK: </label>
                    <div class="col-md-9">
                        {{$data->bank_name or 'Chưa cập nhật'}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Số TK: </label>
                    <div class="col-md-9">
                        {{$data->id or 'Chưa cập nhật'}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Chi nhánh: </label>
                    <div class="col-md-9">
                        {{$data->bank_address or 'Chưa cập nhật'}}
                    </div>
                </div>
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="form-group form-actions">
                <div class="col-md-9 col-md-offset-4">
                    <input type="submit" name="btn_save_exit" class="btn btn-primary" value="Save & Exit">
                    <input type="reset" class="btn btn-danger" value="Reset">
                </div>
            </div>
    </form>
</div>
@stop