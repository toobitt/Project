$(function(){
	function News(){
		this.baseSrc = 'http://fapi.wifiwx.com/mobile/api/cmc/';
		this.baseParam = '?appkey=d0WTCC30fX1FRwUD5XYtjKLTQtnE8Kwb&appid=28';
	};
	News.prototype.slider = function(){
		var url = this.baseSrc + 'indexpic.php' + this.baseParam;
		var _this = this;
		$.ajax({
			type: "get",
            url: url,
            dataType: "jsonp",
            jsonp: "callback",
            success: function(json){
            	var arr = [];
            	$.each(json,function(k,v){
            		var data = {};
            		var pic = v['indexpic'];
            		data.src = pic['host'] + pic['dir'] + pic['filepath'] + pic['filename'];
            		arr.push(data);
            	});
            	$('#slider-item-tpl').tmpl(arr).appendTo('#flipsnap');
            	var itemWid = $('body').width();
            	$('.slider .item').width( itemWid );
            	$('#flipsnap').width( itemWid*4 );
            	_this.bae_banner = $.bae_banner("#flipsnap", "#indicator span", 3000);
            }
        });
		return this;
	};
	News.prototype.getColumnData = function( param ){
		var url = this.baseSrc + 'news_recomend_column.php' + this.baseParam;
		var _this = this;
		$.ajax({
			type: "get",
            url: url,
            dataType: "jsonp",
            jsonp: "callback",
            success: function(data){
            	$('#subnav-item-tpl').tmpl(data).appendTo('.subnav');
            	if( param.callBack ){
            		param.callBack();
            	}
            }
        });
//		$.getJSON( url, function( data ){
//			$('#subnav-item-tpl').tmpl(data).appendTo('.subnav');
//			if( callBack ){
//        		callBack();
//        	}
//		} );
		return this;
	};
	News.prototype.getList = function( param ){
//		var offset = param.offset || 0;
		var url = this.baseSrc + param.url + this.baseParam;
		var _this = this;
		$.ajax({
			type: "get",
            url: url,
            dataType: "jsonp",
            data : param.data || {},
            jsonp: "callback",
            success: function(json){
            	$('#list-item-tpl').tmpl(json, _this.item).appendTo('#thelist');
            	console.log(json);
            	if( json.length < 20 ){
            		$('#pullUp').hide();
            		$.myScroll.refresh();
            		console.log($.myScroll);
            	}
            }
        });
		return this;
	};
	News.prototype.detail = function( param ){
		var url = this.baseSrc + param.url + this.baseParam;
		var _this = this;
		$.ajax({
			type: "get",
            url: url,
            dataType: "json",
            data : param.data || {},
            jsonp: "callback",
            success: function(json){
            	$('#news-detail-tpl').tmpl( json ).appendTo('.content');
            	$('artical').html( $('artical').text() );
            	console.log(1);
            },
            error : function(){
            	console.log('error');
            }
        });
		return this;
	};
	News.prototype.item = {
			picSrc : function(param){
				var src = param.host + param.dir + param.filepath + param.filename;
				return src;
			}
	};
	News.prototype.showLoading = function(){
		this.loading = $.bae_progressbar({
			message:"<p>正在努力加载数据...</p><p>Loading...</p>",
			modal:true,
		});
		return this;
	};
	News.prototype.closeLoading = function(){
		this.loading.close();
		return this;
	};
	News.prototype.hrefStrToObj = function(href){
		var str = href.slice(1),
			newStr = '{"' + str.replace(/=/g,'":"').replace(/&/g,'","') + '"}';
		var	obj = JSON.parse(newStr);
		return obj;
	}
	
	$.news = new News();
});