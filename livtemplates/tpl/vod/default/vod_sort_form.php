{template:head}
<div style="width:300px;height:300px;border:1px solid red;margin:0px auto;text-align:center;">
<h2>视频类别编辑/添加</h2><br/><br/>
<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data">

  <label>视频类别名称</label>
  <input type="text" name="sort_name" value="{$formdata['sort_name']}" /><br/>
  {if $formdata['father']}
  <label>原类别所属类型:{$formdata['father']}</label><br/>
  {/if}
  <label>类别所属类型</label>
  {template:form/select,new_sort_father,,$video_sort_type}
  <br/>
  <input type="hidden" value="{$a}" name="a" />
  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
  <input type="submit"  class="button_2"  value="{$optext}" />
</form>
</div>
{template:foot}