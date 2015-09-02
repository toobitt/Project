{template:head}
{js:2013/ajaxload_new}
{js:highcharts/highcharts}
{js:cdn/cdn_chart}
{js:2013/list}
{css:2013/list}
{code}
$list = $cdn_flow_list[0][0];
$buckets =$cdn_flow_list[0]['buckets'];
$domain = $cdn_flow_list[0]['domain'];
$bandwidth = $cdn_flow_list[0]['bandwidth'];
$reqs = $cdn_flow_list[0]['reqs'];
$discharge = $cdn_flow_list[0]['discharge'];
$date = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$start_point = $cdn_flow_list[0]['start_time'];
{/code}
<script>
$(function(){
	var globaldata = {
			bandwidth_data : [],
			reqs_data : [],
			discharge_data : [],
			start_time : '',
			end_time : '',
			interval : 3600,	//默认一小时间隔
			start_point : ''
	};
	var bandwidth = {code}echo $bandwidth ? json_encode($bandwidth) : '{}';{/code};
	var reqs = {code}echo $reqs ? json_encode($reqs) : '{}';{/code};
	var discharge = {code}echo $discharge ? json_encode($discharge) : '{}';{/code};
		
	globaldata['interval'] = {code}echo ( $date == 1 ) ? 300 : 3600;{/code};
	globaldata['start_point'] = {code}echo $start_point ? $start_point : '[]';{/code};
	var bandwidth_data = $.map( bandwidth, function( value, key ){
		globaldata.start_time || ( globaldata.start_time = key + ':00' );
		globaldata.end_time = key + ':00';
		return value;
	} );
	var reqs_data = $.map( reqs, function( value ){
		return value;
	} );
	var discharge_data = $.map( discharge, function( value ){
		return value;
	} );
	globaldata.bandwidth_data = bandwidth_data;
	globaldata.reqs_data = reqs_data;
	globaldata.discharge_data = discharge_data;
	if ( $.isArray( globaldata['start_point'] ) && (!globaldata['start_point'].length) ){
		$('.chart-box').hide();
	}else{
		$.renderChart( globaldata );
	}
});
</script>
<style type="text/css">
.tuji_pics_show{width:398px;height:300px;background:#000 url({$image_resource}loading7.gif) no-repeat center;border:1px solid gray;position:relative;}
.tip_box{width:200px;height:100px;position:absolute;left:25%;top:-33%;background:none repeat scroll 0 0 #000000;opacity:0.7;display:none;z-index:20;}
.close_tip{position:absolute;left:89%;top:6%;z-index:20;width:15px;height:15px;background: url({$image_resource}hoge_icon.png) no-repeat -185px -18px;overflow:hidden;}
.pic_info{width:95%;height:15%;cursor:pointer;}
.arrL{position:absolute;width:50%;height:100%;cursor:pointer;left:0;top:0;z-index:10;}
.arrR{position:absolute;width:50%;height:100%;cursor:pointer;left:50%;top:0;z-index:10;}
.btnPrev{position:absolute;top:37%;left:12px;width:39px;z-index:15;height:80px;cursor:pointer;background:url({$image_resource}btnL_1.png)}
.btnNext{position:absolute;top:37%;right:12px;width:39px;z-index:15;height:80px;cursor:pointer;background:url({$image_resource}btnR_1.png)}
.btn_l{background:url({$image_resource}btnL_2.png) no-repeat;}
.btn_r{background:url({$image_resource}btnR_2.png) no-repeat;}
.special-slt{width:100px}
.special-ztlj{width:320px}
.chart-box{margin:10px 0;}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
<div id="bandwidth-chart" class="chart-box"></div>
<div id="discharge-chart" class="chart-box"></div>
 <div class="common-list-content" {if $start_point}style="border-top:1px solid #ccc;"{/if}>
                {template:unit/cdnflowsearch}
                <form method="post" action="" name="listform">
                    <div class="m2o-list">
						<!--排序模式打开后显示排序状态-->
						<div class="m2o-title m2o-flex m2o-flex-center">
			            <div class="m2o-item m2o-flex-one m2o-bt" title="日期">日期</div>
			            <div class="m2o-item m2o-time" title="流量">流量</div>
			            <div class="m2o-item m2o-time" title="请求量">请求量</div>
			             <div class="m2o-item m2o-time" title="峰值带宽">峰值带宽</div>
			              <div class="m2o-item m2o-time" title="峰值时间">峰值时间</div>
			        </div>
	                <div class="m2o-each-list">
					    {if $list}
		       			    {foreach $list as $k => $v} 
		                      {template:unit/cdnflowlist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有流量信息</p>
		  				{/if}
	                </div>
    			</form>
    			<div class="edit_show">
				<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
</div>
</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
{template:foot}