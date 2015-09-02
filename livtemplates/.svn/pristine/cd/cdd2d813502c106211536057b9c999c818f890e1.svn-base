$(function(){
 (function($){
	var catalogInfo = {
			catalog_box : '' +
					'<div class="form-dioption-item">' +
						'<a class="common-catalog-button overflow" href="javascript:;" _default="设置编目">编目设置</a>' +
					'</div>' +
            		'',
    		catalog_tip : '' +
    				'<div class="pop-catalog">' +
						'<article>' +
							'<div class="pop-add-title">' +
								'<h2>设置编目</h2>' +
								'<div class="pop-menu"><a class="pop-close">关闭</a></div>' +
							'</div>' +
							'<div class="pop-add-box">' +
								'{{each catalogList}}' +
								'<section data-id="${catalog_sort_id}">' +
									'<h4>${catalog_sort_name}</h4>' +
									'<ul class="catalog-list clear">' +
										'{{tmpl($value["arrItem"]) "catalog_Item"}}' +
									'</ul>' +
								'</section>' +
								'{{/each}}' +
								'<input type="hidden" class="catalogdel" name="${catalogdel}" value="" />' +
								'{{if materialdel}}<input type="hidden" class="materialdel" name="${materialdel}" value="" />{{/if}}' +
							'</div>' +
						'</article>' +
					'</div>' +
						'',
          
			catalog_item : '' +
            		'<li id="${catalog_field}" data-type="${type}" class="catalog-item" _id="${catalog_id}" >' +
	            		'<label title="${zh_name}">${zh_name}:</label>' + 
	            		'{{if style}}' +
	            		'<div class="content" data-required="${required}" data-batch="${batch}">' +
	            		'</div>' +
	            			'{{if required==1}}<span>*必填</span>{{/if}}' +
	            		'{{/if}}' +
	            		'<em class="del" title="删除编目选项"></em>' +
	            		'</li>' +
            		'',
	}
	
            	
	$.widget('catalog.create_catalog', {
		options : {
			isShow : false,
			requestInfoUrl : '',
		},
		
		_create : function(){
			this.form = $('form');
			this.globalcontent = [];
			$.template('catalog_Item', catalogInfo.catalog_item);
			$.template('catalog_Info', catalogInfo.catalog_tip);
		},
		
		_init : function(){
			this._on({
				'click .common-catalog-button' : '_getData'
			});
			this._initCatalog();
		},
		
		/*初始化*/
		_initCatalog : function(){
			var _this = this,
				contentid = $("input[name='id']").val();
			$.getJSON( _this.options.requestInfoUrl, {id : contentid}, function( data ){
				if( data ){
					var field = data['field'] ? data['field'] : '',
						catalogdel = data['catalogdel'] ? data['catalogdel'] : '',
						materialdel = data['materialdel'] ? data['materialdel'] : '';
					_this._selectSort( field, catalogdel, materialdel );
				}
			});
		},
		
		/*初始化编目回调*/
		_selectSort : function( data, catalogdel, materialdel ){
			var info = {},
				_this = this,
				arrTist = [],
				arrItem = [],
				widget = this.element;
			if(data != false ){
				$(catalogInfo.catalog_box).appendTo(this.element);
				$.each(data, function( key, value ){
					var arrInfo = _this.getInfo( value.html );
					arrTist.push({
						arrItem : arrInfo,
						catalog_sort_name : value.catalog_sort_name,
						catalog_sort_id : value.catalog_sort_id
					});
					info.catalogList = arrTist;
				});
				info.catalogdel = catalogdel;
				info.materialdel = materialdel;
				$.tmpl('catalog_Info', info).appendTo( this.form );
				$('.pop-catalog').tip_catalog({checkdata : _this.checkboxData, imgbatchData : _this.imgbatchData});
				this._getGlobalInfo();
				this._getImagePic();
			}
		},
		
		_getData : function( event ){
			var item = $(event.currentTarget).closest('.form-dioption-item');
			var tname = 'sort-box-with-show';
			if(item.hasClass(tname)){
				$('.pop-catalog').tip_catalog('close');
			}else{
				item.addClass(tname);
            	$('.pop-catalog').addClass('catalog-show');
			}
		},
		
		clearShow : function(){
			this.element.find('.form-dioption-item').removeClass('sort-box-with-show');
		},
		
		getInfo : function( data ){
			var arrInfo = [];
			var _this = this;
			if( !data ){
				return false;
			}
			$.each(data, function( key, value ){
				_this.globalcontent.push({
					catalog_id : value.catalog_id,
					content : value.style,
					tdata : value.data,
					type : value.type,
					batch : value.batch,
					catalog_field : value.catalog_field
				});
				if( value.type == 'checkbox' ){
					(_this.checkboxData || (_this.checkboxData = [])).push(value);
				}
				if( value.type == 'img' && value.batch == 1 ){
					(_this.imgbatchData || (_this.imgbatchData = [])).push(value);
				}
				arrInfo.push(value);
			});
			return arrInfo;
		},
		
		_getGlobalInfo : function(){
			var content = this.globalcontent;
			this.form.find('.catalog-item').each(function(){
				var tid = $(this).attr('_id'),
					$this = $(this);
				$.each(content, function(key, value){
					if(value.catalog_id == tid){
						if( value.batch == 1){
							if( value.tdata && value.tdata[0] && !$.isArray(value.tdata[0]) ){
								$.each(value.tdata, function(k, v){
									$('<div class="img-batch"/>').appendTo( $this.find('.content') ).html( value.content );
									$this.find('.content').find('input[type="file"]').attr('name', value.catalog_field + '[]');
								});
							}
							$('<div class="img-batch"/>').appendTo( $this.find('.content') ).html( value.content );
							$this.find('.content').find('input[type="file"]').attr('name', value.catalog_field + '[]');
						}else{
							$this.find('.content').html(value.content);
						}
					}
				});
			});
		},
		
		_getImagePic : function(){
			var content = this.globalcontent;
			var _this = this;
			$.each(content, function(key, value){
				if(value.tdata && value.type == 'img'){
					var imageData = value.tdata;
					_this.img = [];
					if( imageData && imageData[0] && !$.isArray(imageData[0]) ){
						$.each(imageData, function(k, v){
							pic = $.globalImgUrl(v, '44x40');
							id = v.id;
							_this.img.push( {pic : pic, id : id} );
						});
						_this._getImage(_this.img, 'img', value.catalog_id, value.batch);
					}
				}
			});
		},
		
		_getImage : function(img, type, id, batch){
			var obj = $('.pop-catalog').find('.catalog-item').filter(function(){
				return ($(this).data('type') == type && $(this).attr('_id') == id);
			});
			var len = obj.find('.img-batch').length;
			if( batch == '1' ){
				obj.find('.img-batch').each(function(k, v){
					if( img[k] ){
						$(this).attr('_id', img[k].id);
						$(this).find('.upload_pic').addClass('no-bgpic').find('img').attr('src', img[k].pic);
						$('<em class="del-img"/>').appendTo( $(this) );
					}
				});
			}else{
				obj.find('.upload_pic').addClass('no-bgpic').find('img').attr('src', img[0].pic);
			}
		},
		
	});
	$.widget('catalog.tip_catalog',{
		options : {
			checkdata : '',
			imgbatchData : ''
		},
		
		_create : function(){
			this.delname = [];
			this.delImg = []
		},
		
		_init : function(){
			var _this = this;
			this._on({
				'click .del' : '_del',
				'click .pop-close' : 'close',
				'click .content .upload_pic' : '_upload',
				'change .content .catalog_avatar' : '_filePic',
				'click .content .del-img' : '_delImg'
			});
			this._initform();
		},
		
		close : function(){
			this.element.removeClass('catalog-show');
			$('#lumin').create_catalog('clearShow');
		},
		
		_del : function( event ){
			var self = $(event.currentTarget);
			var item = self.closest('li'),
				name = item.attr('id');
				widget = this.element;
			var required = item.find('.content').data('required');
			var tname = item.find('label').attr('title');
			if( required ){
				this._tip(self, '不可移除编目' + tname, 80);
				return false;
			}
			if(!item.siblings().length){
				item.closest('section').remove();
			}
			this.delname.push(name);
			this.element.find('.catalogdel').val(this.delname);
			item.remove();
			event.stopPropagation();
		},
		
		_upload : function( event ){
			var self = $(event.currentTarget),
				box = self.closest('.content');
			if(box.find(' .upload_pic ').length > 1){
				box.find('.catalog_avatar').last().click();
			}else{
				self.next().click();
			}
		},
		
		_filePic : function( event ){
			var op = this.options,
				self = event.currentTarget;
				obj = $(self).prev();
			    info = {};
		   var  file=self.files[0];
		        reader=new FileReader();
		   reader.onload=function(e){
				imgData=e.target.result;
			var img = obj.addClass('no-bgpic').find('img');
			!img[0] && (img = $('<img />').appendTo( obj ));
	            img.attr('src', imgData);
	            var box = $(self).closest('.content');
            if(box.data('batch') == 1){
            	$.each(op.imgbatchData, function(k, v){
            		if( v.catalog_id == box.closest('.catalog-item').attr('_id') ){
	            		$('<div class="img-batch"/>').appendTo( box ).html( v.style );
	            		$('<em class="del-img"/>').appendTo( box.find('.img-batch:nth-last-child(2)') );
	            		box.find('input[type="file"]').attr('name', v.catalog_field + '[]');
            		}
            	});
			}
			}  
			reader.readAsDataURL(file);    
		},
		
		_delImg : function( event ){
			var self = $(event.currentTarget),
				id = self.closest('.img-batch').attr('_id');
			this.delImg.push( id );
			this.element.find('.materialdel').val( this.delImg );
			self.closest('.img-batch').remove();
		},
		
		_handlerRequiredData : function( item ){
			var type = item.data('type'), val;
			var str = '';
			switch( type ){
				case 'text': {
					val = item.find('input[type="text"]').val();
					!val && (str = item.find('label').attr('title'));
					return str;
				}
				case 'img' : {
					val = item.find('img').attr('src');
					!val && (str = item.find('label').attr('title'));
					return str;
				}
				case 'radio' : {
					str = this._handlerType( item, type, '单选框' );
					return str;
				}
				case 'option' : {
					val = item.find('select').val();
					!val && (str = item.find('label').attr('title'));
					return str;
				}
				case 'checkbox' : {
					str = this._handlerType( item, type, '多选框' );
					return str;
				}
				case 'textarea' : {
					val = item.find('textarea').val();
					!val && (str = item.find('label').attr('title'));
					return str;
				}
			}
		},
		
		_handlerType : function( item, type, name ){
			var ischecked = false, str;
			item.find('input[type="' + type + '"]').each(function(){
				var check = $(this).prop('checked');
				check && (ischecked = true);
			});
			!ischecked && (str = item.find('label').attr('title'));
			return str;
		},
		
		_tip : function( dom, str, dleft ){
			dom.myTip({
				string : str,
				delay: 2000,
				dtop : 5,
				dleft : dleft,
				padding : 10,
				width : 'auto'
			});
		},
		
		_initform : function(){
			var form = this.element.closest('form');
			var _this = this;
			form.submit(function(){
				var isvain = false;
				var dtip = [];
				var stip = '编目';
				_this.element.find('.catalog-item').each(function(){
					var content = $(this).find('.content');
					if(content.data('required')){
						var str = _this._handlerRequiredData( $(this) );
						if(str){
							dtip.push(str);
							isvain = true;
						}
					}
					if( content.find('input[type="checkbox"]')[0] ){
						var checkobj = content.find('input[type="checkbox"]');
						var i = 0;
						checkobj.each(function(){
							if($(this).prop('checked')){
								i += 1;
							}
						});
						if(i == 0){
							var checkdata = _this.options.checkdata;
							var name = checkobj.eq(0).attr('name');
							$.each(checkdata, function(k, v){
								if( name == v.catalog_field + '[]'){
									$('<input type="hidden" name="' + v.catalog_field + '[]" value=""/>').appendTo( content );
								}
							});
						}
					}
				});
				var ttip = dtip.join(',');
				stip += ttip;
				stip += '还没有填写';
				if(isvain){
					var dom = form.find('input[type="submit"]');
					_this._tip( dom, stip, -180 );
					return false;
				}
			});
		},
	});
 })($);
 $('#lumin').create_catalog({
 	requestInfoUrl : './run.php?mid=' + gMid + '&a=catalog',
 });
});


