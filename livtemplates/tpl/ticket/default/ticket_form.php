{template:head}
{js:jquery.multiselect.min}
{js:live/my-ohms}
{css:ad_style}
{css:column_node}
{css:2013/form}
{css:ticked_list}

<script type="text/javascript">
	function hg_addConnectDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title'>时间: </span><input type='text' name='connect_start_time[]' style='width:90px;' class='start-time way-time'>--<input type='text' name='connect_end_time[]' style='width:90px;' class='end-time way-time'>电话：&nbsp;<input type='text' name='connect_tel[]' size='17'/>&nbsp;&nbsp;<span class='option_del_box' style='float:right'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if(confirm('确定删除该联系方式吗？'))
		{
			$(obj).parent().parent().remove();
		}
		hg_resize_nodeFrame();
	}
	$( function(){
		$('.select').each( function(){
			var self = $(this),
				method = self.data('method'),
				oldValue = '',
				input = $('#star_ids');
			self.multiselect({
				checkAllText: '全选',
				uncheckAllText: '取消全选',
				noneSelectedText: self.data( 'title' ),
				selectedList: self.data( 'length' ),
				beforeopen: function () {
					oldValue = input.val();
				},
				open : function(){
					var multiselect = $('.ui-multiselect-menu');
					multiselect.prepend( $( '#search-tmpl' ).tmpl( {method : method} )  );
					multiselect.find( '.search-button' ).on( 'click' , function( event ){
						var self = $(event.currentTarget);
						var box = self.closest( '.ui-multiselect-menu' ),
							k = box.find( '.search-k' ).val(),
							list = box.find( '.ui-multiselect-checkboxes' ),
							method = self.data( 'method' );
						var url = './run.php?mid=' + gMid + '&a=' + method;
						$.get( url , { name : k } ,function( data ){
							 list.html( data );
						} );
					} );
					
				},
				close : function(){
					var newValue = !self.val() ? '' : self.val().join( ',' );
					var multiselect = $('.ui-multiselect-menu');
					multiselect.find( '.search-range' ).remove();
					if ( newValue == oldValue ) return;
					input.val( newValue );
				}
			});
		} );
		
		$('body').on('click', '.new-search-multiselect input', function( event ){
			var self = $(this),
				value = $(this).val();
			$(this).closest('li').toggleClass('selected');
			var selected_item = $('.new-search-multiselect.selected');
			var values = selected_item.map( function(){
				return $(this).data('value');
			} ).get().join();
			var ids = selected_item.map( function(){
				return $(this).data('key');
			} ).get().join();
			setTimeout( function(){
				$('.ui-multiselect').find('span').eq(1).text( values );
				$('#star_ids').val( ids );
			}, 10 )
		})
		
		$('body').on('hover', '.new-search-multiselect', function( event ){
			$(this).find('.ui-corner-all').addClass('ui-state-hover');
		},function(){
			$(this).find('.ui-corner-all').removeClass('ui-state-hover');
		})
		
	} )
	$(function(){
		var ohmsInstance = $('#ohms-instance').ohms();	
		$('.wrap').on({
			'mousedown' : function(){
				var disOffset = {left : 0, top : 0};
				var $this = $(this);
	             ohmsInstance.ohms('option', {
	                time : $this.val(),
	                target : $this
	            }).ohms('show', disOffset);
	            return false;
			},
			 'set' : function(event, hms){
	         	var $this = $(this);
	         	var time = [hms.h, hms.m, hms.s].join(':');
         		var box = $this.parent('.form_ul_div'),
         			bool = $this.is('.start-time'),
         			other = bool ? box.find('input.end-time') : box.find('input.start-time'),
         			otherval = other.val();
     			if( otherval ){
     				if( bool && time >= otherval){
	         			myTip( $this, '开始时间不能大于或等于结束时间' );
	         			return false;
	         		}
	         		if( !bool && time <= otherval ){
	         			myTip( $this, '结束时间不能小于或等于开始时间' );
	         			return false;
	         		}
     			}
         		$this.val(time);
         	}
		}, '.way-time');
	});
	
	function myTip(dom, str, left){
		dom.myTip({
			string : str,
			delay: 2000,
			dtop : 15,
			dleft : left || 130,
			width : 'auto',
			padding: 10
		});
	};
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div id="ohms-instance" style="position:absolute;display:none;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}票务信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">标题：</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:440px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">描述：</span>
								<textarea rows="3" cols="80" name="brief">{$formdata['brief']}</textarea>
							</div>
						</li>
						
						{code}
							$status_css = array(
								'class' =>'transcoding down_list',
								'show' => 'status_item',
								'width' => 120,
								'state' => 0,
								'is_sub' => 1
							);
							$sort = $sort[0];
							$default_val = 0;
							$sort[$default_val] = '选择分类';
							$formdata['sort_id'] = $formdata['sort_id']?$formdata['sort_id']:0;
						{/code}
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">分类：</span>									
								{template:form/search_source,sort_id,$formdata['sort_id'],$sort,$status_css}						
							</div>
						</li>
						{if $formdata['venue_id'] || $a == 'create'}
						
						{code}
							$venue_css = array(
								'class' =>'transcoding down_list',
								'show' => 'venue_item',
								'width' => 120,
								'state' => 0,
								'is_sub' => 1
							);
							$default_val = 0;
							$venue[$default_val] = '选择场馆';
							$formdata['venue_id'] = $formdata['venue_id']?$formdata['venue_id']:0;
						{/code}
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">场馆：</span>									
								{template:form/search_source,venue_id,$formdata['venue_id'],$venue,$venue_css}						
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						{else}
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">场馆：</span>
								<input type="text" value="{$formdata['venue']}" name='venue' style="width:220px;">
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">地址：</span>
								<input type="text" value="{$formdata['address']}" name='address' style="width:220px;">
							</div>
						</li>
						{/if}
						
						<!--{code}
							$column_css = array(
								'class' =>'transcoding down_list',
								'show' => 'column_item',
								'width' => 120,
								'state' => 0,
								'is_sub' => 1
							);
							$default_val = 0;
							$appendColumn = $appendColumn[0]['column'];
							$appendColumn[$default_val] = '选择栏目';
							$formdata['column_id'] = $formdata['column_id']?$formdata['column_id']:0;
						{/code}
						 <li class="i">
							<div class="form_ul_div clear">
								<span class="title">栏目：</span>									
								{template:form/search_source,column_id,$formdata['column_id'],$appendColumn,$column_css}						
							</div>
						</li> -->
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">参演明星：</span>
								<select class="select" multiple="multiple" data-method="get_stars" data-length="{code}echo count($appendStar[0]);{/code}" data-title="选择明星">
								{code} $tmpArray = explode(',', $formdata['star_ids']); {/code}
								{foreach $appendStar[0] as $k => $v}
									<option value="{$k}" {if in_array($k, $tmpArray)}selected="selected"{/if}>{$v}</option>
								{/foreach}
								</select>
								<input type="hidden" name="star_ids" value="{$formdata['star_ids']}" id="star_ids" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title overflow">开始时间：</span>
								<input type="text" class="date-picker" _time="true" name="start_time" value="{$formdata['start_time']}"  style="width:220px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div"><span class="title">结束时间：</span>
								<input type="text" class="date-picker" _time="true" name="end_time" value="{$formdata['end_time']}" style="width:220px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span class="title">时间描述：</span>
								<input type="text" value="{$formdata['show_time']}" name='show_time' style="width:220px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">价格描述：</span>
								<input type="text" value="{$formdata['price_notes']}" name='price_notes' style="width:220px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">取票地址：</span>
								<input type="text" value="{$formdata['ticket_address']}" name='ticket_address' style="width:220px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">总票数：</span>
								<input type="text" value="{$formdata['goods_total']}" name='goods_total' style="width:220px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">剩余票数：</span>
								<input type="text" value="{$formdata['goods_total_left']}" name='goods_total_left' style="width:220px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">购票外链：</span>
								<input type="text" value="{$formdata['outlink']}" name='outlink' style="width:220px;">
							</div>
						</li>
						<li class="i">
							{if $formdata['tel']}
							{foreach $formdata['tel'] as $k=>$v}
							<div class='form_ul_div clear'>
								<span class='title'>时间: </span>
								<input type='text' name='connect_start_time[]' style='width:90px;' class='start-time way-time' value="{$v['start_time']}">--<input type='text' name='connect_end_time[]' style='width:90px;' class='end-time way-time' value="{$v['end_time']}">
								电话：&nbsp;<input type='text' name='connect_tel[]' size='17'  value="{$v['tel']}" />&nbsp;&nbsp;<span class='option_del_box' style='float:right'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>
							{/foreach}
							{/if}
							<div id="extend"></div>
							<div class="form_ul_div clear">
								<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addConnectDom();">添加联系方式</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								{code}
									$index_img = '';
									if(!empty($formdata['index_url']))
									{	
										$pic = $formdata['index_url'];
										$index_img = $pic['host'] . $pic['dir'] .'100x75/'. $pic['filepath'] . $pic['filename'];
									}
								{/code}
								<span class="title">索引图：</span>
								{if $index_img}
										<img src="{$index_img}" alt="索引图" style="height: 100px"/>
								{/if}
								<input type="file" value='' name='Filedata'/>
								<input type="hidden" name="index_id" value="{$formdata['index_id']}" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">座位图：</span>
								{if $formdata['seat_map']}
									<img src="{$formdata['seat_map']}" alt="索引图" style="height: 100px"/>
								{/if}
								<input type="file" value='' name='seat_map'/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">详细介绍:</span>
								<textarea rows="10" cols="8" name="content">{$formdata['content']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">详情外链：</span>
								<input type="text" value="{$formdata['content_link']}" name='content_link' style="width:320px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
<script type="text/x-jquery-tmpl" id="search-tmpl">
<div class="search-range">
<input type="text" class="search-k"  />
<span class="search-button" data-method="${method}"></span>
</div>
</script>
{template:foot}