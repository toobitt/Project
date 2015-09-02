<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=content_form&id=${id}&infrm=1">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}">删除</a>
			{{if !(+iscopy)}}
			<a href="./run.php?mid={$_INPUT['mid']}&a=copy&id=${id}" data-id="${id}" class="adv-clone-btn" onclick="return hg_copy_ad(this.href, ${id});">复制</a>
			{{/if}}
			<!--
			{{if distribution}}
			<a target="_blank"  href="./run.php?mid={$_INPUT['mid']}&a=adpreview&content_id=${id}&mtype=${mtype}">预览</a>
			{{/if}}
			-->
			{{if $.inArray(+status, [1,3,4]) !== -1}}
			<a href="./run.php?mid={$_INPUT['mid']}&a=form_publish&content_id=${id}&infrm=1">投放</a>
			{{/if}}
		</div>
		<div class="record-edit-btn-area clear">
			
			{{if status == 1}}
			{{if distribution}}
			<a href="./run.php?mid={$_INPUT['mid']}&a=adcancell&id=${id}" class="adv-line-btn" _name="下架" onclick="return hg_ajax_post(this, '下架', 0);">下架</a>
			{{/if}}
			{{/if}}
			{{if status == '6'}}
			<a href="./run.php?mid={$_INPUT['mid']}&a=adonline&id=${id}" class="adv-line-btn" _name="上架" onclick="return hg_ajax_post(this, '上架', 0);">上架</a>
			{{/if}}
		</div>
		<div class="record-edit-line mt20"></div>
		
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