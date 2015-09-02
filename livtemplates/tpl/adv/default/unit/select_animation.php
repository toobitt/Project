{if $formdata[-1]}
<style type="text/css">
.big_content{width:520px;height:auto;}
.adv_sort_list{width:100%;height:26px;background:#eeeeee;}
.tbg{background:#C7DEEE;}
</style>
<div id="wuliao_{$_INPUT['id']}" class="big_content" style="border:1px solid #CCCCCC;margin-left:186px;margin-top:-10px;">
  <div id="wuliao_search" class="big_content tbg" style="height:30px;border-bottom:1px solid #CCCCCC;">
   <div style="float:left;margin-left:10px;margin-top:6px;font-weight:bold;color:#3d5f7a;">动画库</div>
   <input type="text" name="search" style="float:left;margin-left:244px;height:15px;margin-top:5px;" id="serach_con" {if $formdata['condition']}value="{$formdata['condition']}"{/if}/>
   <input type="button" onclick="hg_advpos_para_search({$_INPUT['id']})"  value="搜索" style="float:left;margin-left:10px;height:24px;margin-top:4px;padding-top:0px;"/>
  </div>
  <div class="adv_sort_list" style="border-bottom:1px solid #CCCCCC;">
   <div style="width:33px;float:left;height:26px;text-align:center;"></div>
   <div style="width:233px;float:left;border-left:1px solid #CCCCCC;height:26px;"><div style="margin-top:5px;margin-left:5px;font-weight:bold;color:#6a6a6a;">广告效果名称</div></div>
   <div style="width:248px;float:left;border-left:1px solid #CCCCCC;height:26px;"><div style="margin-top:5px;margin-left:5px;font-weight:bold;color:#6a6a6a;">说明</div></div>
  </div>
  {foreach $formdata[-1] as $ani_k=>$ani_v}
  <div  style="width:100%;height:26px;border-bottom:1px solid #CCCCCC;" onclick="hg_advpos_para({$_INPUT['id']},{$ani_v['id']})">
   <div style="width:33px;float:left;height:26px;text-align:center;"><input type="radio"  name="animation_id" value="{$ani_v['id']}" style="margin-top:3px;" /></div>
   <div style="width:233px;float:left;height:26px;"><div style="margin-top:5px;margin-left:6px;">{$ani_v['name']}</div></div>
   <div style="width:248px;float:left;height:26px;"><div style="margin-top:5px;margin-left:6px;">{$ani_v['brief']}</div></div>
  </div>
  {/foreach}
  <div   class="" style="width:100%;height:26px;border-bottom:1px solid #CCCCCC;background:#eeeeee;">
   <div style="margin-left:10px;float:left;height:26px;text-align:center;">
   	 <div style="margin-top:5px;color:#CCCCCC;">{if $formdata['cp'] !=1}<a href="###" onclick="hg_advpos_para({$_INPUT['id']},0,{$formdata['prepage']})">< 上一页</a>{/if}   当前页  {if $formdata['cp'] != $formdata['tp'] && $formdata['nextpage']>0}<a href="###" onclick="hg_advpos_para({$formdata['id']},0,{$formdata['nextpage']})">下一页 ></a>{/if}</div>
   </div>
   <div style="float:right;height:26px;"><div style="margin-top:5px;">第{$formdata['cp']}页(共{$formdata['total']}条记录)</div></div>
  </div>
   <div class="big_content tbg" style="height:30px;">
   		<input type="button" onclick="hg_cancell_select_ani({$formdata['id']})"  value="取消" style="float:left;margin-left:10px;height:24px;margin-top:4px;padding-top:0px;" />
  </div>
</div> 
{/if}













