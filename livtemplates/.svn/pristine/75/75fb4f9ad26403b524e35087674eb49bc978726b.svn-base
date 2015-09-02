$(function(){
	(function($){
		var addressInfo = {
			addritem : '' +
				'<li style="cursor:pointer;">' + 
					'<a href="###" onclick="if(hg_select_value(this, 0, \'${addrtype}\',\'${addrid}\', 0)){${addrclick}(this);};" attrid="${id}" class="overflow">${name}</a>' + 
				'</li>' +
				''
		}
		$.widget('address.address_list',{
			options : {
				getProviceUrl : '',
				getCityUrl : '',
			},
			
			_create : function(){
				$.template('addrItem', addressInfo.addritem);
			},
			
			_init : function(){
				this._initAddr();
			},

			_initAddr : function(){
				var s_province = this.element.find('.province_search'),
					box = this.element.find('#province_show');
				var _this = this;
				if(s_province.length){
					$.getJSON(this.options.getProviceUrl, function( data ){
						_this._handlerData( data, box, 'province_id', 'province_show');
					});
				}
				var province_id = this.element.find('#province_id').val(),
					city_id = this.element.find('#city_id').val();
				this.get_city( province_id, 1);
				this.get_area( city_id, 1);
			},
			
			get_city : function( id, type ){
				var _this = this;
				var box = this.element.find('#city_show');
				var city = this.element.find('#city_id'),
					s_city = this.element.find('#display_city_show');
				$.getJSON(this.options.getCityUrl, {id : id}, function( data ){
					_this._handlerData( data, box, 'city_id', 'city_show');
					type && _this._initData(data, city, s_city, 'city_id');
				})
			},
			
			get_area : function( id, type){
				var _this = this;
				var box = this.element.find('#area_show');
				var area = this.element.find('#area_id'),
					s_area = this.element.find('#display_area_show');
				$.getJSON(this.options.getAreaUrl, {id : id}, function( data ){
					_this._handlerData( data, box, 'area_id', 'area_show');
					type && _this._initData(data, area, s_area, 'area_id');
				})
			},
			
			_sortData : function(type, value){
				switch(type){
					case 'province_id': {
						value.name = value.name;
						value.addrclick = 'hg_get_city_by_province';
						break;
					} 
					case 'city_id': {
						value.name = value.city;
						value.addrclick = 'hg_get_area_by_city';
						break;
					}
					case 'area_id': {
						value.name = value.area;
						break;
					}
				}
			},
			
			_handlerData : function( data, box, type, ulclass){
				var provinceData = [],
					_this = this;
				$.each(data, function(key, value){
					_this._sortData(type, value);
					value.addrid = type;
					value.addrtype = ulclass;
					provinceData.push( value );
				});
				$.tmpl('addrItem', provinceData).appendTo( box );
				if(type == 'province_id'){
					var province = this.element.find('#province_id'),
						s_province = this.element.find('#display_province_show');
					_this._initData( data, province, s_province, type );
				}
			},
			
			_getName : function( type, value ){
				switch(type){
					case 'province_id': {
						return value.name;
					} 
					case 'city_id': {
						return value.city;
					}
					case 'area_id': {
						return value.area;
					}
				}
			},
			
			_initData : function( data, obj, box, type ){
				var _this = this;
				var id = obj.val();
				if(id){
					$.each(data, function(key, value){
						if(value.id == id){
							var name = _this._getName(type, value);
							box.html(name);
						}
					});
				} 
			},
			
			clear_area : function(){
				var box = this.element.find('#area_show');
				box.find('li:not(:first-child)').detach();
				this.element.find('#display_area_show').html('所有区');
				this.element.find('#area_id').val(0);
			},
		});
	})($);
	$('.address_box').address_list({
		getProviceUrl : './region.php?a=province',
		getCityUrl : './region.php?a=city',
		getAreaUrl : './region.php?a=area',
	});
});
function hg_get_city_by_province( obj )
{
	var id = $(obj).attr('attrid')
	$('.address_box').address_list('get_city', id, 0);
	$('.address_box').address_list('clear_area');
}
function hg_get_area_by_city( obj ){
	var id = $(obj).attr('attrid');
	$('.address_box').address_list('get_area', id, 0);
}
