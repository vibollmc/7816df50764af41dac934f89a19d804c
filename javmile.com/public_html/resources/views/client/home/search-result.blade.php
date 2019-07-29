@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>Search results</strong>
                <div class="pull-right sort">
                    <small>
                        <ul class="nav nav-tabs">
                            <li class="">
                                <?php
                                    $name = 'sort';
                                    $sort = isset($_GET['sort'])? $_GET['sort']: NULL;
                                 ?>
                                <form id="form-sort" action="{{\URL::current()}}" method="get" class="form-inline" accept-charset="utf-8">
                                    <span>Order by: </span>
                                    <select name="{{$name}}" class="" size="1">
                                        @if($name=='sort')
                                        <option class="enter-sort" value="order-desc" <?php echo $sort == 'order-desc'? 'selected': ''; ?>>Public date</option>
                                        <option class="enter-sort" value="viewed-desc" <?php echo $sort == 'viewed-desc'? 'selected': ''; ?>>Most view</option>
                                        <option class="enter-sort" value="title_ascii-asc" <?php echo $sort == 'title-asc'? 'selected': ''; ?>>Title - A-Z</option>
                                        <option class="enter-sort" value="title_ascii-desc" <?php echo $sort == 'title-desc'? 'selected': ''; ?>>Title - Z-A</option>
                                        @endif
                                    </select>
                                    <?php unset($uri['page']); ?>
                                    @foreach($uri as $k => $array)
                                        @if(is_array($array))
                                            @foreach($array as $key => $item)
                                                <input type="hidden" name="{{$k}}[{{$key}}]" value="{{$item}}">
                                            @endforeach
                                        @else
                                            <input type="hidden" name="{{$k}}" value="{{$array}}">
                                        @endif
                                    @endforeach
                                </form>
                            </li>
                        </ul>
                    </small>
                </div>
            </h4>
            <div class="thumbnail-list">
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
                @foreach($result as $key => $item)
                    @include('client.block.film-item')
                @endforeach
                <div class="clearfix"></div>
                <div class="pull-right">
                    <div class="dataTables_paginate paging_bootstrap"> {!! $result->render() !!} </div>
                </div>
                <div class="clearfix"></div>
            </div>
			<div class="clearfix"></div>
			<center>	
				<div class="row">
					<div class="col-md-6">
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
					<div class="col-md-6">
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
        <div class="col-md-3 hidden-sm">
            @include('client.block.popular')
            @include('client.block.actor')
            <?php $adnow = 1; ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop