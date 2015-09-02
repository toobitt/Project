<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<!--
<style>
html,body{height:100%;width:100%;}
#scroll-box{height:100%;width:100%;overflow:auto;}
</style>
-->
{css:2013/index}

{code}
$dir = $RESOURCE_URL.'menu2013/app/';
$style = '';
$style2x = '';
$checkImgs = array();
$appendMenusClone = array();

$numer = 0;
foreach($menu_apps as $key => $val)
{
    foreach($val as $kk => $vv)
	{
		$appendMenusClone[$vv['app_uniqueid']] = $vv;
        $checkImgs[$vv['app_uniqueid']] = $dir . $vv['app_uniqueid'] . '.png';

        $style .= '.app-item.app-' . $vv['app_uniqueid'] . ' .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-bai.png);}';
        $style .= "\n";
        $style .= '.index-apps .app-item.app-' . $vv['app_uniqueid'] . ' .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'.png);}';
        $style .= "\n";
        $style .= '.index-apps .app-item.app-' . $vv['app_uniqueid'] . ':hover .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-bai.png);}';

        $style .= "\n";
        $style2x .= '.app-item.app-' . $vv['app_uniqueid'] . ' .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-bai-2x.png);}';
        $style .= "\n";
        $style2x .= '.index-apps .app-item.app-' . $vv['app_uniqueid'] . ' .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-2x.png);}';
        $style .= "\n";
        $style2x .= '.index-apps .app-item.app-' . $vv['app_uniqueid'] . ':hover .app-item-name{background-image:url(' . $dir . $vv['app_uniqueid'] .'-bai-2x.png);}';
        $style .= "\n";
		$numer++;
    }
}



$number = ceil($numer / 16);

$checkImgsLength = count($checkImgs);

$myapps = array();
$custom = trim($_user['app_custom_menus']);
$myapp_bs = $custom ? explode(',', $custom) : array();
$myapp_count = count($myapp_bs);
if($myapp_count){
	foreach($myapp_bs as $val){
		$appendMenusClone[$val] && $myapps[] = $appendMenusClone[$val];
	}
}
$colors = array('0091db', 'e5012c', '59b630', 'ffb40e', 'a747a7', 'cccd19', 'ed4988', '61cccd');
$menuColors = array();
$index = 0;
$max = count($colors) - 1;
foreach($menu_group as $key => $val){
    if($index == $max){
        $index = 0;
    }
    $menuColors[$key] = $colors[$index];
    $index++;
}

{/code}
<script>
var checkImgs = {code}echo json_encode($checkImgs);{/code};
var noImgs = [];
var checkLength = {$checkImgsLength};
var checkIndex = 0;
$.each(checkImgs, function(i, n){
    var img = new Image();
    img.uniqueid = i;
    img.onload = function(){
        checkIndex++;
        if(checkIndex >= checkLength){
            checkResult();
        }
    };
    img.onerror = function(){
        checkIndex++;
        noImgs.push(this.uniqueid);
        if(checkIndex >= checkLength){
            checkResult();
        }
    };
    img.src = n;
});

var checkResultState = false;
function checkResult(){
    if(checkResultState){
        return;
    }
    checkResultState = true;
    $(function(){
        var style = '';
        var style2x = '';
        $.each(noImgs, function(i, n){
            style += '.app-item.app-' + n + ' .app-item-name{background-image:url({$dir}default-bai.png) !important;}';
            style += '.index-apps .app-item.app-' + n + ' .app-item-name{background-image:url({$dir}default.png) !important;}';
            style += '.index-apps .app-item.app-' + n + ':hover .app-item-name{background-image:url({$dir}default-bai.png) !important;}';
            style2x += '.app-item.app-' + n + ' .app-item-name{background-image:url({$dir}default-bai-2x.png) !important;}';
            style2x += '.index-apps .app-item.app-' + n + ' .app-item-name{background-image:url({$dir}default-2x.png) !important;}';
            style2x += '.index-apps .app-item.app-' + n + ':hover .app-item-name{background-image:url({$dir}default-bai-2x.png) !important;}';
        });
        style += '@media only screen and (-webkit-min-device-pixel-ratio: 2),only screen and (-moz-min-device-pixel-ratio: 2),only screen and (-o-min-device-pixel-ratio: 2/1),only screen and (min-device-pixel-ratio: 2) {';
        style += style2x;
        style += '}';
        $('<style>'+ style +'</style>') .appendTo('body');
    });
}
</script>
<style>
{code}
echo '.app-item-name{background:url(' . $RESOURCE_URL .'menu2013/app/default.png) no-repeat left center;background-size:30px;}';
echo '.index-apps .app-item-name{background-size:48px;}';
echo $style;
echo '@media only screen and (-webkit-min-device-pixel-ratio: 2),only screen and (-moz-min-device-pixel-ratio: 2),only screen and (-o-min-device-pixel-ratio: 2/1),only screen and (min-device-pixel-ratio: 2) {';
echo '.app-item-name{background-image:url(' . $RESOURCE_URL .'menu2013/app/default-2x.png);background-size:30px;}';
echo '.index-apps .app-item-name{background-image:url(' . $RESOURCE_URL .'menu2013/app/default-2x.png);background-size:48px;}';
echo $style2x;
echo '}';
{/code}
</style>


{code}
if(0){
echo '<div style="1display:none;position:fixed;right:0px;top:0px;bottom:0px;z-index:100000000;background:#fff;width:200px;overflow:auto;">';
echo '<div><input style="width:100px;" id="find-app-name-uniqueid" placeholder="搜索"/></div>';
echo '<script>
$(function($){
$("#find-app-name-uniqueid").on("change blur", function(){
    var val = $.trim($(this).val());
    if(!val){
        $(".app-name-uniqueid").show();
    }else{
        $(".app-name-uniqueid").filter(function(){
            var check = $(this).data("name").indexOf(val) == -1;
            $(this).show();
            return check;
        }).hide();
    }
});
});
</script>';
foreach($appendMenusClone as $_kk => $_vv){
    echo '<div class="app-name-uniqueid" data-name="' . $_vv['name'] . '">' . $_vv['name'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $_vv['app_uniqueid'] . '</div>';
}
echo '</div>';
}
{/code}



<div id="scroll-box">
<div class="menu-box fixed">
    <div class="menu-top">
        <div class="menu-inner">
            <div class="menu-info">
                <a href="./index.php" class="menu-logo"></a>
                <span class="home-title">新媒体综合运营平台</span>
                <span class="app-title" style="display:none;"></span>
            </div>
            <div class="menu-other">
                <div class="user-box">
                    <a target="mainwin" href="./infocenter.php" class="user-info" _selfname="个人设置">
                        {code}
                        $avatar = $_user['avatar'];
                        $pic = $avatar['host'] . $avatar['dir'] . '48x48/' . $avatar['filepath'] . $avatar['filename'];
                        {/code}
                        <span class="user-pic" style="background-image:url({$pic});"></span>
                        <span class="user-name">{$_user['user_name']}</span>
                    </a>
                    <ul class="user-option">
						{if $_user['group_type'] <= $_settings['max_admin_type']}
						<li class="appstore-option"><a href="appstore.php" target="formwin">应用商店</a></li>
						<li class="upgrade-option"><a href="upgrade.php">平台更新</a></li>
						<li class="stats-option"><a target="mainwin" href="stats.php" _selfname="运行状态">运行状态</a></li>
						{if $_settings['hostmanage']}<li class="stats-option"><a href="{$_settings['hostmanage']}" target="mainwin" _selfname="云主机">云主机</a></li>{/if}
						{/if}
                        <li class="infocenter-option"><a target="mainwin" href="./infocenter.php" _selfname="个人设置">个人设置</a></li>
                        <li class="logout-option"><a href="./login.php?a=logout">退出</a></li>
                    </ul>
                </div>
                <a href="#" class="menu-app">应用</a>
                <a href="#" class="menu-home on" style="display:none;">主页</a>
            </div>
        </div>
    </div>

    <div class="app-box hidden">
        <div class="app-inner">
            <div class="app-list app-list-normal">
                {foreach $myapps as $key => $val}
                    {code}
                    $href = $val['url'] . ($val['id'] ? '&menuid='.$val['id'] : '');
                    {/code}
                    <a target="mainwin" href="{$href}" _color="{$menuColors[$val['father_id']]}" class="app-item app-item-{$menuColors[$val['father_id']]} app-{$val['app_uniqueid']}" _app="{$val['app_uniqueid']}">
                        <span class="app-item-name">{$val['name']}</span>
                        <span class="app-item-del">x</span>
                    </a>
                {/foreach}
                {for $reduce = 8 - count($myapps); $reduce > 0; $reduce--}
                    <a href="javascript:;" class="app-item app-kong"></a>
                {/for}
            </div>
        </div>
    </div>

    <div class="app-options">
        <div class="app-cat fenye">
            <span class="app-cat-fenzu" _type="fenzu">分组</span>
            <span class="app-cat-fenye" _type="fenye">分页</span>
        </div>
        <span class="app-set">设置</span>
        <div class="app-tag">
            <ul class="app-tag-fenzu" _type="fenzu">
                {foreach $menu_group as $key => $val}
                <li _name="{$val['name']}" _index="{$key}"><span class="tag-span"></span><span class="tag-name">{$val['name']}</span></li>
                {/foreach}
            </ul>
            <ul class="app-tag-fenye" _type="fenye">
                {for $index = 1; $index <= $number; $index++}
                <li _index="{$index}"><span class="tag-span"></span><span class="tag-name">{$index}</span></li>
                {/for}
            </ul>
        </div>
    </div>
</div>

<div class="brige-box"></div>
<div class="index-box">
    <div class="index-inner">

        <div class="index-apps index-apps-fenye" style="display:none;">
        {code}
        $index = 1;
        $kai = false;
        {/code}
        {foreach $menu_apps as $key => $val}
        {if $val}
            {foreach $val as $kk => $vv}
                {code}
                $currentYe = ceil($index / 16);
                $href = $vv['url'] . ($vv['id'] ? '&menuid='.$vv['id'] : '');
                {/code}
                {if $index % 16 == 1}
                    {code}
                        $kai = true;
                    {/code}
                    <div class="app-each" _fenye="{$currentYe}">
                        <span class="app-each-title">{$currentYe}</span>
                {/if}
                {if !$vv['disabled']}
                <a target="mainwin" href="{$href}" _color="{$menuColors[$key]}" _app="{$vv['app_uniqueid']}" class="app-item app-item-{$menuColors[$key]} app-{$vv['app_uniqueid']}">
                    <span class="app-item-name">{$vv['name']}</span>
                </a>
                {else}
                <a href="javascript:;" class="app-item-disabled app-item-{$menuColors[$key]} app-{$vv['app_uniqueid']}"><span class="app-item-name">{$vv['name']}</span></a>
                {/if}
                {if $index % 16 == 0}
                    {code}
                        $kai = false;
                    {/code}
                    <br/>
                    </div>
                {/if}
                {code}
                $index++;
                {/code}
            {/foreach}

        {/if}
        {/foreach}
        {if $kai}
            <br/>
            </div>
        {/if}
        </div>

        <div class="index-apps index-apps-fenzu" style="display:none;">
        {foreach $menu_apps as $key => $val}
        {if $val}
            <div class="app-each app-each-{$menuColors[$key]}" _fenzu="{$key}">
                <span class="app-each-title">{$menu_group[$key]['name']}</span>
                {foreach $val as $kk => $vv}
                    {code}

                    $href = $vv['url'] . ($vv['id'] ? '&menuid='.$vv['id'] : '');
                    {/code}
                    {if !$vv['disabled']}
                    <a target="mainwin" href="{$href}" _color="{$menuColors[$key]}" _app="{$vv['app_uniqueid']}" class="app-item app-item-{$menuColors[$key]} app-{$vv['app_uniqueid']}">
                        <span class="app-item-name">{$vv['name']}</span>
                    </a>
                    {else}
                    <a href="javascript:;" class="app-item-disabled app-item-{$menuColors[$key]} app-{$vv['app_uniqueid']}"><span class="app-item-name">{$vv['name']}</span></a>
                    {/if}
                {/foreach}
                <br/>
            </div>
        {/if}
        {/foreach}
        </div>
    </div>
</div>

<img src="{$RESOURCE_URL}loading2.gif" id="top-loading"/>
<div class="top-box" style="display:none;opacity:0;">
    <div class="top-nav"></div>
    <div id="livwinarea1" class="top-iframe-box">
        <iframe frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true" src="{$iframe_attr}" name="mainwin" id="mainwin" class="top-iframe"></iframe>
    </div>
</div>

</div>




<iframe frameborder="no" scrolling="auto" hidefocus="hidefocus" allowtransparency="true" style="display:none;opacity:0;" name="formwin" id="formwin" class="top-iframe-form"></iframe>

{js:iframe/option_iframe}
{js:domcached/jquery.json-2.2.min}
{js:domcached/domcached-0.1-jquery}
{js:2013/index}
<script>
{code}
$host = '';
$request = $_SERVER["REQUEST_URI"];
if(strpos($request, 'livworkbench') !== false){
    $host = $host.'/livworkbench';
}
{/code}
var serverHost = '{$host}';
window.featherEditorConfig = {
    imgrecvServer: serverHost +"/canvas/check.php",

    imgrecvBase: serverHost +"/canvas/",

    imgtrackServer: serverHost +"/canvas/check.php",

    jsonp_imgserver: serverHost +"/canvas/getImg.php",

    featherTargetAnnounce: serverHost +"/canvas/feather_target_announce.html",

    featherFilterAnnounce: serverHost +"/canvas/feather_filter_announce.html",

    feather_baseURL: serverHost +"/canvas/"
}
</script>
<script src="./canvas/js/feather.js"></script>
<script>
$(function(){
    window['featherEditor'] = window['featherEditor'] || new Aviary.Feather({
        apiKey: '111',
        apiVersion: 2,
        language : 'zh_hans',
        tools: 'all',
        maxSize : 3000,
        onSave: function(imageID, newURL) {
            $('body').trigger('_picsave', [newURL]);
        }
    });
});
</script>

{code}
$__top = true;
{/code}

{code}
foreach ($menu_apps as $apps) {
	foreach($apps as $app) {
		$mid_to_app_uniqueid[$app['module_id']] = $app['app_uniqueid'];
	}
}
{/code}
<script>
(function($){
    $.globalData = (function(){
        var cache = {};
        return {
            set : function(key, value, append){
                if(append){
                    $.extend(cache[key], value);
                }else{
                    cache[key] = value;
                }
            },

            get : function(key){
                return $.type(cache[key]) == 'undefined' ? '' : cache[key];
            },

            destroy : function(key){
                delete cache[key];
            }
        }
    })();
})(jQuery);
$.globalData.set('quanzhong', {code} echo json_encode($weight ? $weight : array()); {/code});
$.globalData.set('mid_to_app_uniqueid', {code} echo json_encode($mid_to_app_uniqueid); {/code});
$.globalData.set('setting_prms', {code} echo $_user['prms_menus'] ? json_encode($_user['prms_menus']) : '{}'; {/code});
$.globalData.set('MAX_ADMIN_TYPE', {code}echo MAX_ADMIN_TYPE;{/code});
</script>


<!-- ***授权到期*** -->
{if $licenseInfo["tip_way"] || ($licenseInfo['expire_time'] && $licenseInfo["leftday"] < 30)}
{js:2013/authtip}
{if $licenseInfo['expire_time']}
{code}
$string = '您当前使用的M2O产品还不是正式授权，到期时间为' . $licenseInfo["expire"] . '，感谢您的使用和建议！';
{/code}
{else}
{code}
$string = '您当前使用的M2O产品还不是正式授权，感谢您的使用和建议！';
{/code}
{/if}
<script>
$.m2oAuth = {
    type : '{$licenseInfo["tip_way"]}' || '{$_REQUEST["t"]}' || 'tc',
    duration : '{$licenseInfo["tip_dur"]}' || '{$_REQUEST["d"]}' || 10,
    string : '{$string}'
};
</script>
{/if}
{if $licenseInfo['openhelp']}
<script type="text/javascript">
var helpWinState = 0;
function hg_openHelp()
{
	if (helpWinState == 0)
	{
		var close = '<span  onclick="hg_openHelp();" style="position: fixed; z-index: 10000002; right: 26px; top: 16px; width: 16px; height: 16px; line-height: 14px; cursor: pointer; border-radius: 2px; color: rgb(255, 255, 255); font-size: 16px; font-weight: bold; text-align: center; background: rgb(186, 38, 38);">x</span>';
		var html = close + '<iframe frameborder="no" hidefocus="hidefocus" allowtransparency="true" src="http://service.liv.cn/?_m2ocode={$_m2ocode}" name="helpwin" id="helpwin" style="width:100%;height:100%;overflow:hidden;"></iframe>';
		$('#rightpanel').html(html);
		$('#rightpanel').show();
		helpWinState = 2;
	}
	else if(helpWinState == 1)
	{
		$('#rightpanel').show();
		helpWinState = 2;
	}
	else if (helpWinState > 0)
	{
		$('#rightpanel').hide();
		helpWinState = 1;
	}
}

var gTimer, gBTitle = '';
function hg_setTitle(title, san)
{
	top.document.title = title + '{$this->mTemplatesTitle}';
	clearTimeout(gTimer);
	if (san == 1)
	{
		gTimer = setTimeout("hg_setTitle('　　　　　　', 2)", 1000);
	}
	else if (san == 2)
	{
		gTimer = setTimeout("hg_setTitle('" + gBTitle + "', 1)", 1000);
	}
}

function hg_alarmreply(data)
{
	data = eval(data);
	try
	{
		if (data.ErrorCode)
		{
			return;
		}
	}
	catch (e)
	{
	}
	if (data.count.reply > 0)
	{
		if (data.count.reply > 99)
		{
			data.count.reply = '...';
		}
		$('#newhelpreply').html(data.count.reply);
		$('#newhelpreply').show();
		helpWinState = 0;
		gBTitle = '【' + data.count.reply + ' 条新回复】';
		hg_setTitle(gBTitle, 1);
	}
	else
	{
		$('#newhelpreply').html('');
		$('#newhelpreply').hide();
		hg_setTitle('{$this->mTemplatesTitle}', 0);
	}
	setTimeout("hg_check_newreply()", data.timeout);
}
function hg_check_newreply()
{
	var script = document.createElement("script");
	script.id='scriptid1';
	script.src = 'http://service.liv.cn/newreply.php?_m2ocode={$_m2ocode}&callback=hg_alarmreply';
	document.body.appendChild(script);
	document.getElementById('scriptid1').remove();
}
hg_check_newreply();
</script>
<div id="helpframe" class="m2o-to-top" style="cursor:pointer;display: block;font-size: 14px;bottom: 74px;right: 20px;background: #666;width: 54px;height: 24px;text-align: center;color: #fff;padding-top: 6px;" onclick="hg_openHelp();"><span id="newhelpreply" style="position: fixed; z-index: 10000002; width: 18px; height: 18px; line-height: 16px; border-radius: 9px; color: #fff; font-size: 10px; text-align: center; background: red;right:14px;bottom:94px;display:none">0</span>帮助</div>
<style type="text/css">
		#rightpanel{  display:none;position: fixed;height: 83%;width: 80%;right: 20px;top:10px;border: #ccc solid 1px;background: #ddd;overflow: hidden;padding: 4px;bottom: 106px;z-index: 9999;}
</style>
<div id="rightpanel"></div>
{/if}
{template:foot}