{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
{/code}

{code}
$attr_for_edit = array('id');
foreach ($list as $k => $v) {
	$less_list[$k] = array();
	foreach ($attr_for_edit as $attr) {
		$less_list[$k][$attr] = $v[$attr];
	}
}
$js_data['list'] = $less_list;
//print_r($list);
{/code}
<script>
globalData = window.globalData || {};
$.extend(globalData, {code}echo json_encode($js_data);{/code});
</script>
{css:vod_style}
{css:ad_style}
{css:common/common_list}
{css:2013/list}
{js:vod_opration}
{js:2013/list}
{js:2013/ajaxload_new}
{js:box_model/list_sort}
{js:common/common_list}
{js:underscore}
{js:Backbone}
{js:jqueryfn/jquery.tmpl.min}
{js:ad}
{js:aboke/boke_list}
<style>
.w80{width:80px;}
.color{color: #8fa8c6;}
.record-edit-area div .img:after{left:30%}
.boke-title{-webkit-transition: all 0.15s ease-in 0s;transition: all 0.15s ease-in 0s;}
.m2o-each:hover .boke-title{padding-left:15px;}
.item-list{border-radius:2px;max-width: 170px;height: auto;background: #789cc2;line-height: 23px;font-size: 12px;position: absolute;top: 32px;right: 298px;z-index: 99999;display:none;}
.item-list li{float:left;color:white;margin-left: 13px;}
.item-list em{border:6px solid #789cc2; border-color: transparent transparent #789cc2;position:absolute;top:-12px;left:80px;}
.m2o-each .m2o-state:hover .item-list{display:block}
</style>
<div class="common-list-content" style="min-height:auto;min-width:auto;">


    
    <div class="search_a" id="info_list_search">
        <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
            <input type="hidden" name="a" value="show" />
            <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <div class="right_2">
                <div class="button_search">
                    <input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                </div>
                {template:form/search_input,k,$_INPUT['k']}                        
            </div>
        </form>
    </div>
    
    
                
	<form action="" method="post">
	 <div class="m2o-list">
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		       <div class="m2o-item m2o-paixu" title="排序">
		        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="名称">图片/名称</div>
            <div class="m2o-item m2o-state" title="系统分类">系统分类</div>
            <div class="m2o-item m2o-style w80" title="用户分类">用户分类</div>
            <div class="m2o-item m2o-style w80" title="状态">状态</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list) && count($list)>0}
				{foreach $list as $k => $v}	
		            {template:unit/video_detail}
		        {/foreach}
			{else}
				<p class="common-list-empty">没有你要找的内容！</p>
			{/if}
        </div>
        <div class="m2o-bottom m2o-flex m2o-flex-center">
		  	 <div class="m2o-item m2o-paixu">
        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
    		</div>
    		<div class="m2o-item m2o-flex-one">
    		 <!--    <a class="batch-handle">审核</a>
    		   <a class="batch-handle">打回</a>-->
    		   <a class="batch-handle">删除</a>
    		</div>
    		<div id="page_size">{$pagelink}</div>
		</div>
    </div>
   </form>
 </div>
 <script>
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id , img ,vodurl){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id'],
                   	   img : n['img'],
                   	   vodurl : n['vodurl']
                   }
                   return false;
               }
              
            });
            return info;
        }
    });
    $('.m2o-each').geach();
	$('.m2o-list').glist();
</script>

<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1">编辑</a>
				<a class="option-delete">删除</a>
				<a></a>
				<a></a>
			</div>
			<div class="m2o-option-line"></div>
			<div class="record-edit-area clear">  
				<div><span class="record-edit-play-shower play-button img"><img src="{{= img}}" style="width:135px;height:65px;"/></span></div>  
        	</div>
		</div>
    </div>
	<div class="m2o-option-confirm">
			<p>确定要删除该内容吗？</p>
			<div class="m2o-option-line"></div>
			<div class="m2o-option-confim-btns">
				<a class="confim-sure">确定</a>
				<a class="confim-cancel cancel">取消</a>
			</div>
	</div>
	<div class="m2o-option-close"></div>
</div>
</script>
<script type="text/x-jquery-tmpl" id="video-tpl">
<div class="video-box" style="width:360px;height:300px;position: absolute;right: 0px;top: 0px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="360" height="300">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="videoUrl={{= vodurl_m3u8}}&autoPlay=true&aspect=${aspect}">
  </object>
 <span class="record-edit-back-close"></span>
</div>
</script>
{template:foot}
