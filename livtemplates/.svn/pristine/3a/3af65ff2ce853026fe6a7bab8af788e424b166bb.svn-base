{code}
//print_r($list);
{/code}

<li order_id="{$v['order_id']}" _id="{$v['id']}" class="common-list-data clear"  id="r_{$v['id']}" name="{$v['id']}">
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="{$v['id']}" title="{$v['id']}" />
	     </a>
	  </div>
   </div>
   <div class="common-list-right">
		<div class="common-list-item wd50">
		     <span></span>
		</div>
		<div class="common-list-item wd120">
		{if $v['status'] != 'waiting'}
		     <div class="trans-jdt" style="float:left;margin-top:4px;">
			  	  <div style="width:{$v['transcode_percent']}%;" class="trans-progess"></div>
			 </div>
			 <span class="trans-percent">{$v['transcode_percent']}%</span>
		{else}
			等待中...
		{/if}
		</div>
		<div class="common-list-item wd70" style="position:relative;">
		<!-- 等待的任务 没有暂停/恢复 功能 -->
		{if $v['status'] != 'waiting'}
		     <span class="vedio-button pause {if $v['is_task_paused'] == 'true'}continue{else}pended{/if}" _id="{$v['id']}"></span>
		{/if}
		</div>
		<div class="common-list-item wd50">
		     <span class="vedio-button delete" _id="{$v['id']}"></span>
		</div>
		<!--  <div class="common-list-item wd50">
		     <span class="vedio-button speed"  _id="{$v['id']}"></span>
		</div>-->
		
		<div class="common-list-item" style="width:35px;">
		{if $v['status'] == 'waiting'}
		     <span class="take-precedence"  _id="{$v['id']}" title="点击设置优先级">{if $v['waiting_task_weight']}{$v['waiting_task_weight']}{else}0{/if}</span>
		     <div class="precedence-box">
		     	 <div class="arrow"></div>
			     <ul class="precedence-list">
			     	<li _id="{$v['id']}" _weight="0">0</li>
			     	<li _id="{$v['id']}" _weight="1">1</li>
			     	<li _id="{$v['id']}" _weight="2">2</li>
			     	<li _id="{$v['id']}" _weight="3">3</li>
			     	<li _id="{$v['id']}" _weight="4">4</li>
			     	<li _id="{$v['id']}" _weight="5">5</li>
			     </ul>
		     </div>
		{/if}
		</div>
	</div>
   <div class="common-list-biaoti">
	    <div class="common-list-item biaoti-transition">
	      <div class="common-list-overflow max-wd">
				 <span>{$v['title']}</span>
		   </div>
		</div>
   </div>
</li>