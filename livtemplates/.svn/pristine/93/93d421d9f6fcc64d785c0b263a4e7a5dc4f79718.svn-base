{template:head}
{js:2013/ajaxload_new}
{js:2013/list}
{css:2013/list}
{code}
$list = $cdn_log_analysis_list[0][0];
$buckets =$cdn_log_analysis_list[0]['buckets'];
$domain = $cdn_log_analysis_list[0]['domain'];
$name = $cdn_log_analysis_list[0]['name'];
if(!$name)
{
	$name = 'URL';
}
$type = $cdn_log_analysis_list[0]['type'];
$_INPUT['start_time'] = $_INPUT['start_time'] ? $_INPUT['start_time'] : date('Y-m-d', TIMENOW - 24 * 3600);
{/code}
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
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
 <div class="common-list-content">
                {template:unit/cdnloganalysissearch}
                <form method="post" action="" name="listform">
                <div style="font-size:16px;text-align:center;padding;10px;line-height:40px;height:40px">空间{$_INPUT['bucket']}里域名为{$_INPUT['domain']}下 {$_configs['cdn_log_analysis_type'][$_INPUT['type']]}{$_INPUT['start_time']}日志</div>
                    <div class="m2o-list">
						<!--排序模式打开后显示排序状态-->
						<div class="m2o-title m2o-flex m2o-flex-center">
			            <div class="m2o-item m2o-flex-one m2o-bt" title="{$name}">{$name}</div>
			            {if $type =='size'}
			            <div class="m2o-item m2o-time" title="大小">大小</div>
			            {else}
			            <div class="m2o-item m2o-time" title="次数">次数</div>
			            {/if}
			            {if $type !='http_status'}
			            <div class="m2o-item m2o-time" title="流量">流量</div>
			            {/if}
			        </div>
	                <div class="m2o-each-list">
					    {if $list}
		       			    {foreach $list as $k => $v} 
		                      {template:unit/cdnloganalysislist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有日志信息</p>
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
   <div id="getimgtip"  class="ordertip"></div></body>
{template:foot}