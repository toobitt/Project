$(function(){
	(function($){
		var info = {
			template : '' +
					'<select>' +
						'{{each pages}}' +
						'<option value="${$value}" {{if $item.page_num == $value}}selected{{/if}}>每页${$value}条</option>' +
						'{{/each}}' +
					'</select>' + 
					''
		};
		$.widget('hoge.page',{
			options : {
				current_page : 0,
				total_page : 0,
				total_num : 0,
				page_num : 0,
				page_num_list : [20,40,60],
				show_all : true,
				next_btn : false
			},
			
			_create : function(){
				this.page_tpl_name = 'page_tpl_name';
				$.template( this.page_tpl_name, info.template );
				// $.template( this.page_tpl_name );
			},
			
			_init : function(){
				this._on({
	                'click span[_page]' : '_click',
	                'change select' : '_change'
	            });
				this._createPage();
			},
			
			_createPage : function(){
				var options = this.options;
				total_page = options.total_page;
				total_num = options.total_num;
				page_num = options.page_num;
				current_page = options.current_page;
				if(total_page < 2){
					this.element.hide();
					return;
				}
				var html = '';
				html += '<div class="hoge_page">';
				if(options.show_all){
					html += '<span class="page_all">共' + total_page + '页/计' + total_num + '条</span>';
				    html += '<span class="numbers-box"></span>';
				}
				if( options.show_all || !options.show_all && options.next_btn ){
					if (current_page > 1){
						html += '<span class="page_next" _page="1"><a>|<</a></span>';
						html += '<span class="page_next" _page="' + (current_page - 1) + '"><a><<</a></span>';
					}
				}
				$.each([-2, -1, 0, 1, 2],function(i, n){
					var check = false;
					var val = current_page + n;
					if(n < 0){
						if(val > 0){
							check = true;
						}
					}else if(n > 0){
						if(val <= total_page){
							check = true;
						}
					}else{
						html += '<span id="pagelink_' + val + '" class="page_cur">' + current_page + '</span>';
					}
					if(check){
						html += '<span class="page-code" _id="pagelink_' + val + '" _page="' + val + '"><a class="page_bur" id="page_' + page_num + '">' + val + '</a></span>';
					}
				});
				if( options.show_all || !options.show_all && options.next_btn ){
					if(current_page < total_page){
						html += '<span class="page_next" _page="' + (current_page + 1) + '"><a>>></a></span>';
						html += '<span class="page_next" _page="' + total_page +'"><a>>|</a></span>';
					}
				}
				html += '</div>';
				this.element.html(html);
				this._createNumbers();
			},
			
			_createNumbers : function(){
	        	var op = this.options,
	        		page_numbers = {},
	        		page_numbers_box = this.element.find( '.numbers-box' );
	            page_numbers['pages'] = op.page_num_list;
	            $.tmpl( this.page_tpl_name, page_numbers, {
	            	page_num : op.page_num
	            } ).appendTo( page_numbers_box );
			},
			
			_change : function( event ){
				var self = $( event.currentTarget ),
					value = self.val(),
					current_page = this.element.find( '.page_cur' ).text();
				this._trigger('page', null, [current_page,value]);
			},
			
			_click : function( event ){
				var self = $(event.currentTarget),
					page = self.attr('_page'),
					page_num = this.element.find('select').val();
				this._trigger('page', null, [page,page_num]);
			},
			
	        show : function(){
	            this.element.show();
	        },
	        
	        hide : function(){
	            this.element.hide();
	        },
	        
	        refresh : function( option ){
	            this.show();
	            $.extend( this.options, option );
	            this._createPage();
	        }
		});
	})($);
});

