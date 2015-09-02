{template:head}
{css:news_add}
{css:column_node}
{js:column_node}
{js:contribute}
{js:hg_sort_box}
{code}
  $markswf_url = RESOURCE_URL.'swf/';
{/code}
<script type="text/javascript">
	$(function(){
		hg_swf_image();	
	});

	jQuery(function($){
		if (!$('.common-publish-button').size()) return;
		var pub = $("#form_publish");
		
	    $('.common-publish-button').on('click', function(event){
	        event.stopPropagation();
	        event.preventDefault();
	      
	        if ( $(this).data('show') ) {
	        	$(this).data('show', false);
	       		pub.css({top: -450})
	        } else {
	        	$(this).data('show', true);
	        	pub.css({top: 600});	
	        }
	    });
	    pub.on('click', '.publish-box-close', function () { $('.common-publish-button').trigger('click'); });
	    pub.find('.publish-box').hg_publish({
	    	change: function () {
	    		 $('.common-publish-button').html(function(){
	        		var hidden = $('.publish-name-hidden', pub).val();
	       			return hidden ? ($(this).attr('_prev') + '<span style="color:#000;">' + hidden + '</span>') : $(this).attr('_default');
	    		 });	
	    	},
	    	maxColumn: 3
	    });
	});
</script>
{code}
	//hg_pre($formdata);
{/code}
<div class="wrap clear"  >
	<div class="ad_middle">
		<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form"   >
			<h2>{$optext}报料
				{if $formdata['video_url']}
				<span onclick="show_video();" title="显示、关闭视频/ALT+W" class="edit_video_show">视频预览</span>
				{/if}
			</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="top_left clear">
					    <input type="text" value="{if $formdata['title']}{$formdata['title']}{/if}" name="title" id="title" class="title">
						{code}
							$item_css = array(
								'class' => 'transcoding down_list',
								'show' => 'sort_item',
								'width' => 120,
								'state' => 0,
								'is_sub' => 1
							);
							$name = array();
							$default_contri_sort = 0;
							$name[$default_contri_sort] = '未分类';
							foreach ($show_sort[0] as $k=>$v)
							{
								$name[$k] = $v['name'];
							}
							$formdata['sort_id'] = $formdata['sort_id'] ? $formdata['sort_id'] : 0;
						{/code}
						{template:form/search_source,sort_id,$formdata['sort_id'],$name,$item_css}
					
						 <textarea name="brief" id="brief" style="margin-top:13px" class="brief">{$formdata['brief']}</textarea>
					</div>
					<div class="indexpic">
						<input type="hidden" value="{$indexpic}" name="indexpic" id="indexpic">
						{code}
							if(!empty($formdata['indexpic']))
							{
								$url = $formdata['indexpic']['host'].$formdata['indexpic']['dir'].'160x120/'.$formdata['indexpic']['file_path'].$formdata['indexpic']['file_name'];
							}
						{/code}
						<img  src="{$url}" alt="索引图"  id="img_{$formdata['id']}"/>
					</div>
					<div class="clear"></div>
				</li>
				<!-- 编辑器存在样式问题 -->
				<!--  
				<li class="i">
					<div class="form_ul_div clear">
						{code}
                			//echo hg_editor('content',$formdata['text']);
           		 		{/code}	
					</div>				
				</li>
				-->
				<li class="i">
					<textarea rows="10" cols="8" name="content">{$formdata['text']}</textarea>
				</li>	
				{if $a=='update'}		
				<li class="i">
					<div class="form_ul_div clear">
						<div id="affix_title" class="affix_title_default">
							<span class="view" onclick="hg_show_affix();">查看文章附件</span>
							<input type="hidden" value="{$formdata['id']}" id="material_history" name="material_history"/>
						</div>
						<div id="affix_content" class="affix_content" style="display:none;">					
							<div id="image_box">
								{if $formdata['pic']}
									{foreach $formdata['pic'] as $mk=>$mv}
										{code}
											$url = $mv['host'].$mv['dir'].'100x100/'.$mv['file_path'].$mv['file_name'];
											$ori_url = $mv['host'].$mv['dir'].$mv['file_path'].$mv['file_name'];
										{/code}								
										<div id="affix_{$mv['material_id']}" class="imglist" onmouseover="hg_indexpic_show({$mv['material_id']},1);return false;" onmouseout="hg_indexpic_show({$mv['material_id']},0);return false;">
											<div id="del_{$mv['material_id']}" class="del" onclick="hg_material_del({$mv['material_id']});">x</div>
											<input type="hidden" name="material_id[]" value="{$mv['material_id']}" />
											<input type="hidden" name="material_name[]" value="{$mv['filename']}"/>
											<img  src="{$url}"   onclick="insert_into({$mv['material_id']},'{$ori_url}','pic')"/>
											<div id="over_{$mv['material_id']}" class="over" onclick="hg_material_indexpic({$formdata['id']},{$mv['material_id']})">设为索引</div>	
										</div>
									{/foreach}
								{/if}
							   <div id="image_material"></div>
							   <div class="clear" style="margin-bottom:10px;"></div>
							</div>
						</div>
					</div>
				</li>
		
				{code}
					$audit_css = array(
						'class' =>'transcoding down_list',
						'show' => 'audit_item',
						'width' => 120,
						'state' => 0,
						'is_sub' => 1
					);
					$formdata['audit'] = $formdata['audit']?$formdata['audit']:1;
				{/code}
				 
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">审核状态：</span>									
						{template:form/search_source,audit,$formdata['audit'],$_configs['contribute_audit'],$audit_css}						
					</div>
				</li>
				
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">审核意见：</span>					
						<textarea name="opinion" id="opinion"  class="brief">{$formdata['opinion']}</textarea>
					</div>
				</li>
				{/if}
				
				<!--  此处仅供测试  -->
				{if $a == 'create'}
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">视频上传：</span>					
						<input name="videofile[]" type="file" />
						<input name="videofile[]" type="file" />
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">图片上传：</span>					
						<input name="photos[]" type="file" />
						<input name="photos[]" type="file" />
					</div>
				</li>
				{/if}
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">发生时间：</span>
						<input type="text" value="{if $formdata['event_time']}{$formdata['event_time']}{/if}" name="event_time" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width: 219px"/>					
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">当事人：</span>
						<input type="text" value="{if $formdata['event_user_name']}{$formdata['event_user_name']}{/if}" name="event_user_name" style="width: 219px"/>					
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">当事人电话：</span>
						<input type="text" value="{if $formdata['event_user_tel']}{$formdata['event_user_tel']}{/if}" name="event_user_tel" style="width: 219px"/>					
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">事件发生地：</span>
						<input type="text" value="{if $formdata['event_address']}{$formdata['event_address']}{/if}" name="event_address" style="width: 519px"/>					
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">报料人诉求：</span>
						 <textarea name="event_suggest">{$formdata['event_suggest']}</textarea>
					</div>
				</li>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				{if $a=='update'}
				<li class="i">
					<a class="common-publish-button overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
					{template:unit/publish_for_form, 1, $formdata['column_id']}
				</li>
				{/if}
				<input type="hidden" name="user_id" value="{$formdata['user_id']}"/>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">报料人：</span>
						<input type="text" value="{$formdata['user_name']}" name="user_name" style="width: 219px"/>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">电话：</span>
						<input type="text" value="{$formdata['tel']}" name="tel" style="width: 219px"/>					
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">邮箱：</span>
						<input type="text" value="{$formdata['email']}" name="email" style="width: 219px"/>					
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">住址：</span>
						<input type="text" value="{$formdata['addr']}" name="addr" style="width: 219px"/>					
					</div>
				</li>
			    	
				<li class="i">
                  <div class="form_ul_div clear">
	        	 	<span class="title">是否跟进：</span>
	        	 	    <div class="member_type">
			        	 	{foreach $_configs['contribute_follow_return'] as $key=>$vo}
					         	<input type="radio" class="type" name="is_follow" value="{$key}" {code} if($formdata['is_follow']==$key ) echo "checked=\'checked\'"; {/code} >
					         	<span>{$vo}</span>
					    	{/foreach}
        	 	       </div>
        	        </div>
        	    </li>
				
				{if BOUNTY}
				{if $a=='update'}
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">付费状态：</span>
							
						{code}
						$pay_css = array(
							'class' =>'transcoding down_list',
							'show' => 'pay_item',
							'width' => 120,
							'state' => 0,
							'is_sub' => 1
						);
						$formdata['is_bounty'] = $formdata['is_bounty']?$formdata['is_bounty']:0;
						{/code}
						{template:form/search_source,is_bounty,$formdata['is_bounty'],$_configs['bounty'],$pay_css}					
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">赏金：</span>
						<input type="text" value="{$formdata['money']}" name="money" style="width: 219px"/>					
					</div>
				</li>
				{/if}
				{/if}
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">地图：</span>
						{code}
							$hg_bmap = array(
								'height' => 480,
								'width'  => 600,
								'longitude' => isset($formdata['baidu_longitude']) ? $formdata['baidu_longitude'] : '0', 
								'latitude'  => isset($formdata['baidu_latitude']) ? $formdata['baidu_latitude'] : '0',
								'zoomsize'  => 13,
								'areaname'  => $_configs['areaname'],
								'is_drag'   => 1,
							);
						{/code}
						{template:form/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
					</div>
				</li>
				<!--  
				<li class="i">
					<div class="form_ul_div clear">
						{code}
							$position = $show_position[0];
							$hg_map = array(
								'height'=>480,
								'width'=>600,							
								'longitude'=>$formdata['longitude'],          //经度
								'latitude'=>$formdata['latitude'], 			  //纬度
								'zoomsize'=>13,          //缩放级别，1－21的整数
								'areaname'=>$position,   //显示地区名称，纬度,经度与地区名称二选1
								'is_drag'=>1,            //是否可拖动 1－是，0－否
								'objid'=>'',
							);
						{/code}
						{template:form/google_map,longitude,latitude,$hg_map}					
								
					</div>
				</li>
				-->
			</ul>
			
			<br />
			<input type="submit" id="submit_ok" name="sub" value="{$optext}报料" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
		</form>
	</div>
	
	<!-- 视频模板开始 -->
	{if $formdata['video_url']}	
	<div id="hoge_edit_play" style="position:absolute;left:750px;top:-340px;dispaly:none">
		<img class="move_img_a" src="" id="img_move" style="width:320px;" />
		<object id="video" type="application/x-shockwave-flash" data="{$markswf_url}vodPlayer.swf?{$formdata['time']}" width="320" height="270">
			<param name="movie" value="{$markswf_url}vodPlayer.swf?{$formdata['time']}">
			<param name="allowscriptaccess" value="always">
			<param name="wmode" value="transparent">
			<param name="allowFullScreen" value="true">
			<param name="flashvars" value="jsNameSpace=adminDemandPlayer&startTime={$formdata['start']}&duration={$formdata['duration']}&videoUrl={$formdata['video_url'][0]['m3u8']}&videoId={$formdata['video_url'][0]['vodid']}&snap=true&autoPlay=false&snapUrl={$formdata['snapUrl']}">
		</object>
		<span></span>
	</div>
	{/if}
	<!-- 视频模板结束 -->

	<div class="right_version">
		<h2>
			<a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a>
		</h2>
	</div>
</div>
{template:foot}