(function($){
    $.widget('point.pian', {
        options : {
            title : '.vod-title',
            kz : '.camera-btn',
            start : '.start',
            save : '.save',
            cancel : '.cancel',
            tip : '.video-pian-tip',
            duration : '.duration',
            yulan : '.yulan',						
            'current-video-id' : 0,
            'current-video-title' : '',
            'ajax-url' : ''
        },

        _create : function(){
            var root = this.element;
            this.title = root.find(this.options['title']);
            this.kz = root.find(this.options['kz']);
            this.start = root.find(this.options['start']);
            this.tip = root.find(this.options['tip']);
            this.duration = root.find(this.options['duration']);
            this.yulan = root.find(this.options['yulan']);			
            this.startInfo = 0;
            this.endInfo = null;
            this.id = 0;
         },

        _init : function(){
            var root = this.element;
            var handler = {};
            handler['click ' + this.options['save']] = '_save';
            handler['click ' + this.options['cancel']] = '_cancel';
            handler['click ' + this.options['kz']] = '_kz';
            handler['click ' + this.options['yulan']] = '_yulan';			
            this._on(handler);
         },

        setKZ : function(imgData){
            this.kz.html('<img src="' + imgData + '"/>');
         },

        setStart : function(time, timeString, imgData){
            this._setSE('start', time, timeString, imgData);
         },

        setEnd : function(time, timeString, imgData){
            this._setSE('end', time, timeString, imgData);
         },

        _setSE : function(type, time, timeString, imgData){
            /*timeString = timeString.split(':');
            timeString.length = 3;
            timeString = timeString.join(':');*/
            this[type + 'Info'] = {
                time : time,
                timeString : timeString,
                imgData : imgData
            };
            var img = imgData ? '<img src="' + imgData + '"/>' : '';
            this[type].html(img + '<span class="time-val">' + timeString + '</span>');
        },

        _save : function(){
            var _this = this;
            _this._addClass();
            if(!_this.title.val() || !_this.startInfo){
                _this._tip('no');
                _this._delay(_this._removeClass, 1000);
                return;
            }
            var url = _this._replace(_this.options['ajax-url']);
            var data = {
                videoid : _this.options['current-video-id'],
                brief : $.trim(_this.title.val()) || _this.options['current-video-title'],
                point : $('#video')[0].currentTime,                             //_this._duration(this.endInfo['time']),			
            };
	            _this._tip('send');
             $.post(url, data,function(json){
                   _this._delay(function(){
                        _this._tip('success');
                    }, 1000);
                _this._delay(function(){
                   _this._removeClass();
                   _this._success(json[0]);
                   _this.empty();
                    }, 1500);
                },
                'json'
            ).success(function(json){	
		      var id = json[0];
			  var info = {},
			      duration = parseFloat( $('#video')[0].duration );
				  info.id = id;
				  info.brief = data.brief;
				  info.point = _this._duration(data.point);  
				  info.time =  data.point;
				  info.precent = ( parseFloat(data.point) / duration )*100 + '%';
		     //   var arr = [];
			      var arr =$('.point-list li').map(function(){
					     return $(this).attr('_time');
                       }).get();
             //     arr.push(time);
           	    console.log(arr);
         /*    for(i=0;i<arr.length;i++){
			      if(data.point-arr[i]<=0){ 
					  //console.log(i);
					 $('#point-tpl').tmpl(info).insertBefore( $('.point-list li').eq(i) );
				//   var obj = $('.point-list').find('li');
				     $('.number').html(arr.length+1);
					 $('#point-show').tmpl(info).appendTo('.video-tips');					
					 return false;
				   }
			  }
			    $('#point-tpl').tmpl(info).appendTo('.point-list');
	          //var obj = $('.point-list').find('li');
				$('.number').html(arr.length+1);
			    $('#point-show').tmpl(info).appendTo('.video-tips'); */
	     	 var need = false;
			 var index = 0;
			 $.each(arr , function(key , value){
				 if(data.point < value){
					 index = key;
					 need = true;
					 return;
				 }
			 });
			 if( need ){
				 $('#point-tpl').tmpl(info).insertBefore( $('.point-list li').eq(index) );	 
			 }else{
				  $('#point-tpl').tmpl(info).appendTo('.point-list');
			 }
			 $('.number').html(arr.length+1);
			 $('#point-show').tmpl(info).appendTo('.video-tips');
            }).error(function(){
                _this._tip('error');
                _this._delay(_this._removeClass, 1000);
            });
        },

        _success : function(data){
            if(data && data[0]){
                if(this.id){
                    this._trigger('editAfter', event, [data[0]]);
                }else{
                    this._trigger('saveAfter', event, [data[0], this.element.offset()]);
                }
            }
        },

        _addClass : function(){
            this.element.addClass('option');
        },

        _removeClass : function(){
            this.element.removeClass('option');
        },

        _cancel : function(){
			
            this.empty();
        },

        _kz : function(){
            this._trigger('kzClick');
        },

        _yulan : function(){

        },
       edit : function(data){
            var imgSrc = data['img_info'] || '';
            imgSrc = imgSrc ? imgSrc.replace('80x60/', '') : '';
            this.id = data['id'];
            this.title.val(data['title']);
            this.setKZ(imgSrc);
            var _this = this;
            this._trigger('setEndAfter', null, [parseInt(data['split_end']) / 1000, function(){
                _this._trigger('setStartAfter', null, parseInt(data['split_start']) / 1000);
            }]);
        },

        empty : function(){
            this.id = 0;
            this.title.val('');
            this.kz.empty();
            this.start.empty();
            this.tip.empty();
            this.duration.empty();
            this.yulan.hide();
            this.startInfo = null;
            this.endInfo = null;
        },

        _tip : function(code){
            var tips = {
                no : '打点时间或者标题没有设置',
                send : '提交中...',
                success : '提交成功',
                error : '发生错误，请重新提交'
            };
            this.tip.html(tips[code]);
        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z]+)}}/ig, function(all, match){
                return data[match];
            });
        },

        _duration : function(duration){
			
            duration = parseInt(duration);
            var h = parseInt(duration / 3600);
            var m = parseInt(duration / 60);
            var s = parseInt(duration % 60);
            return (h > 0 ? h + '时' : '') + ( (h > 0 || m > 0) ? m + '分' : '') + s + '秒';
        }

    });
    
      $.widget('split.dian',{
			options : {
				del : '.delete',	
				update : '.update',	
				'delete-url' : '',
				'update-url' : '',
                },
			_create : function(){
			var root = this.element;
            this.delete = root.find(this.options['del']);
            this.delete = root.find(this.options['update']);
			},
			_init : function(){
				var op = this.options,
				handlers = {};
				handlers['click ' + op['del'] ] = '_delete'; 
				handlers['click ' + op['update'] ] = '_update'; 
				this._on(handlers);  
			},   
			_delete : function(event){
				  
			   var self = $(event.currentTarget);
			   var obj = self.closest('li'); 
			   var _this = this;
               var url = _this._replace(_this.options['delete-url']);
               var data = {
                     id : obj.attr("_id")		 
			        }; 		
            var method = function(){
              $.post(url,data,function(){
					 obj.remove();
			       var idObj=obj.attr("_id");
			       var obj1 = $('.point-list').find('li');	   
			           $('.number').html(obj1.length);
                   var object = $('.video-tips').find('.video-point[_id='+idObj+']');
                       object.remove();
                  }
			    )
			  }
			     this._remind( '是否要删除此条打点?', '删除提醒' , method );
		  },   
		   _remind : function( title , message , method ){
				jConfirm( title, message , function(result){
						if( result ){
						    method();
					          }else{}
					      });
				       },	 
	 
		   _update: function(event){
		         var self = $(event.currentTarget);
				 var obj = self.closest('li');
				 var object = obj.find("input");
				 var _this = this;
				 var url = _this._replace(_this.options['update-url']);
				 var data = {
				         id : obj.attr("_id"),
						 brief : object.val(),
					};   
		      var method = function(){
                  $.post( url,data,function(){}
				        )
			  }
				   this._remind( '是否要修改此条打点?', '修改提醒' , method );
		  },	 
				 
          _replace : function(tpl, data){
                 return tpl.replace(/{{([a-z]+)}}/ig, function(all, match){
                 return data[match];
                });
             },
             
});
    
})(jQuery);