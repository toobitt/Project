/*覆盖显示发布的函数*/
hg_vodpub_show = function() {
    var top;
    top = $('.pub').offset().top;
    $('#vodpub').show().animate( { 'top': top - 305 }, function() {
        $('#vod_fb').css('top', top).show();
    });
};

jQuery(function($){
    $(window).on('resize', function(){
        var me = $(this),
            height = me.height(),
            width = me.width();
        $('.form-left').height(height);
    }).triggerHandler('resize');

    var doc = $(document);
    $(window).on('load', function(){
        $('iframe').each(function(){
            $(this.contentWindow.document).on('click', function(){
                var focus = doc.data('current-focus');
                if(focus){
                    $(focus).triggerHandler('blur');
                    doc.triggerHandler('current-focus-call');
                }
            });
        });
    });
});

jQuery(function($){
    var these = $('.ext-futi input, .ext-zuozhe input, .ext-laiyuan input');
    these.inputHide({
        yizhi : true
    });
    these.on('focus', function(){
        $(this).prev().hide();
    }).on('blur', function(){
        $(this).prev()[$(this).data('hasval') ? 'show' : 'hide']();
    });
    these.trigger('blur');
});

jQuery(function($){
    var indexpic = $('#indexpic_url')
        .on('iload', function(event, src, id){
            var me = $(this);
            if(!src){
                src = me.attr('_src');
            }else{
                $('#indexpic').val(id);
            }
            var width, height;
            width = height = 160;
            var img = new Image();
            img.onload = function(){
                me.removeAttr('width height');
                var w = this.width,
                    h = this.height,
                    pw = w / width,
                    ph = h / height;
                if(pw >= 1 && pw > ph){
                    me[0].width = width;
                }
                if(ph >= 1 && ph > pw){
                    me[0].height = height;
                }
                me.removeAttr('_src').attr('src', src).closest('.indexpic-box').find('.indexpic-suoyin, .indexpic-suoyin-current').removeClass().addClass('indexpic-suoyin-current');
                me.show();
            };
            img.src = src;
        })
        .on('idelete', function(){
            $('#indexpic').val(0);
            $(this).attr('src', $(this).attr('_default')).closest('.indexpic-box').find('.indexpic-suoyin-current').removeClass().addClass('indexpic-suoyin');
        });

    if(indexpic.attr('_state') > 0){
        indexpic.trigger('iload');
    }else{
        indexpic.attr('src', indexpic.attr('_src')).removeAttr('_src');
    }
});

jQuery(function() {
    $('form').submit(function(){
        var title = $('#title');
        if(!$.trim(title.val())){
            jAlert('标题不能为空！', '提示').position($('.form-dioption-submit')[0]);
            return false;
        }

        var titles = [];
        $('.page-left-title-input').each(function(){
            titles.push($(this).val());
        });
        $('#pagetitles').val(encodeURIComponent(JSON.stringify(titles)));

        var pizhus = [];
        $('.biaozhu-item').each(function(){
            var pizhu = {};
            var item = $(this).find('.biaozhu-item-content');
            pizhu['id'] = item.attr('_id');
            pizhu['name'] = item.attr('_name');
            pizhu['content'] = item.attr('_title');
            var replys = [];
            $(this).find('.biaozhu-item-reply-each').not(':last').each(function(){
                var nameBox = $(this).find('.biaozhu-item-reply-name');
                replys.push({
                    id : nameBox.attr('_id'),
                    name : nameBox.attr('_name'),
                    reply : $(this).find('.biaozhu-item-reply-content').html()
                });
            });
            pizhu['replys'] = replys;
            pizhus.push(pizhu);
        });
        $('#pizhus').val(encodeURIComponent(JSON.stringify(pizhus)));
    });
});

jQuery(function($) {
	var v = $('#quanzhong'),
		m = $('#weight'),
		getLabelBy = (function() {
			var label = [0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90];
			return function(level) {
				return label[level];
			}
		})();
	v.on('click', '.quanzhong-option .down', function() {
		v.trigger('update', [ +(m.val()) - 1 ] );
	}).on('click', '.quanzhong-option .up', function() {
		v.trigger('update', [ +(m.val()) + 1 ] );
	}).on('update', function(e, level) {
		if (level < 0 || level > 12) {
			return;
		}
		v.find('.quanzhong').text( getLabelBy(level) ).parent().attr('class', 'quanzhong-box' + level);
		m.val(level);
	});
	
});
