{code}
	foreach ($formdata as $k => $v) {
		$$k = $v;
	}
	//print_r( $formdata );
{/code}
{template:head}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/ajaxload_new}
{css:share_frame}
{css:2013/button}

<form id="share_form" method="post" action="run.php?mid={$_INPUT['mid']}&a=share">
	<div class="share-frame">
		<div class="title">
			<span>分享</span>
			<span class="title-label">{$title}:</span>
			<a class="close"></a>
		</div>
		<div class="content">
			<div class="share-list">
				{if is_array($plat_type)}
				
					{foreach $plat_type as $k =>$v}
						{foreach $plat_user[$v['id']] as $user}
							{if !$user['expired']}
							<div class="share-user-item" _type="{$user['plat_type']}" _id="{$user['id']}">
							<label style="background-image:url({code}echo hg_fetchimgurl($v['picurl'], 18, 15);{/code});">
								<input type="checkbox" name="users[]" class="checkbox-user" value=""/>
								<span class="overflow">{$user['name']}</span>
								<span class="mode-list"><span>
							</label>
							<ul class="item-list" data-platId="{$user['platId']}" data-token="{$user['token']}">
								{foreach $user['mode_type'] as $item}
								<li data-id="{$item['fid']}"><a>{$item['name']}</a></li>
								{/foreach}
							</ul>
							</div>
							{/if}
						{/foreach}
					{/foreach}
				
				{/if}
				<div class="add-user-box">
					<div class="share-user-item share-user-item-add">
						<a class="add-label"><span>添加新帐号</span></a>
					</div>
					<!-- 平台列表 -->
					<ul class="plat-list">
						<li style="border-bottom:1px solid #ccc;margin-bottom:4px;">平台列表</li>
						{foreach $plat_type as $k =>$v}
						<li>
							{code}
							$img =  $v['picurl'];
							$img_url = hg_bulid_img($img,40,30);
							{/code}
							{if $img_url}<span class="plat-img"><img src="{$img_url}" /></span>{/if}
							<span class="plat-name">{$v['name']}</span>
							<a href="{$v['url']}" class="plat-user" title="添加用户">+</a>
						</li>
						{/foreach}
					</ul>
					<!-- 平台列表 -->
				</div>
			</div>
		</div>
			<div class="share_bg"></div>
			<div class="info">
			    <input type="text" class="info_text" value="{$title}" name="title">
				<p class="count-tip-mark">还可以输入<em>130</em>字</p>
				<textarea name="content" class="my-content">{$content}</textarea>
				<div style="display:none;" class="admin-con">{$con}</div>
			</div>
			<div class="media">
				{if $formdata['pic']}
				<ul>
					<li class="videos" data-value="{code}echo hg_fetchimgurl($formdata['pic'], 640);{/code}">
						<a>
							<img src="{code}echo hg_fetchimgurl($formdata['pic'], 78, 52);{/code}">
							<span class="play"></span>
						</a>
					</li>
				</ul>
				<input type="hidden" name="pic" id="pic" />
				{/if}
			</div>
			
			<div class="share-area clear">
				<a class="share-button">立即分享</a>
			</div>
		</div>
	</div>
</form>
<div class="plat-frame-box">
	<div class="title">
		<span>添加平台用户</span>
		<a class="close"></a>
	</div>
	<div class="content">
	<iframe src="" id="plat-frame" name="plat-frame" frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true"></iframe>
	</div>
</div>
<script>
$(function() {
	var frame_box = $('.plat-frame-box'),
		plat_frame = frame_box.find( '#plat-frame' );
	function countStr(text) {
		var i, sum = 0, halfExist = false;
		for (i = 0; i < text.length; i++) {
			if ( text.charCodeAt(i) < 128 ) {
				halfExist || sum++;
				halfExist = !halfExist;
			} else {
				sum++;
			}
		}
		return sum;
	}
	
	var shareForm = {
			count: function() {
				var text = this.$('textarea').val();
				var num = countStr(text);
				num = 140 - num;
				this.$('.info .count-tip-mark').html( num >= 0 ? 
				'还可以输入<em>' + num + '</em>个字' :
				'已超出<em class="error">' + -num + '</em>个字' );
			},
			close: function() {
				parent.App && parent.App.trigger('closeShare_box');
			},
			openPlat : function(){
				this.$('.plat-list').slideDown();
				this.$('.share_bg').show();
			},
			closePlat : function(){
				this.$('.plat-list').slideUp();
				this.$('.share_bg').hide();
			},
			addUser : function( event ){
				var self = $( event.currentTarget ),
					url = self.attr('href');
				this.closePlat();
				frame_box.show();
				plat_frame.attr( 'src', url );
				event.preventDefault();
			},
			onload : function(){
				$('<img src="' + RESOURCE_URL + 'loading2.gif" style="position:absolute; left:35px; width:30px;"/>').appendTo( this.$('.share-area') );
			},
			onsave: function() {
				if ( this.saving ) return;
				var _this = this;
				this.saving = true;
				$('#pic').val( $('.media li.selected').data('value') );
				var stop = $.globalLoad();
				this.onload();
				$('#share_form').ajaxSubmit({
					success: function(data) {
						var msg, success, color;
						try { data = JSON.parse(data); 
							!(data[0] && data[0].id) ? 
							(msg = '分享失败', success = false) :
							(msg = '分享成功', success = true);
						} catch(e) {
							(msg = '分享失败', success = false);
						}
						!success && ( color = 'red' );
						$(_this.el).myTip( {
							string : msg,
							color : color,
							dtop : 220,
						} );
						success && _this.close();
						_this.$('.share-area').find('img').remove();
					},
					complete: function() {
						_this.saving = false;
					}
				});
			},

			$: function(s) {
				return this.el.find(s);
			},
		
			init: function(el) {
				this.el = el;
				this.el
				.on('click', '.share-button', $.proxy(this.onsave, this))	
				
				.on('click', '.media li', function() {
					$(this).siblings().removeClass('selected');
					$(this).toggleClass('selected');
				})
				
				.on('mouseenter', '.share-user-item:not(.share-user-item-add)', function(){
					var self = $(this);
					 self.find('ul').show();
				})
				
				.on('mouseleave', '.share-user-item:not(.share-user-item-add)', function(){
					 var self = $(this);
					 self.find('ul').hide();
				})
				
				.on('click', '.share-user-item:not(.share-user-item-add)', function( event ){
					var self = $( event.currentTarget );
						checked = self.find('.checkbox-user').prop('checked');
              		if(checked){
	        	  		var obj = self.find('ul'),
			   	   			type = self.attr('_type');
		   	   			self.addClass('selected');
			   			if(type == 7){
			   				my_content = $('.my-content');
	   			      		my_content.val( $('.admin-con').text() );
			   				obj.show();
					   		self.find('.mode-list').show();
					  		$('.info_text').show();
			   			}else{
			   				var arr0 = {
	                     		 platId : obj.data('platid'),
	                      		 plat_type  :self.attr('_type'),
	                         	 token : obj.data('token'),
	                      		 section_id : self.attr('_id')
	                   		}
			          		var json0 = JSON.stringify(arr0);
			          		self.find('.checkbox-user').attr({'value':json0});
			   			}
		   			}else{
		   				self.removeClass('selected');
		   			}
				})
			
			.on('click', '.item-list li', function(event){
					var self = $(event.currentTarget);
					var box = self.closest('.share-user-item'),
						obj = box.find('.mode-list'),
						item = box.find('.item-list'),
						check = box.find('.checkbox-user');
					obj.text( self.text() ).css('opacity',1);
					item.slideUp();
					var arr = {
	                      	platId : item.data('platid'),
	                      	plat_type  : box.attr('_type'),
	                      	token : item.data('token'),
	                      	section_id : self.attr('data-id')
	                }
			    	var json = JSON.stringify(arr);
					check.attr({'value':json}).prop('checked', true);
			})
					
			.on('click', '.close', $.proxy(this.close, this))
			
			.on('keyup blur', 'textarea', $.proxy(this.count, this))
			.on('click', '.share-user-item-add', $.proxy(this.openPlat, this))
			.on('click', '.share_bg', $.proxy(this.closePlat, this))
			.on('click', '.plat-user', $.proxy(this.addUser, this))
			this.count();
			}
		};
	
	shareForm.init($('#share_form'));
	
	if (parent !== self) {
		parent.$('#add_share').css({
			width: $('.share-frame').outerWidth(),
			height: $('.share-frame').outerHeight()
		}).find('.publish-box').data('publishBox').adjustPosition();
	}

	frame_box.on( 'click', '.close', function(){
		var parent_iframe = parent.$('iframe'),
			src = parent_iframe.attr('src');
		frame_box.hide();
		plat_frame.attr( 'src', '' );
		parent_iframe.attr( 'src', src );
	} );
	
});
</script>
<script type="text/x-jquery-tmpl" id="type-tpl">
      <li data-id="${fid}"><a>${name}</a></li>
</script>
</body>
</html>