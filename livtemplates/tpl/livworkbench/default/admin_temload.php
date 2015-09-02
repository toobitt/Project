{template:head}


{code}
//print_r($file);
{/code}
<style>
.menu li{width:100px;height:30px;border:1px solid #b2c8da;float:left;margin:15px 0 0 10px;line-height:30px;text-align:center;background:#eee;border-radius:2px;-webkit-box-shadow:0 0 3px #ccc;-moz-box-shadow:0 0 3px #ccc;-o-box-shadow:0 0 3px #ccc;box-shadow:0 0 3px #ccc;cursor:pointer;}
.menu li em{width: 20px;height: 20px;margin:7px 0 0 5px;background:url("{$RESOURCE_URL}icon.png") no-repeat -60px -183px;display:inline-block;float:left;}
.menu li a{color:#1a4c9a;width: 100%;height: 100%;display: block;}
.select{border: 1px solid #6aa3ea!important;background:#e2eef8!important;}
.bg-pic{background:url("{$RESOURCE_URL}icon.png") no-repeat -78px -183px!important;}
.list .list-id{text-align:left;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu">
		{foreach $file as $k => $v}
			<li><a href="{$v}" target="content"><em></em>{$k}</a></li>
		{/foreach}	
		</ul>
	</div>
</div>
<iframe frameborder="no" scrolling="yes"  src="" name="content" style="width:100%;height:620px"></iframe>
<script>
	(function($){
		$('.menu li').on('click' , function(event){
			var self = $(event.currentTarget);
				self.addClass('select').siblings().removeClass('select');
				self.find('em').addClass('bg-pic').end().siblings().find('em').removeClass('bg-pic');
				self.find('a').addClass('color').end().siblings().find('a').removeClass('color');
		});
		$('.menu li:first').click();			
	})($);
</script>