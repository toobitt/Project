{code}
foreach ($formdata As $k => $v) 
{
	$$k = $v;
}
//hg_pre($indexpic_url);
{/code}		
			<div class="info"><label>标题：</label><input name="title" value="{$title}" class="title"/></div>
			<div class="info"><label class="alignT">描述：</label><textarea name="brief" class="descr">{$brief}</textarea></div>
			<div class="info"><label>外部连接：</label><input name="outlink" value="{$outlink}" class="outlink"/></div>
			<div class="info">
				<label class="alignT">索引图片：</label>
				<div id="indexpic_preview" class="pic-area" {if !$indexpic}style="display:none;"{/if} >
                    <input type="button" value="更改图片" class="form-add hover-add editor-btn" />
					<img src="{code}echo $indexpic_url['host'].$indexpic_url['dir'].$indexpic_url['filepath'].$indexpic_url['filename'];{/code}" class="preview-hover"/>
				</div>
				
				<input type="button" value="+添加图片" class="form-add" {if $indexpic}style="display:none;"{/if} />
			
				<input name="indexpic" id="indexpic_value" value="{$indexpic}" type="hidden"/>
                <input name="material_id[]" id="material_id" value="{$indexpic}" type="hidden"/>
			</div>
			<div class="info" style="margin-left:60px;">
				{template:unit/publish, 1, $column_id}
			</div>
			<div class="mt10">
				<input type="submit" value="确定" class="sure"/>
				<input type="hidden" name="submit_type" value="1" />
				<input type="hidden" name="a" value="update" />
				<input type="hidden" name="id" value="{$formdata['id']}" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<input type="hidden" name="ajax" value="1" />
			</div>