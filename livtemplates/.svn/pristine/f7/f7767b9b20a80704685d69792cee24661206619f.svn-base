{template:head}
{css:vod_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:vod_style}
{css:edit_video_list}
{css:column_form}
{js:albums_app/category}
<div id="hg_page_menu" class="head_op_program controll-area"{if $_INPUT['infrm']} style="display:none"{/if}>
    
</div>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div class="column-outer-box">
	    <span class="column-left-button"></span>
	    <span class="column-right-button"></span>
	    <div class="column-bg"></div>
	    <div class="column-box">
	        <div class="column-inner-box">
	            <div class="column-each">
	                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加分类</span></div>
	                <ul class="column-ul">
	                    {foreach $sort_list as $kk => $vv}
	                    <li _id="{$vv['id']}" _fid="{$vv['fid']}" _node="PaikeMenu">
	                        <a class="column-edit-button" href="#id={$vv['id']}" onclick="return false;"></a>
	                        {if !$vv['is_last']}
	                        <a class="column-next-button" href="#id={$vv['id']}" onclick="return false;">&gt;</a>
	                        {else}
	                        <a class="column-delete-button" href="javascript:;" _delete_id="{$vv['id']}">—</a>
	                        {/if}
	                        <span class="column-name">{$vv['name']}</span>
	                    </li>
	                    {/foreach}
	                </ul>
	            </div>
	            <div class="column-each">
	                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加分类</span></div>
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
	            <a class="column-delete-button" href="javascript:;">删除<span></span>相册分类</a>
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
	        <a class="column-edit-button" href="#id={{id}}" onclick="return false;"></a>
	        {{next}}
	        {{del}}
	        <span class="column-name">{{name}}</span>
	    </li>
	</textarea>
	<textarea id="column-tpl-next" style="display:none;">
	    <a class="column-next-button" href="javascript:;">&gt;</a>
	</textarea>
	<textarea id="column-tpl-delete" style="display:none;">
	    <a class="column-delete-button" href="javascript:;">—</a>
	</textarea>
	<textarea id="column-tpl-child" style="display:none;">
	    <div class="column-each">
	        <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加相册分类</span></div>
	        <ul class="column-ul">
	            <li><img src="{$RESOURCE_URL}loading2.gif" style="width:20px;"/></li>
	        </ul>
	    </div>
	</textarea>
	<div id="column-iframe-box">
	<div id="column-iframe-box">
	<img id="column-loading" src="{$RESOURCE_URL}loading2.gif"/>
	<iframe id="column-iframe" src="" style="display:none;width:100%;" scrolling="no" frameborder="0"></iframe>
	</div>
</body>

{template:foot}