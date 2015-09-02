<style>
.bg-box{width:300px;height:285px;background:#fff;position: absolute;top:-400px;left:50%;border: solid 8px #659efd;transition: all .5s;-webkit-transition: all .5s;z-index: 99999;}
.bg-box .bg-title{width: 100%;height: 34px;background: #659efd;}
.bg-box .bg-title span{font-size: 18px;color: #fff;}
.bg-box .bg-title .pop-save-button{position: absolute;top: 0px;right: 32px;}
.bg-box .bg-title .pop-close-button2{float: right;}
.bg-box .bg-pic{width: 100%;height: 235px;overflow-y: scroll;}
.bg-box .bg-pic li{width: 120px;margin: 10px;float:left;cursor:pointer;border:1px solid white;position:relative;}
.bg-box .bg-pic img{width:120px;height:30px;}
.bg-box .bg-pic span{display: block;width: 80px;text-align: center;overflow: hidden;margin: auto;white-space: nowrap;text-overflow: ellipsis;}
.bg-box .bg-pic .select-pic{width:20px; height:20px; position: absolute; bottom: -5px;right: -5px; display:none;background:url("{$RESOURCE_URL}select-bottom.png") no-repeat;}
.select{border:1px solid #84b542!important;}
</style>

<div class="bg-box">
	<div class="bg-title">
		<span>背景图片库</span>
		<input type="button" value="确定" class="pop-save-button">
		<input type="button" class="pop-close-button2">		  
	</div>
	<ul class="bg-pic">
		{foreach $bg_picture as $k => $v}
		<li class="bg-list" data-id="{$v['id']}" id="{$v['id']}" data-url="{$v['dir']}" _name="{$v['name']}" _type="{$v['type']}">
			<img src="{$v['dir']}" />
			<span title="{$v['name']}">{$v['name']}</span>
			<p class="select-pic"></p>
		</li>
		{/foreach}
	</ul>
</div>