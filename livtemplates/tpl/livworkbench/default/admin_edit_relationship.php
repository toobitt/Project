<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<h2>正在编辑[{$appinfo['name']}]应用依赖应用</h2>
<div class="wrap n">
    <form name="editform" action="" method="post" class="ad_form h_l" >
        {if $message}
        <div style="color:red;" id="msg" class="msg">{$message}</div>
        {/if}
        <ul class="form_ul">
            <li class='i'>
                <span>依赖应用:</span><br/>
                {code}
                    $appinfo['relyonapps'] = explode(',', $appinfo['relyonapps']);
                    //$appinfo['relyonapps'] = array('material', 'recycle');
                    //print_r($appinfo['relyonapps']);
                {/code}
                {if is_array($applications) && !empty($applications)}
                    {foreach $applications as $k => $v}
                        <input type="checkbox" name="related_app[]" value="{$v['softvar']}" {if in_array($v['softvar'], $appinfo['relyonapps'])}checked=checked{/if}/>{$v['name']}
                    {/foreach}
                {/if}
            </li>
            <li class='i'>
                <input type="submit" name="sub" value="保存" class="button_6_14" style="float:left;margin-left:20px;" />
            </li>
            <input type="hidden" name="app" value="{$appinfo['app_uniqueid']}" />
            <input type="hidden" name="a" value="do_edit_relationship" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
            <input type="hidden" name="goon" value="1" />
        </ul>
    </form>
</div>

{template:foot}