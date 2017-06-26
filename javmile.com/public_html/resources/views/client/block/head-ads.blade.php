@if(\Session::has('user_agent') and \Session::get('user_agent')['moblie'])
<div id="abdMastheadMb" style="margin:0 auto; width:100%;"></div>
@else
<div id="abdMasthead" style="margin:0 auto; width:970px"></div>
@endif