<div style="width:100%;height:300px;text-align:center;">
<form action="./run.php?mid={$_INPUT['mid']}" method="post" name="create_collect_form" id="create_collect_form"   onsubmit="return hg_ajax_submit('create_collect_form', '');" >
   <br/>
   <label>填写集合名称：</label>
   <input type="text" name="collect_name" id="collect_name" /><br/><br/>
   <lable>选择所属类别：</label>
   <select name="sort_name">
   {foreach $formdata['vod_sort'] as $v}
      <option value="{$v['id']}">{$v['sort_name']}</option>
   {/foreach}
   </select><br/><br/>
   <label>选择所属来源：</label>
    <select name="source">
    {foreach $formdata['source'] as $k => $v}
      <option value="{$k}">{$v}</option>
    {/foreach}
   </select><br/><br/>
   <input type="submit" class="button_2" value="创建" />
  <input type="hidden" value="insert2collect" name="a" />
  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
  <a class=" button_2" onclick="return hg_ajax_post(this, '添加至集合', 0);" href="./run.php?mid=20&a=add_to_collect&id={$formdata['id']}">返回</a>
  
</form>
</div>