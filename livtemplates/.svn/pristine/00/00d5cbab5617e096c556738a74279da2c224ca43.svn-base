(function($){
    $.m2oClassName = {
        list : 'm2o-list',
        each : 'm2o-each',
        item : 'm2o-item',
        btn  : 'm2o-ibtn',
        option : 'm2o-option',
        current : 'on'
    };

    $.widget('m2o.glist', {
        options : {
        	selected : '.m2o-each.selected',
        	checkAll : '.checkAll',
        	each : '.m2o-each',
        	delete_url : '',
        	audit_url : '',
        	custom_delete : false,	//如果为真自己处理删除操作
        	custom_audit : false	//如果为真自己处理审核操作
        },

        _create : function(){

        },

        _init : function(){
        	this._on( {
        		'click .checkAll' : '_toggleSelectAll',
        		'click .batch-delete' : '_batchDelete',
        		'click .batch-audit' : '_batchAudit',
        		'click .batch-back' : '_batchBack',
        		'click .batch-handle' : '_batchClick'
        	} );
        },
        
        _toggleSelectAll : function( event ){
        	var widget = this.element,
        		op = this.options,
        		self = $(event.currentTarget),
        		checked = self.prop('checked');
        	widget.find( op['each'] ).geach( 'toggleSelect', checked );
        },
        
        
        /*待整改去掉*/
        _batchDelete : function( event ){
        	this._trigger('batchDelete',event,this);
        },
        
        _batchAudit : function( event ){
        	this._trigger('batchAudit',event,this);
        },

		_batchBack : function( event ){
			this._trigger('batchBack',event,this);
		},
		/*待整改去掉*/
		
		
		
		_batchClick : function( event ){
			var self = $( event.currentTarget );
			this._batchHandle( self );
		},
		
		_batchHandle : function( target ){
			var op = this.options,
				param = {};
			param.targets = this.element.find( op['selected'] );
			param.target = target;
			param.name = target.text();
			param.ids = param.targets.map(function(){
							return $(this).data('id');
						}).get();
			this._confirm( param );
		},
		
		_confirm : function( param ){
			var _this = this,
				ids = param.ids || [],
				name = param.name,
				message = name + '提醒';
			if( !ids.length ){
				jAlert('请选择要' + name + '的数据！', message ).position( param.target );
			}else{
				var tip = '您确定要批量' + name +  '选中记录吗?';
				jConfirm( tip, message, function( result ){
					result && _this._batchajax( param );
				}).position( param.target );
			}
		},
		
		_batchajax : function( param ){
			var op = this.options,
				cmd = '',
				name = param.name;
			param.key = this.options.key;
			param.ids = param.ids.join(',');
			if( name == '删除' ){
				param.url = this.options.delete_url;
				if( op.custom_delete ){
					this._trigger( 'deleteCallback', null, [param] );
					return;
				}
				cmd = 'delajax';
			}
			if( name == '审核'  || name == '打回' ){
				param.url = this.options.audit_url;
				param.items = param.targets.find( '.m2o-audit' );
				param.status = ( name == '审核' ) ? 1 : 0;
				if( op.custom_audit ){
					this._trigger( 'auditCallback', null, [param] );
					return;
				}
				cmd = 'auditajax';
			}
			$.handle.execute( param, cmd );
		},
		
        _destroy : function(){

        }
    });

    $.widget('m2o.geach', {
        options : {
        	checkbox : '.m2o-check',
        	selected : 'selected',
            title : '.m2o-bt',
            tpl : '#m2o-option-tpl',
            needInfoBtn : true,
            audit_url : '',
            delete_url : '',
            custom_delete : false,	//如果为真自己处理删除操作
        	custom_audit : false	//如果为真自己处理审核操作
        },

        _create : function(){
        },

        _init : function(){
	    	this._on( {
	    		'click .m2o-state' : '_audit',
	    		'click .m2o-check' : '_chkClick',
	    		'click .m2o-ibtn' : '_iClick',
	    		'click .m2o-audit' : '_m2oAudit',
	    		'click .m2o-delete' : '_m2oDelete'
	    	} );
	    	this._on(this.element, {
                'click' : '_click',
            });
        },
        
        _audit : function( event ){
        	this._trigger('audit',event,this);
        },
        
        _m2oAudit : function( event ){
        	this._m2oHandle( $(event.currentTarget), 'audit' );
        },
        
        _m2oDelete :function( event ){
        	this._m2oHandle( $(event.currentTarget), 'delete' );
        },
        
        _m2oHandle : function( target, type ){
        	var cmd = '',
        		param = {};
        	param.targets = target.closest( this.element );
        	param.ids = param.targets.data('id');
        	param.key = this.options.key;
        	if( type == 'delete' ){
        		cmd = 'delajax';
        		param.url = this.options.delete_url;
            	if( this.options.custom_delete ){
            		this._trigger( 'deleteCallback', event, [param] );
            		return;
            	}
        	}
        	if( type == 'audit' ){
        		var audit = target.attr('_status'),
        		cmd = 'auditajax';
        		param.status = (audit == 1 ? 0 : 1);
        		param.url = this.options.audit_url;
        		param.items = target;
    			param.self = true;
            	if( this.options.custom_audit ){
            		this._trigger( 'auditCallback', event, [param] );
            		return;
            	}
        	}
        	$.handle.execute( param, cmd );
        },
        
        _chkClick : function( event ){
        	var op = this.options,
        		self = $(event.currentTarget),
        		checked = self.prop( 'checked' );
        	this.toggleSelect( checked );
        },
        
        toggleSelect : function( bool ){
        	var op = this.options;
        	this.element[( bool ? 'add': 'remove') + 'Class' ]( op['selected'] );
        	this.element.find( op['checkbox'] ).prop( 'checked', bool );
        },
        
        _click : function(event){
        	if( this.element.parent().hasClass('gDragMode') ){
        		return;
        	}
        	var target = $(event.target);
        	if( target.is(this.options['title']) ){
        		this.onoff();
        	}
        },
        
        _iClick : function( ){
        	this.onoff();
        },

        onoff : function(){
            var $this = this.element;
            var isOn = $this.hasClass($.m2oClassName.current);
            this[isOn ? '_doOff' : '_doOn']();
        },

        _doOn : function(){
            var m2o = $.m2oClassName;
            var op = this.options,
            	widget = this.element,
            	option = {};
            if(widget.has('.'+ m2o.option)[0] ){
            	option = widget.find('.'+ m2o.option);
            }else{
                var id = this.element.data('id');
                option = $(this.options['tpl']).tmpl($.geach.data(id) || {}).appendTo(this.element);
                option.goption({
                	key : op.key,
                	audit_url : op.audit_url,
                    delete_url : op.delete_url,
                    custom_delete : op.custom_delete,	
                	custom_audit : op.custom_audit,
                	deleteCallback : op.deleteCallback || '',
                	auditCallback : op.auditCallback || '',
                });
            }
            this.show(option);
        },

        _doOff : function(){
            var m2o = $.m2oClassName;
            var widget = this.element,
            	option = widget.find('.' + m2o.option);
            this.hide(option);
        },
        
        show : function(option){
        	var _this = this,
        		m2o = $.m2oClassName;
            this.element.addClass(m2o.current);
            option.goption('openView');
            this.element.siblings().each( function(){
            	var option = $(this).find( '.' + m2o.option )
            	option && option.goption('closeView',true);
            } );
        },
        
        hide : function(option,hide){
        	var m2o = $.m2oClassName;
            this.element.removeClass(m2o.current);
            option.goption('closeView',hide);
            option.goption('reset');
        },

        _destroy : function(){
        }
    });

    $.widget('m2o.goption', {
        options : {
        	delete_url : '',		// 删除接口
        	audit_url : '',			// 审核接口
        	custom_delete : false,
        	custom_audit : false,
            'play-model' : 'play-model',
            'confirm-model' : 'confirm-model',
            'up-model' : 'up-model',
            'play-back' : 'play-back',
            'confirm-back' : 'confirm-back',
            'm2o-close': '.m2o-option-close'
        },

        _create : function(){
        },

        _init : function(){
        	var handlers = {},
        		op = this.options;
            //默认处理
            $.proxy($.geach.option, this)();
            this._trigger('init', null, [this]);
            this._on( {
            	'click .m2o-option-close' : '_close',
            	'click .option-delete' : '_delete',
            	'click .option-audit' : '_audit',
            	'click .option-publish' : '_optionpublish',
            	'click .m2o-publish' : '_publish',
            	'click .m2o-special' : '_special',
            	'click .m2o-block' : '_block',
            	'click .m2o-share' : '_share',
            	'click .confim-sure' : '_deleteSubmit',
            	'click .confim-cancel' : '_deleteCancel'
            } );
        },
        
        _close : function(){
        	var op = this.options,
        		widget = this.element,
        		self = widget.find(op['m2o-close']);
        	if( self.hasClass( op['play-back']) || self.hasClass(op['confirm-back']) ){
        		this.reset();
        		this.adjustLook();
        		return;
        	}
        	this.closeView();
        	return false;
        },
        
        getWH : function(){
        	var widget = this.element,
        		video = widget.find('video');
        	widget.show().css({width:'',height:''});
        	if( video ){
        		var width = video.width(),
        			height = video.height();
        		widget.css({'width': width + 'px', 'height': height + 'px'});
        	}
        	return [widget.width(),widget.height()];
        },
        
        openView : function(){
        	var animate = true;
        	this.adjustLook( animate );
        },
        
        closeView : function(hide){
        	var widget = this.element,
        		op = this.options,
        		m2o = $.m2oClassName,
        		attrs = { width:0,height:0 };
        	var stopfn = function() { widget.removeAttr('style').hide(); };
        	widget.closest( '.'+ m2o.each ).removeClass( m2o.current );
        	hide && widget.hide();
        	if ( widget.hasClass( op['up-model']) ){
        		attrs['top'] = 6;
        	}
        	widget.stop().animate(attrs,200,stopfn);
        		
        },
        
        judgeBox : function(){
        	var widget = this.element,
        		op = this.options;
        		m2o_each = widget.closest( '.' + $.m2oClassName['each'] );
        	var wh = this.getWH(),
	    		h = wh[1],
	    		window_h = $(window).height(),
	    		top = m2o_each.offset().top;
	    	if( h+top  >= window_h ){
	    	    var btnH = parseInt(widget.find( op['m2o-close']).height() );
	    	    top = - h + btnH + 6;
	    	    widget.addClass( op['up-model'] ) ;
	    	}else{
	    		top = '6px';
	    		widget.removeClass( op['up-model'] ) ;
	    	}
	    	return top;
       },
        
        adjustLook : function( animate ){
        	var widget = this.element;
	    	var stopfn = function() { widget.css({ width: '', height: '' }); },
	    		top = this.judgeBox();
	    	var wh = this.getWH(),
	    		w = wh[0],
	    		h = wh[1];
	    	if( animate ){
		    	widget.stop().css({
		    		width : 0,
		    		height : 0
		    	}).animate({ width: w, height: h, top:top }, 200, stopfn);
	    	}else{
	    		widget.css({ top:top });
	    	}
        },
        
        _optionpublish : function(){
        	console.log(1111);
        },
        
        _publish : function(event){
        	this._openPublishBox(event,'openColumn_publish');
        },
        
        _special : function(event){
        	this._openPublishBox(event,'openSpecial_publish');
        },
        
        
        _block : function(event){
        	this._openPublishBox(event,'openBlock_publish');
        },
        
        _share : function( event ){
        	this._openPublishBox(event,'openShare_box');
        	return false;
        },
        
        _openPublishBox : function(event,type){
        	var id = this.element.data('id');
        	App.trigger(type, event, recordCollection.get(id), recordsView.get(id));
        },
        
        _delete : function(event){
        	var _this = this,
        		widget = this.element,
        		op = this.options;
        	var	btn = widget.find( op['m2o-close'] ),
        		self = $(event.currentTarget),
        		id = self.data('id');       	
        	m2o_each = widget.closest( '.' + $.m2oClassName['each'] );
        	widget.addClass( op['confirm-model'] );
	    	var top = this.judgeBox();
	    	widget.css('top', top);
        	btn.addClass( op['confirm-back'] );
        },
        
        _deleteSubmit : function( event ){
        	if( this.options.custom_delete ){
        		this._trigger( 'deleteCallback', event );
        		return;
        	}
        	this._optionHandle( $(event.currentTarget), 'delete' );
        },
        
        _audit : function( event ){
        	this._optionHandle( $(event.currentTarget), 'audit' );
        },
        
        _optionHandle : function( target, type ){
        	var cmd = '',
        		param = {};
        	param.targets = this.element.closest( '.' + $.m2oClassName.each );
        	param.ids = param.targets.data('id');
        	param.key = this.options.key;
        	if( type == 'delete' ){
        		cmd = 'delajax';
        		param.url = this.options.delete_url;
        	}
        	if( type == 'audit' ){
        		var obj = target.closest('.m2o-each').find('.m2o-audit'),
	        		audit = obj.attr('_status'),
	        		cmd = 'auditajax';
	        		param.status = (audit == 1 ? 0 : 1);
	        		param.url = this.options.audit_url;
	        		param.items = obj;
	    			param.self = true;
	    			param.target = target;
            	if( this.options.custom_audit ){
            		this._trigger( 'auditCallback', event, [param] );
            		return;
            	}
        	}
        	$.handle.execute( param, cmd );
        },
        
        _deleteCancel : function(){
        	var op = this.options,
        		widget =this.element;
        	var	btn = widget.find( op['m2o-close'] );
        	widget.removeClass( op['confirm-model'] );
        	btn.removeClass( op['confirm-back'] );
        	var top = this.judgeBox();
    		widget.css('top', top);
        },

        reset : function(){
        	var widget = this.element,
    			op = this.options,
    			btn = widget.find(op['m2o-close']);
        	widget.removeClass( op['play-model'] + ' ' + op['confirm-model'] );
        	btn.removeClass( op['play-back'] + ' ' + op['confirm-back'] );
        	widget.find('video').remove();
        },
        _destroy : function(){
        }
    });

    $.geach = function(){
        var init = false;
        return {
            init : function(){
                if(init) return;
                init = true;
                this.data = $.globalListData;
            },

            data : function(id){
                this.init();
                return this.data[id];
            },

            option : function(){

            }
        };
    }();
    
    $.handle = function(){
     	var status_text = ['待审核','已审核','已打回'],
			status_color = ['#8ea8c8','#17b202','#f8a6a6'];
		return {
			delajax : function( options ){
				var p = options;
				var url = p.url || './run.php?mid=' + gMid + '&a=delete&ajax=1';
				url += '&' + p.key + '=' + p.ids;
				$.globalAjax( p.targets, function(){
        		return $.getJSON( url, function( data ){
						if(data['callback']){
							eval( data['callback'] );
							return;
						}else{
							p.targets.remove();
						}
					});
				});
			},
			auditajax : function( options ){
				var p = options;
				var url = p.url || './run.php?mid=' + gMid + '&a=audit&ajax=1';
				url += '&' + p.key + '=' + p.ids + '&audit=' + p.status;
				$.globalAjax( p.items , function(){
	        		return $.getJSON( url, function( data ){
							if( data['callback'] ){
								eval( data['callback'] );
								return;
							}else{
								if( $.isArray( data ) ){
									var data = data[0],
										status = data.status;
									color = status_color[status];
									text = status_text[status];
									p.items.text(text).attr('_status', status).css({'color':color});
									status == 1 ? $('.option-audit').text('打回') : $('.option-audit').text('审核');
								} else{
									$('body').myTip( {
										string : '操作失败',
										color : 'red',
										dtop : 300
									} );
								}
							}
					});
				});
			},
			execute : function( options, cmd ){
				var option = $.extend( {
					key: 'id',
					ids : '',
					status : '',
					url : '',
					self : false,
					target : null,
					targets : null
				}, options );
				this[cmd]( option );
			}
    	 };
    }();
    
    
})($);