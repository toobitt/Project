{if $hg_data}
{foreach $hg_data as $k=>$v}
<li><a onclick="hg_add_para()" href="javascript:void(0)" title="继续添加">+</a><input type="text" name="para_zh[]" value="{$v}">&nbsp;<input type="text" name="para_en[]" value="{$k}">&nbsp;{template:form/select, form_style[], $formdata['form_style'][$k],$_configs['form_style']}<a title="删除" href="javascript:void(0)" onclick="hg_delete_para(this)">-</a></li>
{/foreach}
{else}
<li><a onclick="hg_add_para()" href="javascript:void(0)" title="继续添加">+</a><input type="text" name="para_zh[]" value="">&nbsp;<input type="text" name="para_en[]" value="">&nbsp;{template:form/select, form_style[], $formdata['form_style'][$k],$_configs['form_style']}</li>
{/if}