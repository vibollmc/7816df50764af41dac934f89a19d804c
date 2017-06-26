@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2><span class="text-danger">Phim: <a href="{{route('film_show', ['category' => $result->category->slug, 'slug' => $result->slug])}}" target="blank">{{$result->title}}</a></span></h2>
    </div>
    <p class="text-right">
        <a id="{{$result->id}}" href="javascript:void(0)" class="btn btn-success btn-edit">{{is_null($result->fixing)? 'Xác nhận': 'Xong'}}</a>
    </p>
    <!-- END Table Styles Title -->
    <label>Thống kê lỗi:</label>
    <?php foreach ($errors as $value): ?>
        @if($result->reports->where('error_id', $value->id)->count()> 0)
        <h4><a href="javascript:void(0)" class="label label-primary">{{$value->title}}</a> ({{$result->reports->where('error_id', $value->id)->count()}}) <a href="{{route('report_spam', ['able'=>$result->id, 'error'=>$value->id])}}" class="label label-warning">Spam</a></h4>
        @foreach($result->reports->where('error_id', $value->id) as $item)
        <ul>
            <li><a class="label label-success">{{$item->user->username}}</a></li>
            <ul>
                <li>{!!$item->content!!}</li>
            </ul>
        </ul>
        @endforeach
        @endif
    <?php endforeach ?>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script>
    $(document).ready(function(){
        $('body').on('click', '.btn-edit', function(){
            var i = $(this);
            $.ajax({
                type: 'GET',
                url: '{{route('change_report')}}',
                data: {_token: '{{ csrf_token() }}', id: i.attr('id')},
                success: function(res){
                    window.location = '{{route('admin_fixing')}}';
                },
                error: function(err){
                    console.log(err);
                },
            });
        });
    });
</script>
@stop