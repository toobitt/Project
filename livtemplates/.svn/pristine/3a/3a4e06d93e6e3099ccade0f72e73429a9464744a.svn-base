
	<div class="column-outer-box">
	    <span class="column-left-button"></span>
	    <span class="column-right-button"></span>
	    <div class="column-bg"></div>
	    <div class="column-box">
	        <div class="column-inner-box">
	            <div class="column-each">
	                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加参数</span></div>
	                <ul class="column-ul">
	                	{code}
	                	//print_r($formdata['mode_node']);
	                	{/code}
	                    {foreach $formdata['mode_node'] as $kk => $vv}
	                    <li _id="{$vv['id']}" _fid="{$vv['fid']}">
	                        <a class="column-edit-button" href="#id={$vv['id']}" onclick="return false;"></a>
	                        {if !$vv['is_last']}
	                        <a class="column-next-button" href="#id={$vv['id']}" onclick="return false;">&gt;</a>
	                        {/if}
	                        <span class="column-name">{$vv['name']}</span>
	                    </li>
	                    {/foreach}
	                </ul>
	            </div>
	            <div class="column-each">
	                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加参数</span></div>
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
	            <a class="column-delete-button" href="javascript:;">删除<span></span>参数</a>
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
	        <span class="column-name">{{name}}</span>
	    </li>
	</textarea>
	<textarea id="column-tpl-next" style="display:none;">
	    <a class="column-next-button" href="javascript:;">&gt;</a>
	</textarea>
	<textarea id="column-tpl-child" style="display:none;">
	    <div class="column-each">
	        <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加参数</span></div>
	        <ul class="column-ul">
	            <li><img src="{$RESOURCE_URL}loading2.gif" style="width:20px;"/></li>
	        </ul>
	    </div>
	</textarea>
	<input type="hidden" id="out_variable_ids" name="out_variable_ids" value="" />
	<input type="hidden" id="default-site" value="{$formdata['mode_id']}" />
	<div id="column-iframe-box">
	<img id="column-loading" src="{$RESOURCE_URL}loading2.gif"/>
	<iframe id="column-iframe" src="" style="display:none;width:100%;" scrolling="no" frameborder="0"></iframe>
	</div>

