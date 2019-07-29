<h4 class="sub-header">
    <a href="{{route('category', 'phim-sap-chieu')}}">
        <strong>New</strong>
        <i class="gi gi-play"></i>
    </a>
</h4>
<div class="thumbnail-list cover-block">
	<center>
		
		<!-- 250x250
		The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
		In case you use the code multiple times then be sure that every id is unique!
		-->
		<div id="ea_5116030_node">&nbsp;</div>

		<script type="text/javascript" language="javascript" charset="utf-8">
		/*
		code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
		if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
		*/ 

		if (typeof eaCtrl =="undefined"){ 
			var eaCtrlRecs=[];
			var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
			var js = document.createElement('script');
			js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=5116030");
			document.head.appendChild(js);
		}
		/*
		End load eactrl 

		Command eaCtrl to load ads
		*/
		eaCtrl.add({"display":"ea_5116030_node","sid":5116030,"plugin":"banner","traffic_type":"all","subid":""});
		</script>

	</center>
	<div class="clearfix"></div>
	<br />
    @foreach($trailer_block as $key => $item)
        @include('client.block.film-item-cover')
    @endforeach
</div>
<div class="clearfix"></div>