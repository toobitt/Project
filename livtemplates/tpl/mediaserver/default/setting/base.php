<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">源视频存放目录：</span>
			<input type="text" value="{$settings['define']['UPLOAD_DIR']}" name='define[UPLOAD_DIR]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">源视频目录域名：</span>
			<input type="text" value="{$settings['define']['SOURCE_VIDEO_DOMIAN']}" name='define[SOURCE_VIDEO_DOMIAN]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div pre-vod">
			<span  class="title">目标视频目录：</span>
			<input type="text" value="{$settings['define']['TARGET_DIR']}" name='define[TARGET_DIR]' style="width:200px;">
			<!-- <font class="important" style="color:red">对应的vod目录索引是：{$settings['dir_index']}</font> -->
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">目标视频域名：</span>
			<input type="text" value="{$settings['define']['TARGET_VIDEO_DOMAIN']}" 		name='define[TARGET_VIDEO_DOMAIN]' 		style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">视频类型：</span>
			<input type="text" value="{$settings['base']['video_type']['allow_type']}" 	name='base[video_type][allow_type]' 	style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">启用ts命令：</span>
			<input type="text" value="{$settings['define']['FFMPED2TS_CMD']}" 	name='define[FFMPED2TS_CMD]' 	style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">ts片段长度：</span>
			<input type="text" value="{$settings['define']['TS_DURATION']}" 	name='define[TS_DURATION]' 	style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">大视频存放目录：</span>
			<input type="text" value="{$settings['define']['FTP_UPLOAD_DIR']}" 	name='define[FTP_UPLOAD_DIR]'  style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">提取视频目录：</span>
			<input type="text" value="{$settings['define']['PICK_UP_DIR']}" 	name='define[PICK_UP_DIR]'  style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">水印默认位置：</span>
			<input type="text" value="{$settings['define']['WATER_POS']}"  name='define[WATER_POS]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">多码转码服务器：</span>
			<input type="text" value="{$settings['define']['MORE_BITRATE_SERVER']}"  name='define[MORE_BITRATE_SERVER]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">强制转码服务器：</span>
			<input type="text" value="{$settings['define']['MANDATORY_SERVER']}"  name='define[MANDATORY_SERVER]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">是否生成ismv：</span>
			<input type="radio" name="define[NOT_CREATE_ISMV]" value="0" {if !$settings['define']['NOT_CREATE_ISMV']}checked="checked" {/if} />是
			<input type="radio" name="define[NOT_CREATE_ISMV]" value="1" {if $settings['define']['NOT_CREATE_ISMV']}checked="checked" {/if} />否
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">截图的时间位置：</span>
			<input type="text" value="{$settings['define']['SNAP_PIC_POS']}"  name='define[SNAP_PIC_POS]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div fengge">
			<span>视频可修改的头信息配置</span>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">专辑：</span>
			<input type="text" value="{$settings['base']['metadata']['album']}"  name='base[metadata][album]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">艺术家：</span>
			<input type="text" value="{$settings['base']['metadata']['artist']}"  name='base[metadata][artist]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">描述：</span>
			<input type="text" value="{$settings['base']['metadata']['comment']}"  name='base[metadata][comment]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">作曲家：</span>
			<input type="text" value="{$settings['base']['metadata']['composer']}"  name='base[metadata][composer]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">版权：</span>
			<input type="text" value="{$settings['base']['metadata']['copyright']}"  name='base[metadata][copyright]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">创建时间：</span>
			<input type="text" value="{$settings['base']['metadata']['creation_time']}"  name='base[metadata][creation_time]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">流派：</span>
			<input type="text" value="{$settings['base']['metadata']['genre']}"  name='base[metadata][genre]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">标题：</span>
			<input type="text" value="{$settings['base']['metadata']['title']}"  name='base[metadata][title]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
</ul>
<div class="pre-catalog">
	<em class="pre-arrow">$nbsp;</em>
	<label>去<span class="pre-hostname">localhost</span>的 nginx 中增加如下配置：</label>
	<div class="vod-item">
	{code}
		$vod_domain = $settings['define']['TARGET_VIDEO_DOMAIN'];
		$search = '^http\://';
		if( !ereg( $search, $vod_domain ) ){
			$vod_domain = 'http://'.$vod_domain;
		}
		$i=$settings['dir_index'];
		!$i && $i = '';
	{/code}
  <pre>
  location /vod{$i} / 
  {
      proxy_pass {$vod_domain};
      proxy_redirect  off;
   }
   </pre>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('.pre-catalog').on({
			_init : function(){
				var hostname = location.host;
				$(this).find('.pre-hostname').html( hostname );
				$(this).triggerHandler('_auto');
			},
			_auto : function( event ){
				var $this = $(this);
				var off_height = $('.pre-vod').offset().top,
					dom_height = $('.pre-vod').height();
				setTimeout(function(){
					var height = $this.height(),
						self_top = off_height - dom_height - height/2 - 10,
						arr_top = height/2 - 5;
					if( self_top < 10 ){
						self_top = 10;
						arr_top = off_height - dom_height - 25;
					}
					$this.css({
						'height': height,
						'top' : self_top
					});
					$this.find('.pre-arrow').css('top' , arr_top );
				}, 0);
			}
		}).triggerHandler('_init');
	});
</script>