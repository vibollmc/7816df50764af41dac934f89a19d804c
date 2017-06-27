@extends('admin.master')

@section('content')
<ul class="breadcrumb breadcrumb-top">
</ul>
<div class="row">
    <!-- Table Styles Block -->
    <div class="block">
        <!-- Table Styles Title -->
        <div class="block-title">
            <h2>mã Script Google Analytic hoặc quảng cáo </h2>
        </div>
        <!-- END Table Styles Title -->
        <p>
            @if (Session::has('message'))
            {!! Session::get('message') !!}
            @endif
        </p>
        <div class="table-options clearfix"></div>
        <form action="{{ route('update_analytic') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label class="col-md-3 control-label">Analytic</label>
                <div class="col-md-9">
                    <textarea name="data" style="min-height: 200px;" class="form-control">{{$result->data or ''}}</textarea>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group form-actions">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" value="Save">
                    <input type="reset" class="btn btn-danger" value="Reset">
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
</script>
@stop