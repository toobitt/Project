{css:column_node}
{js:column_node}
<ul class="form_ul" id="alllist">
{code}
	$_id = $id;
{/code}
{if $formdata}
{foreach $formdata as $_k=>$_v}
<li>
	<div class="form_ul_div clear">
	
	{code}
		if($_v['col'])
		{
			list($attr,$pub_col) = each($_v['col']);
			
            if($pub_col)
            {
                $pub_col_name = @implode(',', $pub_col);
                $pub_col_id = @implode(',', array_keys($pub_col));
            }
		}
	{/code}
	<div class="form_fb">
	<a class="common-publish-button overflow" href="javascript:;" _default="栏目选择：无" _prev="选择栏目：">栏目选择：{if $pub_col_name}{$pub_col_name}{else}点击此处{/if}</a>
    </div>
	</div>
	<div class="column-area">
	 <input name="{$_k}col_{$_id}" class="column-id" type="hidden" value="{$pub_col_id}"/>
	 <input name="{$_k}col_{$_id}_name" class="column-name" type="hidden" value="{$pub_col_name}"/>
	</div>
</li>
{foreach $_v['field'] as $fname=>$fzh}
<li>
	<div class="form_ul_div clear">
	<input type="hidden" name="{$_k}fields_{$_id}[]" value="{$fname}"><span class="title" title="{$fname}">{$fzh}：</span>
	<span>
	{code}
		/*select样式*/
		$condi_style = array(
		'class' => 'down_list i select_margin s',
		'show' => $fname.'_ul_' . $_id,
		'width' => 120,	
		'state' => 0, 
		'is_sub'=>1,
		'more'=> $_id,
		);
		$d = $_v['value'][$fname]['simbol'] ? $_v['value'][$fname]['simbol'] : 0;
	{/code}
	{template:form/search_source, $_k.con_.$fname, $d, $_configs['conditions'], $condi_style}
	</span>
	<input style="margin-left:10px;vertical-align:middle" type="text" value="{$_v['value'][$fname]['value']}" name="{$_k}value_{$_id}[]">
	</div>
</li>
{/foreach}
{/foreach}
{else}
<h4 class="hg_error">客户端标志丢失</h4>
{/if}
</ul>
