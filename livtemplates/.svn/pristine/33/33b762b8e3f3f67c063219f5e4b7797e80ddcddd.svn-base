$(function($){
	(function($){
		$.widget('market.special_activity',{
			options : {
				'market-edit' : '.market-edit',
				'add-commodity-tpl' : '#add-commodity-tpl',
				'commodity-each' : '.commodity-each',
				'selected' : 'selected',
				'commodity-add' : '.commodity-add',
				'market-init' : 'market-init',
				'zan' : '.zan',
				'del' : '.del',
				'reaudit' : '.reaudit',
				'market-figure' : '.market-figure',
				'image-file' : '.image-file',
				'market-init' : 'market-init',
				'add-pic-tpl' : '#add-pic-tpl',
				'pic-list' : '.pic-list',
				'pic-first' : '.pic-each:first-child',
				'current' : 'current',
				'pic-each' : '.pic-each',
				'commodity-adv' : '.commodity-adv',
				'market-logo' : '.market-logo',
				'back' : '.back',
				'pic-del' : '.pic-del',
				'agree-zan' : 'agree-zan',
				'cover-layer' : '.cover-layer',
				'state_show' : '#state_show li',
				'recomd_show' : '#recomd_show li',
				'market-list' : '.market-list',
				'type-select' : '.type-select',
				'market-type' : '.market-type',
				'sort-box' : '#sort-box',
				'sort_id' : '#sort_id',
				'sort-box-inner' : '.sort-box-inner',
				'sort-label' : '.sort-label',
				'sort-box-with-show' : 'sort-box-with-show',
			},
			_create : function(){
				this.status = ['','待审核','已审核','已打回'];
				this.status_color = ['','#8ea8c8','#17b202','#f8a6a6'];
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['cover-layer'] ] = '_editCommodity';
				handlers['click ' + op['commodity-add'] ] = '_addCommodity';
				handlers['click ' + op['zan'] ] = '_zanCommodity';
				handlers['click ' + op['market-figure'] ] = '_triggerUpload';
				handlers['click ' + op['reaudit'] ] = '_auditCommodity';
				handlers['click ' + op['pic-each'] ] = '_chooseImg';
				handlers['click ' + op['del'] ] = '_delCommodity';
				handlers['click ' + op['back'] ] ='_backActivity';
				handlers['click ' + op['pic-del'] ] ='_delImg';
				handlers['click ' + op['state_show'] ] = '_pasteStatus';
				handlers['click ' + op['recomd_show'] ] = '_pasteStatus';
				this._on(handlers);
				this.activity_id = $( op['commodity-adv'] ).data('id');
				this.market_id = this.element.attr('market_id');
				this._initForm();
				this._initUpload();
			},
			_initForm : function(){
				var op = this.options,
					info = {};
				info.opera = '新增';
				info.value = '新增';
				info.method = 'create';
				info.market_id = this.market_id;
				info.activity_id = this.activity_id;
				$( op['add-commodity-tpl'] ).tmpl( info ).prependTo( op['market-edit'] );
				$( op['sort-box'] ).clone(true).prependTo( op['market-type'] );
				this._hgSortPicker();
			 	var sort_name = "选择分类";
				$( op['sort-label'] ).empty().text( sort_name );
			},
			
			_hgSortPicker : function(){
				var op = this.options,
					sp = $( op['sort-box'] ).find( op['sort-box-inner'] ),
					label = $( op['sort-box'] ).find( op['sort-label'] );
				if( sp[0] ){
					sp.hgSortPicker({
						nodevar : label.attr('_multi'),
						width : 191,
						change : function(id, name){
							label[0].firstChild.nodeValue = name;
		                    label.prev().show();
		                    $( op['sort_id'] ).val(id);
		                    label.trigger('click');
						},
						getId: function() {
		                    return $( op['sort_id'] ).val();
		                },
		                baseUrl: label.attr('baseUrl') || undefined
					});
					sp.hide();
				}else{
					return;
				}
				label.toggle(function(){
					sortBian();
           			sp.slideDown(500, function () { hg_resize_nodeFrame(); });
				},function(){
					 sortBian();
            		 sp.slideUp(500);
				});
				$( op['sort-box'] ).click(function(e) {
		            if (e.target == this || e.target == $(this).find('label:first')[0] ) {
		                label.trigger('click');
		            }
		        });
		        function sortBian() {
		            $( op['sort-box'] ).toggleClass( op['sort-box-with-show'] );
		        }
			},
			
			_initUpload : function(){
				var widget = this.element,
					_this = this;
					op = this.options;
				var url = "./run.php?mid=" + gMid + "&a=uploadProductImg";
				widget.find( op['image-file'] ).ajaxUpload({
					url : url,
					phpkey : 'logo',
					after : function( json ){
						_this._uploadIndexAfter(json);
					}
				});
			},
			
			_uploadIndexAfter : function( json ){
				var op = this.options,
					data = json['data'];
				var info = {};
				info.imginfoid = data.id;
				info.img_info = data.img_info;
				info.market_id = this.market_id;
				$( op['add-pic-tpl'] ).tmpl( info ).appendTo( op['pic-list'] );
				var img = $( op['market-figure'] ).find('img');
				if(!img.attr('src')){
					var goal = $( op['pic-first'] ).addClass( op['current'] ),
						src = goal.find('img').attr('src'),
						id = goal.find( 'input' ).val();
					img.attr('src',src);
					$( op['market-logo'] ).val(id);
				}
			},
			
			_editCommodity : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					widget = this.element,
					_this = this,
					box = self.closest( op['commodity-img'] );
					item = self.closest( op['commodity-each'] ),
					id = item.attr('_id');
				var	url = './run.php?mid=' + gMid + '&a=getProductInfo&id=' + id; 
				if( item.hasClass( op['selected'] ) ){
					return;
				}else{
					item.addClass( op['selected'] )
					.siblings().removeClass( op['selected'] );
				}
				$.getJSON(url, function( data ){
					var data = data[0][0];
					var	info = {};
						img_id = data.img_id;
						img = data.img;
					info.nname = data.name;
					info.id = data.id;
					info.index_img_id = data.index_img_id;
					info.index_img = data.index_img;
					info.brief = data.brief;
					info.product_standard = data.product_standard;
					info.vender = data.vender;
					info.old_price = data.old_price;
					info.now_price = data.now_price;
					info.activity_id = data.activity_id;
					info.product_unit = data.product_unit;
					product_sort_id = data.product_sort_id;
					product_sort_name = data.product_sort_name;
					info.url = data.url;
					info.opera = '编辑';
					info.value ='保存';
					info.method = 'update';
					info.market_id = this.market_id;
				$( op['market-edit'] ).empty();
				$( op['add-commodity-tpl'] ).tmpl( info ).appendTo( op['market-edit'] );
				$( op['market-edit'] ).addClass( op['market-init'] );
				$( op['sort-box'] ).clone(true).prependTo( op['market-type'] );
				_this._hgSortPicker();
				 if(!product_sort_name){
				 	var sort_name = "选择分类";
				 }else{
				 	var sort_name = product_sort_name;
				 }
				$( op['sort-label'] ).empty().text( sort_name );
				$( op['sort_id'] ).val( product_sort_id );
				imgid = img_id.split(',');
				index_id = info.index_img_id;
				if(imgid[1]){
					for(var i=0; img[i]; i++ ){
		        		var info = {};
		        		info.img_info = img[i];
		        		info.imginfoid = imgid[i];
		        		$( op['add-pic-tpl'] ).tmpl( info ).appendTo( op['pic-list'] );
		        	}
				}else{
					var info = {};
	        		info.img_info = img;
	        		info.imginfoid = imgid;
	        		$( op['add-pic-tpl'] ).tmpl( info ).appendTo( op['pic-list'] );
				}
				widget.find( op['pic-each'] ).each(function(){
	        		var mid = $(this).find('input').val();
	        		if(mid == index_id){
	        			$(this).addClass( op['current'] );
	        		}
	        	});
				});
			},
			_addCommodity : function(){
				var op = this.options,
					info = {};
				info.opera = '新增';
				info.value = '新增';
				info.method = 'create';
				info.market_id =this.market_id;
				info.activity_id = this.activity_id;
				$( op['market-edit'] ).empty();
				$( op['add-commodity-tpl'] ).tmpl( info ).prependTo( op['market-edit'] );
				$( op['market-edit'] ).removeClass( op['market-init'] );
				$( op['sort-box'] ).clone(true).prependTo( op['market-type'] );
				this._hgSortPicker();
			 	var sort_name = "选择分类";
				$( op['sort-label'] ).empty().text( sort_name );
				$( op['commodity-each'] ).each(function(){
					$(this).removeClass( op['selected'] );
				});
			},
			_zanCommodity : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['commodity-each'] );
				var url = "./run.php?mid=" + gMid + "&a=recommendProduct",
					info = {};
					info.id = item.attr('_id'),
					info.activity_id = this.activity_id;
					info.market_id =this.market_id;
					$.getJSON(url, info, function( data ){
						var data = data[0],
							is_recommend = data.is_recommend;
						
						self.attr('zid',is_recommend);
						self[(is_recommend ? 'add' : 'remove') + 'Class']( op['agree-zan'] );
					});
			},
			
			_delCommodity : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['commodity-each'] );
				var id = item.attr('_id'),
					activity_id = this.activity_id;
					market_id = this.market_id;
				this._del(id, activity_id, market_id, item);
				event.stopPropagation();
			},
			
			_del : function(id, activity_id, market_id, item){
				var method = function(){
					var url = "./run.php?mid=" + gMid + "&a=delete";
					$.get(url, {id : id, activity_id : activity_id, market_id : market_id}, function(){
						item.remove();
					})
				}
				this._remind('你确定要删除选中商品','删除提示', method)
			},
			_remind : function(title, message, method){
				jConfirm(title, message, function( result ){
					if(result){
						method();
					}else{
						return false;
					}
				});
			},
			
			_chooseImg : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				var img = $( op['market-figure'] ).find('img');
				if(self.hasClass( op['current'] )){
					return;
				}else{
					self.addClass( op['current'] )
					.siblings().removeClass( op['current'] );
				}
				var src = self.find('img').attr('src'),
					id = self.find('input').val();
					img.attr('src',src);
					$( op['market-logo'] ).val(id); 
			},
			
			_auditCommodity : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				var id = self.attr('_id');
					status = self.attr('_status');
				 this._audit(self, id, status);
				 event.stopPropagation();
			},
			
			_audit : function(self, id, status){
				var _this = this,
					url = "./run.php?mid=" + gMid + "&a=audit";
				$.getJSON(url, {id: id, status: status},function( data ){
					var data = data[0];
					status = data['status'];
					status_id = data['id'];
					status_text = data['status_format'];
					status_color = _this.status_color[status];
				self.text(status_text).css('color',status_color).attr('_status',status);
				});
			},
			
			_triggerUpload : function(){
				var op = this.options;
				$( op['image-file'] ).click();
			},
			_backActivity : function(){
				var market_id = this.market_id;
				window.location.href = './run.php?mid=564&market_id=' + market_id;
			},
			_delImg : function( event ){
				var op = this.options,
					widget = this.element,
					self = $(event.currentTarget);
				self.closest( op['pic-each'] ).remove();
				widget.find( op['pic-each'] ).each(function(){
					if($(this).hasClass( op['current'] )){
						return;
					}else{
						$( op['pic-first'] ).addClass( op['current'] );
					}
				});
				var goal = $( op['pic-each'] + '.current'),
					img = goal.find('img').attr('src'),
					id = goal.find('input').val();
				$( op['market-figure'] ).find('img').attr('src',img);
				$( op['market-logo'] ).val(id);
				event.stopPropagation();
			},
			
			_pasteStatus : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				self.closest( op['market-list'] ).submit();
			},
			
		});
	})($);
	$('.m2o-main').special_activity();
});
