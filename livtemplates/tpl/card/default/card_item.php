
   	   {code}
   	    $status_color = $_configs['status_color'];
		$formdata['update_time'] = date("Y-m-d",$formdata['update_time']);
		$time_1 = time();
		$time_2 = $formdata['validtime'];
		if($time_1 >= $time_2)
		{
			$guoqi = "已过期";
		}
		else
		{
			$guoqi = "没过期";
		}
		$formdata['validtime'] = date("m-d H:i",$formdata['validtime']);
		{/code}
   	  <li class="card-item card-page" _id="{$formdata['id']}" order_id="{$formdata['order_id']}">
   	  	<div class="card-item-title">
   	  		<span class="card-name">{$formdata['title']}</span>
   	  		<span class="card-del">删除</span>
   	  	</div>
   	  	<div class="card-item-content">
   	  		<p><span class="card-number">{$formdata['contentnumber']}条</span>内容
   	  		<br>
   	  		<span class="card-time">显示至： {$formdata['validtime']}</span>
   	  		</p>
   	  		<p><span class="card-name">{$formdata['user_name']}</span> 于<span>{$formdata['update_time']}</span></p>
   	  	</div>
   	  	<div class="card-item-set">
   	  		<em></em>
   	  		<span class="card-status" _status="{$formdata['status']}" _id="{$formdata['id']}" style="color:{$status_color[$formdata['status']]}">{$_configs['audit_status'][$formdata['status']]}</span>
   	  		<span style="color:#CCC;">{$guoqi}</span>
   	  		<div class="common-switch  {if $formdata['is_on']}common-switch-on{/if}">
               <div class="switch-item switch-left" data-number="0"></div>
               <div class="switch-slide"></div>
               <div class="switch-item switch-right" data-number="100"></div>
            </div>
   	  	</div>
   	  	<span class="card-checked"></span>
   	  </li>