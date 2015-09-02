<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="appauthform"  id="transcode_form" onsubmit="return hg_ajax_submit('transcode_form');">
<div class="item">
	<label>转码服务器名称：</label>
	<input type="text" name="name"   id="name"  value="{$formdata['name']}"/>
</div>
<div class="item">
	<label>主机：</label>
	<input type="text" name="trans_host" id="trans_host"  value="{$formdata['trans_host']}"/>
</div>
<div class="item">
	<label>端口：</label>
	<input type="text" name="trans_port" id="trans_port"  value="{$formdata['trans_port']}"/>
</div>
<div class="item" style="margin:0;">
	<label style="vertical-align:top;">是否开启：</label>
	<div style="display:inline-block;margin:10px 0 0 10px;">
	     <div class="common-switch {if $formdata['is_open']}common-switch-on{/if}">
	       <div class="switch-item switch-left" data-number="0"></div>
	       <div class="switch-slide"></div>
	       <div class="switch-item switch-right" data-number="100"></div>
    	</div>
	 </div>
	<input type="checkbox" style="display:none;" name="is_open" id="is_open" {if $formdata['is_open']}checked="checked"{/if} value="1" />
</div>
<div class="item">
	<label style="vertical-align:top;">需要下载文件：</label>
	<div class="list-area">
	      <div class="current">{if $formdata['is_carry_file']}需要{else}不需要{/if}</div>
	      <span class="arrow"></span>
	      <div class="list-menu">
	           <div class="menu-item" data-id="1">需要</div>
	           <div class="menu-item" data-id="0">不需要</div>
	      </div>
	</div>
	<input type="checkbox" style="display:none;" name="is_carry_file" id="is_carry_file" {if $formdata['is_carry_file']}checked="checked"{/if} value="1"  />
</div>
{if $a == 'update'}
<div style="background:#f4f4f4;padding:10px 0;">
	<div class="item" style="margin-bottom:6px;">
		<label>版本号：</label>
		<span>{$formdata['version']}</span>
	</div>
	<div class="item" style="margin-bottom:6px;">
		<label>源视频目录：</label>
		<span>{$formdata['source_path']}</span>
	</div>
	<div class="item">
		<label>目标视频目录：</label>
		<span>{$formdata['target_path']}</span>
	</div>
</div>
<div class="item"><a class="trans-module-del trans-margin" href="run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}"  onclick="return hg_ajax_post(this,'删除',1);">删除转码服务器</a></div>
{/if}
	<input type="submit" value="保存" class="trans-module-save" />
<input type="hidden" value="{$a}" name="a" />
<input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>