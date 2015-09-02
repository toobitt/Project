<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"{$_scroll_style}>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
-->
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}</title>

{csshere}

{css:default}
{css:public}
{css:upload}
{css:jquery-ui-min}

<script type="text/javascript">
var gPixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
var RESOURCE_URL = '{$RESOURCE_URL}';
var SCRIPT_URL = '{$SCRIPT_URL}';
var client_id = 1;
var gMid = '{$_INPUT['mid']}';
var gMenuid = '{$_INPUT['menuid']}';
var gRelate_module_id = '{$relate_module_id}';
var gToken = "{$_user['token']}";
var show_conf_menu='{$show_conf_menu}';
</script>
{$this->mHeaderCode}

{jshere}

{js:jquery.min}
{js:jquery-ui-min}
{js:jquery.form}

{js:jqueryfn/jquery-ui-timepicker-addon}
{js:jqueryfn/jquery.tmpl.min}
{js:hg_datepicker}
{js:jqueryfn/jquery.switchable-2.0.min}
{js:hg_switchable}
{js:global}
{js:alertbox.min}
{js:alertbox}
{js:md5}
{js:common}
{js:ajax}
{js:swfupload}
{js:swfupload.queue}
{js:fileprogress}
{js:handlers}
{js:livUpload}
{js:upload}
{js:vod}
{js:swfobject}
{js:lazyload}
{js:DatePicker/WdatePicker}
{js:common/pic_edit}

<script>
jQuery(function($){
    $.pixelRatio = gPixelRatio;
    if($.pixelRatio > 1){
        $('img.need-ratio').each(function(){
            $(this).attr('src', $(this).attr('_src2x'));
        });
    }


});
</script>
{if $_INPUT['infrm']}
<style type="text/css">
	/*body{background:#fff;}
	.wrap{border:0;box-shadow:none;padding:10px 0 10px 10px;}*/
	.wrap .search_a{padding:0}
</style>
{/if}

<?php 
/* $Id: use jquery plugin alert - zhangfeihu $ */
?>
{css:alert/jquery.alerts}
{css:jquery-ui-custom}
{js:alert/jquery.alerts}

</head>
<body{$this->mBodyCode}{$_scroll_style}>
{template:head/nav}

