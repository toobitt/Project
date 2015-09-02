<?php 
/* 
$Id:message_received_list.php 17960 2013-03-21 14:28:00 jeffrey $ 
*/
?>
{template:head}
{code}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}

<style type="text/css">
.right_1 a {text-decoration:underline; cursor:pointer; margin-right:10px;}
.gery { color:#999;}
.green { color:#090;}
.red { color:#F66; }
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
<!--这里是导航右侧 新增操作的地方-->
</div>
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						<!--分类下拉-->
						<!--<div class="transcoding down_list" style="width: 104px; z-index: 10000;"  onmouseover="hg_search_show(1,'_node_show_','', this);" onmouseout="hg_search_show(0,'_node_show_','', this);">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle"><a><em></em><label id="display__node_show_" class="overflow">所有分类</label></a></span>
							<ul id="_node_show_" style="display:none;"  class="overflow">
								{foreach $list['cate'] as $k => $v}
								<li style="cursor:pointer;" ><a href="./run.php?mid={$_INPUT['mid']}&a=show&infrm=1&cateid={$k}" class="overflow">{$v}</a></li>
								{/foreach}
							</ul>
						</div>-->
						<!--分类下拉结束-->
					</div>
					<input type="hidden" name="a" value="show" />
					<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
					<div class="right_2">
						<!--搜索开始-->
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						{template:form/search_input,key,$_INPUT['key']}
						<!--搜索结束-->
				    </div>
	            </form>
			</div>

			<div class="list_first clear" id="list_head">
				   <span class="left">
                    	<a class="lb">选择</a>
                    </span>
                   <a style="width:100px;margin-top:8px;float:left;">标题</a>
				   <a style="width:120px;margin-top:8px;float:left;">发送号码</a>
				   <a style="width:170px;margin-top:8px;float:left;">接收号码</a>
				   <a style="width:100px;margin-top:8px;float:left;">是否图片</a> 
				   <a style="width:100px;margin-top:8px;float:left;">是否视频</a> 
				   <a style="width:100px;margin-top:8px;float:left;">是否附件</a>
				   <span class="right" style="width:200px;">
						 <a style="width:80px;">操作</a>
                   </span>
           </div>
           <form method="post" action="" name="listform">
               <ul class="list" id="vodlist">
					 {if is_array($list['date']) &&!empty($list['date']) && count($list['date'])>0}
						{foreach $list['date'] as $k => $v}		
		                     {template:unit/active}
		                {/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  			{/if}
					<li style="height:0px;padding:0;" class="clear"></li>
               </ul>
	           <div class="bottom clear">
	               <div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" style="margin-left:5px;"/>
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'id','','ajax');" name="batdelete">删除</a>
		               <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','审核',1,'id','&status=1','ajax','');" name="bataudit" >审核</a>
		               <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'back','打回',1,'id','&status=2','ajax','');"  name="bataudit" >打回</a>
				   </div>
                   {$pagelink}
			  </div>
           </form>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
function hg_change_shenhe(ids){
	//var test = ids;
	//for(i in test ){
	  //alert(i);           //获得属性
	  //alert(i+':'+test[i]);  //获得属性
	//}
	//alert(ids.constructor);

	 //alert(ids);
	 arrid = new Array;
	 arrid = ids.split(",");
	 for(i=0;i<arrid.length;i++){
    		$("#status_"+arrid[i]).html("<span class='green'>已审核</span>");
	 }
}

function hg_change_dahui(ids){
	  //alert(ids);
	  //console.dir(ids);
	 arrid = new Array;
	 arrid = ids.split(",");
	 for(i=0;i<arrid.length;i++){
	 	$("#status_"+arrid[i]).html("<span class='red'>已打回</span>");
	 }
}
</script>
{template:foot}