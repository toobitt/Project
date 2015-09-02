{template:head}
{css:ad_style}
<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="wrap ad_form">
<h2>视频类别编辑/添加</h2>
<ul class="form_ul">
<li class="i"><span>视频类别名称：</span><input type="text" name="sort_name" value="{$formdata['sort_name']}" /></li>

  {if $formdata['father']}
  <li class="i"><span>原类别所属类型：</span>{$formdata['father']}</li>
  {/if}
  <li class="i"><span>类别所属类型：</span>
  {template:form/select,new_sort_father,,$video_sort_type}</li>
  </ul>
  <input type="hidden" value="{$a}" name="a" />
  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</br>
  <input type="submit"  class="button_2"  value="{$optext}"  class="button_2"/>
</form>

{template:foot}