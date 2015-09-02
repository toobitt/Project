(function( $ ){
	$.widget('role.column', {
		options : {
			popTitle : '站点选择',
			ptop : '306',
			id : 'pop',
			unique : null,
			target : null,
			app : null,
			nodes : null,
			tpl : ''
		},
		_create : function(){
		},
		_init : function(){
			this._columnTpl();
			this._check();
		},
		
		_check : function(){
			var _this = this,
				op = this.options;
			var options = {
				id : op.id,
				popTitle : op.popTitle,
				ptop : op.ptop,
				unique : op.unique
			}
			var column = op.target.find('.check-all').data('column');
			column && (options.publishsys = column)
			options.savePop = function( event ){
				var dom = $(event.currentTarget);
				_this._handleData( dom );
			};
			var authPop = $.modalPop( op.id );
			authPop.publishsys( options );
		},
		
		_handleData : function( dom ){
			var siteid = dom.find('.site-hiddenid').val(),
				sitename = dom.find('.site-hiddenname').val();
			var column_id = [], column_name = []; 
			dom.find('.site_result').find('li').each(function(){
				var $this = $(this);
				if( siteid.indexOf( $this.attr('_siteid') ) > -1 ){
					return;
				}
				column_id.push( $this.attr('_id') );
				column_name.push( $this.attr('_name') );
			});
			column_id = column_id.join(','),
			column_name = column_name.join(',');
			var allid = (siteid && column_id ? (siteid + ',') : siteid) + column_id,
				allname = (siteid && column_id ? (sitename + ',') : sitename) + column_name;
			this.column(allid, allname);
			allid && this._callback( allid );
		},
		
		_callback : function( allid ){
			var target = this.options.target,
				check = target.find('.check-all');
			if( check.prop('checked') ){
				check.prop('checked', false);
				target.find('.tc-node').show();
				this.options.app.app('save', false);
			}
		},
		
		_columnTpl : function(){
			var column = this._initColumn();
			if( column instanceof Object ){
				columnid = column.columnid;
				columnname = column.columnname;
    			this.column(columnid, columnname);
			}else{
				this.column();
			}
				
		},
		
		column : function(column_id, column_name){
			var op = this.options;
			var nodebox = op.target.find('.tc-node .tc-box');
			var arr_column = [], arr_name = column_name ? column_name.split(',') : [];
			if( column_id && column_id != 1){
				$.each(column_id.split(','), function(k, v){
					arr_column.push({
						id : v,
						name : arr_name[k]
					});
				});
			}else{
				arr_column.push({
					id : null,
					name : '暂无已选模板'
				})
			}
			var len = arr_column.length;
			
			$.tmpl(op.tpl, arr_column).appendTo( nodebox.empty() );
			if( column_id == 1 ){
				op.target.find('.check-all').prop('checked', false);
				nodebox.show();
			}
		},
		
		_initColumn : function(){
			var arr_site = [], arr_column = [], arr_name = [];
			var nodes = this.options.nodes;
			if( nodes && nodes.length ){
				$.each(nodes, function(k, v){
					var arr_nodes = v.split('_'),fegmentid,
						len = arr_nodes.length;
					if( arr_nodes[0] == 'page' ){
						arr_column.push({
							id : v,
							name : arr_nodes[ len - 1 ],
							fegmentid : (len == 4) ? arr_nodes[1].substring(2) : arr_nodes[2].substring(2)
						});
					}else{
						if( v != 1 ){
							arr_site.push({
								siteid : v,
								name : arr_nodes[ len - 1 ]
							});
						}
					}
					arr_name.push( arr_nodes[ len - 1 ] );
				});
				this.widget().find('.check-all').data('column', {
					site : arr_site,
					column : arr_column
				});
				return {
					columnid : nodes.join(','),
					columnname : arr_name.join(',')
				}
			}else{
				return 1;
			}
		},
	})
})(jQuery)
