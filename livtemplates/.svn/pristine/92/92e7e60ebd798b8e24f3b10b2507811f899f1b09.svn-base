$(function(){
	var mySort = {
		config : function( setting ){
			mySort.config = {
				$area : $('#sort-box'),
				$box : $('#sort-box').find('.sort-box-inner'),
				$label : $('#sort-box p.sort-label'),
				$hiddenid : $('#sort_id'),
				$show : 'sort-box-with-show'
			};
			mySort.param = {
				fid : 4,
				defineAction : 'get_vod_node',
				baseUrl : './run.php',
				width: 208,
				change: function(id, name){
					return mySort.callback(id, name);
				}
			};
			$.extend(mySort.param, setting);
			mySort.init();
			mySort.setup();
		},
		init : function(){
			mySort.config.$box.hgSortPicker(mySort.param).hide();
		},
		
		setup : function(){
			mySort.config.$label.click(mySort.toggleBox);
		},
		
		toggleBox : function(){
			var config = mySort.config;
			mySort.switchSlide();
			var time = config.$area.hasClass(config.$show) ? 500 : 0;
			var timer1 = setTimeout(mySort.switchClass, time);
		},
		
		switchClass : function(){
			mySort.config.$area.toggleClass(mySort.config.$show);
		},
		
		switchSlide : function(){
			mySort.config.$box.slideToggle(500);
		},
		
		callback : function(id, name){
			var config = mySort.config;
			config.$label[0].firstChild.nodeValue = name;
            config.$label.prev().show();
            config.$hiddenid.val(id);
           	mySort.toggleBox();
		},
	}
	mySort.config();
});