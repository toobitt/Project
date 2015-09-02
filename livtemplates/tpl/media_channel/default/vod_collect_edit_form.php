{template:head}
<div style="width:500px;height:500px;border:1px solid red;">
<form action="./run.php?mid={$_INPUT['mid']}"  method="post" >
    <label>集合标题：</label>
    <input type="text" name="collect_name" value="{$formdata['collect_name']}" /><br/>
    <label>所属类别：</label>
    <select name="sort_name">
      {foreach $formdata['sort'] as $v}
	      {if $v['id'] == $formdata['vod_sort_id']}
	         <option value="{$v['id']}"  selected >{$v['sort_name']}</option>
	      {else}
	         <option value="{$v['id']}">{$v['sort_name']}</option>
	      {/if}
      {/foreach}
    </select><br/>
    <label>来&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;源：</label>
    <select name="source">
      {foreach $formdata['source'] as $k => $v}
	      {if $k == $formdata['source']}
	         <option value="{$k}" selected >{$v}</option>
	      {else}
	         <option value="{$k}">{$v}</option>
	      {/if}
      {/foreach}
    </select><br/>
    <label>描&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;述：</label><br/>
    <textarea name="comment" style="width:235px;height:218px;">
      {$formdata['comment']}
    </textarea><br/><br/>
  <input type="submit" class="button_2" value="{$optext}" />
  <input type="hidden" value="{$a}" name="a" />
  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />

</form>
</div>
{template:foot}


