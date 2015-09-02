$(document).ready(function(){
	//your code go here
	$(".share_box").hover(function(){
		$(this).addClass("share_on");
	},function(){
		$(this).removeClass("share_on");
	});
	
	/*search_key*/
	$(".search_key").focus(function(){
		if($(this).val()=="成员/圈子/活动/话题"){
			$(this).val("");
		}
	}).blur(function(){
		if($(this).val()==""){
			$(this).val("成员/圈子/活动/话题");
		}
	});

	/*用户登录注册*/
	if($(".account").size()){
		
		
		$(".email_field, .username_field, .password_field").find("input").focus(function(){
			//$(this).parent().addClass("active").end().siblings(".tip_message").show().siblings(".error_message").hide();
			$(this).parent().addClass("active");
			if(this.defaultValue==$(this).val()){
				$(this).val("");
			}
			$(this).siblings(".label").hide();
		}).blur(function(){
			$(this).parent().removeClass("active");			
			if($.trim($(this).val()) == ""){
				$(this).val(this.defaultValue);
				$(this).siblings(".label").show();
			}
		});

		$(".password_field .label").click(function(){
			$(".password_field input").focus();
		});


		$(".other_field label").click(function(){
			var agreeProtocol=$(".other_field input").attr("checked");
			$(".other_field input").attr("checked", !agreeProtocol);
			if(agreeProtocol){
				$(this).parent().removeClass("ckecked");
			}else{
				$(this).parent().addClass("ckecked").find(".error_message").hide();
			}
		});
		
		
		$(".register_form form").bind("submit",function(e){			
			
			$(".error_message").hide();
			$(".tip_message").show();

			var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
			if(!pattern.test($.trim($(".email_field input").val()))){
				$(".email_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("邮箱格式不正确");
				e.preventDefault();	return false;
			}
	
			/*
			if($.trim($(".username_field input").val())== "" || $(".username_field input").val().length<6 || $(".username_field input").val().length >14){
				$(".username_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("用户名格式不正确");
				e.preventDefault();	return false;
			}
			*/
						
			if($.trim($(".password_field input").val())== "" || $(".password_field input").val().length<6 ){
				$(".password_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("密码格式不正确");
				e.preventDefault();
				return false;
			}
		
			if(!$(".agree_protocol").attr("checked")){
				$(".other_field .error_message").show().html("同意协议才能注册");
				e.preventDefault();
				return false;
			}
		});

		$(".login_form form").bind("submit",function(e){		
			/*
			if($.trim($(".username_field input").val())== "" || $(".username_field input").val().length<6 || $(".username_field input").val().length >14){
				$(".username_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("用户名格式不正确");
				e.preventDefault();	return false;
			}
			*/			
			if($.trim($(".password_field input").val())== "" || $(".password_field input").val().length<6 ){
				$(".password_field input").focus();
				$(".error_message").html("密码格式不正确");
				e.preventDefault();
				return false;
			}

		});


	}/*用户登录注册*/

	/*行动微博弹出框*/
	$(".plugin_weibo_close").click(function(){
		$(".plugin_weibo").hide(500);
	});
	/*
	$(".aisay").click(function(){
		$(".plugin_weibo").show(500).removeClass("plugin_weibo_our");
	});*/

	/*表情*/
	$(".sys_mood").click(function(){
		var sysmood=$(this).offset();
		$(".sys_mood_pane").css({"left": sysmood.left, "top": sysmood.top + $(this).height()}).show();
	});
	//
	//$(".sys_mood_pane span").click(function(){
	//	$(this).parent().hide();
	//	$(".plugin_weibo_reply_cnt").val($(".plugin_weibo_reply_cnt").val()+ $(this).attr("data-value"));
	//});
	
	void function () {
		var allLi = $('#join_group li'),
			timer = null,
		    selector = 'li:nth-child(4n+1),li:nth-child(4n+2)';
		$('#join_group').find(selector).find('> div').addClass('group_hot_grid3_left');
		$('#join_group').find(':not(' + selector + ')').find('> div').addClass('group_hot_grid3_right');
		allLi.filter(':gt(11)').find('> div').addClass('group_hot_grid3_bottom');
		allLi.mouseenter(function() {
			var curEls = $(this).find('div.group_hot_grid3');
			timer = setTimeout(function() {
				curEls.show('fast');
			}, 300);
		}).mouseleave(function() {
			clearTimeout(timer);
			$('.group_hot_grid3').hide();
		});
	} ();

});

(function($){
    $.createMask = function(options){
        options = $.extend({
            'z-index' : 1000
        }, options);
        var mask = $('#mask-layer');
        if(!mask[0]){
            mask = $('<div/>').attr({
                id : 'mask-layer'
            }).appendTo('body').css({
                position : 'absolute',
                left : 0,
                top : 0,
                background : '#000',
                opacity : .35,
                filter : 'alpha(opacity=35)',
                'z-index' : options['z-index']
            }).on({
                '_show' : function(){
                    $(this).data('open', true).triggerHandler('_resize');
                },
                '_hide' : function(){
                    $(this).data('open', false).hide();
                },
                '_resize' : function(){
                    var d = $(document), dw = d.width(), dh = d.height();
                    $(this).css({
                        width : dw,
                        height : dh,
                        display : 'block'
                    });
                }
            });
            $(window).on('resize', function(){
                var mask = $('#mask-div');
                mask.data('open') && mask.triggerHandler('_resize');
            });
        }
        return mask;
    }


    /*$(function(){
        $(document).on('dblclick', function(){
            $.createMask().trigger('_show');
        });
    });*/
})(jQuery);


(function($){
    $.textareaInsert = function(options){
        options = $.extend({
            textarea : '#content',
            val : ''
        }, options);
        var ta = $(options['textarea']);
        if($.trim(ta.val()) == ta.attr('_default')){
            ta.val('');
        }
        ta.replaceSelection(options['val']);
    }

    $.fn.biaoqing = function(options){
        options = $.extend({
            event : 'click',
            property : 'alt',
            textarea : '#content',
            callback : $.noop
        }, options);

        return this.each(function(){
            $(this).on(options['event'], function(event){
                event.preventDefault();
                $.textareaInsert({
                    textarea : options['textarea'],
                    val : $(this).attr(options['property'])
                });
                options['callback'] && options['callback'].call(this);
            });
        });
    }
})(jQuery);