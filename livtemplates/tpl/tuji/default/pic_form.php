{template:head}
{css:ad_style}
<form action="" method="post" enctype="multipart/form-data"  class="wrap ad_form">
<h2>图片管理</h2>
{if !is_array($formdata[0])}
<ul class="form_ul">
<li><span>图片名称：</span>{$formdata['old_name']}</li>
<li><img src="{$formdata['pic_url']}" /></li>
<li><span>描述：</span>{template:form/textarea,desc,$formdata['desc']}</li>
</ul>
{else}
<ul class="form_ul">
{foreach $formdata as $k=>$v}
<li><span>图片名称：</span>{$v['old_name']}</li>
<li><img src="{$v['pic_url']}" /></li>
</li><input type="hidden" name="id[]" value="{$v['id']}">
<span>描述：</span>{template:form/textarea,desc[],$v['desc']}</li>
{/foreach}
</ul>
{/if}
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" name="sub" value="{$optext}" class="button_2"/>
</form>
{template:foot}