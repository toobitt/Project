<div id="livUpload_div" style="height: auto;display:none;">
<div class="set-area" style="border-top:none;">
<div class="set-area-title" data-max="2000M">设置</div>
<ul class="set-area-nav">
	<li class="server_id">转码服务器(<span class="select-item">空闲</span>)</li>
	<li class="water_id">水印(<span class="select-item">无</span>)</li>
	<li class="mosaic_id">马赛克(<span class="select-item">无</span>)</li>
	<li class="vod_config_id">转码配置(<span class="select-item">无</span>)</li>
</ul>
<div id="fastset">
<div class="fast-set">
	<div class="set-item server clear" data-name="server_id">
		<div class="transcode_server">
			<ul>
				{foreach $transcode_server[0]  as $k => $v}
				{if count($v)>2}
				<li  data-id="{$v['id']}" data-name="{$v['name']}" data-set="true" data-type="server" class="{if $v['id'] == -1}select{/if}" title="转码中:{if $v['transcode_on']}{$v['transcode_on']}{else}0个{/if};等待中:{if $v['ranscode_wait']}{$v['ranscode_wait']}{else}0个{/if}">
					<span>{$v['name']}</span>
					<span class="flag"></span>
				</li>	
				{/if}
				{/foreach} 										  
			</ul>
		</div>	
	</div><div class="set-item water-area clear" data-name="water_id">
		<div class="watermark">
			<div class="title">水印列表</div>
			<ul>
				{foreach $water_pic  as $k => $v}
				<li data-id="{$v['id']}"  data-name="{$v['name']}" data-set="true"  data-type="water">
					  <img src="{$v['water_pic']}"/>
					  <span>{$v['name']}</span>
					  <span class="flag"></span>
				</li>	
				{/foreach}
				{if $default_water}
				<li class="select" data-id=""  data-name="{$default_water_name}" data-set="true"  data-type="water">
					  <img src="{$default_water}"/>
					  <span>{$default_water_name}</span>
					  <span class="flag"></span>
				</li>
				{/if}				
			</ul>
		</div>		<div class="water-position set-item" data-name="water_pos">
			<div class="title">水印位置</div>
			<ul>
			<li data-id="0,0" data-name="左上">左上</li>
			<li data-id="1,0" data-name="中上">中上</li>
			<li data-id="2,0" data-name="右上">右上</li>
			<li data-id="0,1" data-name="左中">左中</li>
			<li data-id="1,1" data-name="中中">中中</li>
			<li data-id="2,1" data-name="右中">右中</li>
			<li data-id="0,2" data-name="左下">左下</li>
			<li data-id="1,2" data-name="中下">中下</li>
			<li data-id="2,2" data-name="右下">右下</li>
			</ul>
		</div></div>	<div class="set-item mosaic clear" data-name="mosaic_id">
			<ul>
				{foreach $mosaic  as $k => $v}
				<li  data-id="{$v['id']}" data-name="{$v['name']}"  data-set="true">
					<span>{$v['name']}</span>
					<span class="flag"></span>
				</li>	
				{/foreach} 
			</ul>
	</div>	<div class="set-item vod_config mosaic clear" data-name="vod_config_id">
			<ul>
				{foreach $vod_config[0]  as $key => $val}
				<li  data-id="{$val['id']}" data-name="{$val['name']}"  data-set="true">
					<span>{$val['name']}</span>
					<span class="flag"></span>
				</li>	
				{/foreach} 
			</ul>
	</div>	
		<input type="hidden" class="fast-set-hidden" name="server_id" value="">
		<input type="hidden" class="fast-set-hidden" name="mosaic_id" value="">
		<input type="hidden" class="fast-set-hidden" name="water_id" value="">
		<input type="hidden" class="fast-set-hidden" name="water_pos" value="">
		<input type="hidden" class="fast-set-hidden" name="no_water" value="1">
		<input type="hidden" class="fast-set-hidden" name="vod_config_id" value="">
</div>
</div>
</div>
</div>