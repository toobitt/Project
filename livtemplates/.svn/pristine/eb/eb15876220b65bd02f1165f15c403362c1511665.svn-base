<li id="r_{$v['id']}"  name="{$v['id']}"   orderid="{$v['order_id']}">
   <div class="title" onclick="hg_showAddAuth({$v['id']});">{$v['name']}</div>
   <div class="transcode-con-area">
      <div class="transcode-con-top">
			<div class="info">
			     <span class="info-detail">当前/等待：</span>
			     <span class="current-num" id="cur_num_{$v['id']}">{$v['cur_num']}</span>/<span class="wait-num" id="wait_num_{$v['id']}">{$v['waiting_tasks']}</span>
			</div>
			{if $v['tasks_status']}
			<div class="switch-vedio-box" data-id="{$v['id']}"  _src="./run.php?mid=473&server_id={$v['id']}">
					<ul class="transcode-vediolist">
					 {foreach $v['tasks_status'] as $kk => $vv}
					     <li>
			  				<div class="vedio-name overflow">{$vv['id']}</div>
			  				<div class="trans-jdt" style="float:left;margin-top:4px;">
			  					<div style="width:{if $vv['transcode_percent'] < 0 }0{else}{$vv['transcode_percent']}{/if}%;" class="trans-progess"></div>
			  				</div>
			  			</li>
					 {/foreach}
					 </ul>
	  		</div>
	  		{/if}
  		</div>
  		<div class="arrow-controll">
  		     <span class="vedio-prev prevdisabled" id="vedio-prev{$v['id']}"></span>
             <span class="vedio-next" id="vedio-next{$v['id']}"></span>
  		</div>
   </div>
   <div class="common-switch {if $v['is_open']}common-switch-on{/if}">
       <div class="switch-item switch-left" data-number="0"></div>
       <div class="switch-slide"></div>
       <div class="switch-item switch-right" data-number="100"></div>
    </div>
</li>