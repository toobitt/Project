<script type="text/javascript">
  var vs = hg_get_cookie('video_subinfo');
  $(document).ready(function(){
	$('#video_subinfo').css('display',vs?vs:'block');
  });
</script>
{if $formdata['id']} 
<!-- 
<div class="info clear vider_s"  >
  
   
  <div id="vodPlayer" class="content-stand">
  {code}
  	if(!empty($formdata['img']))
  	{
  		$formdata['url'] = $formdata['img'][0]['host'] . $formdata['img'][0]['dir'] . '400x300/' . $formdata['img'][0]['filepath'] . $formdata['img'][0]['filename'];
  	}
  {/code}
  {if $formdata['url']} 		
	<img src="{$formdata['url']}" alt="缩略图"/>
  {/if}
  </div> 
 <span onclick="hg_close_opration_info();" title="关闭/ALT+Q" ></span> 

</div> --> 
<div class="info clear cz" style="heigth:auto;min-height:0;" id="vodplayer_{$formdata['id']}">
<span onclick="hg_close_opration_info();" title="关闭/ALT+Q" style="background: url('../../.././../livtemplates/tpl/lib/images/bg-all.png') -67px -70px no-repeat;width:26px;height:26px;top:2px;right:3px;display:inline-block;font-size:0;cursor:pointer;position:absolute;"></span> 
	<dl style="display: block;min-height:0px;">
		<dt class="face" style="float:left;width:50px;display:inline;">
			{code}
			$formdata['avatar'] = $formdata['avatar']['host'] . $formdata['dir'] . $formdata['filepath'] . $formdata['filename'];
			{/code}
			<img src="{$formdata['avatar']}" alt="" width="50" height="50" />
		</dt>
		<dd class="content"  style="display: block;margin-left:60px;min-height:150px;background: none;">
			<p style="display: block; font-size:14px;line-height:24px;background: none;">
				<a href="#" style="font-size:14px;color:#0078B6;">{$formdata['nick']}：</a>
				<span style="font-size:14px;line-height:24px;background: none;">{$formdata['text']}</span>
			</p>
			<ul style="padding-left:0px;border-top:none;height:auto;padding-top:0;margin-bottom:0;">
				{if is_array($formdata['img']) && count($formdata['img'])>0}
					{foreach $formdata['img'] as $k => $v}	
						{code}
							if($v['id'])
							{
								$img = $v['host'] . $v['dir'] . $formdata['picsize']['thumbnail'] . $v['filepath'] . $v['filename'];
							}
							else
							{
							$img = $v['host'] . $v['dir'] . $formdata['picsize']['thumbnail'] . $v['filepath'] . $v['filename'];
							}
						{/code}	
		          		<li style="margin:10px 10px 0 0;float:left;"><img src="{$img}" alt width="120" height="90"/></li>
		            {/foreach}
				{else}
				{/if}				
			</ul>
			{if $formdata['source_info']}
				<dl style="display:block;border:1px solid #EEEEEE;-moz-border-radius:3px;-webkit-border-radius:3px;padding:10px 20px;background:#FBFBFB;float:left;">
					<dt class="face" style="float:left;width:50px;display:inline;height:auto;">
						{code}
						$formdata['source_info']['avatar'] = $formdata['source_info']['avatar']['host'] . $formdata['source_info']['avatar']['dir'] . $formdata['source_info']['avatar']['filepath'] . $formdata['source_info']['avatar']['filename'];
						{/code}
						<img src="{$formdata['source_info']['avatar']}" alt="" width="50" height="50" />
					</dt>
					<dd class="content" style="display: block;margin-left:60px;background:#FBFBFB;height:auto;min-height:0;">
						<p style="display: block;font-size:14px;line-height:24px;">
							<a href="#" style="font-size:14px;color:#0078B6">{$formdata['source_info']['nick']}：</a>
							<span style="font-size:14px;line-height: 24px;background:none;">{$formdata['source_info']['text']}</span>
						</p>
						<ul style="padding-left: 0px;border-top:none;height:auto;">
							{if is_array($formdata['source_info']['img']) && count($formdata['source_info']['img'])>0}
								{foreach $formdata['source_info']['img'] as $k => $v}
									{code}
										if($v['id'])
										{
											$img = $v['host'] . $v['dir'] .$formdata['picsize']['thumbnail']  . $v['filepath'] . $v['filename'];
										}
										else
										{
										$img = $v['host'] . $v['dir'] . $formdata['picsize']['thumbnail']  . $v['filepath'] . $v['filename'];
										}
									{/code}
									<li sytle="margin:0 10px 0 0;float:left;"><img src="{$img}" alt width="120" height="90" /></li>
								{/foreach}
							{else}
							{/if}
						</ul>
						<p style="margin-top:15px;clear:both;">
							<a href="#" style="margin-right:10px;color:#7AA5B9;">{$formdata['source_info']['create_time']}</a>
							<a href="#" style="margin-right:10px;color:#7AA5B9;">来自{$formdata['source_info']['comefrom']}</a>
						</p>
					</dd>
				</dl>			
			{/if}
			<p style="margin-top:15px;clear:both;float:left;margin-bottom:10px;">
				<a href="#" style="margin-right:10px;color:#7AA5B9;">{$formdata['create_time']}</a>
				<a href="#" style="margin-right:10px;color:#7AA5B9;">来自{$formdata['comefrom']}</a>
			</p>
		</dd>
	</dl>	 
</div> 
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;">
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id={$formdata['id']}" onclick="return hg_ajax_post(this, '审核', 0, 'hg_change_status');">审核</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=0&id={$formdata['id']}" onclick="return hg_ajax_post(this, '打回', 0, 'hg_change_status');">打回</a>
		</li>
	</ul>
</div>
{else}
此新闻已经不存在,请刷新页面更新
{/if}