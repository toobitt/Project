{template:head}
{js:common/ajax_upload}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;
		{/code}
	{/foreach}
{/if}
{code}
    $optext = $id ? '更新' : '新增';
    $ac = $id ? 'update' : 'create';
    $aceDir = './res/ace/';
    //print_r($formdata);
{/code}
<script src="{$aceDir}ace.js"></script>
<script>
jQuery(function($){
    var needEditor = ['html', 'css'];
    $.each(needEditor, function(i, n){
        var editor = $['edit' + n] = ace.edit($('#' + n)[0]);
        editor.setTheme('ace/theme/github');
        editor.getSession().setMode('ace/mode/' + n);
    });

    $.edithtml.on('blur', function(event, edit){
        var dys = $('#dys');
        dys.triggerHandler('reset');
        var reg = /liv_[^<]+/g;
        var string = edit.getValue();
        var matchs = string.match(reg);
        if(matchs.length){
            var maps = {};
            $.each(matchs, function(i, n){
                maps[n] = maps[n] || 0;
                maps[n]++;
            });
            var len = matchs.length;
            var names = $.unique(matchs);
            var nowLen = names.length;
            dys.triggerHandler('list', [names]);
            if(len != nowLen){
                var errNames = [];
                $.each(maps, function(i, n){
                    n > 1 && errNames.push(i);
                });
                dys.triggerHandler('error', [errNames]);
            }
        }
    });

    $('form').on({
        submit : function(event){
            if($('#dys').data('error')){
                jAlert('有重复的单元', '提醒').position($('input:submit'));
                return false;
            }
            $.each(needEditor, function(i, n){
                $('#' + n + '-input').val($['edit' + n].getValue());
            });
        }
    });

    $('.edit-tab').on({
        click : function(){
            var on = 'on';
            if($(this).hasClass(on)) return;
            $(this).addClass(on).siblings().removeClass(on)
            var which = $('.edit-content li').eq($('.edit-tab li').index(this)).addClass(on);
            which.siblings().removeClass(on);
            $['edit' + which.find('.editor').attr('id')].focus();
        }
    }, 'li');

    $('#yl').click(function(){
        var iframe = $('iframe');
        if(!iframe.attr('src')){
            var src = $(this).attr('href');
            if(src == '#' || !src){
                //return false;
            }else{
                iframe.attr('src', src);
            }
        }
        $('#yl-box').dialog('open');
        return false;
    });

    $('#yl-box').dialog({
        autoOpen : false,
        minWidth : 620,
        minHeight : 300,
        resizable : false,
        dialogClass : 'no-close'
    });

    var dys = $('#dys').on({
        list : function(event, names){
            $(this).find('em').html(names.length);
            var $ul = $(this).find('ul');
            $.each(names, function(i, n){
                n = n.replace('liv_', '');
                $ul.append('<li>' + n + '</li>')
            });
            $(this).show();
        },
        error : function(event, names){
            $(this).data('error', 1);
            $(this).find('div').show().find('span').empty().html(names.join(', &nbsp;'));
        },
        reset : function(){
            $(this).data('error', 0);
            $(this).find('div').hide();
            $(this).hide().find('ul').empty();
        }

    });
    dys.on({
        click : function(){
            $('.edit-tab li:first').trigger('click');
            $.edithtml.focus();
            $.edithtml.findAll('liv_' + $(this).text());
        }
    }, 'li');

    $.edithtml.focus();
    $.edithtml.blur();
    $.edithtml.focus();

    var currentUpload = null;
    $('#img-upload-file').ajaxUpload({
        url : "./run.php?mid=" + gMid + "&a=upload",
        phpkey : 'Filedata',
        type : 'image',
        after : function(info){
            var indexpic = $.globalImgUrl(info.data);
            $(currentUpload).next().val(encodeURIComponent(JSON.stringify(info.data)));
            $('img', currentUpload || 'body').attr('src', indexpic).show().prev().remove();
        }
    });
    $('.img-upload').on({
        click : function(){
            currentUpload = this;
            $('#img-upload-file').trigger('click');
        }
    });
});
</script>
<style>
.wrap{padding:15px;}
h2{border-bottom:1px solid #d1d1d1;padding:5px;}
.each{padding:15px 0;}
.part{border-bottom:1px dotted #dcdcdc;
display:-webkit-box;display:-moz-box;display:box;
-webkit-box-align:center;-moz-box-align:center;box-align:center;
}
.part label{margin-right:8px;}
.part .item:last-child{margin-left:50px;}
.edit-tab{position:relative;top:1px;}
.edit-tab li{cursor:pointer;float:left;width:130px;height:44px;line-height:44px;background:#f0f0f0;text-align:center;font-size:14px;}
.edit-tab .on{background-color:#454545;color:#fff;}
.edit-content{border:1px solid #ccc;}
.edit-content li{display:none;width:1000px;height:300px;}
.edit-content .on{display:block;}
form{display:block;padding:0 10px;}
input[type="submit"]{border:none;padding:0;height:38px;line-height:38px;width:138px;text-align:center;color:#fff;background:#5f9be4;cursor:pointer;margin-top:5px;font-size:16px;border-radius:2px;}
.editor{width:100%;height:100%;}
.ace_print-margin{display:none !important;}
.down_list{display:inline-block;}

.editbox{position:relative;}
.option{position:absolute;right:10px;top:20px;z-index:2;}
.option a{display:inline-block;height:22px;line-height:22px;padding:0 15px;background:#dd5a52;color:#fff;cursor:pointer;border-radius:2px;}
.option a:last-child{background-color:#f6b900;margin-left:10px;}

.dys{position:absolute;left:300px;top:15px;z-index:3;padding:5px;height:20px;background:rgb(104, 35, 35);color:#fff;display:none;}
.dys label{float:left;}
.dys em{color:green;font-size:12px;font-weight:bold;font-style:normal;}
.dys ul{float:left;max-width:420px;height:25px;overflow:hidden;}
.dys li{float:left;padding:0 15px;margin-right:5px;background:green;}
.dys div{position:absolute;top:-100%;left:0;background:red;color:#fff;padding:3px 5px;}
.dys span{background:#333;}

.no-close.ui-dialog{border-radius:0;}
.no-close .ui-widget-header{border:none;background:none;}
.no-close.ui-dialog .ui-dialog-titlebar-close{background:#333;border-radius:0;}
.no-close.ui-dialog .ui-dialog-titlebar-close:hover{background:#333;}

.img-item{border-bottom:1px dotted #dcdcdc;}
.img-upload{cursor:pointer;position:relative;height:100px;min-width:100px;padding:10px 0;display:inline-block;}
.img-upload span{height:100%;line-height:120px;position:absolute;z-index:1;left:0;top:0;width:100px;}
.img-upload img{position:relative;z-index:2;max-height:100px;}

iframe{height:300px;width:600px;display:block;overflow-x:hidden;}
</style>
<div class="wrap clear">
<h2>{$optext}布局</h2>
<form action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post">
    <div class="part each">
        <div class="item">
            <label>布局分类：</label>
            {code}
                $item_source = array(
                    'class' => 'down_list',
                    'show' => 'item_show',
                    'width' => 100,/*列表宽度*/
                    'state' => 0, /*0--正常数据选择列表，1--日期选择*/
                    'is_sub'=>1,
                );
                $layout_node = $layout_node[0];
                $node_data[0] = '选择分类';
                $default = $node_id ? $node_id : 0;
                foreach($layout_node as $k =>$v)
                {
                    $node_data[$v['id']] = $v['title'];
                }
            {/code}
            {template:form/search_source,node_id,$default,$node_data,$item_source}
        </div>
        <div class="item">
            <label>标题：</label>
            <input type="text" name="title" value="{$title}" style="width:150px;padding:0 2px;height:22px;"/>
        </div>
    </div>

    <div class="img-item item">
        <div class="img-upload">
            {code}
            $indexpic_encode = $indexpic ? urlencode(stripcslashes(json_encode($indexpic))) : '';
            $indexpic = $indexpic ? $indexpic['host'] . $indexpic['dir'] . $indexpic['filepath'] . $indexpic['filename'] : '';
            {/code}
            {if !$indexpic}<span>上传小的示意图</span>{/if}
            <img src="{$indexpic}" style="{if !$indexpic}display:none;{/if}"/>
        </div>
        <input type="hidden" name="indexpic" value="{$indexpic_encode}"/>
        <br/>
        <div class="img-upload">
            {code}
            $indexpic_encode_2 = $indexpic_2 ? urlencode(stripcslashes(json_encode($indexpic_2))) : '';
            $indexpic_2 = $indexpic_2 ? $indexpic_2['host'] . $indexpic_2['dir'] . $indexpic_2['filepath'] . $indexpic_2['filename'] : '';
            {/code}
            {if !$indexpic_2}<span>上传大的效果图</span>{/if}
            <img src="{$indexpic_2}" style="{if !$indexpic_2}display:none;{/if}"/>
        </div>
        <input type="hidden" name="indexpic_2" value="{$indexpic_encode_2}"/>

        <input type="file" style="display:none;" id="img-upload-file" />
    </div>

    <div class="editbox each">
        <div id="dys" class="dys">
            <label>单元（<em></em>）：</label>
            <ul></ul>
            <div>有重复的单元：<span></span></div>
        </div>
        <div class="option">
   			{code}
	      	    $ext = urlencode("layout_id=" . $id);
	      	    $gmid = $_INPUT['mid'];
		    {/code}
            <a href="magic/main.php?gmid={$gmid}&ext={$ext}&bs=b" id="ys" go-blank target="_blank">进魔力视图预设</a>
            <a href="magic/magic.php?a=preview&gmid={$_INPUT['mid']}&layout_id={$id}&bs=b" id="yl" target="_blank">预览</a>
        </div>
        <ul class="edit-tab clearfix">
            <li class="on">内容</li>
            <li>样式</li>
        </ul>
        <ul class="edit-content">
            <li class="on">
                <textarea name="content" id="html-input" style="display:none;">{$content}</textarea>
                <div id="html" class="editor">{$content}</div>
            </li>
            <li>
                <textarea name="css" id="css-input" style="display:none;">{$css}</textarea>
                <div id="css" class="editor">{$css}</div>
            </li>
        </ul>
    </div>
    <input type="submit" value="{$optext}布局"/>

<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />

</form>

<div id="yl-box"><iframe 1scrolling="no" frameborder="0"></iframe></div>
</div>

{template:foot}