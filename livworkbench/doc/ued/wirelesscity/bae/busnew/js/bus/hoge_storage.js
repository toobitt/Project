/*
 * 实例化对象 var storage = new Hg_localstorage( {key : 'deploy'} );		//key 为本地存储的key 值
 * 得到储存值	storage.getItem();
 * 更新储存值 storage.updateItem( value, type );		//value: 要改变的数据; type: true表示增加数据, false表示减少数据;
 * 重新设置储存值 storage.resetItem( value );			//将原来的存储值清空，重新保存
 * 删除储存值 storage.removeItem();
 * 清空储存值	storage.clearItem(); 
 * */


var defaultOptions = {
	key: '',
};
function Hg_localstorage( options ){
	this.options = options = $.extend({}, defaultOptions, options);
}
$.extend(Hg_localstorage.prototype, {
	getItem : function( key ){
		var key = key ? key : this.options.key;
		var values = localStorage.getItem( key );
		if(values){
			return JSON.parse(values);
		}else{
			return [];
		}
	},
	updateItem : function( value, type, key){
		this.handleData( value, type, key );
		var values = this.getItem( key );
		return values;
	},
	
	setItem : function( key, values ){
		var vajues = JSON.stringify(values);
		var key = key ? key : this.options.key;
		localStorage.setItem( key, vajues );
	},
	
	resetItem : function( value ){
		this.removeItem();
		this.setItem( value );
	},
	
	handleData : function( value, type, key ){
		var val;
		if(value instanceof Object){
			val = this.handleObject( value, type, key );
		}else{
			val = this.handleString( value, type, key );
		}
		this.setItem( key, val );
	},
	
	handleString : function( value, type ){
		var values = this.getItem() || [];
		if(type){
			values = this.nullString(values, value);
		}else{
			var eq = $.inArray(value, values);
			values.splice(eq, 1);
		}
		return values;
	},
	
	dataObject : function(v, value){
		var content = v.content || [];
		content.push({
			data : value.content,
			time : value.time
		});
 		 v.content = content;
 		 return false;
	},
	
	handleObject : function( value, type, key ){
		var values = this.getItem( key ) || [];
		var _this = this;
		var isbool = values.length ? this.judgeObject(values, value) : false;
		if( isbool ){
			if(type){
				var isnull = true;
    			var info = {};
			 	if( values.length ){
			 		$.each(values, function(k, v){
		 				if(value.key == v.key){
		 					isnull = false;
		 					return false;
		 				}
				 	});
				 }
			 	if( isnull ){
			 		values = this.nullObject(values, value);
			 	}
			 }else{
			 	var dvalue = [];
		 		$.each(values, function(k, v){
		 			if(v.key == value.key){
		 				v = null;
		 			}
		 			if( v ){
		 				dvalue.push(v);
		 			}
			 	});
			 	values = dvalue;
			 }
		}else{
			values = this.nullObject(values, value);
		}
		 return values;
	},
	
	nullObject : function(values, value){
 		values.push(value);
 		return values
	},
	
	nullString : function( values, value ){
		values.push( value );
		return values; 
	},
	
	judgeObject : function(values, value){
		var isbool = $.each(values, function(k, v){
			return (v.key == value.key);
		});
		return isbool;
	},
	
	removeItem : function( key ){
		var key = key ? key : this.options.key;
		localStorage.removeItem( key );
	},
	
	clearItem : function(){
		localStorage.clear();
	},
});