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
	getItem : function(){
		var values = localStorage.getItem( this.options.key );
		if(values){
			return JSON.parse(values);
		}else{
			return [];
		}
	},
	updateItem : function( value, type){
		this.handleData( value, type );
		var values = this.getItem();
		return values;
	},
	
	setItem : function( values ){
		var vajues = JSON.stringify(values);
		localStorage.setItem( this.options.key, vajues );
	},
	
	resetItem : function( value ){
		this.removeItem();
		this.setItem( value );
	},
	
	handleData : function( value, type ){
		var val;
		if(value instanceof Object){
			val = this.handleObject( value, type );
		}else{
			val = this.handleString( value, type );
		}
		this.setItem( val );
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
	
	handleObject : function( value, type ){
		var values = this.getItem() || [];
		var _this = this;
		var isbool = values.length ? this.judgeObject(values, value) : false;
		if( isbool ){
			if(type){
				var isnull = true;
    			var info = {};
			 	if( values.length ){
			 		$.each(values, function(k, v){
			 			if(v.content.length){
			 				if(v.content[0] instanceof Object){
				 				if(value.key == v.key){
				 					isnull = _this.dataObject( v, value );
				 				}
				 			}else{
				 				//_this.dataString(v, value);
				 			}
			 			}else{
			 				isnull = _this.dataObject( v, value );
			 			}
				 	});
				 }
			 	if(isnull){
			 		values = this.nullObject(values, value);
			 	}
			 }else{
		 		$.each(values, function(k, v){
		 			if(v.key == value.key){
		 				var content = v.content;
		 				var dvalue = [];
		 				$.each(v.content, function(l, t){
		 					if(t.data == value.content){
		 						t = null;
		 					}
		 					if(t){
		 						dvalue.push(t);
		 					}
		 				});
		 				v.content = dvalue;
		 			}
			 	});
			 }
		}else{
			values = this.nullObject(values, value);
		}
		 return values;
	},
	
	nullObject : function(values, value){
		var content = [];
 		content.push({
 			data : value.content,
 			k : value.time
 		})
 		values.push({
 			key : value.key,
 			content : content
 		});
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
	
	removeItem : function(){
		localStorage.removeItem( this.options.key );
	},
	
	clearItem : function(){
		localStorage.clear();
	},
});