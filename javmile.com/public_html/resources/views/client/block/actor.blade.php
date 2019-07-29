<h4 class="sub-header">
    <strong>Pornstars</strong>
</h4>
<div class="thumbnail-list">
	<center>
		
		<!-- stylesheet for native ads, you can change this however you want to fit into your site -->
		<style type="text/css">
		#ea_5116028_node #ntvRow {
			background-color: #eee;
		}
		#ea_5116028_node #ntvCell {
			padding: 5px;
			text-align: left;
		}
		#ea_5116028_node #ntvContent{
			width:300px;
			height:350px;
			overflow:hidden;
			border: 1px solid #000000;
		}
		#ea_5116028_node  #ntvTitle {
			clear:both;
			padding: 5px;
			float:left;
			font-size: 15px;
			height: 20px;
			overflow-y: hidden;
		}
		#ea_5116028_node #ntvDescr {
			clear:both;
			padding: 5px;
			float:left;
			font-size: 20px;
			max-height: 80px;
			overflow-y: hidden;
		}
		#ea_5116028_node #ntvImage img{
			width:300px;
			height:232px;;
		}
		#ea_5116028_node #ntvDisplayUrl {
			clear:both;
			padding:5px;
			float:left;
			font-size: 15px;
			height: 26px;
			overflow-y: hidden;
		}
		</style>

		<!-- Element used to insert all data -->

		<div id="ea_5116028_node">&nbsp;</div>

		<!--The code below contains html used to display the native ads including replacements, you can change the html however you want to fit into your site-->

		<script type="text/javascript" language="javascript" charset="utf-8">
		/*
		code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
		if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
		*/ 

		if (typeof eaCtrl =="undefined"){ 
			var eaCtrlRecs=[];
			var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
			var js = document.createElement('script');
			js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=5116028");
			document.head.appendChild(js);
		}
		/*
		End load eactrl 

		Command eaCtrl to load ads
		*/
		eaCtrl.add({"plugin":"native","subid":"","sid":5116028,"display":"ea_5116028_node","traffictype":"all",
		settings:{
			"rows":1,
			"cols":1
		},
		"tpl_body":'<table cellpadding="0" cellspacing="0" id="ntbTbl"><tbody>{body}</tbody></table>',
		"tpl_row":'<tr valign="top" id="ntvRow">{items}</tr>',
		"tpl_item":'<td id="ntvCell"><div id="ntvContent"><a id="{href_itemid}" href="#"><div id="ntvTitle">{title}</div><div id="ntvImage">{image}</div><div id="ntvDescr">{description}</div><div id="ntvDisplayUrl">{displayurl}</div></a></div></td>'
		});
		</script>

	</center>
	<div class="clearfix"></div>
	<br />
    @foreach($actor as $key => $item)
        @if(!is_null($item->image_server))
            <?php
                $image_data = json_decode($item->image_server->data);
                $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                $tooltip_id = uniqid();
             ?>
            <div class="col-md-6 thumbnail-item">
                <div class="thumbnail film-tooltip" style="width: 100%;" data-tooltip-content="#{{$tooltip_id}}">
                    <a href="{{route('star_show', $item->slug)}}">
                        <img src="{{$image_prefix.$item->thumb_name}}" alt="{{$item->title}}" class="cover img-reponsive">
                    </a>
                    <div class="caption">
                        <div class="title">
                            <h2><a href="{{route('star_show', $item->slug)}}">
                                <?php
                                    if(strlen($item->title) > 72 and strpos($item->title, ' ', 72) > 0){
                                        $title =  substr($item->title, 0, strpos($item->title, ' ', 72)) . '..';
                                    }else{
                                        $title =  $item->title;
                                    }
                                    echo $title;
                                ?>
                            </a></h2>
                        </div>
                    </div>
                    <div class="overlay">
                        <a href="{{route('star_show', $item->slug)}}" title="">
                            <img src="{{asset('themes/client/img/play.png')}}">
                        </a>
                    </div>
                </div>
            </div>
            @if(($key+1)%2 == 0)
                <div class="clearfix"></div>
            @endif
        @endif
    @endforeach
</div>