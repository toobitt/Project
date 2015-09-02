<?php 
/* $Id: head_bootstrap.tpl.php 1 2014-11-10 15:08:29Z zhangzhen $ */
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}</title>

{csshere}

{css:base/reset}
{css:jquery-ui/1.11.2/jquery-ui.min}
{css:jquery-ui/jquery-ui-custom}
{css:bootstrap/3.3.0/bootstrap.min}

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

{js:bootstrap/3.3.0/jquery-1.11.1.min}
{js:jquery-ui/1.11.2/jquery-ui.min}
{js:jqueryfn/jquery.form}

{js:jqueryfn/jquery-ui-timepicker-addon}
{js:jqueryfn/jquery.tmpl.min}
{js:jqueryfn/jqueryfn_custom/ajaxload_new}
{js:jqueryfn/jqueryfn_custom/hg_datepicker}
{js:md5}


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

</head>
<body{$this->mBodyCode}{$_scroll_style}>
{template:head/nav}

