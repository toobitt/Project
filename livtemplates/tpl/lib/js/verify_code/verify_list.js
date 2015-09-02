$(function(){
    $.extend($.geach || ($.geach = {}), {
        data : function(id){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id']
                   }
                   return false;
               }
            });
            return info;
        }
    });
    
    $('.m2o-each').geach({																								
	   	custom_audit : true,
	   	auditCallback : function(event){
	   		var status_text = ['待审核','已审核','已打回'],
	   			status_color = ['#8ea8c8','#17b202','#f8a6a6'];
	   		var self = $(event.currentTarget),
	   			id = self.data('id'),
	   			_this = this,
	   			item = self.find('.m2o-audit'),
	   			status = item.attr('_status'),
	   			url = './run.php?mid=' + gMid + '&a=audit&ajax=1&id=' + id + '&audit=' + (status == 1 ? 0 : 1);
    		$.globalAjax( item , function(){
        		return $.getJSON( url,function( json ){
						if(json['callback']){
							eval( json['callback'] );
							return;
						}else{
							item.text( json[0].audit ).attr('_status' , json[0].status ).css('color' , status_color[ json[0].status ] );
							initswitch();														/*因为打回状态下不能设置默认，审核后要重新实例化开关按钮*/
						}
					});
			});
	    },
    });
	$('.m2o-list').glist();
	initswitch();
	
	function initswitch(){
		$('.common-switch').each(function(){
			var $this = $(this),
				obj = $this.parent();
			var id = $this.closest('.m2o-each').data('id'),
				status = $this.closest('.m2o-each').find('.m2o-audit').attr('_status'),
				tname = 'common-switch-on';
			$this.hasClass( tname ) ? val = 100 : val = 0;
			$this.hg_switch({
				'value' : val,
				'callback' : function( event, value ){
					if(!$this.hasClass( tname )){
						var bool = judgeBefore( $this );
						if(bool){
							tip( obj , '开启其它样式则自动关闭' );
							$this.addClass( tname );
							$this.parent().attr('_status', 1);
							$this.find('.ui-slider-handle').css({'left': '100%'});
							return false;
						}
					}
					if( status == 1){																		/*如果重新实例化开关，status取值不准确*/
						onOff(id, obj);
					}else{
						tip( obj , '先审核' );																/*打回状态下不能设置默认；需先审核*/
						$this.removeClass('common-switch-on');
						$this.find('.ui-slider-handle').css({'left': '0%'});
					}
				}
			});
		});
	};
	
	function judgeAfter( obj, status ){
		var	box = obj.closest('.m2o-each'),
			other = box.siblings().find('.common-switch'),
			tname = 'common-switch-on';
			other.removeClass('common-switch-on').end().find('.ui-slider-handle').css({'left': '0%'});
	};
	
	function judgeBefore( obj ){
		var	box = obj.closest('.m2o-each'),
			other = box.siblings().find('.common-switch'),
			tname = 'common-switch-on';
		var noon = true;
		$.each(other, function(){
			if($(this).hasClass( tname )){
				var noon = false;
			}
		});
		return noon;
	};
	
	function onOff(id, obj){
		var url = './run.php?mid=' + gMid + '&a=set_default';
		$.getJSON( url, {id : id } ,function( data ){
			var data = data[0],
				status = data['switch'];
				obj.attr('_status', status);	
				judgeAfter( obj, status );	
		});
	};
	
	function tip( self , str ){
		self.myTip({
			string : str,
			delay: 2000,
			dtop : 5,
			dleft : -180,
			width : 150
		});
	};
});