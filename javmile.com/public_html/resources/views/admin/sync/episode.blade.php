@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Danh sách tập phim <strong>{{$film->title_search.' - '.$film->title_en}}</strong></h2>
    </div>
    <!-- END Table Styles Title -->
    <!-- Table Styles Content -->
    <div class="table-responsive">
        <table class="table table-vcenter table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 80px;" class="text-center"><input type="checkbox"></th>
                    <th style="width: 120px;">Ảnh</th>
                    <th>Thông tin</th>
                    <th class="text-center">Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @if($current->count() > 0)
                    @foreach($current as $key => $item)
                    <?php
                        if (isset($_GET['filter'])) {
                            $value = $item->film;
                        }else{
                            $value = $item;
                        }
                        $image_data = json_decode($value->image_server->data);
                        $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                    ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" id="id[{{$key}}]" name="id[{{$key}}]" value="{{$value->id}}"></td>
                        <td><img src="{{ $image_prefix.$value->thumb_name }}" class="img-responsive"></td>
                        <td>
                            <a href="{{route('film_detail', ['category' => $value->category->slug, 'slug' => $value->slug])}}">{{$value->title.' - '. $value->title_en}}</a><br/>
                            Tập mới nhất: {{$value->category_id == 2? $value->exist_episodes.'/'.$value->episodes: $value->exist_episodes}}<br/>
                            Trạng thái: {{$value->online == 1? 'Online': 'Ẩn'}}<br/>
                            Người đăng: {{is_null($value->user)? 'admin': $value->username}}<br/>
                            Lượt xem: {{$value->viewed}}
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a href="{{route('old_film_sync', ['id' => $value->id, 'old_id' => $film->id])}}" class="btn btn-danger">Sync data</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="btn-group btn-group-xs pull-right">
        <a href="{{route('admin_old_change', $film->id)}}" data-toggle="tooltip" title="Change" class="btn btn-danger">Xóa phim</a>
    </div>
    <div class="table-responsive">
    @if(count($result)> 0)
        <?php
        $find    = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
        $replace = ['.1', '.2', '.3', '.4', '.5', '.6', '.7', '.8', '.9', '.10', '.11', '.12', '.13', '.14', '.15', '.16', '.17', '.18', '.19', '.20', '.21', '.22', '.23', '.24'];
        ?>
        <div class="form-group">
            <label class="control-label">Vietsub full</label>
            @foreach($result->whereLoose('present', 3) as $key => $item)
                <?php
                    $name = (int)$item->name;
                    $ep_full[$name] = $item->url;
                ?>
            @endforeach
            @if(isset($ep_full))
                <?php ksort($ep_full); ?>
                <div class="clearfix">
                        @foreach($ep_full as $key => $item)
                            {{$key.'|'.$item}}<br/>
                        @endforeach
                </div>
            @endif
        </div>
        <div class="form-group">
            <label class="control-label">Vietsub part</label>
                @foreach($result->whereLoose('present', 2) as $key => $item)
                    <?php
                        $title = str_slug(strtolower($item->name));
                        $title = str_replace('-', '', $title);
                        $title = str_replace($find, $replace, $title);
                        $name = explode('.', $title);
                        if (isset($name[1])) {
                            # code...
                            $ep_part[$name[0].'.'.$name[1]] = $item->url;
                        }else{
                            $ep_part[$name[0]] = $item->url;
                        }
                    ?>
                @endforeach
            @if(isset($ep_part))
            <?php ksort($ep_part); ?>
            <div class="clearfix">
                    @foreach($ep_part as $key => $item)
                        {{$key.'|'.$item}}<br/>
                    @endforeach
            </div>
            @endif
        </div>
        <div class="form-group">
            <label class="control-label">Thuyết minh</label>
            @foreach($result->whereLoose('present', 1) as $key => $item)
                <?php
                    $name = (int)$item->name;
                    $ep_tm[$name] = $item->url;
                ?>
            @endforeach
            @if(isset($ep_tm))
            <?php ksort($ep_tm); ?>
            <div class="clearfix">
                    @foreach($ep_tm as $key => $item)
                        {{$key.'|'.$item}}<br/>
                    @endforeach
            </div>
            @endif
        </div>
        <div class="form-group">
            <label class="control-label">Khác</label>
            <div class="clearfix">
                    @foreach($result->whereLoose('present', 0) as $key => $item)
                        {{$item->name.'|'.$item->url}}<br/>
                    @endforeach
            </div>
        </div>
    @endif
    </div>
        <!-- END Responsive Full Content -->
</div>
<!-- Load and execute javascript code used only in this page -->
<script src="{{asset('themes/admin/js/pages/tablesGeneral.js')}}"></script>
<script>
$(function(){
    TablesGeneral.init();
});
$('#searchSubmit').on('click', function(){
    $('#search-film').submit();
});
</script>
@stop