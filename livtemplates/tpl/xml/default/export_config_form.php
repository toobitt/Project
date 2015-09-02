{template:head}
{css:ad_style}
{css:common/common_form}
{css:column_node}
{css:hg_sort_box}
{css:export_config_form}
{js:area}
{js:ad}
{js:common/common_form}
{js:hg_sort_box}
{js:2013/ajaxload_new}
{js:column_node}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;
						
		{/code}
	{/foreach}
{/if}
{code}//print_r( $xml );{/code}
<style>
.ad_middle h2 , .ad_form .form_ul li.nav-title{background:none;}
.ad_form .form_ul li.nav-title{margin: 10px 0px 10px 0px;}
.dead_line{width:100%;border-bottom:1px solid #DEDEDE;position:relative;margin-bottom: 10px;}
.dead_line span{display:block;width:100px;height:30px;display: block;width: 120px;height: 30px;position: absolute;left: 50%;top: -10px;background: url({$RESOURCE_URL}auth/auth_open.png) no-repeat 85px 0px #fff;text-indent: 10px;}
</style>
<div class="wrap clear">
	<div class="ad_middle" style="width:850px">
		<form name="editform" action="" method="post" class="ad_form h_l">
			<h2></h2>
			<ul class="form_ul">
			    <div class="dead_line"><span>基本配置</span></div>
				<li class="i nav-title">
					<div class="form_ul_div clear info">
						<span class="title">配置标题: </span>
						<input type="text" name="title"  class="w200" required value="" />
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear info">
						<span class="title">模版: </span>
						<ul class="tpl">
							{foreach $xml[0] as $k => $v}
							<li _id="{$v['id']}">
								<input type="radio" name="xml_id" value="{$v['id']}"/>
							<!-- <input type="hidden" name="xml_id" value="{$v['id']}" />
								<input type="hidden" name="xml_name" value="{$v['title']}" />-->
								<span>{$v['title']}</span>
							</li>
							{/foreach}
						</ul>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear info">
						<span class="title">下载文件:</span>
						<div class="need-file">
							<input type="checkbox" name="need_file" value="1" />
						</div>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear info">
						<span class="title">每次导出:</span>
						<div class="need-file">
							<input type="number" name="file_num" style="margin-right: 10px;" />条
						</div>
					</div>
				</li>
				<div class="dead_line"><span>视频筛选条件</span></div>
				<li class="i">
					<div class="form_ul_div clear info">
						<span class="title">关键字: </span>
						<input type="text" name="key"  class="w200" value="" />
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear info">
						<span class="title">时间: </span>
				 		{code}
		                    $date_source = array(
		                        'class' 	=> 'down_list',
		                        'show' 		=> 'date_show',
		                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
		                        'is_sub'	=>	1,
		                        'width'     => 125,
		                    );
		                    
		                    if($_configs['date_search_config'])
		                    {
		                    		$date_default = 1;
		                    }
		                    else
		                    {
		                     	$date_default = '1';
		                    }
		                   
		                    foreach($_configs['date_search_config'] as $k =>$v)
		                    {
		                        $date_sort[$k] = $v;
		                    }
		                {/code}
		                {template:form/search_source,date,$date_default,$date_sort,$date_source}
		                <span class="define-condition-area">
		                	<input type="text" placeholder="开始时间" name="start_time" class="date-picker">
		                	至
		                	<input type="text" name="end_time" class="date-picker" placeholder="结束时间">
		                </span>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear info">
						<span class="title">权重: </span>
						{code}
		                    $weight = array(
		                        'class' 	=> 'down_list',
		                        'show' 		=> 'weight_show',
		                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
		                        'is_sub'	=>	1,
		                        'width'     => 125,
		                    );
		                    $weight_default = 1;
		                    $weight_sort[1] = '所有权重';
		                    $weight_sort[other] = '自定义权重'
		                {/code}
		                {template:form/search_source,weight_id,$weight_default,$weight_sort,$weight}
		                <span class="define-condition-area">
		                	<input type="number" min='0' max='100' class="w120" name="start_weight" placeholder="最小权重0" />
		                	至
		                	<input type="number" min='0' max='100' class="w120" name="end_weight" placeholder="最大权重100" />
		                </span>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear info">
						<span class="title">添加人: </span>
						<input type="text" name="add_user_name"  {if $field} readonly="readonly" {/if} value="{$field}" />
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">栏目：</span>
							<div style="display:inline;float:left;margin-right:10px;">
								 <a class="common-publish-button overflow" href="javascript:;" _default="无" _prev=""></a>
								{template:unit/publish_for_form, 1, $fromdata['column_id']}			
							</div>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">分类：</span>
						{code}
							$hg_attr['exclude'] = 1;
							$hg_attr['node_en'] = 'vod_media_node';
							$hg_attr['multiple'] = 1;
						{/code}
						{template:unit/class,district_id,$district_id,$node_data}
					</div>
				</li>
			</ul>
			<input type="hidden" name="a" value="{$action}" />
			<input type="hidden" name="is_del" id="is_del" value="0" />
			<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<br />
			<div class="temp-edit-buttons">
				<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
				<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
			</div>
		</form>
	</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
<script type="text/javascript">
	$(function(){
		var MC = $('.ad_middle');
		MC.on('click' , '.overflow' , function(event){
			var self = $(event.currentTarget),
				attrid = self.attr('attrid'),
				item = self.closest('.i')
			attrid == 'other' ? item.find('.define-condition-area ').addClass('show') : item.find('.define-condition-area ').removeClass('show') ;
		});
	});
</script>
{template:foot}
