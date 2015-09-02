{js:vod_opration}
<div style="width:434px;height:200px;">
<form action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data" name="sort_form" id="sort_form"  onsubmit="return hg_ajax_submit('sort_form','','','hg_move_sort');">
  <label>请选择要移动到的类别:</label>
  <select name="sort_name">
     {foreach $formdata as $v}
     <option value="{$v['id']}">{$v['name']}</option>
     {/foreach}
  </select>
  <input type="hidden" name="vod_leixing" value=0 />
  <input type="hidden" value="update_move" name="a" />
  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
  <input type="submit"  class="button_2" value="移动" name="move_vod" />
</form>
</div>