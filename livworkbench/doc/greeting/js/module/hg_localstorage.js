/*
 * 实例化对象 var storage = $.Hg_localstorage( {key : 'deploy'} );		//key 为本地存储的key 值
 * 得到储存值	storage.getItem();
 * 更新储存值 storage.updateItem( value, type );		//value: 要改变的数据; type: true表示增加数据, false表示减少数据;
 * 重新设置储存值 storage.resetItem( value );			//将原来的存储值清空，重新保存
 * 删除储存值 storage.removeItem();
 * 清空储存值	storage.clearItem(); 
 * 增加储存值 storage.addItem();
 * 减少储存值 storage.deleteItem();
 * */

(function(){
	var pluginName = 'Hg_localstorage';
	
	function Storage( option ){
		this.op = $.extend({
			key : 'id',
			lmt : false				//如果需要时间戳限制，设置lmt为true 即可
		}, option );
	}
	
	$.extend(Storage.prototype, {
		getItem : function( key ){
			var key = key || this.op.key;
			var values = localStorage.getItem( key );
			if( values ){
				var values = JSON.parse(values);
				return this.op.lmt ? values.val : values;
			}else{
				return [];
			}
		},
		
		updateItem : function( value, type, key){
			key = key || this.op.key;
			this.handleData( value, type, key );
			var values = this.getItem( key );
			return values;
		},
		
		deleteItem : function( value, key ){
			key = key || this.op.key;
			this.deleteData( value, key );
			var values = this.getItem( key );
			return values;
		},
		
		deleteData : function( value, key ){
			var values = this.getItem( key );
			if( this.op.lmt ){
				values = values.val;
			}
			if( typeof value == 'string' ){
				var eq = $.inArray(value, values);
				values.splice(eq, 1);
			}else{
				this.find( values, value.key || value.id, function( ii, item ){		//删除
					values.splice(ii, 1);
				} );
			}
			this.setItem( key, values );
		},
		
		addItem : function( value, key ){
			var val = this.getItem( key || this.op.key );
			if( this.op.lmt ){
				val = val.val;
			}
			val.push( value );
			this.setItem( key, val );
			var values = this.getItem( key );
			return values;
		},
		
		setItem : function( key, values ){
			if( this.op.lmt ){
				lmtValus = {
					val : values,
					lmt : new Date().getTime()
				}
			}else{
				lmtValus = values;
			}
			var vajues = JSON.stringify( lmtValus );
			var key = key || this.op.key;
			localStorage.setItem( key, vajues );
		},
		
		resetItem : function( value, key ){
			key = key || this.op.key;
			this.removeItem( key );
			this.setItem( key, value );
		},
		
		handleData : function( value, type, key ){
			var val;
			if(value instanceof Object){
				val = this.handleObject( value, type, key );
			}else{
				val = this.handleString( value, key );
			}
			this.setItem( key, val );
		},
		
		handleString : function( value, key ){
			var values = this.getItem( key ) || [];
			if( this.op.lmt ){
				values = values.val;
			}
			var eq = $.inArray(value, values);
			if( eq == -1 ){
				values.push( value );
			}else{
				values.splice(eq, 1);
			}
			return values;
		},
		
		handleObject : function( value, type, key ){
			var values = this.getItem( key ) || [];
			
			if( this.op.lmt ){
				values = values.val;
			}
			if( !values.length ){
				values.push( value );
				return values;
			}
			
			if( type ){
				var isFind = this.find( values, value.key || value.id, function( ii, item ){		//编辑
					values.splice(ii, 1, value);
				} );
				
				if( !isFind ){
					values.push( value );
				}
			}else{
				this.find( values, value.key || value.id, function( ii, item ){		//删除
					values.splice(ii, 1);
				} );
			}
			return values;
		},
		
		removeItem : function( key ){
			var key = key || this.op.key;
			localStorage.removeItem( key );
		},
		
		clearItem : function(){
			localStorage.clear();
		},
		
		find: function(data, name, fn){
		    var isFind = false;
		    $.each( data, function(ii, item ){
		      if (item.key == name) {
		        $.isFunction( fn ) && fn(ii, item);
		        isFind = true;
		        return false;
		      }
		    });
		    return isFind;
		},
	});
	
	$[pluginName] = function( option ) {
		var options = {};
		if( typeof option == 'string' ){
			options.key = option;
		}else{
			options = option
		}
		return new Storage( options );
	};
})();
