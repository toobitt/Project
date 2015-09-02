<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
<!--  
<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增天气源</strong></a>
-->
</div>
<div class="wrap">
    <form>
        <table  border="0" cellpadding="0" cellspacing="0" width="100%"  id="channel_table" class="form_table">
            <tr id="item_th" class="h" align="left" valign="middle" >
                <th width="30" align="center"></th>
                <th class="text_indent">服务提供商</th>
                <th class="text_indent" width="120">官方网站</th>
                <th class="text_indent">接口API</th>
                <th class="text_indent" width="80">状态</th>
                <th class="text_indent" width="80">来源</th>
                <th class="text_indent" width="120">管理</th>
            </tr>
            <tbody id="status">
            {if $weather_source_list}
            {foreach $weather_source_list as $k => $v}
            <tr orderid="{$v['id']}"  id="r_{$v['id']}" class="h"   name="{$v['id']}"  align="left"  valign="middle"  onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
                <td align="center" id="primary_key_img_{$v['id']}"><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" class="n-h" /></td>
                <td class="text_indent"><span id="name_{$v['id']}">{$v['support_name']}</span></td>
                <td class="text_indent"><span onclick="hg_backup_flv({$v['id']});" style="cursor:pointer;" title="预览">{$v['official_site']}</span></td>
                <td class="text_indent" >{$v['weather_api_url']}</td>
                <td class="text_indent">{if $v['is_open']}启用{else}关闭{/if}</td>
                <td class="text_indent">{if $v['is_system']}系统内置{else}用户添加{/if}</td>
                <td class="text_indent">
                	<!--  
                    <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</i></a>
                    <a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&infrm=1">删除</a>
                	-->
                </td>
                <input type="hidden" id="hidden_uri_{$v['id']}" value="{$v['file_uri']}" />
            </tr>
            {/foreach}
            {else}
            <tr><td class="hg_error" colspan="10">暂无记录</td></tr>
            {/if}
            </tbody>
        </table>
        <!--  
        <div class="live_delete">
            <input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" class="n-h">
            <a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');">删除</a>
        </div>
       	-->
        <div>{$pagelink}</div>
    </form>
    <div style="cursor:move;position:absolute;top:81px;left:120px;width:400px;height:314px;background:#000000;display:none;border:5px solid #B2B2B2;border-radius:3px;" id="flv_box"></div>
</div>
{template:foot}