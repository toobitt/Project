jQuery(function($){
	var obj = {
		options:{
            ohms : null,
			slider_am : '#slider_am',
			slider_pm : '#slider_pm',
			gTheme:{},
			gStart:{},
			gId:{},
			gProKeys:'',
			gPid:-1,
			program_am_slider:program_am_slider,
			program_pm_slider:program_pm_slider,
			prevKey:0,
			preview_toggle:0,
			resave_toggle:0,
			copy_toggle:0,
			currentKey:0
		},
		_create:function(){
			this._on({
				'click .program-con':'_con_click',
				'click .program-delete' : '_programDelete',
				'focus .theme':'_setIndex',
				'click .theme-label':'_dbEdit',
				'blur .theme':'_verifyChange',
				'blur .item-theme' : '_itemChange',
				'click #clear_all':'_clearAll',
				'click #preview':'_preview',
				'click #resave':'_resave',
				'click #copy' :'_copy',
				'click .model':'_changeModel',
				'click .item-indeximage' : '_addPic',
				'click .item-bottom' : '_addItem',
				'click .del' : '_delItem',
				'click .item-the' : 'editTheme',
			});
			var _this = this;
			$('#program_bg').click(function(){
				_this.prgramBgClick(_this);
				var arrow=$('.theme-arrow');
				_this._hideArrow(arrow);
			});
			this.element.keydown(function(event){  
                if(event.keyCode == 13)
				{
					_this.prgramBgClick(_this);
					var arrow=$('.theme-arrow');
					_this._hideArrow(arrow);
				}
			});
		},
		_init:function()
		{
			var _this = this;
			var tmp_theme = {};
			var tmp_start = {};
			var tmp_id = {};
			this.element.find('div[class="program-li"]').each(function(index,event){
				var keys = $(event).attr('_key');
				tmp_theme[keys] = $(event).find('input[class^=theme]').val();
				tmp_start[keys] = $(event).attr('_start');
				tmp_id[keys] = $(event).attr('_id');
			});

			this._setOption('gTheme',tmp_theme);
			this._setOption('gStart',tmp_start);
			this._setOption('gId',tmp_id);
			//console.log(this.options.gTheme);
			
			this.slider_am = this.element.find(this.options['slider_am']);
			this._initSlider(this.slider_am,0);
			this.slider_am.find('a').hide();
			this.slider_pm = this.element.find(this.options['slider_pm']);
			this._initSlider(this.slider_pm,1);
			this.slider_pm.find('a').hide();
			this.amdata = $.globalamProgram;
			this.pmdata = $.globalpmProgram;
			this._initProgram();
			this._initItem();
			this._uploadIndex();
			this.element.on({
                mousedown : function(){
                    var item = $(this).closest('.m2o-each');
                    var disOffset = {left : 0, top : 0};
                   // console.log(_this.options.ohms);
                    _this.options.ohms.ohms('option', {
                        time : $(this).html(),
                        target : $(this)
                    }).ohms('show', disOffset);
                    return false;
                },
                 set : function(event, hms){
                 	var item = $(this).closest('.m2o-each');
                 	var time = [hms.h, hms.m].join(':');
                 	var noon = item.attr('_noon');
                 	var interval = (noon == '1') ? '下午' : '上午';
                 	if( (noon=='1')&&(hms.h>=12) || (noon=='0')&&(hms.h<12)){
                 		$(this).html(time);
                    	item.attr('_start',time);
                    	_this.ChangeItem( item );
                 	}else{
						jAlert('请设置'+ interval +'的时间！','时间提醒');
						return false;
					}
                }
            }, '.item-t');
            this.element.sortable({
                items : '.m2o-each',
                axis : 'y',
                scroll : true,
                placeholder : 'drop-item-place',
                revert : false,
                zIndex : 10000,
            });
            this._getTemplateDrag();
		},

		_initSlider : function(e,noon){
			var _this = this;
			e.slider({
				orientation: "vertical",
				//animate: "fast",
				value:0,
				min: 0,
				max: 720,
				step: 1,
				noon:noon,
				disabled:false,
				create: function(event, ui) {
					var slider_shadow = '<div class="slider_shadow"></div>';
					$(this).append(slider_shadow);
					var time_dom = '<span class="time_block">' + obj.sliderToTime($(this).slider("option", "value"),$(this).slider("option", "noon")) + '</span>';
					time_dom+='<em></em>';
					$(this).find('a').html(time_dom);
				},
				start:function(event,ui)
				{	
					var noon = $(this).slider("option", "noon");
					if(_this.options.currentKey)
					{
						var _tmp_keys = _this.options.currentKey.split(',');
						var _keys = _tmp_keys[0];
						if(_keys)
						{
							var _parent = $('div[_key="' + _keys + '"]');
							var _self = _parent.find('span[class^=program-con]');
							if(noon == parseInt(_tmp_keys[1]))
							{
							}
							else
							{
								if(!_parent.children('input[class^=theme]').val())//为空
								{
									if(parseInt(_parent.find('span[class^=program-con]').attr('id')))
									{
										jAlert('节目单不为空！','节目单提醒');
										return false;
									}
									else
									{
										_parent.css({'z-index':10});
										$("#program_bg").hide();
									}
								}
								else//不为空
								{
									_this._saveSingleProgram(_this,_self,_parent);
								}
							//	return false;
							}
						}						
					}
					if(noon)
					{
						_this.slider_am.find('a').hide();
						_this.slider_pm.find('a').show();
					}
					else
					{
						_this.slider_am.find('a').show();
						_this.slider_pm.find('a').hide();						
					}				
				},
				click: function( event ) {
				},
				stop:function(event,ui){
					//console.log(_this.options.currentKey);
				},
				slide: function(event, ui) {
					if($(this).slider("option", "noon"))
					{
						$(".program-pm .time_block").html(_this.sliderToTime(ui.value,$(this).slider("option", "noon")));
					}
					else
					{
						$(".program-am .time_block").html(_this.sliderToTime(ui.value,$(this).slider("option", "noon")));
					}
					
					if(_this.options.prevKey && _this.options.prevKey != _this.options.currentKey)
					{
						$('div[_key="' + _this.options.prevKey.split(',')[0] + '"]').children('span[class="program-start"]').show();
					}
					if(_this.options.currentKey)
					{
						var _tmp_keys = _this.options.currentKey.split(',');
						var _keys = _tmp_keys[0];
						if(_keys)
						{
							var _self = $('div[_key="' + _keys + '"]');
							var noon = $(this).slider("option", "noon");
							if(noon == parseInt(_tmp_keys[1]))
							{
								if(parseInt(_self.attr('_id')))//更新
								{
									_this.editForm(ui.value,$(this).slider("option", "noon"),_this);
								}
								else//创建移动
								{
									var noon = $(this).slider("option", "noon");
									_this.createForm(ui.value,noon,_this);
								}
							}
							else
							{
								return false;
							}
						}
					}
					else
					{
						_this.createForm(ui.value,$(this).slider("option", "noon"),_this);
					}
				}
			}).on({
				'setSlider' : function(event,data){
					e.slider("option", "value",data.slider);
					e.slider("option", "noon",data.noon);
					//console.log(data.noon);
					//console.log(_this.sliderToTime(data.slider,data.noon));
					if(data.noon)
					{
						$(".program-pm .time_block").html(_this.sliderToTime(data.slider,data.noon));
					}
					else
					{
						$(".program-am .time_block").html(_this.sliderToTime(data.slider,data.noon));
					}
				},
				'mousemove' :function(event){
					if(!$("#program_bg").is(":visible"))
					{
						var position = { x: event.pageX, y: event.pageY };
						var offset = $(this).offset();
						var cha = parseInt(position.y - offset.top);
						var slider_value = cha;
						var this_slider = '#'+$(this).attr('id');
						$(this_slider).css({'border':'1px dashed #ccc'});
						var noon = ('#'+$(this).attr('id')) == _this.options.slider_am ? 0 : 1 ; 
						var other_slider = noon ? _this.options.slider_am : _this.options.slider_pm;
						if($(other_slider + ' .time-move').attr('class'))
						{
							$(other_slider + ' .time-move').hide();
						}
						
						if(cha <= e.slider("option", "min"))
						{
							slider_value = e.slider("option", "min");
						}
						if(cha >= e.slider("option", "max"))
						{
							slider_value = e.slider("option", "max");
						}
						var pos = slider_value;
						slider_value = e.slider("option", "max") -  slider_value;
						if(!$(this_slider + ' .time-move').attr('class'))
						{
							var time_dom = '<span class="time-move">' + obj.sliderToTime(slider_value,noon) + '</span>';
							$(this).find('a').before(time_dom);
						}
						$(this_slider + ' .time-move').css({'top':(pos-14)+'px'});
						$(this_slider + ' .time-move').html(obj.sliderToTime(slider_value,e.slider("option", "noon"))).show();
						$(this_slider + ' a[class^=ui-slider-handle]').css({'top':(pos-11)+'px'});
					}
				},
				'mouseout' :function(event){
					var this_slider = '#'+$(this).attr('id');
					$(this_slider).css({'border':'1px solid white'});
					$(this_slider + ' .time-move').hide();
				}
			});			
		},
		_showArrow:function(obj){
			obj.show();
		},
		_hideArrow:function(obj){
			obj.hide();
		},
		_getSlider:function(_this,noon)
		{
			var tmp_data = {};
			tmp_data.slider = noon ? _this.options.slider_pm : _this.options.slider_am;
			tmp_data.objSlider = noon ? _this.slider_pm : _this.slider_am;
			tmp_data.objOtherSlider = noon ? _this.slider_am : _this.slider_pm;
			return tmp_data;
		},
		_dbEdit:function(event)
		{
			var _self = $(event.target).parent('div[class="program-li"]').find('span[class^=program-con]');
			this._editMode(_self,this);
		},
		_con_click:function(event)
		{
			var _self = $(event.target).parent('span[class^=program-con]');
			var _parent = _self.parent('div[class="program-li"]');
			var noon = parseInt(_parent.attr('_noon'));
			var sliderData = this._getSlider(this,noon);
			sliderData.objSlider.trigger('setSlider',{'slider':parseInt(_parent.attr('_slider')),'noon':noon});
			$(sliderData.slider + ' .time-move').hide();
			this._editMode(_self,this);
		},
		
		/*start by hu */
		_changeModel : function( event ){
			var op = this.options,
				self = $(event.currentTarget);
			if(this.currentTheme == false){
				jAlert('有节目名称为空！','节目单提醒');
				return false;
			}
			if(self.hasClass('active')){
				return;
			}else{
				$('.model').toggleClass('active');
				$('#program_model').add('#program_menu').toggle();
				if(self.html() == '列表模式'){
					$('.m2o-am-list').empty();
					$('.m2o-pm-list').empty();
					if($('.print').length){
						$('.print').hide();
					}
					this._initItem();
				}else{
					var tid = '';
					$('.save_temp').length && (tid = $('.save_temp').data('id'));
					$('.program-li').remove();
					if($('.print').length && tid){
						$('.print').show();
					}
					this._initProgram();
				}
			}
		},
		
		_initProgram : function(){
			var amdata = this.amdata;
			var pmdata = this.pmdata;
			this._ergodic(amdata, 0);
			this._ergodic(pmdata, 1);
		},
		
		_ergodic : function( data, type ){
			var obj = (type == '0') ? '#slider_am' : '#slider_pm';
			if(data.length){
				$.each(data, function(key, value){
					var top = parseInt(value.pos) + 20;
					value.top = top;
					value.noon = (parseInt(value.start) >= 12) ? '1' : '0';
				});
				$( "#add-program-tpl" ).tmpl( data ).insertBefore( obj );
			}
		},
		
		_addPic : function( event ){
			var self = $(event.currentTarget);
			this.box = self;
			$('.image-file').click();
			event.stopPropagation();
		},
		
		_uploadIndex : function(){
			var _this = this;
			var url = "./run.php?mid=" + gMid + "&a=upload_indexpic";
			$('.image-file').ajaxUpload({
				url : url,
				phpkey : 'img',
				before : function(){
					_this.box.addClass('item-index');
				},
				after : function( json ){
					var data = json['data'];
					var src = $.globalImgUrl(data, '40x35');
					_this.box.removeClass('item-index').find('img').attr('src',src);
					var item = _this.box.closest('.m2o-each');
					_this.ChangeItem( item );
				}
			});
		},
		
		_addItem : function( event ){
			var self = $(event.currentTarget);
			var obj = self.prev();
			if(obj.find('.item-nodata')){
				obj.find('.item-nodata').remove()
			}
			var isAm = self.hasClass('add-am') ? 0 : 1;
			var info ={};
			info.key = this.randNum(4);
			info.id = '0';
		    info.is_plan = '0';
		    info.interval = isAm ? "下午" : "上午";
		    info.noon = isAm ? "1" : "0";
		    info.theme = '';
		    info.start = isAm ? "12:00" : "00:00";
		    box = isAm ? ".m2o-pm-list" : '.m2o-am-list';
			$( "#add-item-tpl" ).tmpl(info).appendTo( box );
			var last = obj.find('.m2o-each:last');
			last.find('.item-theme').show();
		},
		
		_initItem : function(){
			var amdata = this.amdata;
			var pmdata = this.pmdata;
			this._judgeData(amdata, 0);
			this._judgeData(pmdata, 1);
		},
		
		_judgeData : function( data , type){
			var box = (type == '0') ? '.m2o-am-list' : '.m2o-pm-list';
			if(data.length){
				$.each(data, function(key, value){
					value.interval = (type == '0') ? '上午' : '下午';
					var index_pic = value.index_pic;
					if(index_pic == 'undefined'){
						value.index_pic ='';
					}
				});
				$( "#add-item-tpl" ).tmpl( data ).appendTo( box );
			}else{
				$( "#add-nodata-tpl" ).tmpl().appendTo( box );
			}
		},
		
		editTheme : function( event ){
			var self = $(event.currentTarget);
			var item = self.closest('.m2o-each');
			self.hide();
			item.find('.item-theme').show().focus();
		},
		
		_itemChange : function(event){
			var self = $(event.currentTarget),
			    item = self.closest('.m2o-each');
			var val = self.val();
			if(val){
				self.hide();
				item.find('.item-the').show().html(val);
			}
			this.ChangeItem( item );
			event.stopPropagation();
			event.preventDefault();
		},
		
		ChangeItem : function(item){
			var	parent = item.parent();
			var noon = item.attr('_noon'),
				start = item.attr('_start'),
				interval = item.find('.item-interval').html();
			var time = start.split(":"),
			 	hour = parseInt(time[0]),
				minutes = parseInt(time[1]),
				htour = (noon == '1') ? hour-12 : hour;
			var top = htour * 60 + minutes + 20,
				slider = this.topToSlider(parseInt(top));
			this.currentTheme = true;
			item.attr('_slider', slider);
			var val = item.find('.item-theme').val();
			if(parent.children('div[_start="' + start + '"]').length > 1)
			{
				jAlert('该时间已经存在节目','时间提醒');
			}else{
				if(val){
					this.Toggleadd(top, noon, item, 0);
				}else{
					this.currentTheme = false;
				}
			}
		},
		
		Toggleadd : function(top, noon, parent, type){
			var amdata = this.amdata,
				pmdata = this.pmdata;
			var info = {},
				isEqual = false,
				isPmual = false,
				key,
				_parent = parent;
			var data = amdata.length ? amdata : [];
			var box = parent.parent();
			data.push('0');
			info.id = _parent.attr('_id');
			info.start = _parent.attr('_start');
			info.is_plan = _parent.attr('_plan');
			info.slider = _parent.attr('_slider');
			key = info.key= _parent.attr('_key');
			info.noon = noon;
			info.top = top;
			info.pos = top-20;
			info.index_pic = _parent.find('img').attr('src');
			info.theme = _parent.find('input[class*=theme]').val();
			var sort = data.pop();
			if(sort == noon){
				this.amdata = this._judgeEqual(data, noon, key, info, type);
			}else{
				this.pmdata = this._judgeEqual(pmdata, noon, key, info, type);
			}
			var len = box.find('.m2o-each').length;
			if(type && (len == '1')){
				$( "#add-nodata-tpl" ).tmpl().appendTo( box );
			}
		},
		
		_judgeEqual : function(data, noon, hash, info, type){
			var isEqual = false;
			var arr = data.length ? data : [];
			$.each(arr, function(key, value){
				if(type && value.key == hash){
					arr.splice(key, 1);
					return false;
				}
				if(value.noon == noon && value.key == hash){
					isEqual = true;
					arr.splice(key, 1, info);
				}
				
			});
			if(!type && isEqual == false){
				arr.push(info);
			}
			return arr;
		},
		
		topToSlider:function(top)
		{
			var slider = Math.round((720-parseInt(top)) + 20);
			return slider;
		},
		
		_delItem : function(event){
			var self = $(event.currentTarget),
				_this = this;
			var item = self.closest('.m2o-each'),
				box = self.closest('.m2o-each-list'),
				key = item.attr('_key'),
				noon = parseInt(item.attr('_noon'))
				id = parseInt(item.attr('_id')),
				val = item.find('.item-the').html();
			var data = [];
			if(id && val)
			{
				jConfirm('是否删除该记录', '删除提醒', function(result){
					if(result){
						_this.Toggleadd(0, noon, item, 1);
						item.remove();
					}
				});
			}else{
				_this.Toggleadd(0, noon, item, 1);
				item.remove();
			}
		},
		
		_getTemplateDrag : function(){
			var _this = this;
			var $program_menu = $('#program_menu'),		//滑动模式
		    	$program_model = $('#program_model'),	//列表模式
		    	$program_choose = $('#program-choose');	//节目库选择
		    $('li', $program_choose).draggable({
		    	helper : "clone",
		    });
		    $program_model.droppable({
		    	accept : "#program-choose > li",
		    	activeClass: "ui-program-default",
		    	hoverClass: "ui-program-hover",
		    	drop : function(event, ui){
		    		var obj = ui.draggable;
	    			_this.templateData(obj, 1);
		    	}
		    });
		     $program_menu.droppable({
		    	accept : "#program-choose > li",
		    	activeClass: "ui-program-default",
		    	hoverClass: "ui-program-hover",
		    	drop : function(event, ui){
		    		var obj = ui.draggable;
		    		_this.templateData(obj, 0);
		    	}
		    });
		},
		
		templateData : function( obj, model ){
			var amdata = this.amdata,
				pmdata = this.pmdata;
			var amdata = amdata.length ? amdata : [],
		    	pmdata = pmdata.length ? pmdata : [];
			var start = obj.find('em').html(),
    			noon = obj.attr('_noon');
    			theme = obj.find('span').html();
    			img = obj.find('img').attr('src');
			var param ={};
			param.key = this.randNum(4);
			param.id = '0';
		    param.is_plan = '0';
		    param.interval = (noon == '1') ? "下午" : "上午";
		    param.noon = noon;
		    param.start = start;
		    param.index_pic = '';
		    param.theme = theme;
		    param.index_pic = img;
		    var time = start.split(":"),
			 	hour = parseInt(time[0]),
				minutes = parseInt(time[1]),
				htour = (noon == '1') ? hour-12 : hour;
			var top = htour * 60 + minutes + 20;
			param.top = top;
			param.pos = top - 20;
			param.slider = this.topToSlider(parseInt(top));
			box = noon>0 ? ".m2o-pm-list" : '.m2o-am-list';
	   	 	model_box = noon>0 ? ".program-pm" : '.program-am';
		    var isAm = this.judgeRepeat(amdata, param, noon, model);
		    var isPm = this.judgeRepeat(pmdata, param, noon, model);
		    if(isAm || isPm){
		    	jAlert('已选择该节目单，请重选！','节目单提醒');
		    	return false;
		    }
		    if(model){
		    	$( box ).find('.item-nodata').remove();
		    	$( "#add-item-tpl" ).tmpl(param).appendTo( box );
		    }else{
		    	$('#add-program-tpl').tmpl(param).appendTo( model_box );
		    }
		    if(noon > 0){
				pmdata.push(param);
			}else{
				amdata.push(param);
			}
			this.amdata = amdata;
			this.pmdata = pmdata;
		},
		
		judgeRepeat : function(data, info, noon){
			var isEqual = false;
			$.each(data, function(key, value){
				if(value.start == info.start){
					isEqual = true;
				}
			});
		    return isEqual;
		},
		
		/*end by hu*/
		_editMode:function(_self,_this)
		{
			if(_this.options.prevKey && _this.options.prevKey != _this.options.currentKey)
			{
				$('div[_key="' + _this.options.prevKey.split(',')[0] + '"]').children('span[class="program-start"]').show();
			}
			var _parent = _self.parent('div[class="program-li"]');
			var arrow=_parent.find('.theme-arrow');
			var noon = parseInt(_parent.attr('_noon'));
			var sliderData = _this._getSlider(_this,noon);
			if(parseInt(_parent.attr('_id')))
			{
				var pos_tmp = _this.sliderToTop(parseInt(_parent.attr('_slider')))-19;
			}
			else
			{
				var pos_tmp = _this.sliderToTop(parseInt(_parent.attr('_slider')))-19;
			}
			$(sliderData.slider + ' a[class^=ui-slider-handle]').css({'top':pos_tmp+'px'});
			_this._showArrow(arrow);
			sliderData.objSlider.find('a').hide();//sliderToTop
			sliderData.objOtherSlider.find('a').hide();
			if(_this.options.currentKey)//保存
			{	
				var _tmp_keys = _this.options.currentKey.split(',');
				var _parent = $('div[_key="' + _tmp_keys[0] + '"]');
				var _self = _parent.find('span[class^=program-con]');
				if(noon != parseInt(_tmp_keys[1]))
				{
					jAlert('有节目未保存！','节目单提醒');
					return false;
				}
				if(!_parent.children('input[class^=theme]').val())
				{
					if(parseInt(_parent.attr('_id')))
					{
						jAlert('节目不能为空！','节目单提醒');
						return false;
					}
					else
					{
						_this.prgramBgClick(_this);
					}					
				}
				_this._saveSingleProgram(_this,_self,_parent);
			}
			else//编辑
			{
				//_self.addClass('edit-selected');
				_parent.children('input[class^=theme]').attr('disabled',false).show();
				_parent.children('span[class^=theme-label]').hide();				
				_parent.children('span[class="program-delete"]').css({'display':'inline-block'});
				_parent.children('span[class="program-start"]').hide();
				_parent.children('span[class="program-con"]').hide();
				
				_parent.css({'z-index':10000});
				var keys = _parent.attr('_key');
				_this._setOption('currentKey',keys + ',' + noon);
				//console.log(_this.options.currentKey);
				sliderData.objSlider.trigger('setSlider',{'slider':parseInt(_parent.attr('_slider')),'noon':noon});
				$("#program_bg").show();
				sliderData.objSlider.find('a').css({'z-index':99999}).show();
				_this.checkChange(_this.options.currentKey,_this);
			}
		},
		
		prgramBgClick:function(_this)
		{
			var _keys = _this.options.currentKey;
			if(_keys)
			{
				var _tmp_keys = _keys.split(',');
				var _parent = $('div[_key="' + _tmp_keys[0] + '"]');
				var _self = _parent.find('span[class^=program-con]');
				if(!_parent.children('input[class^=theme]').val())
				{
					if(parseInt(_parent.attr('_id')))
					{
						jAlert('节目不能为空！','节目单提醒');
						return false;
					}
					else
					{
						var noon = parseInt(_parent.attr('_noon'));
						var sliderData = this._getSlider(this,noon);
						_this._setOption('currentKey',0);
						_parent.remove();
						$("#program_bg").hide();
						sliderData.objSlider.find('a').css({'z-index':0}).hide();
					}
				}
				else
				{
					_this._saveSingleProgram(_this,_self,_parent);
				}			
			}
		},
		_saveSingleProgram:function(_this,_self,_parent)
		{
			if(_parent.parent('div[class^=program-]').children('div[_slider="' + parseInt(_parent.attr('_slider')) + '"]').length > 1)
			{
				jAlert('该时间已经存在节目','节目单提醒');
			}
			else
			{
				var noon = parseInt(_parent.attr('_noon'));
				var top =  parseInt(_parent.css('top'));
				var sliderData = this._getSlider(this,noon);
				_parent.children('input[class^=theme]').attr('disabled','disabled').hide();
				_parent.children('span[class^=theme-label]').html(_parent.children('input[class^=theme]').val()).show();
				_parent.children('span[class="program-start"]').show();
				_parent.children('span[class="program-delete"]').hide();
				_parent.children('span[class="program-con"]').show();
				sliderData.objSlider.trigger('setSlider',{'slider':parseInt(_parent.attr('_slider')),'noon':noon});
				_this.options.prevKey = _this.options.currentKey;
				_this.checkChange(_this.options.currentKey,_this);
				_this._setOption('currentKey',0);				
				_parent.css({'z-index':10});
				$("#program_bg").hide();
				sliderData.objSlider.find('a').css({'z-index':0}).hide();
				_this.Toggleadd(top, noon, _parent, 0);
			}			
		},
		_verifyChange:function(event,ui)
		{
			this.checkChange($(event.target).parent('div[class="program-li"]').attr('_key')+','+$(event.target).parent('div[class="program-li"]').attr('_noon'),this);
			var arrow=$(event.currentTarget).parent().find('.theme-arrow');
			this._hideArrow(arrow);
		},
		
		_programDelete:function(){
			var _this = this;
			//console.log(this.options.currentKey);
			if(this.options.currentKey)
			{
				var _tmp_keys = this.options.currentKey.split(',');
				var _parent = $('div[_key="' + _tmp_keys[0] + '"]');
				var _self = _parent.find('span[class^=program-con]');
				var noon = parseInt(_parent.attr('_noon'));
				var sliderData = this._getSlider(this,noon);
				if(parseInt(_parent.attr('_id')) && _parent.find('input[class^=theme]').val())
				{
					var confirm_this = this;
					jConfirm('是否删除该记录', '删除提醒', function(result){
						if(result){
							confirm_this.options.prevKey = confirm_this.options.currentKey;
							confirm_this.checkChange(confirm_this.options.currentKey,confirm_this,1);
							confirm_this._setOption('currentKey',0);
							_parent.remove();
							$("#program_bg").hide();		
							sliderData.objSlider.find('a').css({'z-index':0}).hide();
							_this.Toggleadd(0, noon, _parent, 1);
						}
					});
				}
				else
				{
					this.options.prevKey = this.options.currentKey;
					this.checkChange(this.options.currentKey,this,1);
					this._setOption('currentKey',0);
					_parent.remove();
					$("#program_bg").hide();	
					sliderData.objSlider.find('a').css({'z-index':0}).hide();
					_this.Toggleadd(0, noon, _parent, 1);
				}	
			}						
		},
		checkChange:function(keys,_this,type)
		{
			if(keys)
			{
				var _tmp_keys =  keys.split(',');
				keys = _tmp_keys[0];
				var _self = $('div[_key="' + keys + '"]');
				var noon = _self.attr('_noon');
				if(noon == parseInt(_tmp_keys[1]))
				{
					var thisTheme = _self.find('input[class^=theme]').val();
					var thisStart = _self.attr('_start');
					if(type)//删除
					{
						if(parseInt(_self.attr('_id')) || parseInt(_self.attr('_id')))
						{
							if(_this.options.gProKeys.indexOf(keys+',') == -1)
							{
								_this.options.gProKeys += keys + ',';
							}
						}
						else
						{
							var reg = eval("/" + keys + ",/ig");
							_this.options.gProKeys = _this.options.gProKeys.replace(reg,'');
						}
						
					}
					else//添加 | 修改 | 删除
					{
						if(!thisTheme)
						{//删除
							//console.log(222222222);
						}
						else
						{// 更新 | 创建
							if(parseInt(_self.attr('_id')))
							{// 更新
								if(thisTheme != _this.options.gTheme[keys] || thisStart != _this.options.gStart[keys])
								{								
									if(_this.options.gProKeys.indexOf(keys+',') == -1)
									{
										_this.options.gProKeys += keys + ',';
									}						
								}
								else
								{
									var reg = eval("/" + keys + ",/ig");
									_this.options.gProKeys = _this.options.gProKeys.replace(reg,'');
								}
							}
							else// 创建
							{
								if(_this.options.gProKeys.indexOf(keys+',') == -1)
								{
									_this.options.gProKeys += keys + ',';
								}
							}
						}
					}
					if(!_this.options.gProKeys)
					{
						// if($("#save_edit").attr('class').indexOf('gray') == -1)
						// {
							// $("#save_edit").addClass('gray').removeClass('blue');
						// }
						hg_taskCompleted(_this.options.gPid);			
					}
					else
					{
						// if($("#save_edit").attr('class').indexOf('gray') > -1)
						// {
							// $("#save_edit").removeClass('gray').addClass('blue');
						// }
						if(_this.options.gPid == -1)
						{
							_this.options.gPid = hg_add2Task({'name':'节目单《' + thisTheme + '》未保存'});
							//console.log(obj.options.gPid);
						}
					}
				}
				else
				{
					jAlert('有节目未保存～','节目单提醒');
					return false;
				}
			}
			
		},
		_setIndex:function(event)
		{
			var _self=$(event.currentTarget);
			var _parent = _self.parent('div[class="program-li"]');
			_parent.css({'z-index':10000});
			$('#program_bg').show();
			var noon = parseInt(_parent.attr('_noon'));
			var sliderData = this._getSlider(this,noon);
			sliderData.objSlider.find('a').css({'z-index':99999}).show()
		},
		editForm:function(slider,noon,_this)
		{
			if(_this.options.currentKey)
			{
			//	console.log( _this.options.currentKey);
				var _tmp_keys =  _this.options.currentKey.split(',');
				var _keys = _tmp_keys[0];
				if(noon == parseInt(_tmp_keys[1]))
				{
					var _self = $('div[_key="' + _keys + '"]');
					_self.css({'top':_this.sliderToTop(slider) + 'px'});
					_self.attr('_slider',slider);
					_self.find('span[class="program-start"]').html(_this.sliderToTime(slider,noon)).hide();
					_self.attr('_start',_this.sliderToTime(slider,noon));
					var sliderData = _this._getSlider(_this,noon);
					$(sliderData.slider + ' .time-move').hide();
					_this.checkChange(_this.options.currentKey,_this);
					$(sliderData.slider + ' a[class^=ui-slider-handle]').css({'top':(720-slider)+'px'});
				}
				else
				{
					jAlert('有未保存的节目！','节目单提醒');
					return false;
				}
			}			
		},
		createForm:function(slider,noon,_this)
		{
			if(!_this.options.currentKey)
			{
				var prev_slider = 0;
				var next_slider = 0;
				var max_slider = 0;
				var min_slider = 0;
				var length = 0;
				var father_dom = '';
				if(noon)
				{
					father_dom = $(_this.options.slider_pm).parent('div[class^=program]');
				}
				else
				{
					father_dom = $(_this.options.slider_am).parent('div[class^=program]');
				}
				$(father_dom).find('div[class="program-li"]').each(function(){
					if(prev_slider)
					{
						if(slider < prev_slider && slider > parseInt($(this).attr('_slider')))
						{
							next_slider = prev_slider;
						}
					}
					if(!length)
					{
						max_slider = $(this).attr('_slider');
						min_slider = $(this).attr('_slider');
					}
					if(max_slider <= parseInt($(this).attr('_slider')))
					{
						max_slider = parseInt($(this).attr('_slider'));
					}
					if(min_slider >= parseInt($(this).attr('_slider')))
					{
						min_slider = parseInt($(this).attr('_slider'));
					}
					prev_slider = $(this).attr('_slider');
					length ++;
				});
				var pos = _this.sliderToTop(slider);
				var _keys = _this.randNum(4);
				var _start = _this.sliderToTime(slider,noon);
				var sliderData = _this._getSlider(_this,noon);
				if(_this.timeTOSlider($(sliderData.slider + ' .time-move').html(),noon) != _this.timeTOSlider(_start,noon))
				{					
					_start = $(sliderData.slider + ' .time-move').html();
					slider = _this.timeTOSlider($(sliderData.slider + ' .time-move').html(),noon);
					//console.log(_this.timeTOSlider(_start,noon));
					pos = _this.sliderToTop(slider);
					$(sliderData.slider + ' .time_block').html(_start);
				}
				pos = pos - 11;
				var html = '<div class="program-li" _start="' + _start + '" _id="0" _plan="0" _slider="' + slider + '" _key="' + _keys + '" _noon="' + noon + '" style="z-index:999;top:' + pos + 'px;"><span class="program-start" style="display: none;">' + _start + '</span><span style="display:none;" class="program-con"><em></em></span><span class="theme-arrow" style="display:block;"></span><input class="theme" type="text" style="display:inline-block;" value=""/><span class="theme-label" style="display:none;"></span><span class="program-delete" style="display: inline">删除</span></div>';
				//console.log(length);
				var item = $('div[_key="' + _keys + '"]');
				if(length)
				{
					if(!next_slider)
					{
						if(next_slider >= max_slider)
						{
							next_slider = max_slider;
						}
						if(next_slider <= min_slider)
						{
							next_slider = min_slider;
						}
					}
					var _single = $(father_dom).children('div[_slider="' + next_slider + '"]');
					$(_single).after(html);
				}
				else
				{
					father_dom.find('div[id^=slider_]').before(html);
				}
				_this.options.currentKey = _keys + ',' + noon;
				sliderData.objSlider.find('a').css({'z-index':99999});
				$('div[_key="' + _keys + '"]').css({'z-index':10000});
				$(sliderData.slider + ' .time-move').hide();
				$('#program_bg').show();
			}
			else
			{
				var _tmp_keys =  _this.options.currentKey.split(',');
				var _keys = _tmp_keys[0];
				if(noon == parseInt(_tmp_keys[1]))
				{
					var _self = $('div[_key="' + _keys + '"]');
					_self.css({'top':_this.sliderToTop(slider) + 'px'});
					_self.attr('_slider',slider);
					_self.find('span[class="program-start"]').html(_this.sliderToTime(slider,noon)).hide();
					_self.attr('_start',_this.sliderToTime(slider,noon));
					var sliderData = _this._getSlider(_this,noon);
					$(sliderData.slider + ' a[class^=ui-slider-handle]').css({'top':(720-slider)+'px'});
				}
				else
				{
					//暂无任何操作
				}			
			}
		},
		_clearAll:function(event)
		{
			var _this = this;
			$('div[class="program-li"]').each(function(index,event){
				_this.options.gProKeys += $(event).attr('_key') + ',';
			}).remove();
			if(!_this.options.gProKeys)
			{
				// if($("#save_edit").attr('class').indexOf('gray') == -1)
				// {
					// $("#save_edit").addClass('gray').removeClass('blue');
				// }
				hg_taskCompleted(_this.options.gPid);			
			}
			else
			{
				// if($("#save_edit").attr('class').indexOf('gray') > -1)
				// {
					// $("#save_edit").removeClass('gray').addClass('blue');
				// }
				if(_this.options.gPid == -1)
				{
					_this.options.gPid = hg_add2Task({'name':'节目单已被清空！'});
					//console.log(obj.options.gPid);
				}
			}
			//$("#save_edit").removeClass('gray').addClass('blue');
		},
		
		_resave : function(){
			if(this.options.resave_toggle)
			{
				$('.template-box').slideUp();
				this.options.resave_toggle = 0;
			}
			else
			{
				$('.template-box').slideDown();
				$('.week-box').slideUp();
				$('.preview').slideUp();
				this.options.resave_toggle = 1;
			}			
		},
		
		_preview:function()
		{

			if(!this.options.preview_toggle)
			{
				$('.preview_li').remove();

				var obj_am = [];
				var obj_pm = [];
				
				$('.program-am').find('div[class="program-li"]').each(function(index,event){
					obj_am[parseInt($(event).attr('_slider'))] = '<li class="preview_li"><span>'+$(event).attr('_start')+'</span>'+$(event).find('span[class^=theme-label]').html()+'</li>';
				});
				$('.program-pm').find('div[class="program-li"]').each(function(index,event){
					obj_pm[parseInt($(event).attr('_slider'))] = '<li class="preview_li"><span>'+$(event).attr('_start')+'</span>'+$(event).find('span[class^=theme-label]').html()+'</li>';
				});
				var html_am ='' ;
				var html_pm = '';
				if(obj_am.length > 0)
				{
					obj_am.sort();
					for(var property in obj_am)
					{
						html_am += obj_am[property];
					}
				}
				if(obj_pm.length > 0)
				{
					obj_pm.sort();
					for(var property in obj_pm)
					{
						html_pm += obj_pm[property];
					}
				}
				
				$('ul[class*=am]').find('li[class="preview_title"]').after(html_am);
				$('ul[class*=pm]').find('li[class="preview_title"]').after(html_pm);
				$('.preview').slideDown();
				$('.template-box').slideUp();
				$('.week-box').slideUp();
				this.options.preview_toggle = 1;
			}
			else
			{
				$('.preview').slideUp();
				this.options.preview_toggle = 0;
			}
		},
		
		_copy:function()
		{
			if(this.options.copy_toggle)
			{
				$('.week-box').slideUp();
				this.options.copy_toggle = 0;
			}
			else
			{
				$('.week-box').slideDown();
				$('.preview').slideUp();
				$('.template-box').slideUp();
				this.options.copy_toggle = 1;
			}			
		},
		
		sliderToTop:function(slider)
		{
			var top = Math.round((720-parseInt(slider)) + 20);
			return top;
		},
		sliderToTime:function(slider,type)
		{
			var tmp_slider;
	    	if(type)
			{
	    		tmp_slider = 1440 - parseInt(slider);
			}
			else
			{
				tmp_slider = 720 - parseInt(slider);
			}
			var hour = tmp_slider/60;
	    	var min = tmp_slider%60;
			hour = hour < 10 ? '0' + parseInt(hour) : parseInt(hour);
			min = min < 10 ? '0' + parseInt(min) : parseInt(min);
		   	return hour + ':' + min;
		},
		timeTOSlider:function(time,type)
		{
			var tmp = time.split(":");
			var hour = parseInt(tmp[0]);
			var min = parseInt(tmp[1]);
			if(type)
			{
				hour = hour-12;
			}
			var slider = 720 - (hour*60 + min);
			return slider;
			//console.log(tmp[1]*60+tmp[0]);
		},
		randNum:function(len)
		{
			var salt = '';
			for (var i = 0; i < len; i++)
			{
				var tmp = parseInt(Math.ceil(Math.random()*10));
				if(!tmp)
				{
					tmp = '2';
				}
				salt += tmp;
			}
			return salt;
		}
	};
	
	/*obj*/
	
	$.widget("channel.program", obj);
	// $(".time_block").val("$" + $("#slider_am").slider("value"));
    
    $(".save").click(function(){
	    var data = '';
	    if($(".wrap").program('option','currentKey'))
	    {
	    	jAlert('有节目未保存！','节目单提醒');
		    return false;
	    }
	    var space = '';
	    if($('#program_menu').css('display') == 'none'){
	    	 var box = ".m2o-each";
	    }else{
	    	 var box = ".program-li";
	     }
	    $(box).each(function(index,event){
			var tmp_data = '{"start":"'+$(event).attr("_start")+'","theme":"' + $(event).find("input[class*=theme]").val() + '","id":"' + $(event).attr("_id") + '","index_pic":"' + $(event).find("div[class*=indeximage] img").attr('src') + '"}';
			data += space + tmp_data;
			space = ',';
		});
		data = '[' + data + ']';
		var url = './run.php?'+'mid=' + gMid + '&a=update_day&html=1';
		var info = {
			data : data, 
			channel_id: parseInt($(this).attr('_channel_id')), 
			dates: today
		}
		$.post(url, info, saveprogram_callback, 'JSON');
		//var url = './run.php?'+'mid=' + gMid + '&a=update_day&html=1&data=' + data + '&channel_id=' + parseInt($(this).attr('_channel_id')) + "&dates=" + today;
		//hg_request_to(url);
    });
    
    $(".sbutton").click(function(){
	    var data = '';
	    var channelid = $(this).attr('_channel_id');
	    //console.log($(".wrap").program('option','currentKey'));
	    if($(".wrap").program('option','currentKey'))
	    {
	    	jAlert('有节目未保存！','节目单提醒');
		    return false;
	    }
	    var template_name = $('.template-box').find('input').val();
	    if(!template_name){
	    	$(this).myTip({
				string : '请输入模板名称',
				delay: 2000,
				dtop : -10,
				dleft : 80,
			});
			return false;
	    }
	    var space = '';
	    if($('#program_menu').css('display') == 'none'){
	    	 var box = ".m2o-each";
	    }else{
	    	 var box = ".program-li";
	     }
	    $(box).each(function(index,event){
			var tmp_data = '{"start":"'+$(event).attr("_start")+'","theme":"' + $(event).find("input[class*=theme]").val() + '","id":"' + $(event).attr("_id") + '","index_pic":"' + $(event).find("div[class*=indeximage] img").attr('src') + '"}';
			data += space + tmp_data;
			space = ',';
		});
		data = '[' + data + ']';
		var url = './run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=update&html=1';
		$.post(url, {id: channelid, data : data, title : template_name}, program_callback, 'JSON');
		//hg_request_to(url, {id: channelid}, '', 'program_callback');
    });
    
    function getCurrentDay(date){
        return (date || '').split('-')[2];
    }

    function getCurrentMonth(month){
        return months[month] + '月';
    }

    $('.common-top-content').on({
        click : function(){
            var prev = !!$(this).hasClass('prev-week-btn');
            var parent = $(this).parent();
            var selfWeek = parent.find('.self-week');
            var imgload = parent.find('.img-load');
            if(imgload.is(':visible')){
                return false;
            }
            selfWeek.css('opacity', 0);
            if(!imgload[0]){
                imgload = $('<img class="img-load" src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>').appendTo(parent);
            }
            imgload.show();

            var channelId = selfWeek.data('channelid');
            var week = prev ? -1 : 1;
            var datesA = selfWeek.find('a');
            var dates = datesA.eq(week == -1 ? 0 : 6).attr('data-date');
            
            var _this = this;
            $.getJSON(
                'run.php?mid=' + gMid + '&a=get_week',
                {
                    week : week,
                    channel_id : channelId,
                    dates : dates
                },
                function(json){
                    json = json[0];
                    var _week = json['week'];
                    var _isSchedule = json['is_schedule'];
                    var _month = getCurrentMonth(parseInt(json['month']) - 1);
                    parent.find('.self-month').html(_month);
                    if(_week){
                        $.each(_week, function(i, n){
                            var dateA = datesA.eq(i).html(getCurrentDay(n));
                          //  dateA.attr('href', "javascript:direct('" + n + "');");
                            dateA.attr('data-date', n);
                            dateA.removeClass('is-set current-index');
                            if(_isSchedule[i] > 0){
                                dateA.addClass('is-set');
                            }
                            if(today == n){
                                dateA.addClass('current-index');
                            }
                        });
                    }
                    imgload.hide();
                    selfWeek.css('opacity', 1);
                }
            );
        }
    }, '.prev-week-btn, .next-week-btn');

    $('.self-week, .self-week-title').each(function(){
        $('a', this).eq(currentIndex).addClass('current-index');
    });
});
function hg_program_day(data)
{
   if(data)
   {
	   	gTasks = {};
   		location.reload();
   }
}

function program2db_callback()
{
	//window.location.reload();
	var url = $("#referto").val() ? $("#referto").val() : "./run.php?a=frame&mid=410&menuid=143";
	window.location.href=url;
}
function saveprogram_callback(){
	gTasks = {};
	var url = $("#referto").val() ? $("#referto").val() : "./run.php?a=frame&mid=410&menuid=143";
	window.location.href=url;
}
 function program_callback()
{	
	gTasks = {};
	// window.location.reload();
//	window.location.href="./run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=show";
	var url = $("#referto").val() ? $("#referto").val() : "./run.php?a=frame&mid=410&menuid=143";
	window.location.href=url;
}
function direct(e){
	if($(e).attr("class") && $(e).attr("class").indexOf('is-set') > -1)
	{
		if(today == $(e).attr('data-date'))
		{
			jAlert('相同的一天无法复制','节目单提醒');
		}
		else
		{
		
			jConfirm('是否把' + $(e).attr('data-date') + '的节目复制到' + today, '节目单提醒', function(result){
				if(result){
					var url = './run.php?mid=' + gMid + '&a=copy_day';
					var info = {
						channel_id : $("#save_edit").attr('_channel_id'),
						dates : $(e).attr('data-date'),
						copy_dates : today
					}
					$.post(url, info, program2db_callback, 'JSON');
				//hg_request_to(url);
				}
			});
		}
	}
	else
	{
		jAlert($(e).attr("data-date") + '暂无节目','节目单提醒');
	}
}
function showdiv() {     
	$('.template-box').slideUp();   
	$('.week-box').slideUp();
	$('.preview').slideUp();    
	$("#bg_upload").show();
	$("#show_upload").show();
	$("#program_bg").show();
}
function hidediv() {
	$("#bg_upload").hide();
	$("#show_upload").hide();
	$("#program_bg").hide();
}
function subform()
{
	if($('#upload_file').val() == '')
	{
		return;
	}
	hg_ajax_submit('upload_form');
}
function hg_program_callback(data)
{
	var data = eval('('+data+')');
//	console.log(data);
	if(data == 'error')
	{
		jConfirm('检测出存在相同日期的节目单!确定覆盖吗?', 'TIXING', function(result){
			if(result){
				var url = 'run.php?'+'mid='+gMid+'&a=program2db';
				if(!$('#channel_id').val())
				{
					return;
				}
				var data = {channel_id:$('#channel_id').val()};
				$.post(url, data, program2db_callback, 'JSON');
				//hg_request_to(url, data,'', 'program2db_callback');
			}
			else
			{
				jAlert('您取消了节目单上传！','节目单提醒');
				return;
			}
		});
	}
	else
	{
		if(data == 'success')
		{
			$("#upload_tips").slideDown();
			setTimeout('program2db_callback()',1000);
		}
	}	
}
