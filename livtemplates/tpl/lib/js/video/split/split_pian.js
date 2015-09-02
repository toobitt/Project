(function($){
    $.widget('split.pian', {
        options : {
            title : '.vod-title',
            kz : '.camera-btn',
            start : '.start',
            end : '.end',
            save : '.save',
            cancel : '.cancel',
            tip : '.video-pian-tip',
            duration : '.duration',
            yulan : '.yulan',
            comment : '.comment',
            sbtn : '.start-point',
            ebtn : '.end-point',

            'current-video-id' : 0,
            'current-video-title' : '',
            'ajax-url' : '',
            'publish-url' : '',
            'type' : ''
        },

        _create : function(){
            var root = this.element;
            this.title = root.find(this.options['title']);
            this.kz = root.find(this.options['kz']);
            this.start = root.find(this.options['start']);
            this.end = root.find(this.options['end']);
            this.tip = root.find(this.options['tip']);
            this.duration = root.find(this.options['duration']);
            this.yulan = root.find(this.options['yulan']);
            this.comment = root.find(this.options['comment']);
            this.startInfo = null;
            this.endInfo = null;
            this.keywords = root.find('.form-dioption-keyword');

            this.id = 0;

        },

        _init : function(){
        	$('.save').off("click");
            var root = this.element;

            var handler = {};
            handler['click ' + this.options['save']] = '_save';
            handler['click ' + this.options['cancel']] = '_cancel';
            handler['click ' + this.options['kz']] = '_kz';
            handler['click ' + this.options['yulan']] = '_yulan';
            handler['click ' + this.options['sbtn']] = '_sbtn';
            handler['click ' + this.options['ebtn']] = '_ebtn';
            this._on(handler);
            this.keywords.hg_keywords();
        },

        setKZ : function(imgData){
            this.kz.html('<img src="' + imgData + '"/>');
        },

        setStart : function(time, timeString, imgData){
            this._setSE('start', time, timeString, imgData);
        },

        setEnd : function(time, timeString, imgData){
            this._setSE('end', time, timeString, imgData);
        },

        _setSE : function(type, time, timeString, imgData){
            /*timeString = timeString.split(':');
            timeString.length = 3;
            timeString = timeString.join(':');*/
            this[type + 'Info'] = {
                time : time,
                timeString : timeString,
                imgData : imgData
            };
            var img = imgData ? '<img src="' + imgData + '"/>' : '';
            this[type].html(img + '<span class="time-val">' + timeString + '</span>');

            if(this.startInfo && this.endInfo){
                this.duration.html(this._duration(this.endInfo['time'] - this.startInfo['time']));
                //this.yulan.show();
            }
        },

        _save : function( event ){
        	var self = $( event.currentTarget );
            var _this = this;
            _this._addClass();
            if(!_this.startInfo && !_this.endInfo){
                _this._tip('no');
                _this._delay(function(){
                        _this._removeClass();
                        _this._resetOpacity();
                    }, 1000);
                return;
            }
            if(!_this.title.val()){
            	_this._tip('title');
                _this._delay(function(){
                        _this._removeClass();
                        _this._resetOpacity();
                    }, 1000);
                return;
            }
            var url = _this._replace(_this.options['ajax-url']);
            var data = {
                id : _this.options['current-video-id'],
                split_id : _this.id,
                start_time : _this.startInfo['time'] * 1000,
                end_time : _this.endInfo['time'] * 1000,
                title : $.trim(_this.title.val()),
                imgdata : _this.kz.find('img').attr('src') || _this.start.find('img').attr('src'),
                comment : $.trim(_this.comment.val()),
                column_id : $('.common-form-pop').find('.publish-hidden').val(),
                vod_sort_id : $('#sort_id').val(),
                keywords : $('#keywords').val()
            };
            _this._tip('send');
            var wait = $.globalLoad( self );
            $.post(
                url,
                data,
                function(json){
                    _this._delay(function(){
                        _this._tip('success');
                    }, 1000);
                    _this._delay(function(){
                    	wait();
                        _this._removeClass();
                        _this._resetOpacity();
                        $('#tiao-box').tiao('showItem', $('.vod-spit')) && $('.new-add').html('新增');
                        _this._success(json[0]);
                        _this.empty();
                    }, 1500);
                },
                'json'
            ).success(function(){
            	wait();
            }).error(function(){
            	wait();
                _this._tip('error');
                _this._delay(function(){
                        _this._removeClass();
                        _this._resetOpacity();
                    }, 1000);
            });
        },

        _success : function(data){
            if(data && data[0]){
                if(this.id){
                    this._trigger('editAfter', event, [data[0]]);
                }else{
                    this._trigger('saveAfter', event, [data[0], this.element.offset()]);
                }
            }
        },

		_resetOpacity : function(){
			this.element.find('.video-pian-tip').css({'opacity' : 0});
		},

        _addClass : function(){
            this.element.addClass('option');
        },

        _removeClass : function(){
            this.element.removeClass('option');
        },

        _cancel : function(){
        	$('.new-add').html('新增');
            this.empty();
        },

        _sbtn : function(){
            this._trigger('setVideoSlider', null, [0]);
        },

        _ebtn : function(){
            this._trigger('setVideoSlider', null, [1]);
        },

        _kz : function(){
            /*if(this.kz.find('img')[0]){
                return;
            }*/
            this._trigger('kzClick');
        },

        _yulan : function(){

        },

        edit : function(data, type){
        	if(!type){
        		var imgSrc = data['img_info'] || '';
	            imgSrc = imgSrc ? imgSrc.replace('80x60/', '') : '';
	            this.setKZ(imgSrc);
		            this._trigger('setEndAfter', null, [parseInt(data['split_end']) / 1000, function(){
	                _this._trigger('setStartAfter', null, parseInt(data['split_start']) / 1000);
	            }]);
        	}
        	this.id = type ? 0 : data['id'];
            this.title.val(data['title']);
            this.comment.val(data['comment']);
            $('#sort_id').val(data['vod_sort_id']);
            var keywords = $('.form-dioption-keyword').data('keywords');
            keywords.delEvent();
            if( data['keywords'] ){
            	$('#keywords').val( data['keywords'] );
            	keywords.initEvent(  {val : data['keywords'] });
            }
            data['vod_sort_id'] && this._getSortname( data['vod_sort_id'] );
            data['column_id'] && this._getPublish( data['column_id'] );
            var _this = this;
        },

		_getPublish : function( obj ){
			var publishid = [];
			var pop = $('.common-form-pop');
			var publish = pop.find('.publish-box').data('publish');
			$.each(obj, function(key, value){
				publishid.push( key );
			});
			var opubid = publishid.join(',');
			var url = this.options['publish-url'] + opubid;
			$.getJSON(url, function( data ){
	     	    publish.addResult(data.selected_items || [], { reset: true });
	     	    publish.addChild(data.items || [], '', { reset: true });
	     	    pop.find('[name=pub_time]').val(data.pub_time);
			});
		},

		_getSortname : function( id ){
			var obj = this.element.find('.sort-box li').filter(function(){
				return ($(this).find('input[name="hg-sort-radio"]').val() == id);
			});
			obj.find('input[name="hg-sort-radio"]').prop('checked', true);
			var sortname = obj.find('a').html();
			var sortname = sortname ? sortname : '暂无分类'
			this.element.find('.sort-label')[0].firstChild.nodeValue = sortname;
		},

        empty : function(){
            this.id = 0;
            this.title.val('');
            this.kz.empty();
            this.start.empty();
            this.end.empty();
            this.tip.empty();
            this.duration.empty();
            this.yulan.hide();
            this.startInfo = null;
            this.endInfo = null;
            this.comment.val('');
            this._clearPop();
			this._clearSort();
			var keywords = $('.form-dioption-keyword').data('keywords');
        	keywords && keywords.delEvent();
        },

		_clearPop : function(){
			var pop = $('.common-form-pop');
			pop.find('.publish-result').addClass('empty').find('ul').empty();
			pop.find('.date-picker').val('');
			pop.find('.publish-each:first-child li').each(function(){
				$(this).removeClass('open');
				$(this).find('input').prop('checked', false);
			});
			pop.find('.publish-hidden').val('');
			pop.find('.publish-name-hidden').val('');
			this.element.find('.publish-button span').html('暂未设置');
		},

		_clearSort : function(){
			this.element.find('.sort-label')[0].firstChild.nodeValue = '选择分类';
			this.element.find('#sort_id').val('');
			var box = this.element.find('.save-pop');
			this.element.find('.sort-box li').each(function(){
				$(this).find('input[name="hg-sort-radio"]').prop('checked', false);
			});
		},

        _tip : function(code){
            var tips = {
                no : '开始时间或者结束时间没有设置',
                send : '提交中...',
                success : '提交成功',
                error : '发生错误，请重新提交',
                title : '标题还没有填写'
            };
            this.tip.html(tips[code]).css({'opacity': 1});
        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z]+)}}/ig, function(all, match){
                return data[match];
            });
        },

        _duration : function(duration){
            duration = parseInt(duration);
            var h = parseInt(duration / 3600);
            var m = parseInt(duration / 60);
            var s = parseInt(duration % 60);
            return (h > 0 ? h + '\'' : '') + ( (h > 0 || m > 0) ? m + '\'' : '') + s + '"';
        }

    });

})(jQuery);