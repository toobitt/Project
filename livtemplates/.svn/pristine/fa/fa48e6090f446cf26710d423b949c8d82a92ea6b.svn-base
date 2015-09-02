/*覆盖显示发布的函数*/
hg_vodpub_show = function() {
    var top;
    top = $('.pub').offset().top;
    $('#vodpub').show().animate( { 'top': top - 305 }, function() {
        $('#vod_fb').css('top', top).show();
    });
};

jQuery(function($){
    /*$(window).on('resize', function(){
        var me = $(this),
            height = me.height(),
            width = me.width();
        $('.form-left').height(height);
    }).triggerHandler('resize');*/

    var doc = $(document);
    $(window).on('load', function(){
        $('iframe').each(function(){
            $(this.contentWindow.document).on('click', function(){
                var focus = doc.data('current-focus');
                if(focus){
                    $(focus).triggerHandler('blur');
                    doc.triggerHandler('current-focus-call');
                }

                //$(parent.document).triggerHandler('click');
            });
        });
    });


    /*$(window).on('beforeunload', function(){

        if(!$(document).data('submit')){
            var contentNumber = $(document).data('content-number');
            var nowContentNumber = $($('#idContentoEdit1')[0].contentWindow.document.body).text().replace(/\s/g, '').length;

            if(contentNumber != nowContentNumber){
                return '编辑器内容有变动...';
            }
        }
    });

    $(document).on({
        init : function(event){
            var number = $($('#content').val()).text().replace(/\s/g, '').length;
            $(this).data('content-number', number);
        }
    }).triggerHandler('init');

    $('#submit_ok, #submit').click(function(){
        $(document).data('submit', true);
    });*/
});

jQuery(function($){
    return;
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

    $('#keywords-ajax')
    .on({
        _show : function(event, animate){
            $(this).show();
            var height = $(this).outerHeight();
            var position = $('#keywords-tiqu').offset();
            var css = {
                left : position.left + 29 + 'px',
                top : position.top + (- (height - 13) / 2) + 'px',
                'z-index' : 100001
            };
            if(animate){
                $(this).animate(css, 100);
            }else{
                $(this).css(css);
            }
        },
        _hide : function(){
            $(this).hide();
        }
    })
    .on('click', 'li span', function(){
        if($(this).hasClass('on')){
            $(this).removeClass('on');
            del([$(this).text()]);
        }else{
            $(this).addClass('on');
            ok([$(this).text()]);
        }
        $('#keywords-box').trigger('_pos');
    });


    $('#keywords-box').on('_pos', function(event, first){
        var pos = function(){
            $('#keywords-tiqu').css({
                'margin-top' : 0,
                'top' : '23px'
            });
        }
        var len = $('#keywords-box .each-keyword-item-span').length;
        if(len && first){
            pos();
            return;
        }
        if(len <= 1){
            if(len == 1){
                pos();
            }else if(!len){
                $('#keywords-tiqu').removeAttr('style');
            }

            setTimeout(function(){
                var ajaxBox = $('#keywords-ajax');
                ajaxBox.is(':visible') && ajaxBox.trigger('_show', [true]);
            }, 20)

        }
    }).on('click', '.keywords-del', function(){
        $('#keywords-box').trigger('_pos');
    }).trigger('_pos', [true]);




    $('#keywords-close').on('click', function(){
        $('#keywords-ajax').trigger('_hide');
        $('#keywords-tiqu').removeClass('on');
    });

    function back(json){
        var selectBox = $('#keywords-ajax');
        var tpl = $('#keywords-tpl').val();
        var lis = [];
        if(json['errmsg']){
            jAlert(json['errmsg'], '提醒').position($('#keywords-tiqu'));
            return;
        }
        $.each(json, function(i, n){
            if(n['word']){
                var tplData = {name : n['word']};
                lis.push(tpl.replace(/{{([^}]+)}}/g, function(all, match){
                    return tplData[match] || '';
                }));
            }
        });
        if(lis.length){
            $('#keywords-tiqu').addClass('on');
            selectBox.find('ul').html(lis.join(''));
            var hasArr = has();
            selectBox.find('li span').each(function(){
                var text = $(this).text();
                if($.inArray(text, hasArr) != -1){
                    $(this).addClass('on');
                }
            });
            selectBox.trigger('_show');
        }
    }

    function has(){
        var box = $('#keywords-box');
        var has = [];
        box.find('.each-keyword-item-span').each(function(){
            has.push($(this).text());
        });
        return has;
    }

    function del(text){
        $('#keywords-box .each-keyword-item-span').each(function(){
            if($(this).text() == text){
                $(this).closest('.each-keyword-item').find('.keywords-del').trigger('click');
                return false;
            }
        });
    }

    function ok(selected){
        var box = $('#keywords-box');
        var hasArr = has();
        $.each(selected, function(i, n){
            if($.inArray(n, hasArr) == -1){
                box.trigger('append', [n]);
            }
        });
    }

    function errorTip(mi, title, rotate){
        var offset = mi.offset();
        if(title){
            $('.column-error').stop(true).remove();
            $('<div class="column-error" style="position:absolute;z-index:100002;color:red;border:1px solid red;padding:2px 5px;background:#fff;">'+ title +'</div>').appendTo('body').css({
                top : offset.top - 30 + 'px',
                left : offset.left + 'px'
            }).delay(1000).animate({
                opacity : 0
            }, 2000, function(){
                $(this).remove();
            });
        }

        rotate = rotate || [8, -8, 5, -5, 2, -2, 'end'];
        if($.inArray('end', rotate) == -1){
            rotate.push('end');
        }
        $.each(rotate, function(i, n){
            if(n == 'end'){
                mi.queue('mt', function(next){
                    $(this).removeAttr('style');
                });
            }else{
                mi.queue('mt', function(next){
                    $(this).css('transform', 'rotate(' + n + 'deg)');
                    next();
                }).delay(50, 'mt');
            }
        });
        mi.dequeue('mt');

    }
});

jQuery(function() {
    $('form').submit(function(){
        var title = $('#title');
        var val = $.trim(title.val());
        if(!val || val == title.attr('_default')){
            /*jAlert('标题不能为空！', '提示').position($('.form-dioption-submit')[0]);*/
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


$(function(){
    $('.my-placeholder').on({
        focus : function(event, init){
            $(this).data('focus', true);
            var target = $('#' + $(this).attr('target'));
            var targetVal = target.val();
            var val = init ? targetVal : $.trim($(this).text());
            var placeholder = $(this).attr('placeholder');
            var preval = $(this).attr('preval');
            $(this).addClass('on');
            if(!val || val == placeholder){
                $(this).text('');
            }else if(val == (preval + targetVal)){
                $(this).text(targetVal);
            }else{
                $(this).text(targetVal);
            }
        },

        blur : function(){
            if(!$(this).data('focus')) return;
            $(this).data('focus', false);
            var val = $.trim($(this).text());
            var placeholder = $(this).attr('placeholder');
            var preval = $(this).attr('preval');
            var target = $('#' + $(this).attr('target'));
            $(this).removeClass('on');
            if(!val || val == placeholder){
                $(this).text(placeholder);
                target.val('');
            }else{
                $(this).text(preval + val);
                target.val(val);
            }
        }
    }).each(function(){
        $(this).trigger('focus', [true]).trigger('blur');
    });
});

$(function(){
	(function($){
		var indexPicEdit = function(){
			var imgid = $('#indexpic').val();
			$('#indexpic_url').picEdit({
		    	imgSrc : $(this).attr('_src'),
		        mouseCheck : function(){
		            return $(this).attr('_state') > 0 ? false : true;
		        },
		        saveAfter : function(){
		            top.$('body').find('img.tmp-edit-top-img').remove();
		            top.$('body').off('_picsave').on('_picsave', function(event, info){
		                try{
		                    var img = $(this).find('#formwin')[0].contentWindow.$('#indexpic_url');
		                    img.attr('src', $.globalImgUrl(info, '', true));
		                    top.$('body').find('img#indexpic_url').remove();
		                }catch(e){}
		            }).append($('#indexpic_url').clone().hide().addClass('tmp-edit-top-img'));
		            $.editorPlugin.get($.myueditor, 'imgmanage').imgmanage('refreshPicSrc', imgid);
		        }
		   });
		};
		indexPicEdit();
		var btnBox = $('.form-dioption-submit');
		if( btnBox.length ){
			var prevent = $('<div class="preventClose" style="position:absolute; width:92px; height:34px; right:44px; top:0; background-color:transparent; "></div>').appendTo( btnBox );
			prevent.on('click', function(){
				$(this).myTip({
					string : '编辑器正在实例化，请稍候再试',
					delay: 2000,
					dtop : 5,
					dleft : -200,
					padding : 10,
					width : 'auto'
				});
			});
		}
		
		
		//上传索引图
		$('.indexpic').click(function(){
			$.editorPlugin.get($.myueditor, 'imgmanage').imgmanage('showInputFile');
		});
		var suoyinId = parseInt( $('#indexpic').val() );
		//初始化编辑器
		var	init = function(){
			$.myueditor = $.m2oEditor.get( 'form-edit-box', {
				initialFrameWidth : 690,
				initialFrameHeight : 590,
				removeFormatTags:'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var,iframe,object,embed',
				editorContentName : 'content',	//编辑器内容的name名
				slide : true,					//风格
				relyDom : '.form-right',		//slide风格依赖dom（用于计算定位和高度）
				needCount : true,				//字数统计
				countDom : '#editor-count',		//字数统计dom
				suoyinId : suoyinId,			//索引图的素材id	
			} );
			
			$.myueditor.addListener('ready', function( editor ){
				btnBox.length && btnBox.find('.preventClose').remove();
			});
			
			$.myueditor.addListener('_setIndex', function(event, data) {
				var index_box = $('.indexpic-box'),
					indexpic_hidden = $('#indexpic'),
					index_flag = index_box.find('.indexpic-suoyin'),
					indexpic_img = index_box.find('img'),
					from_indexpic_material = $('.from-indexpic-material');
				var load = $.globalLoad( index_box );
				if (data) {
					var img = new Image();
					img.src = $.globalImgUrl(data, '160x', true);
					img.onload = function(){
						load();
						indexpic_img.css('width','auto').attr({
							'src' : img.src,
							'_src' : img.src
						});
					};
					indexpic_hidden.val(data.id);
					index_flag.addClass('indexpic-suoyin-current');
					indexPicEdit();
				} else {
					load();
					indexpic_hidden.val('');
					indexpic_img.attr('src',RESOURCE_URL+'news/suoyin-default.png');
					index_flag.removeClass('indexpic-suoyin-current');
				}
				from_indexpic_material.length && from_indexpic_material.remove();
			});
			$.myueditor.addListener('_refreshIndexPic',function(event, data){
				var img = $('.indexpic').find('img'),
					src = img.attr('src');
				if( data ){
					img.attr('src', $.globalImgUrl(data, '160x', true) ).css('width','auto');
				}else{
					img.attr('src',src+'?'+new Date().getTime());
				}
			});
			$.myueditor.addListener('_title', function(event, selectedText) {
				$('#title').val( selectedText ).attr({
					'_value' : selectedText,
					'title' : selectedText
				});
			});
			$.myueditor.addListener('_desc', function(event, selectedText, widget) {
				widget.hideAll();
				$('#brief-clone').text( selectedText );
				$('#brief').val( selectedText );
			});
			$.myueditor.addListener('_keyword',function(event, text, widget){
				widget.hideAll();
				$('#keywords-box')[0] && $('#keywords-box').trigger('append', text);
			});
			$.myueditor.addListener('_uploadSuoyinCallback',function(event, data){
				var src = data[0]['_mSrc'],
					from_indexpic_material = $('.from-indexpic-material');
				$('.indexpic').find('img').attr('src',src);
				$('.indexpic-suoyin').addClass('indexpic-suoyin-current');
				$('#indexpic').val( data[0]['id'] );
				from_indexpic_material.length && from_indexpic_material.remove();
			});
		};
		$.includeUEditor( init, {
			plugins : 'all'
		} );
	})($);
	//编辑器end
	
	// //文稿自动保存
	(function(){
		setInterval(function() {
			var content = '';
			var auto_draft = $('input[name=auto_draft]').val();
	        if(!$("#title").val()) {
	            //return;
	        }
	       
	        if($('input[name=a]').val() != 'create' || auto_draft == 0) {
	            return;
	        }
			if( $.myueditor ){
				 content = $.myueditor.getContent();
			}
	        $('textarea[name="content"]').val(content);
	        var options = {
	            url:'run.php?mid='+gMid+'&a=autoSave&ajax=1',
	            type:'post',
	            data:{"auto_draft":auto_draft, "a":"autoSave"},
	        };
	        $("#content_form").ajaxSubmit(options);        
	    }, 10000);
	})($);
	//文稿自动保存end

    (function () {
        $('#submit_draft').on({
            'click': function (){
                var content = $.myueditor.getContent();
                $('textarea[name="content"]').val(content);
                var options = {
                    url:'run.php?mid='+gMid,
                    type:'post',
                    data:{"a":"autoSave", "ajax": 1},
                    dataType : 'json',
                    success: function ( data ) {
                        $('#submit_draft').myTip( {
                            string : '保存成功'
                        } );
                    }
                };
                $("#content_form").ajaxSubmit(options);
                return;
            }
        });

        $('.draft-button').on({
            'click': function () {
                $(".draft-outer").toggleClass("pop-show");
            }
        });

        $('.draft-slide-no').on({
            'click': function () {
                $(".draft-outer").removeClass("pop-show");
            }
        });

        $('.draft-option-del').on({
            'click':function () {
                var draft_id = $(this).attr("_draft_id");
                var options = {
                    url:'run.php?mid='+gMid,
                    type:'post',
                    data:{"a":"draft_del", "ajax": 1, "draft_id": draft_id},
                    dataType : 'json',
                    success: function ( data ) {
                        $(".draft-item-" +draft_id).slideUp("normal", function() {
                            $(".draft-item-" + draft_id).remove();
                        });
                    }
                };
                $.ajax(options);
                return;
            }
        });

    })($);


});
