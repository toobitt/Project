<?php 
/* $Id: program_record_list.php 1344 2011-10-13 01:26:04Z lijiaying $ */
?>
{template:head}
{css:tab_btn}
{css:edit_video_list}
{js:channels}
{js:vod_opration}
{template:list/common_list}
{css:vod_style}
{css:live_beibowj}
<script type="text/javascript">
function hg_plan_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}
function hg_call_plan_del(data)
{
	data = data.replace(/'/g, "");
	var ids = data.split(",");
	for(i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).slideUp(1000).remove();
	}
	if($("#checkall").attr('checked'))
	{
		$("#checkall").removeAttr('checked');
	}
}
function hg_disable_action(str)
{
	jAlert(str);
}
</script>
<script>
$(function($){
    {js:domcached/jquery.json-2.2.min}
    {js:domcached/domcached-0.1-jquery}
    {js:common/common_list}
    $.commonListCache('site-list');
});
</script>
<style>
.paixu {width: 30px;}
.caozhuo{width: 50px;color:black;}
.qz-time{width:150px;}
.jm-name,.zhuangtai {width: 100px;}
.zhouqi{width:180px;}
.zhouqi span{max-width:170px;display:block;}
.caozhuo-box{position:relative;background:url("{$RESOURCE_URL}common/common-list-info.png") no-repeat;}
.caozhuo-box div{position:absolute;display:none;width:150px;top:-5px;left:0px;}
.caozhuo-box:hover div{display:block;}
</style>

<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<!--  <a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增收录计划</strong></a>
	<a href="?mid={$relate_module_id}&infrm=1" class="button_6"><strong>收录日志</strong></a>-->
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">
	               <span class="left"></span>
	               <span class="middle"><em class="add">新增收录计划</em></span>
	               <span class="right"></span>
	</a>
	<a class="gray mr10"  href="?mid={$relate_module_id}&infrm=1" target="formwin">
	               <span class="left"></span>
	               <span class="middle"><em class="set">收录日志</em></span>
	               <span class="right"></span>
	</a>
</div>
<div class="common-list-content">
	<div class="common-list-search" id="info_list_search">
	    <span class="serach-btn"></span>
		<form name="searchform" id="searchform" action="" method="get">
			<div class="select-search">
		{code}
			$attr_source = array(
				'class' => 'transcoding down_list',
				'show' => 'transcoding_show',
				'width' => 104,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
			);
			$_INPUT['channel_id'] = $_INPUT['channel_id']?$_INPUT['channel_id']:-1;
			$attr_date = array(
				'class' => 'colonm down_list data_time',
				'show' => 'colonm_show',
				'width' => 104,/*列表宽度*/
				'state' => 1,/*0--正常数据选择列表，1--日期选择*/
			);
	
			$channel[-1] = '所有频道';
			foreach($channel_info as $k =>$v)
			{
				$channel[$v['id']] = $v['name'];
			}
			$_INPUT['date_search'] = $_INPUT['date_search']?$_INPUT['date_search']:1;
		{/code}
		{template:form/search_source,channel_id,$_INPUT['channel_id'],$channel,$attr_source}
		{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</div>
			
		</form>
	</div>
		<div style="position: relative;">
			<div id="open-close-box">
				<span></span>
				<div class="open-close-title">显示/关闭</div>
				<ul>
					<li which="caozhuo"><label><input type="checkbox" checked />操作</label></li>
					<li which="zhouqi"><label><input type="checkbox" checked />周期/日期</label></li>
					<li which="qz-time"><label><input type="checkbox" checked />起止时间</label></li>
					<li which="jm-name"><label><input type="checkbox" checked />节目名称</label></li>
					<li which="fabuzhi"><label><input type="checkbox" checked />发布至</label></li>
					<li which="guidan"><label><input type="checkbox" checked />归档分类</label></li>
					<li which="laiyuan"><label><input type="checkbox" checked />来源</label></li>
					<li which="zhuangtai"><label><input type="checkbox" checked />状态</label></li>
					<li which="jihua"><label><input type="checkbox" checked />计划执行</label></li>
				</ul>
			</div>
		</div>

		<form method="post" action="" name="listform" class="common-list-form">
			<ul class="common-list">
				<li class="common-list-head public-list-head clear">
					<div class="common-list-left ">
						<div class="common-list-item paixu">
						    <a class="fz0">排序</a>
						</div>
					</div>
					<div class="common-list-right">
						<!--  <div class="common-list-item caozhuo wd70">操作</div>-->
						<div class="common-list-item fabuzhi wd80">发布至</div>
						<div class="common-list-item guidan wd60">归档分类</div>
						<div class="common-list-item laiyuan wd70">来源</div>
						<div class="common-list-item zhuangtai wd60">状态</div>
						
						<div class="common-list-item jihua wd70">计划执行</div>
					</div>
					<div class="common-list-biaoti">
						<div class="common-list-item wd100">收录频道</div>
						<div class="common-list-item jm-name wd100">节目名称</div>
						<div class="common-list-item qz-time wd180">起止时间</div>
						<div class="common-list-item zhouqi">周期/日期</div>
				    </div>
				</li>
			</ul>
			
			<ul class="common-list site-list public-list" id="sitelist">
				{if $list}
				{foreach $list as $k => $v}
				<li class="common-list-data clear" id="r_{$v['id']}">
					<div class="common-list-left">
			
						<div class="common-list-item paixu">
							<div class="common-list-cell">	
								<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}" {if $v['program_id'] || $v['plan_id']} disabled="disabled"{/if} />
							</div>
						</div>
					</div>
					<div class="common-list-right">
					
						<!--  <div class="common-list-item caozhuo wd70">
								<div class="caozhuo-box"><span style="opacity:0;">操作</span>
									<div>
										<a class="button_2" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
										{if $v['program_id'] || $v['plan_id']}
											<a class="button_2" href="javascript:void(0);" onclick="hg_disable_action('无法删除来源于节目单或节目计划的录制，请到源地址处进行删除操作！');">删除</a>
										{else}
											<a class="button_2" href="javascript:void(0);" onclick="hg_plan_del({$v['id']});">删除</a>
										{/if}
										{foreach $_relate_module AS $kkk => $vvv}
										<a class="button_2" href="./run.php?mid={$kkk}&record_id={$v['id']}&infrm=1">{$vvv}</a>
										{/foreach}
									</div>
							</div>
						</div>-->
						<div class="common-list-item guidan wd80">
                                <div class="common-list-pub-overflow wd80">
                                    <a href="javascript:;" target="_blank"><span class="common-list-pub">{$v['column_name']}</span></a>
                                    {code}
                                    /*if ($v['pub']) {
                                        foreach ($v['pub'] as $kk => $vv) {
                                        	$cu = $vv;
                                        	if($v['pub_url'][$kk])
                                        	{
                                    			$myFabu .= '<a href="'.$v['pub_url'][$kk].'" target="_blank"><span class="common-list-pub">'.$cu.'</span></a>&nbsp;';
                                        	}
                                        	else
                                        	{
                                        		$myFabu .= '<span class="common-list-pre-pub">'.$cu.'</span>&nbsp;';
                                        	}
                                        	echo $myFabu;
                                    	}
                                    }*/
                                    {/code}
							</div>
						</div>
						<div class="common-list-item wd60">
								<span>{$v['sort_name']['name']}</span>
						</div>
						<div class="common-list-item laiyuan wd70">
                                <span>{$v['source']}</span>
                        </div>
						<div class="common-list-item zhuangtai wd60">
							<span style="color:{$list_setting['status_color'][$v['action']]};">{$v['action']}</span>
						</div>
						
						<div class="common-list-item jihua wd70">
							<span>{$v['dates']}</span>
						</div>
						
					</div>
					<div class="common-list-biaoti">
					   <div class="common-list-item biaoti-content biaoti-transition wd100">
								<span class="common-list-overflow max-wd100">{$v['channel']}</span>
					  </div>
					  <div class="common-list-item jm-name wd100">
								<span class="common-list-overflow  max-wd100"><a class="text_p" title="{$v['title']}">{code} echo hg_cutchars($v['title'],12,'');{/code}</a>
								</span>
						</div>	
						<div class="common-list-item qz-time wd180">
								<span>{$v['start_time']} - {$v['end_time']}</span>
								<span class="text_b live-duration">{$v['toff_decode']}</span>
						</div>
						<div class="common-list-item zhouqi">
								<span class="overflow">{$v['cycle']}</span>
						</div>
					</div>
					<div class="common-list-i"  onclick="hg_show_opration_info({$v['id']});"></div>
					</li>
				{/foreach}	
				{else}
					<li>
						<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有内容！</p>
						<script>hg_error_html('#sitelist',1);</script>
					</li>						
				{/if}
			</ul>
			<ul class="common-list  public-list">
				<li class="common-list-bottom clear">
					<div class="common-list-left">	
						<input type="checkbox" name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" /> 
						<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" name="batdelete">删除</a>
					</div>
					{$pagelink}
				</li>
			</ul>
			<div class="edit_show">
				<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
				<div id="edit_show"></div>
			</div>
		</form>	
</div>

<script>
jQuery(function($){
	var old = $('#checkall');
	var clone = old.clone();
	old.after(clone).remove();
	clone.click(function(){
		var state = !!$(this).prop('checked');
		$('#sitelist input:checkbox').each(function(){
			if(!$(this).prop('disabled')){
				$(this).prop('checked', state);
			}	
		});	
	});
});
</script>

{template:foot}