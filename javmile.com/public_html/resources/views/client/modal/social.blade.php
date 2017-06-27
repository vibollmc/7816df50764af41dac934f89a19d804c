<div class="modal fade" id="modal-social" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Like và tham gia fanpage ủng hộ hayphimtv</strong>
            </div>
            <div class="modal-body container-fluid">
                <div class="tab-pane">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="fb-page social-modal-body" data-href="{{env('FACEBOOK_URL')}}" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                            <div class="fb-xfbml-parse-ignore">
                                <blockquote cite="{{env('FACEBOOK_URL')}}">
                                    <a href="{{env('FACEBOOK_URL')}}">FACEBOOK FANPAGE</a>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \Session::set('show_alert', true); ?>