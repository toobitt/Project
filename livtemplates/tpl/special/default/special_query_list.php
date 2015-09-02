<?php 
?>
{template:head}
{css:common/common_list}
{js:common/common_list}
{js:special/special_newform}
{css:vod_style}
{code}
$speid = $formdata['speid'];
$special_column_id = $formdata['special_column_id'];
unset($formdata['speid']);
unset($formdata['special_column_id']);
//print_r($formdata);
{/code}
{css:vod_style}
{css:mark_style}
{css:special}
<div class="wrap">
	<ul class="common-list news-list">
         <li class="common-list-head public-list-head clear">
              <div class="common-list-left">
                   <div class="common-list-item common-paixu">
                        <a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');"  class="common-list-paixu"></a>
                   </div>
              </div>
              <div class="common-list-right">
                   <div class="common-list-item wd90">操作</div>
                   <div class="common-list-item wd100">应用</div>
                   <div class="common-list-item wd70">模块</div>
              </div>
              <div class="common-list-biaoti">
					<div class="common-list-item">内容标题</div>
			  </div>
        </li>
    </ul>
    <ul class="common-list public-list">
       {if $formdata}
		   {foreach $formdata as $k => $v}
			   {code}
			   $info = serialize($v);
			   {/code}
		    <li class="common-list-data clear"  id="r_{$v['id']}" class="h"   name="{$v['id']}">
			<div class="common-list-left">
		        <div class="common-list-item common-paixu">
		                <a class="lb"  name="alist[]" ><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" class="n-h" /></a>
		        </div>
		    </div>
			<div class="common-list-right">
			   <div class="common-list-item wd90">
				      <a title="添加内容" style="cursor:pointer;color:#8fa8c6" onclick="select_content({$v['id']})">选择内容</a>
				</div>
				<div class="common-list-item wd100">
				     <span id="name_{$v['app_name']}">{$v['app_name']}</span>
				</div>
				<div class="common-list-item wd70">
				     <span id="name_{$v['module_name']}">{$v['module_name']}</span>
				</div>
		    </div>
		    <div class="common-list-biaoti">
			    <div class="common-list-item special-biaoti biaoti-transition">
			        	<a class="common-list-overflow max-wd" id="name_{$vv['title']}">
			        	  {code}
					       	 $picinfo = $v['indexpic'];
					       	 $url = $picinfo['host'].$picinfo['dir'].'40x30/'.$picinfo['filepath'].$picinfo['filename'];
				       	 {/code}	
				       	 {if $picinfo}
						 	<img src="{$url}" id="img_{$v['id']}"  class="biaoti-img"/>
						 {else}
						 {/if}
			        	 <span id="name_{$v['title']}">{$v['title']}</span>
			        	</a>
		            	<input type="hidden" name="id" value="{$v['id']}" />
			    </div>
		    </div>
		</li>
            <input type="hidden" id="info_{$v['id']}" name="info_{$v['id']}" value='{$info}' />
			<input type="hidden" id="speid" name="speid" value="{$speid}" />
			<input type="hidden" id="special_column_id" name="special_column_id" value="{$special_column_id}" />
			<input type="hidden" name="html" value="true" />
	   {/foreach}
	   {else}
        <p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">暂无记录</p>
	{/if}
</ul>
<ul class="common-list public-list">
		<li class="common-list-bottom clear">
			<div class="common-list-left">
			 <input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" style="margin-left:5px;"/> 
		     <a onclick="return hg_ajax_batchpost(this, 'create', '全选', 1, 'id','', 'ajax');">全选</a>
			</div>
			{$pagelink}
		</li>
</ul>
{template:foot}
</div>
<script>
//href="./run.php?mid={$_INPUT['mid']}&a=select&id={$v['id']}&infrm=1"
function select_content(id)
{	
	var info;
	info = $("#info_"+id).val();
	speid = $("#speid").val();
	special_column_id = $("#special_column_id").val();
	var url = "run.php?mid="+gMid+"&a=select&info="+info+"&id="+id+"&speid="+speid+"&special_column_id="+special_column_id;
	hg_ajax_post(url);
}
function select_content_callback(data){
	var msg=$.parseJSON(data);
	if(msg.error){
		jAlert(msg.error,'专题内容添加提醒');		
	}else{
		jAlert('专题内容添加成功','专题内容添加提醒');
	}
}
</script>
