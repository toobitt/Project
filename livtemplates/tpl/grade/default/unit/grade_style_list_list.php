<li class="common-list-head clear">
  <div class="common-list-left">
     <div class="common-paixu common-list-item"><a class="common-list-paixu" {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"></a></div>
  </div>
  <div class="common-list-right">
     <div class="common-list-item fbz overflow common-list-pub-overflow">{$v['name']}</div>
     <div class="common-list-item open-close wd70">{$v['points_system']}</div>
     <div class="common-list-item open-close ">{$v['status']}</div>
     <div class="common-list-item open-close vote-tjr wd120">{$v['user_name']}</br>{$v['create_time']}</div>
  </div>
  <div class="common-list-biaoti ">
	<div class="common-list-item open-close vote-biaoti"><img src="{$v['index_pic']}" width=30 height=30></div>
  </div>
</li>