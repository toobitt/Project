<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
			<!-- <a>移动</a> -->
			{if $_configs['App_program']}
			<a target="mainwin" href="run.php?a=relate_module_show&app_uniq=program&mod_uniq=program&mod_main_uniq=channel&mod_a=show&channel_id=${id}&infrm=1&dates={$dates}">节目单</a>
			{/if}
			<a href="run.php?a=relate_module_show&app_uniq=live&mod_uniq=program_shield&mod_main_uniq=channel&mod_a=show&channel_id=${id}&infrm=1&dates={$dates}">屏蔽节目</a>
		</div>
		{if $_configs['App_schedule'] || $_configs['App_live_control']}
		{{if +is_control}}
		<div class="record-edit-btn-area clear">
			 <!-- <a href="./run.php?mid={$_INPUT['mid']}&a=recommend&id=${id}" onclick="return hg_ajax_post(this, '推荐', 0);">签发</a> -->
			 {if $_configs['App_schedule']}
	         <a target="mainwin" href="run.php?a=relate_module_show&app_uniq=schedule&mod_uniq=schedule&mod_main_uniq=channel&mod_a=show&channel_id=${id}&infrm=1">串联单</a>
	         {/if}
	         {if $_configs['App_live_control']}
	         <a target="mainwin" href="run.php?a=relate_module_show&app_uniq=live_control&mod_uniq=live_control&mod_main_uniq=live_control&mod_a=form&id=${id}&infrm=1">播控</a>
	         {/if}
			<!-- <a>区块</a> -->
		</div>
		{{/if}}
		{/if}
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-area clear">
			<div>
				<span class="record-edit-play-shower img" style="background:url(${preview})">
					<img src="${preview}" width="135" height="65" />
				</span>
				<span class="maliu-label"></span>
			</div>
			<div>
				
			</div>
		</div>
		<!--
<div class="record-edit-info">
			<span>访问:${click_num}</span>
			<span>评论:${comm_num}</span>
		</div>
-->
		<span class="record-edit-close"></span>
	</div>
	<div class="record-edit-confirm">
		<p>确定要删除该内容吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a>确定</a>
			<a>取消</a>
		</div>
		<span class="record-edit-confirm-close"></span>
	</div>
	<div class="record-edit-play">
	</div>
</div>
<textarea style="display:none;" type="tpl" id="vedio-tpl">
  <div id="flashBox" style="width:360px;height:300px;">
  </div>
  {{if +is_audio}}
  <script>
  setSwfPlay('flashBox', "${channel_stream[0].output_url}", '360', '300', 100, 'flashBox');
  </script>
  {{else}}
  <script>
   setSwfPlay('flashBox', "${channel_stream[0].m3u8}", '360', '300', 100, 'flashBox');
  </script>
  {{/if}}
  <span class="record-edit-back-close"></span>
</textarea>