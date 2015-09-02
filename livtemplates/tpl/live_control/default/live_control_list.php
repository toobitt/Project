{template:head}
{css:mms_control_list}
{css:tab_btn}

<style type="text/css">
.img a{position:relative;}
.img img{position:absolute;left:0;top:0;-webkit-backface-visibility: hidden;}
.img img.transition{
    -webkit-transition:all 0.5s;
    -moz-transition:all 0.5s;
    -o-transition:all 0.5s;
    transition:all 0.5s;
    opacity:0.5;
    /*-webkit-transform:translate(20px, -20px);
    -moz-transform:translate(20px, -20px);
    -o-transform:translate(20px, -20px);
    transform:translate(20px, -20px); */
}

#list .img, #list .video{height:129px;padding:10px;}
#list .img a{margin:0;}
#list .video{display:none;position:relative;overflow:hidden;}
#list .video-place{background:#000;display:block;height:100%;width:100%;}

.change-button{float:right;margin:0 20px 0 0 !important;color:#8C8A8B !important;display:inline-block;width:146px;height:24px;line-height:24px;position:relative;text-align:center;background:url({$RESOURCE_URL}switch/all.png) no-repeat;}
.change-button .change-bg{width:50%;height:100%;background:url({$RESOURCE_URL}switch/left.png) no-repeat;position:absolute;left:0;top:0;margin:0;padding:0;}
.change-button .change-bg{
    -webkit-transition:left 0.3s;
    -moz-transition:left 0.3s;
    -ms-transition:left 0.3s;
    transition:left 0.3s;
}
.change-button a{display:inline-block;width:50%;height:100%;vertical-align:top;position:relative;z-index:2;overflow:hidden;}
.change-button .change-left{color:#fff;font-weight:bold;}
.change-button .change-right{color:#8C8A8B;font-weight:normal;}
.change-button .change-left:hover{background:url({$RESOURCE_URL}switch/left-hover.png) no-repeat;}
.change-button .change-right:hover{background:url({$RESOURCE_URL}switch/all-hover.png) no-repeat right top;}

.change-button-right .change-bg{left:50%;background-image:url({$RESOURCE_URL}switch/right.png);}
.change-button-right .change-left{color:#8C8A8B;font-weight:normal;}
.change-button-right .change-right{color:#fff;font-weight:bold;}
.change-button-right .change-left:hover{background:url({$RESOURCE_URL}switch/all-hover.png) no-repeat;}
.change-button-right .change-right:hover{background:url({$RESOURCE_URL}switch/right-hover.png) no-repeat;}

.pic_list{width:auto;margin:10px 0 0 15px;}
.pic_list .is-control{background:#dcedfb;}

.video-mask{position:absolute;left:0;top:0;z-index:10;width:100%;height:100%;opacity:0;}
.hoge_page{margin-right:40px;}
</style>

<h2 class="title_bg">直播控制-电视墙<span class="e">自动更新时间：3秒</span>
<span class="change-button"><a href="javascript:;" class="change-left">频道截图</a><a href="javascript:;" class="change-right">视频播放</a><span class="change-bg"></span></span>
</h2>

{code}/*hg_pre($list);*/{/code}
<ul class="pic_list" id="list">
{if $list}
	{foreach $list AS $k => $v}
		<li class="{if $v['is_control'] > 0}is-control{/if}">
			<div class="img">
				<a target="mainwin" href="{if $v['is_control']}run.php?a=relate_module_show&app_uniq=live_control&mod_uniq=live_control&mod_main_uniq=channel&mod_a=form&{$_ext_link}&id={$v['id']}{else}javascript:;{/if}" {if !$v['is_control']}style="cursor:default;"{/if}>
					<span id="img_{$v['id']}">
						<img src="{if $v['current_info']['preview']}{$v['current_info']['preview']}{else}{$RESOURCE_URL}nopic2.png{/if}">
					</span>
				</a>
			</div>
			<div class="video" _url="{$v['channel_stream'][0]['output_url']}" _id="{$v['id']}" _href="{if $v['is_control']}run.php?a=relate_module_show&app_uniq=live_control&mod_uniq=live_control&mod_main_uniq=channel&mod_a=form&{$_ext_link}&id={$v['id']}{else}javascript:;{/if}" {if !$v['is_control']}style="cursor:default;"{/if}>
				<span class="video-place">
					<span id="stream-{$v['id']}"></span>
				</span>
			</div>
			<p>
				<a id="curr_{$v['id']}" class="overflow">{$v['current_info']['program']}</a>
				<span class="overflow m2o-common-title">{$v['name']}</span>
			</p>
			<div class="clr"></div>
		</li>
	{/foreach}
{/if}
</ul>
<div style="clear:both;">
{$pagelink}
</div>
{js:mms_control}
<script type="text/javascript">

    var changeView = false;

    (function($){
        var defaultOptions = {
            leftCallback : $.noop,
            rightCallback : $.noop,
            defaultClass : 'change-button',
            buttons : {}
        };
        $.fn.changeButton = function(option){
            option = $.extend({}, defaultOptions, option);
            return this.each(function(){
                var me = $(this);
                me.data('current', 'left');
                $.each(option['buttons'], function(i, n){
                    me.on('click', '.change-' + n, function(){
                        if(me.data('current') == n) return;
                        me.data('current', n);
                        me.removeClass().addClass(option['defaultClass'] + ' ' + i);
                        option[n + 'Callback']();
                    });
                });
            });
        }
    })(jQuery);

    $(function(){
        $('.change-button').changeButton({
            buttons : {'' : 'left', 'change-button-right' : 'right'},
            leftCallback : function(){
                changeView = false;
                setTimeout(function(){
                    get_mms_list();
                    $('.img').show();
                    $('.video').hide().each(function(){
                        $(this).html('<span class="video-place"><span id="stream-'+ $(this).attr('_id') +'"></span></span>');
                    });
                }, 300);

                $('.e').show();
            },
            rightCallback : function(){
                changeView = true;
                function setVolume(number){
                    try{

                        var current = $(this).find('object');
                        current[0].setVolume(number);
                        /*number = 1;
                        $('object').not(current).each(function(){
                            try{
                                this.setVolume(number);
                            }catch(e){}
                        });*/
                    }catch(e){}
                }

                $('.e').hide();
                $('.video').show();
                $('.img').hide();

                setTimeout(function(){
                    $('.video').each(function(){
                        setSwfPlay($('.video-place span', this).attr('id'), $(this).attr('_url'), '172', '129', 1, '');
                        $(this).append('<a class="video-mask" href="'+ $(this).attr('_href') +'" target="mainwin"></a>');
                    }).hover(function(){
                        setVolume.call(this, 100);
                    }, function(){
                        setVolume.call(this, 1);
                    });
                }, 300);
            }
        });
    });


	function get_mms_list()
	{
	    if(changeView){
	        return;
	    }
		var channel_ids = '{$list[0]["channel_ids"]}';
		var url = 'run.php?mid={$_INPUT["mid"]}&a=get_current_channel_info&channel_id='+channel_ids;
		hg_request_to(url, '', 'get', 'hg_build_mms_list', 1);
	}
	
	/*function hg_build_mms_list (data)
	{
		setTimeout("get_mms_list();", 5000);
		if(!data)
		{
			return;
		}
		for(var n in data[0])
		{
			$('#curr_'+n).text(data[0][n]['pro']);
			var img_src = data[0][n]['img'];
		
			if(!img_src)
			{
				img_src = RESOURCE_URL + 'nopic2.png';
			}
			$('#img_'+n).html('<img src="'+img_src+'" />');
		}
	}*/

    var currentI = 1;
    function hg_build_mms_list(data){
        if(!data) return;
        $.each(data[0], function(i, n){
            $('#curr_' + i).text(n['program']);
            var imgSrc = n['preview'] || RESOURCE_URL + 'nopic2.png';
            if(!n['preview']){
                $('#img_' + i).html('<img src="'+ imgSrc +'"/>');
                return;
            }
            (function(ii, current){
                var img = new Image();
                img.onload = function(){
                    if(currentI != current + 1){
                        return;
                    }
                    $('#img_' + i).append('<img src="'+ imgSrc +'" class="transition"/>');
                    var allImg = $('#img_' + i).find('img');
                    setTimeout(function(){
                        allImg.last().css({
                            'opacity' : 1,
                            //'transform' : 'translate(0px, 0px)'
                        });

                        allImg.not(allImg.last()).css({
                            'opacity' : 0,
                            //'transform' : 'translate(-20px, -20px)'
                        });
                    }, 0);
                    setTimeout(function(){
                        allImg.not(allImg.last()).remove();
                    }, 600);
                };
                img.src = imgSrc;
            })(i, currentI);

        });
        currentI++;
        setTimeout(function(){
            get_mms_list();
        }, 3000);
    }
	setTimeout("get_mms_list();", 2000);
</script>
{template:foot}
