<form class="search-form advance-fill" action="{{route('advance_fill')}}" method="GET">
    <div class="col-md-2">
        <div class="form-group">
            <select name="category_id" class="select-chosen form-control" data-placeholder="Choose a category..">
                <option value="">Chọn mục</option>
                @foreach($categories as $key => $item)
                <option value="{{ $item->id}}" {{(isset($_GET['category_id']) and $_GET['category_id'] == $item->id)? 'selected': ''}}>{!! $item->title !!}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select name="genre" class="select-chosen form-control" data-placeholder="Choose a category..">
                <option value="">Chọn thể loại</option>
                @foreach($genres as $key => $item)
                <option value="{{ $item->id}}" {{(isset($_GET['genre']) and $_GET['genre'] == $item->id)? 'selected': ''}}>{!! $item->title !!}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select name="country" class="select-chosen form-control" data-placeholder="Choose a category..">
                <option value="">Chọn quốc gia</option>
                @foreach($countries as $key => $item)
                <option value="{{ $item->id}}" {{(isset($_GET['country']) and $_GET['country'] == $item->id)? 'selected': ''}}>{!! $item->title !!}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <input name="year" class="form-control" placeholder="Năm phát hành" value="{{isset($_GET['year'])? $_GET['year']: ''}}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select name="sort" class="select-chosen form-control" data-placeholder="Choose a category..">
                <option value="order-desc" >Sắp xếp</option>
                <option value="order-desc" {{(isset($_GET['sort']) and $_GET['sort'] == 'order-desc')? 'selected': ''}} >Mới cập nhật</option>
                <option value="viewed-desc" {{(isset($_GET['sort']) and $_GET['sort'] == 'viewed-desc')? 'selected': ''}} >Xem nhiều nhất</option>
                <option value="date-asc" {{(isset($_GET['sort']) and $_GET['sort'] == 'date-asc')? 'selected': ''}} >Năm phát hành - cũ nhất</option>
                <option value="date-desc" {{(isset($_GET['sort']) and $_GET['sort'] == 'date-desc')? 'selected': ''}} >Năm phát hành - mới nhất</option>
                <option value="title_ascii-asc" {{(isset($_GET['sort']) and $_GET['sort'] == 'title-asc')? 'selected': ''}}>Tiêu đề - A-Z</option>
                <option  value="title_ascii-desc" {{(isset($_GET['sort']) and $_GET['sort'] == 'title-desc')? 'selected': ''}}>Tiêu đề - Z-A</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select name="member" class="select-chosen form-control" data-placeholder="Choose a category..">
                <option>Phim</option>
                <option value="1" {{(isset($_GET['member']) and $_GET['member'] == 1)? 'selected': ''}}>Phim thành viên</option>
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <button type="submit" action="advanceSearchForm" class="btn btn-success">Lọc phim</button>
    </div>
</form>
<div class="clearfix"></div>