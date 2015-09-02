(function($) {
	var defaultOption = {
		site_id : '',
		nodevar: 'news_node',
		fid: 0,
		width: 211,
		mid: gMid,
		baseUrl: './route2node.php',
		getId: function() { return 0; },
		change: function() {},
		defineAction : ''
	};
	function JsonToArray( json, filter ) {
		if ( $.type(json) === 'array' ) {
			return json;
		}
		var d = [];
		$.each( json, function( i, n ) {
			if ( $.inArray(i, filter) != -1 ) {
				return;
			}
			d.push(n);
		});
		return d;
	}
	$.fn.hgSortPicker = function(option) {
		var op = $.extend({}, defaultOption, option);
		op.multi = op.nodevar = op.multi || op.nodevar;
		op.ac = $('form input[name=a]').val();
		function addFirstList(me) {
			if(op.defineAction){
				var url = op.baseUrl + '?mid=' + op.mid + '&a=' + op.defineAction + '&fid=' + op.fid;
			}else{
				var url = op.baseUrl + '?mid=' + op.mid + '&nodevar=' + op.nodevar + '&fid=' + op.fid;
				if( op.site_id ){
					url+= ( '&site_id=' + op['site_id'] + '&ac=' + op['ac'] );
				}
			}
			$.getJSON(url, function(data) {
				data = op.defineAction ? data[0] : data;
				data = JsonToArray(data, ['para']);
				if ( !data.length ) {
					return;
				}
				addNodeList(me, data, true);
			});
		}
		function addNodeList(me, data, first) {
			var html = '<ul>';
			$.each( data, function( i, n ) {
				html += '<li><div class="sort-name"><div class="sort-name-inner"><input type="radio" name="hg-sort-radio" value="' + n.id + '" /><a>' + n.name + '</a></div></div>';
				if ( n.is_last == 0 ) {
					html += '<strong class="sort-next" data-fid="' + n.id + '"></strong>';
				}	
				html += '</li>';
			});
			html += '</ul>';
			me.find('.sort-box').append( html );
			initChecked(me, op.getId());
			initStyle(me);
			if ( first !== true ) {
				addFatherSort(me);
				me.find('.sort-box').animate( { 'left': '-=' + op.width + 'px' }, 200 );
			}
			resizeHeight(me);
			setTimeout(function () {
				hg_resize_nodeFrame();
			}, 300);
		}
		function initChecked(me, id) {
			var radio = me.find('.sort-box').find('input[type=radio]')
			radio.each(function() {
				if ( $(this).val() == id ) {
					$(this).prop('checked', true);
				}
			});
		}
		function initStyle(me) {
			me.find('ul:last li:not(:has(strong))').find('.sort-name').css('width', '100%');
			me.find('li:last').css('border-bottom', '0 none');	
		}
		function addFatherSort(me) {
			var li = me.find('ul:last li:first');
			li.before( '<li><div class="sort-back">返回' + me.getFatherSort() + '</div></li>' )
		}
		function resizeHeight( me ) {
			var ul = me.find('ul:last'),
				uh = ul.find('li').length * 36 - 1,
				bh = $(window).height() - ul.offset().top - 200;
			me.width = (uh >= bh ? bh : uh);
			me.find('ul').height(uh);
			
		}
		return this.each(function() {
			var me = $(this),
				_gAjaxId = 0,
				_fatherSort = '';
			me.addClass('hg-sort-box');
			me.append( '<div class="sort-box"></div>' );
			addFirstList(me);
			me.on('click', 'li .sort-next', function() {
				var ajaxId = ++_gAjaxId, url, self = $(this);
				url = op.baseUrl + '?mid=' + op.mid + '&nodevar=' + op.nodevar + '&fid=' + $(this).data('fid');
				if( op.site_id ){
					url+= ( '&site_id=' + op['site_id'] );
				}
				$.getJSON( url, {
					ac: op.ac
				}, function( data ) {
					if ( ajaxId != _gAjaxId) {
						return;
					}
					data = JsonToArray( data, ['para'] );
					if ( !data.length ) {
						return;
					}
					_fatherSort = self.prev().find('a').text();
					addNodeList( me, data, false );
				});
			}).on( 'click', '.sort-name', function() { 
				var radio = $(this).find('input[type=radio]').prop('checked', true),
					name = radio.next().text(),
					id = radio.val();
				
				if (id == op.getId()) {
					radio = $(this).find('input[type=radio]').prop('checked', false);
					id = 0;
					name = "请选择分类" ;
				}
				op.change(id, name);
				
			}).on( 'click', '.sort-back', function() {
				var ul = me.find('.sort-box ul');				
				me.find('.sort-box').animate( { 'left': '+=' + op.width + 'px' }, 200, function() {
					ul.last().remove();
					resizeHeight(me);
				});
			});
			me.getFatherSort = function() {
				return _fatherSort;
			}
		});
	};
})(jQuery);
