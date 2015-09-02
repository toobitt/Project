<style type="text/css">
ul.wb-group-auto{display:block;background:#ffffff;padding:10px;}
li.wb-group-auto-li{border-bottom:1px solid #EEEEEE;padding-bottom:5px;display:block;height:auto;}
</style>
<ul class="wb-group-auto">
    <li class="wb-group-auto-li">
          <span style="display:inline-block;width:120px;">平台名称</span>
          <span style="display:inline-block;width:70px;">所属类型</span>
          <span style="display:inline-block;width:80px;">添加授权</span>
          <span style="display:inline-block;width:100px;">选择分类</span>
    </li>
	{if is_array($formdata['plat']) && count($formdata['plat'])>0}
		{foreach $formdata['plat'] as $k => $v}		
			<li id="auth_{$v['id']}"   name="{$v['id']}" class="wb-group-auto-li">
				<span style="display:inline-block;width:120px;">
						{code}
							$log = '';
							if($v['picurl'])
							{
								 $log = $v['picurl']['host'] . $v['picurl']['dir'] .'80x60/'. $v['picurl']['filepath'] . $v['picurl']['filename'];
							}   		
						{/code}
						{if $log}
							<img src="{$log}" width="40" height="30" style="vertical-align:middle;width:40px;height:30px;margin-right:5px;" />
						{else}
						{/if}
							<a id="title_{$v['id']}">{$v['name']}</a>      
				</span>
				<span style="display:inline-block;width:70px;">{$v['type_name']}</span>
				<span style="display:inline-block;width:80px;"><a href="#" style="color:green;" onclick="hg_get_auth({$v['id']},{$v['type']});">点此添加授权</a></span>
				<!-- 				
				<span>
						{if $v['can_access']}{$v['expired_time']}{else}<a href="#" style="color:red;" onclick="hg_request_auth({$v['id']},{$v['type']});">授权已过期,点击此处重新授权</a>{/if}
				</span>
				 -->
				 							
				 <span style="display:inline-block;width:100px;">
				 	<select id="sort_{$v['id']}">
				 		<option value="0">选择分类</option>
				 		{foreach $formdata['sort'] as $key=>$val}
				 		<option value="{$key}">{$val}</option>
				 		{/foreach}
				 	</select>
				 </span>
			</li>
		 {/foreach}
	{else}
		<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;margin:0 10px">没有您要找的内容！</p>
		<script>hg_error_html(vodlist,1);</script>
	{/if}
</ul>