{template:head}
{css:tab_btn}
{css:edit_video_list}
{js:channels}
{js:vod_opration}
{code}
$list = $time_shift_server_list;
{/code}
{template:list/common_list}
{css:vod_style}
<script type="text/javascript">
gBatchAction['delete'] = './run.php?mid=' + gMid + '&a=delete';

window.Records = window.Records.extend({
	destroySuccess: function(data, records) {
		var obj = JSON.parse(data);
		eval(obj.callback);
	}
});
function hg_server_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}
function hg_call_server_del(data)
{
	var obj = JSON.parse(data);
	if(obj.run)
	{
		jAlert('该服务下有录制正在运行！');
		return false;
	}
	if(obj.id)
	{
		$("#r_" + obj.id).slideUp(1000).remove();
		return false;
	}
	else
	{
		jAlert("删除有误！");
	}
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
{js:ios/switch}
<script>
$(function(){
	var i = 1;
	var custom_server_state = function(id, callback,state){
		var url = "./run.php?mid=" + gMid + "&a=update_state&id=" + id + "&infrm=1&is_open=" + state;
		hg_ajax_post(url,"","", callback);
	}
	var onandoff = function(self, state){
		var tmp = 'mySwitchCallback' + ++i;
		window[tmp]= function(ajax){
					ajax = ajax[0];
					var title = '';
					if(ajax == 1){
						title = '已启动';
					}else if(ajax == 2){
						title = '未启动';
					}else{
						self.trigger('callback', [state == 'on' ? 'off' : 'on']);
						return;
					}
					self.selector.attr('title', title);
					self.trigger('callback', ['ok']);
					delete window[tmp];
				}
				custom_server_state(self.data('data'), tmp,state == 'on' ? 1 : 0);
	}
	$('.need-switch').each(function(){
		$(this).switchButton({
			data : $(this).attr('vid'),
			init : $(this).attr('state') > 0 ? 'on' : 'off',
			on : function(self){
				onandoff(self, 'on');
			},
			off : function(self){
				onandoff(self, 'off');
			}
		})
	});
});

</script>
<style>
.switch-box{margin-left:-40px;}
</style>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="common-list-content">
	<form method="post" action="" name="listform" class="common-list-form">
		<ul class="common-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left ">
					<div class="common-list-item paixu">
					    <a class="fz0">排序</a>
					</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item wd150">服务器状态</div>
					<div class="common-list-item zhuangtai wd80">是否启动</div>
					<div class="common-list-item jihua wd150">操作时间</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item wd150">版本号</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item wd100">服务器名</div>
					<div class="common-list-item wd350">服务地址</div>
			    </div>
			</li>
		</ul>
		
		<ul class="common-list site-list public-list" id="sitelist">
			{if $list}
			{foreach $list as $k => $v}
			<li class="common-list-data clear" id="r_{$v['id']}">
				<div class="common-list-left">
					<div class="common-list-item paixu">
							<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}" {if $v['program_id'] || $v['plan_id']} disabled="disabled"{/if} />
					</div>
				</div>
				<div class="common-list-right">
				  <div class="common-list-item wd150">
							<span class="common-list-overflow  max-wd100" style="color:{if $v['isSuccess']}green{else}red{/if};">{code} echo $v['isSuccess'] ? '运行中' : '服务停止';{/code}</span>
				  </div>
					<div class="common-list-item zhuangtai wd80" align="center" style="padding-top:4px;margin-left:-30px;">
						<div class="need-switch" title="{if !$v['is_open']}未启动{else}已启动{/if}" state="{$v['is_open']}" style="cursor:pointer;" vid="{$v['id']}"></div>
					</div>
					<div class="common-list-item wd150">
						<span class="common-time">{code} echo date('Y-m-d H:i',$v['create_time']);{/code}</span>
					</div>
					
				</div>
				<div class="common-list-right">
				  <div class="common-list-item wd150">
						<span class="common-list-overflow max-wd100">{$v['version']}</span>
				  </div>
				</div>
				<div class="common-list-biaoti">
				   <div class="common-list-item biaoti-content biaoti-transition wd100">
							<span class="common-list-overflow max-wd100">{$v['name']}</span>
				  </div>
					<div class="common-list-item wd350">
							<span class="overflow">{$v['url']}</span>
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
				{$pagelink}
			</li>
		</ul>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
			<div id="edit_show"></div>
		</div>
	</form>	
</div>
{template:unit/server_edit}
{template:foot}