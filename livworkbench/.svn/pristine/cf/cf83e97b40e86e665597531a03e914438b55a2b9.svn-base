function SquareOverlay(center, width, length, type){
	this._center = center;
	this._length = length;
	this._width = width;
	this._type = type;
}
SquareOverlay.prototype = new BMap.Overlay();
SquareOverlay.prototype.initialize = function(map){
	this._map = map;
	var div = document.createElement('div');
	div.className = "map_tips map_move";
	if( this._type ){
		div.innerHTML = '<div class="map_content m2o-flex"><div class="map_info m2o-flex-one"><p class="m2o-overflow">现在的位置</p><span class="m2o-overflow addressMap"></span></div><a class="sure-btn"></a></div>'; 
	}else{
		div.innerHTML = '<p class="map_route m2o-overflow">当前位置</p>';
	}
	div.style.position = "absolute";
	div.style.width = this._width + "px";
	div.style.height = this._length + "px";
	div.style.background = this._color;
	map.getPanes().markerPane.appendChild( div );
	this._div = div;
	return div;
}
SquareOverlay.prototype.draw = function(){
	var position = this._map.pointToOverlayPixel( this._center );
	this._div.style.left = position.x - this._width/2 + 'px';
	this._div.style.top = position.y - this._length/2 + 'px';
}
SquareOverlay.prototype.addEventListener = function( event, fun ){
	this._div['on'+event] = fun;
}