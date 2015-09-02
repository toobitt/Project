$(function(){
	(function($){
		$.fn.hg_columnautocomplete = function( option ){
			return this.each( function(){
				var $this = $(this),
					defaultOption = { url : './run.php?mid=' + gMid + '&a=get_column_by_name', param : 'column_name' },
					options = $.extend( defaultOption, option );
		
		        var cache = {
		            _cache : {},
		            get : function(key){
		                return this._cache[key];
		            },
		            set : function(key, val){
		                key && (this._cache[key] = val);
		            }
		        };
		
		        var autoClass = $.fn.hg_columnautocomplete.autoClass = $.fn.hg_columnautocomplete.autoClass || (function(){
		            function _autoClass($dom){
		                this.$dom = $dom;
		                this.init();
		            }
		
		            $.extend(_autoClass.prototype, {
		                init : function(){
		                    this.$dom.autocomplete({source : []});
		                },
		                callback : function(value, members){
		                    this.$dom.autocomplete('option', 'source', members);
		                    this.$dom.autocomplete('search' , value);
		                }
		            });
		
		            return _autoClass;
		        })();
		        var autoComplete = new autoClass($this);
		
		        $this.on('keyup', function( event ){
		            if(event.keyCode >= 37 && event.keyCode <= 40){
		                return;
		            }
		            var $this = $(this);
		            var timer = $this.data('timer');
		            timer && clearTimeout(timer);
		            $this.data('timer', setTimeout(function(){
		                var	value = $.trim($this.val());
		                if(value){
		                    var members = cache.get(value);
		                    if(members){
		                        autoComplete.callback(value, members);
		                    }else{
		                        var url = options['url'] + '&' + options['param'] + '=' + value;
		                        var hash = +new Date() + '' + Math.ceil(Math.random() * 1000);
		                        $this.data('ajaxhash', hash);
		                        $.getJSON(url ,function(data){
		                        	var data = data[0];
		                            if(hash != $this.data('ajaxhash')) return;
		                            var members = [];
		                            $.each(data, function(key, value){
		                                members.push( { label : value['name'], value : value['id'] });
		                            });
		                            cache.set(value, members);
		                            autoComplete.callback(value, members);
		                        });
		                    }
		                }
		
		            }, 300));
				});
			});
		};
		
		$.fn.columnautocompleteResult = function( option ){
			var defaultOption = { event: 'autocompleteselect', issubmit : true },
				options = $.extend( defaultOption, option  );
			return this.each( function(){
				$(this).hg_columnautocomplete(options);
				$(this).on( options['event'], function( event,ui ){
					$(this).val( ui.item.label );
					$('input[name="column_name"]').val(ui.item.label);
					$('input[name="column_id"]').val(ui.item.value);
					options['issubmit'] && $(this).closest( 'form' ).submit();
				});
			} );
		};
		
		$.widget('publish.publish',{
			options : {
				'html' : 'html',
				'm2o-each' :  '.m2o-each',
				'm2o-add-btn' : '.m2o-add-btn',
				'source-btn' : '.data_source a',
				'publish-list' : '.publish-list',
				'publish-form' : '.publish-form',
				'content-form' : '#content-form',
				'content-input' : '.content-input',
				'indexpic' : '.indexpic',
				'index-pic-file' : '.index-pic-file',
				'outlink-select' : '.outlink-select'
			},
			_create : function(){
				
			},
			_init : function(){
				var op = this.options,
					handlers = {};
				handlers['click ' + op['m2o-each'] ] = '_click';
				handlers['click ' + op['source-btn'] ] = '_toggleSource';
				handlers['click ' + op['indexpic'] ] = '_uploadPic';
				handlers['click ' + op['outlink-select'] ] = '_outLink';
				handlers['change ' + op['index-pic-file'] ] = '_change';
				handlers['submit ' + op['content-form'] ] = '_submitForm';
				this._on(handlers);
				this._initAutocomplete();
			},
			_initAutocomplete : function(){
				this.element.find('.autocomplete').columnautocompleteResult();
			},
			_click : function( event ){
				this._trigger('clickAfter',event,this);
			},
			_toggleSource : function( event ){
				var op = this.options,
					widget = this.element,
					self = $(event.currentTarget),
					id = self.attr('attrid'),
					publish_form = widget.find( op['publish-form'] ),
					publish_list = widget.find( op['publish-list'] ),
					publish_search_area = widget.find('.choice-area'),
					column_box = widget.find('#search_column_name'),
					isonlydefine = self.data('isonlydefine');
				if( id == '1' ){
					publish_list.hide();
					column_box.hide();
					publish_form.show();
					publish_search_area.addClass('define-mode');
				}else{
					publish_list.show();
					column_box.show();
					publish_form.hide();
					publish_search_area.removeClass('define-mode');
				}
				publish_search_area[ isonlydefine ? 'addClass' : 'removeClass' ]('isonlydefine');
				self.data('isonlydefine',false);
			},
			_uploadPic : function(){
				var op = this.options,
					widget = this.element;
				widget.find( op['index-pic-file'] ).click();
			},
			_change : function( event ){
				var op = this.options,
					widget = this.element,
					self = event.currentTarget,
					file = self.files[0],
					imageType = /image.*/;
				if( !file.type.match(imageType) ){
					alert("请上传图片文件");
					return;
				}
				var reader=new FileReader();
				reader.onload=function( e ){
					var imgData=e.target.result;
					var box = widget.find( op['indexpic'] ),
						img = box.find('img');
		            !img[0] && (img = $('<img/>').appendTo(box));
		            img.attr('src', imgData);
				}
				reader.readAsDataURL(file);
			},
			_submitForm : function( event ){
				var _this =  this,
					op = this.options,
					self = $(event.currentTarget);
				var title = $.trim( self.find( op['content-input'] ).val() ),
					src = self.find( 'img' ).attr( 'src' );
				if( !title && !src  ){
					alert(' 标题和图片不能都为空');
					return false;
				}
				var stop = $.globalLoad( self.find('input[type="submit"]') );
				self.ajaxSubmit({
					success : function( data ){
						stop();
						var error = $.parseJSON( data );
						if( error['callback'] )
						{
							eval( error['callback'] );
							return;
						}
						_this.data = data;
						_this._trigger('submitAfter',null,_this);
						_this.clearForm(self);
					}
				});
				return false;
			},
			clearForm : function( form ){
				form.find('input[name="title"]').val('');
				form.find('input[name="outlink"]').val('');
				form.find('input[name="picture"]').val('');
				form.find('img').remove();
				form.find('textarea').val('');
			},
			_outLink : function( ){
				parent.App.trigger( 'openSource', [$.global_module_data] );
			}
		});
	})($);	
});
