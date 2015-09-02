
{if $value['medias']}

	<input id="rot_{$value['id']}" type="hidden" value="0"/>
	<div id="prev_{$value['id']}" style="display:inline-block;">

	{foreach $value['medias'] as $mk => $mv}
	
		{code}
		$var = array(
			"url" => "",
			"imgname" => "",
			"ori" => "",
			"video_url" => "",
			"video_link" => "",
			"video_img" => "",
			"video_title" => ""
		);
		
		{/code}
		
		
		{if !$mv['type']}
		{code}
			$var['url'] = $mv['small'];
			$var['imgname'] = $mv['larger'];
			$var['ori'] = $mv['ori'];
		{/code}
			{if $var['url']}
			<div style="display:inline-block;*display:inline;">
			<a href="javascript:void(0);" onclick="scaleImg({$value['id']},0)">
				<img class="imgBig" src="{$var['url']}"/>
			</a>
			</div>
			{/if}
		{else}
		{code}
			$var['video_url'] = $mv['url'];
			$var['video_link'] = $mv['link'];
			$var['video_img'] = $mv['img']?$mv['img']:"./res/img/videoplay.gif";
			$var['video_title'] = trim($mv['title'])?$mv['title']:$value['text'];
		{/code}
			{if $var['video_link']&&$var['video_img']&&$var['video_title']}
		    
				<div class="hidden" id="vl_{code} echo $mv['id'] + $value['id'];{/code}">{$var['video_link']}</div>
				<div class="hidden" id="vt_{code} echo $mv['id'] + $value['id'];{/code}">{$var['video_title']}</div>
				<div class="hidden" id="vu_{code} echo $mv['id'] + $value['id'];{/code}">{$var['video_url']}</div>
				<div style="position:relative;display:inline-block;*display:inline;height:auto;">
				<img src="{$var['video_img']}"/>
				<a class="feedvideoplay" href="javascript:void(0);" onclick="scaleVideo({$value['id']},{$mv['id']},{$mv['self']})">
					<img class="pointer" src="./res/img/feedvideoplay.gif"/>
				</a>
				</div>
		   {/if}
		{/if}
	{/foreach}
	</div>
	<div id="disp_{$value['id']}" class="disp">
		<div class="pad_sp">
			<a href="javascript:void(0);" onclick="shlink({$value['id']},0)">收起</a>
			<a target="_blank" href="{$var['ori']}">查看原图</a>
			<a href="javascript:void(0);" onclick="runLeft({$value['id']},0);">左转</a>
			<a href="javascript:void(0);" onclick="runRight({$value['id']},0);">右转</a>
		</div>
		<canvas id="canvas_{$value['id']}" onclick="shlink({$value['id']},0)" class="imgSmall"></canvas>
		<img id="load_{$value['id']}"  onclick="shlink({$value['id']},0)" class="imgSmall" src="{$var['imgname']}"/>
	</div>	
	<div id="v_{$value['id']}" class="hidden" style="text-align:center">		
		
	</div>	
{/if}

{if $transmit_info['text']||!empty($transmit_info['medias'])}

	<div class="comment clear">
	<div class="top"></div>
	<div class="middle clear">
		<p class="subject"><?php echo hg_verify("@".$transmit_info['user']['username'].":".$transmit_info['text'])."<br/>";?>
		</p>

    {if is_array($transmit_info['medias'])}
	
		
		<input id="rot_{code} echo $transmit_info['id'] + $value['id'];{/code}" type="hidden" value="0"/>
		<div id="prev_{code} echo $transmit_info['id'] + $value['id'];{/code}" style="display:inline-block;">
	
		
		{foreach $transmit_info['medias'] as $mk => $mv}
		
			{code}
			$var = array(
				"url" => "",
				"imgname" => "",
				"ori" => "",
				"video_url" => "",
				"video_link" => "",
				"video_img" => "",
				"video_title" => ""
			);
			
			{/code}
			{if !$mv['type']}
			{code}
				$var['url'] = $mv['small'];
				$var['imgname'] = $mv['larger'];
				$var['ori'] = $mv['ori'];
			{/code}
				{if $var['url']}
					<a href="javascript:void(0);" onclick="scaleImg({$value['id']},{$transmit_info['id']})">
						<img class="imgBig" src="{$var['url']}"/>
					</a>
				{/if}
			{else}
				{code}
					$var['video_url'] = $mv['url'];
					$var['video_link'] = $mv['link'];
					$var['video_img'] = $mv['img']?$mv['img']:"./res/img/videoplay.gif";
					$var['video_title'] = trim($mv['title'])?$mv['title']:$value['retweeted_status']['text']; 
				{/code}
				
				{if $var['video_link'] && $var['video_img'] && $var['video_title']}
				
					<div class="hidden" id="vl_{code} echo $mv['id'] + $transmit_info['id'] + $value['id'];{/code}">{$var['video_link']}</div>
					<div class="hidden" id="vt_{code} echo $mv['id'] + $transmit_info['id'] + $value['id'];{/code}">{$var['video_title']}</div>
					<div class="hidden" id="vu_{code} echo $mv['id'] + $transmit_info['id'] + $value['id'];{/code}">{$var['video_url']}</div>
					<div style="position:relative;display:inline-block;*display:inline;">
					<img src="{$var['video_img']}"/>
					<a class="feedvideoplay" href="javascript:void(0);" onclick="scaleVideo({code} echo $transmit_info['id'] + $value['id'];{/code},{$mv['id']},{$mv['self']})">
						<img class="pointer" src="./res/img/feedvideoplay.gif"/>
					</a>
					</div>
		
				{/if}
			{/if}				
		{/foreach}
	</div>
	<div id="disp_{code} echo $transmit_info['id'] + $value['id'];{/code}" class="disp">
		<div class="pad_sp">
			<a href="javascript:void(0);" onclick="shlink({$value['id']},{$transmit_info['id']})">收起</a>
			<a target="_blank" href="{$var['ori']}">查看原图</a>
			<a href="javascript:void(0);" onclick="runLeft({$value['id']},{$transmit_info['id']});">左转</a>
			<a href="javascript:void(0);" onclick="runRight({$value['id']},{$transmit_info['id']});">右转</a>
		</div>
		<canvas id="canvas_{code} echo $transmit_info['id'] + $value['id'];{/code}" onclick="shlink({$value['id']},{$transmit_info['id']})" class="imgSmall"></canvas>
		<img id="load_{code} echo $transmit_info['id'] + $value['id'];{/code}"  onclick="shlink({$value['id']},{$transmit_info['id']})" class="imgSmall" src="{$var['imgname']}"/>
	</div>	
	<div id="v_{code} echo $transmit_info['id'] + $value['id'];{/code}" class="hidden" style="text-align:center">
	</div>	
	{/if}
		<div class="speak">
			<span>
				<a href="<?php echo hg_build_link(SNS_MBLOG.'show.php' , array('id' => $transmit_info['id'])); ?>">{$_lang['original_transmit']}({code} echo $transmit_info['transmit_count'] + $transmit_info['reply_count'];{/code})</a>|
				<a href="<?php echo hg_build_link(SNS_MBLOG.'show.php' , array('id' => $transmit_info['id'])); ?>">{$_lang['original_comment']}(<span>{$transmit_info['comment_count']}</span>)</a>
			</span>
			<div class="clear"></div>
		</div> 
		</div>
	</div>
{/if}