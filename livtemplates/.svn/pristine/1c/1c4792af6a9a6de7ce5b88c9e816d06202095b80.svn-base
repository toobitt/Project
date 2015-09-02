<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{code}
    /*授权的应用标识*/
    $_auth_app_uniqueid = array();
    if($_user['menu'])
    {
        $_auth_app_uniqueid = @array_keys($_user['menu']);
    }
{/code}
{js:domcached/jquery.json-2.2.min}
{js:domcached/domcached-0.1-jquery}
{js:menu}
{code}$menu_type = $_INPUT['mt'];{/code}

{code}
$styles = '';
$ratioStyles = '
    @media only screen and (-webkit-min-device-pixel-ratio: 2),
    only screen and (-moz-min-device-pixel-ratio: 2),
    only screen and (-o-min-device-pixel-ratio: 2/1),
    only screen and (min-device-pixel-ratio: 2) {
';
$dir = $RESOURCE_URL.'menu2012/';
$jsimgs = array();
$jsimgs2x = array();
$cssmenus = $cssmenuchilds = array();
if($append_menus){
    foreach($append_menus as $k => $v){
        $bgname = $v['class'] ? $v['class'] : 'vod';
        if(!in_array($bgname, $cssmenus)){
            $cssmenus[] = $bgname;
        }

        if($v['childs']){
            foreach($v['childs'] as $kk => $vv){
                $bgnamechild = $vv['class'];
                if($bgnamechild && !in_array($bgnamechild, $cssmenuchilds)){
                    $cssmenuchilds[] = $bgnamechild;
                }
            }
        }
    }
}
$cssmenus[] = 'nav';
$filterMenu = array();
foreach($cssmenus as $k => $v){
    if(in_array($v, filterMenu)){
        continue;
    }
    $bgname = $v ? $v : 'vod';
    $img = $dir.$bgname;
    $img_n = $dir.$bgname.'.png';
    $img_h = $dir.$bgname.'-h.png';
    $img_2n = $dir.$bgname.'-2x.png';
    $img_2h = $dir.$bgname.'-h-2x.png';
    $cname = ($v ? '.'.$v : '');
    if($bgname == 'vod'){
        $cname = '';
    }
    $styles .= '
        .menu-li'.$cname.' .menu-icon{background:url('.$img_n.') no-repeat;}
        .menu-li'.$cname.':hover .menu-icon{background-image:url('.$img_h.');}
        .menu-li'.$cname.'.gaoliang .menu-icon{background-image:url('.$img_h.');}
    ';

    $ratioStyles .= '
        .menu-li'.$cname.' .menu-icon{background:url('.$img_2n.') no-repeat;background-size:100%;}
        .menu-li'.$cname.':hover .menu-icon{background-image:url('.$img_2h.');}
        .menu-li'.$cname.'.gaoliang .menu-icon{background-image:url('.$img_2h.');}
    ';

    $jsimgs[] = $img_n;
    $jsimgs[] = $img_h;
    $jsimgs2x[] = $img_2n;
    $jsimgs2x[] = $img_2h;
}
foreach($menus as $kk => $vv){
    $bgnamechild = $vv['class'];
    if($bgnamechild && !in_array($bgnamechild, $cssmenuchilds)){
        $cssmenuchilds[] = $bgnamechild;
    }
}
$filterNav = array('fb-qdbs', 'fb-ydzd', 'nav-server', 'nav_index', 'nav-setting_group', 'nav-settings', 'nav-module', 'nav-sort', 'nav-plogin', 'nav-site', 'nav-columns', 'nav-log', 'nav-crontab', 'hdyy-hdgl');
foreach($cssmenuchilds as $k => $v){
    if(in_array($v, $filterNav)){
        continue;
    }
    $bgname = $v;
    $img = $dir.$bgname;
    $img_n = $dir.$bgname.'.png';
    $img_h = $dir.$bgname.'-h.png';
    $img_2n = $dir.$bgname.'-2x.png';
    $img_2h = $dir.$bgname.'-h-2x.png';
    $cname = ($v ? '.'.$v : '');
    if(!cname){
        continue;
    }
    $styles .= '
        .menu-child'.$cname.' .menu-child-icon{background:url('.$img_n.') no-repeat;}
        .menu-child'.$cname.':hover .menu-child-icon, .menu-child'.$cname.'.menu-child-current .menu-child-icon{background-image:url('.$img_h.');}


    ';

    $ratioStyles .= '
        .menu-all .menu-child'.$cname.' .menu-child-icon{background:url('.$img_2n.') no-repeat;background-size:100%;}
        .menu-all .menu-child'.$cname.':hover .menu-child-icon, .menu-all .menu-child'.$cname.'.menu-child-current .menu-child-icon{background-image:url('.$img_2h.');}
    ';

    $jsimgs[] = $img_n;
    $jsimgs[] = $img_h;
    $jsimgs2x[] = $img_2n;
    $jsimgs2x[] = $img_2h;
}
$ratioStyles .= '}';
echo '<style type="text/css">/*后期合并图片*/'.$styles.$ratioStyles.'</style>';
echo '<script type="text/javascript">';
echo 'var jsimgs = \''.json_encode($jsimgs).'\';';
echo 'var jsimgs2x = \''.json_encode($jsimgs2x).'\';';
echo 'jQuery(function($){
    var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
    var ref;
    if(pixelRatio > 1){
        ref = jsimgs2x;
    }else{
        ref = jsimgs;
    }
    if(ref){
        $.each($.parseJSON(ref), function(i, n){
            var img = new Image();
            img.src = n;
        });
    }
})';
echo '</script>';
{/code}
<div id="hg_menu" class="nav_menu nav_menu_width{$_settings['menu_mode']}">
    <img style="display:none;" src="{$RESOURCE_URL}menu2012/menu-small-logo.png"/>
    <div id="menu-logo">
        <a href="./index.php"></a>
    </div>
    <div class="menu-all {if $menu_type}menu-all-other{/if}">
            {if !$menu_type}
            <ul class="menu-cats">
                {code}
                    $mi = 0;
                {/code}
                {foreach $append_menus as $k => $v}
                {code}
                    /*过滤菜单*/
                    if($_user['group_type'] > $_settings['max_admin_type'])
                    {
                        if(!$v['include_apps'])
                        {
                           continue;
                        }
                        $_include_apps = explode(',', $v['include_apps']);
                        if(!$_include_apps) continue;
                        if(!array_intersect($_auth_app_uniqueid, $_include_apps))
                        {
                            continue;
                        }
                    }
                {/code}
                <li class="{$v['class']} menu-li" {if $v['childs']}childs="{$k}"{/if} title="{$v['name']}">
                    <span class="menu-icon"></span>{$v['name']}
                </li>
                {code}
                    $mi++;
                {/code}
                {/foreach}
                {if $menus}
                <li class="nav menu-li" {if $v['childs']}childs="0"{/if} title="系统配置">
                    <span class="menu-icon"></span>系统配置
                </li>
                {/if}
            </ul>


            <div class="menu-items">
                {code}
                $append_menus[0] = array(
                    'childs' => $menus
                );
                {/code}
                {foreach $append_menus as $k => $v}
                    {if $v['childs']}
                    <div id="childs_{$k}" class="menu-item">
                        <ul class="menu-childs">
                        {code}
                            $i = 0;
                            $len = count($v['childs']);
                        {/code}
                        {foreach $v['childs'] as $kk => $vv}
                            {code}
                                /*子菜单过滤*/
								 $vv['app_uniqueid'] = ($vv['app_uniqueid'] == 'livmedia')? 'media_channel' : $vv['app_uniqueid'];
								 $vv['mod_uniqueid'] = ($vv['mod_uniqueid'] == 'livmedia')? 'vod' : $vv['mod_uniqueid'];
                                if($_user['group_type'] > $_settings['max_admin_type'])
                                {
                                    if(!in_array($vv['mod_uniqueid'], $_user['menu'][$vv['app_uniqueid']]))
                                    {
                                        continue;
                                    }
                                }
                            {/code}
                            {code}
                                $i++;
                                $last = '';
                                if($len == $i){
                                    $last = 'last';
                                }

                                $href = $vv['url'] . ($vv['id'] ? '&menuid='.$vv['id'] : '');
                            {/code}
                            <li class="{$vv['class']} {$last} menu-child" title="{$vv['name']}"><a href="{$href}" target="mainwin"><span class="menu-child-icon"></span>{$vv['name']}</a><span class="menu-child-select"></span></li>
                        {/foreach}
                        </ul>

                    </div>
                    {/if}
                {/foreach}
            </div>

            {else}

            <ul class="menu-cats">
                {code}
                    $append_menus[0] = array(
                        'name' => '系统配置',
                         'class' => 'nav',
                         'childs' => $menus
                    );
                {/code}
                {foreach $append_menus as $k => $v}
                <li class="{$v['class']} menu-li gaoliang" {if $v['childs']}childs="{$k}"{/if} title="{$v['name']}">
                    <span class="menu-icon"></span>{$v['name']}
                    {if $v['childs']}
                    <div id="childs_{$k}" class="menu-item">
                        <ul class="menu-childs">
                        {code}
                            $i = 0;
                            $len = count($v['childs']);
                        {/code}
                        {foreach $v['childs'] as $kk => $vv}
                            {code}
                                $i++;
                                $last = '';
                                if($len == $i){
                                    $last = 'last';
                                }

                                $href = $vv['url'] . ($vv['id'] ? '&menuid='.$vv['id'] : '');
                            {/code}
                            <li class="{$vv['class']} {$last} menu-child" title="{$vv['name']}"><a href="{$href}" target="mainwin"><span class="menu-child-icon"></span>{$vv['name']}</a><span class="menu-child-select"></span></li>
                        {/foreach}
                        </ul>

                    </div>
                    {/if}
                </li>
                {/foreach}
            </ul>

            {/if}
            <div class="menu-hoge"></div>
    </div>
    <div id="menu-state" class="state-normal"></div>
</div>

