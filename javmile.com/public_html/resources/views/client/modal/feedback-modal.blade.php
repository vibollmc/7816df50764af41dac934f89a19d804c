<div class="modal fade" id="modal-feed-back" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Phản ánh</h4>
            </div>
            <form id="report-form" action="{{route('report')}}" method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <div class="row">
                        @if(\Session::has('user') and Session::get('user')->baned_to > time())
                            <p class="alert alert-warning">Ban quản trị đã xem xét những hành động của bạn gần đây mang tính chất spams, nên phản hồi của bạn không thành công. Mọi thắc mắc vui lòng liên hệ ban quản trị. Thân!</p>
                        @else
                            @foreach($report_errors as $key => $item)
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label><input name="id" type="radio" value="{{$item->id}}">{{$item->title}}</label>
                                </div>
                            </div>
                            @endforeach
                            <input type="hidden" name="able_id" value="{{$result->id}}">
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <div class="form-group feed-back-text">
                                    <label for="comment">Mô tả lỗi chi tiết</label>
                                    <textarea name="content" class="form-control" rows="5" id="comment" placeholder="Mô tả chi tiết về lỗi"></textarea>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="text-danger hide report-alert">Vui lòng điền đầy đủ thông tin</span>
                    <button type="submit" action="report-form" class="btn btn-report-room">Gửi</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->