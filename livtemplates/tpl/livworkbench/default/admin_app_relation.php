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
$myapps[] = $appendMenusClone[$val];
}
}
$colors = array('0091db', 'e5012c', '59b630', 'ffb40e', 'a747a7', 'cccd19', 'ed4988', '61cccd');
$menuColors = array();
$index = 0;
foreach($menu_group as $key => $val){
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
                        <a target="mainwin" href="./infocenter.php" class="user-info">
                            {code}
                            $avatar = $_user['avatar'];
                            $pic = $avatar['host'] . $avatar['dir'] . '24x/' . $avatar['filepath'] . $avatar['filename'];
                            {/code}
                            <span class="user-pic" style="background:url({$pic}) no-repeat center center #fff;"></span>
                            <span class="user-name">{$_user['user_name']}</span>
                        </a>
                        <ul class="user-option">
                        </ul>
                    </div>
                    <a href="#" class="menu-app" style="display:none;">应用</a>
                    <a href="#" class="menu-home on">主页</a>
                </div>
            </div>
        </div>

        <div class="app-options">
            <div class="app-cat fenye">
                <span class="app-cat-fenye" _type="fenye">分页</span>
                <span class="app-cat-fenzu" _type="fenzu">分组</span>
            </div>
            <span class="app-set">设置</span>
            <div class="app-tag">
                <ul class="app-tag-fenye" _type="fenye">
                    {for $index = 1; $index <= $number; $index++}
                    <li _index="{$index}"><span class="tag-span"></span><span class="tag-name">{$index}</span></li>
                    {/for}
                </ul>
                <ul class="app-tag-fenzu" _type="fenzu">
                    {foreach $menu_group as $key => $val}
                    <li _name="{$val['name']}" _index="{$key}"><span class="tag-span"></span><span class="tag-name">{$val['name']}</span></li>
                    {/foreach}
                </ul>
            </div>
        </div>
    </div>

    <div class="brige-box {if $myapp_count}brige-app{/if}"></div>
    <div class="index-box">
        <div class="index-inner">

            <div class="index-apps index-apps-fenye">
                {code}
                $index = 1;
                $kai = false;
                {/code}
                {foreach $menu_apps as $key => $val}
                {if $val}
                {foreach $val as $kk => $vv}
                {code}
                $currentYe = ceil($index / 16);
                $href = '?app=' . $vv['app_uniqueid'];;
                {/code}
                {if $index % 16 == 1}
                {code}
                $kai = true;
                {/code}
                <div class="app-each" _fenye="{$currentYe}">
                    <span class="app-each-title">{$currentYe}</span>
                    {/if}
                    <a target="{if $vv['status'] == 1}mainwin{else}_top{/if}" href="{$href}" _color="{$menuColors[$key]}" _app="{$vv['app_uniqueid']}" class="app-item app-item-{$menuColors[$key]} app-{$vv['app_uniqueid']}">
                        <span class="app-item-name">{$vv['name']}</span>
                    </a>
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

        <div class="index-apps index-apps-fenzu">
            {foreach $menu_apps as $key => $val}
            {if $val}
            <div class="app-each app-each-{$menuColors[$key]}" _fenzu="{$key}">
                <span class="app-each-title">{$menu_group[$key]['name']}</span>
                {foreach $val as $kk => $vv}
                {code}

                $href = '?app=' . $vv['app_uniqueid'];
                {/code}

                <a target="{if $vv['status'] == 1}mainwin{else}_top{/if}" href="{$href}" _color="{$menuColors[$key]}" _app="{$vv['app_uniqueid']}" class="app-item app-item-{$menuColors[$key]} app-{$vv['app_uniqueid']}">
                    <span class="app-item-name">{$vv['name']}</span>
                </a>
                {/foreach}
                <br/>
            </div>
            {/if}
            {/foreach}
        </div>
    </div>
</div>

<div class="top-box" style="display:none;opacity:0;">
    <div class="top-nav"></div>
    <div id="livwinarea1" class="top-iframe-box">
        <iframe frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true" src="{$iframe_attr}" name="mainwin" id="mainwin" class="top-iframe"></iframe>
        <img src="{$RESOURCE_URL}loading2.gif" id="top-loading"/>
    </div>
</div>

</div>
{if $appinfo['app_uniqueid']}
<div style="position:absolute;top:250px;left:150px;border:1px #999 solid;background:#eee;padding:10px;width:600px;margin:auto;z-index:999">
    <h2>{$appinfo['name']}</h2>
    <div>
        <a href="admin_app_relation.php?a=build_relationship&app={$appinfo['app_uniqueid']}" style="color:#ff0000;">扫描依赖关系</a>
    </div>
    <div>
        <span>依赖应用：</span>
            {code}
               //print_r($appinfo);
               $appinfo['relyonapps'] = explode(',', $appinfo['relyonapps']);
            {/code}
            {foreach $appinfo['relyonapps'] as $k => $v}
               <span>{$v}</span>
            {/foreach}
    </div>
    <div>
        <a href="admin_app_relation.php?a=edit_relationship&app={$appinfo['app_uniqueid']}" style="color:#ff0000;">编辑</a>
    </div>
</div>
{/if}
<iframe frameborder="no" scrolling="auto" hidefocus="hidefocus" allowtransparency="true" style="display:none;opacity:0;" name="formwin" id="formwin" class="top-iframe-form"></iframe>

{js:iframe/option_iframe}
{js:domcached/jquery.json-2.2.min}
{js:domcached/domcached-0.1-jquery}
{js:2013/index}
<script>
    {code}
    $host = $_SERVER["HTTP_HOST"];
    $request = $_SERVER["REQUEST_URI"];
    if(strpos($request, 'livworkbench') !== false){
        $host = $host.'/livworkbench';
    }
    {/code}
    var serverHost = '{$host}';
    window.featherEditorConfig = {
        imgrecvServer: "http://"+ serverHost +"/canvas/check.php",

        imgrecvBase: "http://"+ serverHost +"/canvas/",

        imgtrackServer: "http://"+ serverHost +"/canvas/check.php",

        jsonp_imgserver: "http://"+ serverHost +"/canvas/getImg.php",

        featherTargetAnnounce: "http://"+ serverHost +"/canvas/feather_target_announce.html",

        featherFilterAnnounce: "http://"+ serverHost +"/canvas/feather_filter_announce.html",

        feather_baseURL: "http://"+ serverHost +"/canvas/"
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
            onSave: function(imageID, newURL) {
                $('body').trigger('_picsave', [newURL]);
            }
        });
    });
</script>

{code}
$__top = true;
{/code}
{template:foot}