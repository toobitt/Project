<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1" target="formwin">编辑</a>
			<!--  <a>移动</a>-->
			<a href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=${ state == globalData.auditValue ? 0 : 1 }&id=${id}" 
				onclick="return hg_ajax_post(this, '{{if state == globalData.auditValue}}打回{{else}}审核{{/if}}', 0, 'hg_change_status');">
				{{if state == globalData.auditValue}}打回{{else}}审核{{/if}}
			</a>
			{{if !+content_count}}
			 <a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>	
			 {{/if}}		
			<a href="./run.php?mid={$_INPUT['mid']}&a=move_form&id=${id}&nodevar=special_sort" data-node ='special_sort'>移动</a>
		</div>
		<div class="record-edit-btn-area clear" style="margin-bottom:20px;">
			{if $_configs['App_publishcontent']}
			<a href="./run.php?mid={$_INPUT['mid']}&a=recommend&id=${id}" onclick="return hg_ajax_post(this, '推荐', 0);">签发</a>
			{/if}
			{{if !template_sign}}
				<a href="./run.php?mid={$_INPUT['mid']}&a=built_template_form&id=${id}" target="_blank" need-back>快速专题</a>
			{{else}}
				<a href="magic/main.php?gmid={$_INPUT['mid']}&ext=${ext}&bs=k" target="_blank" go-blank>快速专题</a>
				{{if column_url}}
				<a href="./run.php?mid={$_INPUT['mid']}&a=build_special&id=${id}&infrm=1"  class="mkpublish" >生成</a>
				<a href="./run.php?a=relate_module_show&app_uniq=publishsys&mod_uniq=template&mod_a=delete_mkpublish_cache&page_data_id=${id}" onclick="delete_mkpublish_cache()" class="delcache" >发布页面</a>
				{{/if}}
			{{/if}}
         </div>
                   
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
</div>