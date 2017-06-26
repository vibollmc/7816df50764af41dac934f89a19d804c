<div class="modal fade" id="modal-advance-fill" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Lọc phim</h4>
            </div>
            <form class="search-form" action="{{route('advance_fill')}}" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Danh mục</label>
                        <div class="">
                            @foreach($categories as $key => $item)
                                <label class="checkbox-inline" for="category-{{$key}}">
                                    <input type="checkbox" id="category-{{$key}}" name="category[]" value="{{ $item->id}}" > {!! $item->title !!}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group fill-group">
                        <label class="control-label">Thể loại</label>
                        <div class="fill-group-list">
                            @foreach($genres as $key => $item)
                                <label class="checkbox-inline" for="genre-{{$key}}">
                                    <input type="checkbox" id="genre-{{$key}}" name="genre[]" value="{{ $item->id}}" > {!! $item->title !!}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group fill-group">
                        <label class="control-label">Quốc gia</label>
                        <div class="fill-group-list">
                            @foreach($countries as $key => $item)
                                <label class="checkbox-inline" for="country-{{$key}}">
                                    <input type="checkbox" id="country-{{$key}}" name="country[]" value="{{ $item->id}}"> {!! $item->title !!}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group film-option">
                        <label class="col-md-3 control-label">Năm phát hành</label>
                        <div class="col-md-3">
                            <input type="number" min="1920" max="{{date('Y', time())+2}}" name="year" class="form-control" placeholder="{{date('Y', time())}}" />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" action="advanceSearchForm" class="btn btn-success">Tìm</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->