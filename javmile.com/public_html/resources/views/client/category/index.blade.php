@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>{{$result['title']}}</strong>
            </h4>
			<div class="clearfix"></div>
			<center class="hidden-sm hidden-md hidden-lg">
				
				<!-- 315x300
				The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
				In case you use the code multiple times then be sure that every id is unique!
				-->
				<div id="ea_5116029_node">&nbsp;</div>

				<script type="text/javascript" language="javascript" charset="utf-8">
				/*
				code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
				if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
				*/ 

				if (typeof eaCtrl =="undefined"){ 
					var eaCtrlRecs=[];
					var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
					var js = document.createElement('script');
					js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=5116029");
					document.head.appendChild(js);
				}
				/*
				End load eactrl 

				Command eaCtrl to load ads
				*/
				eaCtrl.add({"display":"ea_5116029_node","sid":5116029,"plugin":"banner","traffic_type":"all","subid":""});
				</script>

			</center>
			<center class="hidden-xs hidden-md hidden-lg">
				
				<!-- 600x80
				The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
				In case you use the code multiple times then be sure that every id is unique!
				-->
				<div id="ea_3558995_node">&nbsp;</div>

				<script type="text/javascript" language="javascript" charset="utf-8">
				/*
				code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
				if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
				*/ 

				if (typeof eaCtrl =="undefined"){ 
					var eaCtrlRecs=[];
					var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
					var js = document.createElement('script');
					js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=3558995");
					document.head.appendChild(js);
				}
				/*
				End load eactrl 

				Command eaCtrl to load ads
				*/
				eaCtrl.add({"display":"ea_3558995_node","sid":3558995,"plugin":"banner","traffic_type":"all","subid":""});
				</script>

			</center>
			<center class="hidden-xs hidden-sm hidden-lg">
				
				<!-- 728x180
				The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
				In case you use the code multiple times then be sure that every id is unique!
				-->
				<div id="ea_5116026_node">&nbsp;</div>

				<script type="text/javascript" language="javascript" charset="utf-8">
				/*
				code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
				if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
				*/ 

				if (typeof eaCtrl =="undefined"){ 
					var eaCtrlRecs=[];
					var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
					var js = document.createElement('script');
					js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=5116026");
					document.head.appendChild(js);
				}
				/*
				End load eactrl 

				Command eaCtrl to load ads
				*/
				eaCtrl.add({"display":"ea_5116026_node","sid":5116026,"plugin":"banner","traffic_type":"all","subid":""});
				</script>

			</center>
			<center class="hidden-xs hidden-sm hidden-md">
				
				<!-- 960x250
				The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
				In case you use the code multiple times then be sure that every id is unique!
				-->
				<div id="ea_5116027_node">&nbsp;</div>

				<script type="text/javascript" language="javascript" charset="utf-8">
				/*
				code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
				if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
				*/ 

				if (typeof eaCtrl =="undefined"){ 
					var eaCtrlRecs=[];
					var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
					var js = document.createElement('script');
					js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=5116027");
					document.head.appendChild(js);
				}
				/*
				End load eactrl 

				Command eaCtrl to load ads
				*/
				eaCtrl.add({"display":"ea_5116027_node","sid":5116027,"plugin":"banner","traffic_type":"all","subid":""});
				</script>

			</center>
			<div class="clearfix"></div>
            <div class="thumbnail-list">
                <div class="clearfix"></div>
                @foreach($result['result'] as $key => $item)
                    @include('client.block.film-item')
                @endforeach
                <div class="clearfix"></div>
                <div class="pull-right">
                    <div class="dataTables_paginate paging_bootstrap"> {!! $result['pagination'] !!} </div>
                </div>
                <div class="clearfix"></div>
            </div>
			<div class="clearfix"></div>
			<center>	
				<div class="row">
					<div class="col-md-4">
						
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

					</div>
					<div class="col-md-4">
						<center>
							
							<!--
							The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
							In case you use the code multiple times then be sure that every id is unique!
							-->
							<div id="ea_5112241_node">&nbsp;</div>

							<script type="text/javascript" language="javascript" charset="utf-8">
							/*
							code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
							if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
							*/ 

							if (typeof eaCtrl =="undefined"){ 
								var eaCtrlRecs=[];
								var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
								var js = document.createElement('script');
								js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=5112241");
								document.head.appendChild(js);
							}
							/*
							End load eactrl 

							Command eaCtrl to load ads
							*/
							eaCtrl.add({"display":"ea_5112241_node","sid":5112241,"plugin":"banner","traffic_type":"all","subid":""});
							</script>

						</center>
					</div>
					<div class="col-md-4">
						<center>
						
							<!--
							The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
							In case you use the code multiple times then be sure that every id is unique!
							-->
							<div id="ea_3553950_node">&nbsp;</div>

							<script type="text/javascript" language="javascript" charset="utf-8">
							/*
							code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
							if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
							*/ 

							if (typeof eaCtrl =="undefined"){ 
								var eaCtrlRecs=[];
								var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
								var js = document.createElement('script');
								js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=3553950");
								document.head.appendChild(js);
							}
							/*
							End load eactrl 

							Command eaCtrl to load ads
							*/
							eaCtrl.add({"display":"ea_3553950_node","sid":3553950,"plugin":"banner","traffic_type":"all","subid":""});
							</script>

						</center>
					</div>
				</div>
			</center>
			<div class="clearfix"></div>
        </div>
        <div class="col-md-3 hidden-sm hidden-sm">
            @include('client.block.popular')
            @include('client.block.action')
            @include('client.block.actor')
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop