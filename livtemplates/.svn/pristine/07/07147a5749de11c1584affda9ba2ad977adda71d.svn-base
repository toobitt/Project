{template:head}
{js:vod_opration}
{js:share}
{css:vod_style}
{css:edit_video_list}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program">
<?php
global $gGlobalConfig;
 if($_configs['is_open_seo']):?>    
    <a href="?mid={$_INPUT['mid']}&a=seo" class="button_6" style="font-weight:bold;">更新seo</a>
<?php endif;?>    
</div>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <form action="" method="POST" name="add_column" id="add_column">
        <input type="hidden" name="a" value="resume_form" />
        <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
        <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
        <input type="hidden" name="_id" value="{$_INPUT['_id']}" />
        <input type="hidden" name="site_id" value="{$column[0]['site_id']}" />
        <input type="hidden" name="column_fid" value="{$column[0]['column_fid']}" />
    </form>
</div>


{code}
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}
//获取所有站点
$hg_sites = $publish->getallsites();
$_INPUT['site_id'] = $_INPUT['site_id'] ? $_INPUT['site_id'] : 1;
$hg_column = $publish->getdefaultcol(0, 0, 0, $_INPUT['site_id']);
{/code}



<!-- <script>
$(function(){
	var site_item = parent.$('.site-box').find('.site-title');
	if (site_item.length > 1){
		site_item.eq(0).hide();
	}
    parent.$('.site-box').hover(function(){
    	parent.$('.site-ul').show();
    }, function(){
    	parent.$('.site-ul').hide();
    }).find('li').click(function(){
    	parent.$('.site-ul').hide();
        location.href = location.href.replace(/&?site_id=\d*/, '') + '&site_id=' + $(this).attr('_siteid');
    });
});
</script> -->
{css:column_form}
{js:column/column_form}
{template:unit/column_search}
<div class="column-outer-box">
    <span class="column-left-button"><</span>
    <span class="column-right-button">></span>
    <div class="column-bg"></div>
    <div class="column-box">
        <div class="column-inner-box">
            <div class="column-each">
                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加栏目</span></div>
                <ul class="column-ul">
                    {foreach $hg_column as $kk => $vv}
                    <li _id="{$vv['id']}" _fid="{$vv['fid']}">
                        {if $vv['is_auth'] != 2}
                        <a class="column-edit-button" href="#id={$vv['id']}" onclick="return false;"></a>
                        {/if}
                        {if $vv['is_last']}
                        <a class="column-next-button" href="#id={$vv['id']}" onclick="return false;">&gt;</a>
                        {/if}
                        <span class="column-name">{$vv['name']}</span>
                    </li>
                    {/foreach}
                </ul>
            </div>
            <div class="column-each">
                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加栏目</span></div>
                <ul class="column-ul">
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="column-lujin" style="height:0;">
<div class="column-lujin-outer">
    <div class="column-lujin-inner">
        <div class="column-lujin-bg"></div>
        <div class="column-lujin-content">
            <span class="column-lujin-text"></span>
            <a class="column-delete-button" href="javascript:;">删除<span></span>栏目</a>
        </div>
    </div>
</div>
</div>

<input type="button" class="button_6_14 column-lujin-cancel" value="取消编辑">

<textarea id="column-tpl-add" style="display:none;">
    <div class="column-input">
        <div class="column-input-text"><span class="column-input-add"></span><input type="text"/><span class="column-submit">OK</span><button class="column-cancel">NO</button></div>
        <div class="column-input-tip">提交中...</div>
    </div>
</textarea>
<textarea id="column-tpl-li" style="display:none;">
    <li _id="{{id}}" _fid="{{fid}}">
        <a class="column-edit-button" href="#id={{id}}" onclick="return false;" data-auth="{{isauth}}" ></a>
        {{next}}
        <span class="column-name">{{name}}</span>
    </li>
</textarea>
<textarea id="column-tpl-next" style="display:none;">
    <a class="column-next-button" href="javascript:;">&gt;</a>
</textarea>
<textarea id="column-tpl-child" style="display:none;">
    <div class="column-each">
        <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加栏目</span></div>
        <ul class="column-ul">
            <li><img src="{$RESOURCE_URL}loading2.gif" style="width:20px;"/></li>
        </ul>
    </div>
</textarea>

<div id="column-iframe-box">
<img id="column-loading" src="{$RESOURCE_URL}loading2.gif"/>
<iframe id="column-iframe" src="" style="display:none;width:100%;" scrolling="no" frameborder="0"></iframe>
</div>
</body>
{template:foot}