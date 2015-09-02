function SquareOverlay(center, width, length){
	this._center = center;
	this._length = length;
	this._width = width;
}
SquareOverlay.prototype = new BMap.Overlay();
SquareOverlay.prototype.initialize = function(map){
	this._map = map;
	var div = document.createElement('div');
	div.className = "map_tips map_move";
	div.innerHTML = '<div class="map_route"><em class="m2o-overflow">08:25</em><p>当前位置</p></div>';
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