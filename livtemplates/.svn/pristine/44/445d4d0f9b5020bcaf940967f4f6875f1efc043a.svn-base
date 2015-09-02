{template:head}
{code}
//hg_pre($formdata);
$optext = $id > 0 ? '更新' : '添加';
$ac = $id > 0 ? 'update' : 'create';
$role = $formdata['role'];
$extendPrms = $role['extend_prms'];
$apps = $formdata['apps'];
$prms = $role['prms'];
$prms = json_encode($prms ? $prms : array());
{/code}
<script>
var prmsCache = {$prms};
</script>
{css:common/common_form}
{css:2013/iframe_form}
{css:2013/m2o}
{css:column_node}
{css:2013/button}
{css:hg_sort_box}
{css:role}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/ajaxload_new}
{js:2013/ajaxload}
{js:page/page}
{js:flat_pop/base_pop}
{js:auth/column_select}
{js:auth/auth_publishsys}
{js:column_node}
{js:hg_sort_box}
{js:common/common_form}
{js:auth/role}

<form method="post" id="content_form" onsubmit="return false;">
	{template:unit/publish_column_auth, 1, $formdata['column_id']}
    <input type="hidden" id="role-id" value="{$role['id']}"/>
    <div class="common-form-head">
         <div class="common-form-title">
              <h2>{$optext}角色</h2>
              <div class="form-dioption-title form-dioption-item">
                    <input name="name" id="role-name" class="title" placeholder="添加角色名称" value="{$role['name']}"/>
              </div>
              <div class="form-dioption-submit">
                  <input type="submit" id="submit_ok" name="sub" value="保存角色" class="common-form-save" _submit_type="2" />
                  <span class="option-iframe-back">关闭</span>
              </div>
        </div>
    </div>
    <div class="common-form-main clearfix">
        <div class="qx-app">
            <ul class="qx-list">
                {foreach $apps as $kk => $vv}
                    <li>
                        <div class="app-group">
                            <span class="title" data-id="{$kk}"></span>
                            <span class="number">{code}echo count($vv);{/code}</span>
                        </div>
                        <div class="app-list clearfix">
                            {if $vv}
                            {foreach $vv as $kkk => $vvv}
                                <div class="app-item" data-id="{$vvv['id']}" data-mod_uniqueid="{$vvv['mod_uniqueid']}" data-app_uniqueid="{$vvv['app_uniqueid']}">{$vvv['name']}</div>
                            {/foreach}
                            {/if}
                        </div>
                    </li>
                {/foreach}
            </ul>
            <div class="qx-result">
                <div class="qx-result-inner">
                    <div style="display:none;">权限设置：</div>
                    <div class="default"></div>
                    <div class="qx-items"></div>
                </div>
            </div>
        </div>
        <div class="qx-base">
        	<div class="qx-base-item">
        		<a class="common-publish-button overflow" href="javascript:;" _default="限制发布栏目" _prev="限制发布栏目：">限制发布栏目</a>
        	</div>
            <div class="qx-base-item" id="role-brief"><div placeholder="备注">{$role['brief']}</div></div>
            <div class="qx-base-item" id="role-domain"><div placeholder="限制登陆域名">{$role['domain']}</div></div>
            <div class="qx-base-item" id="role-show_other_data" value="{$extendPrms['show_other_data']}">
                <span>查看他人数据</span>
                <select>
                <option value="0">不允许</option>
                {foreach $_configs['org_state'] as $key => $val}
                    <option value="{$key}">{$val}</option>
                {/foreach}
                </select>
            </div>

            <div class="qx-base-item" id="role-manage_other_data" value="{$extendPrms['manage_other_data']}">
                <span>修改他人数据</span>
                <select>
                <option value="0">不允许</option>
                {foreach $_configs['org_state'] as $key => $val}
                    <option value="{$key}">{$val}</option>
                {/foreach}
                </select>
            </div>

            <div class="qx-base-item" id="role-set_weight_limit">
                <span>设置权重上限</span>
                <input type="text" size="5" value="{$extendPrms['set_weight_limit']}" />
            </div>
            {foreach 
            	array('update_audit_content' => '修改审核内容', 
            		'create_content_status'=> '创建内容状态',
            		'update_publish_content' => '修改发布内容',) as $name => $label}
            <div class="qx-base-item" id="role-{$name}" value="{$extendPrms[$name]}">
                <span>{$label}</span>
                <select>
                {foreach $_configs[$name] as $key => $val}
                    <option value="{$key}">{$val}</option>
                {/foreach}
                </select>
            </div>
            {/foreach}
            
        </div>
    </div>
</form>

<script type="text/x-jquery-tmpl" id="tc-tpl">
<div class="tc">
    <div class="tc-sbox">
        <div class="tc-sbox-inner">
            <div class="tc-bg"><div class="tc-bg-inner"></div></div>
            <div class="tc-op" data-string="${opString}">
                {{each op}}
                <div class="tc-op-item" data-key="{{= $index}}"><span class="tc-op-name">{{= $value}}</span></div>
                {{/each}}
            </div>
            <div class="tc-slider"></div>
        </div>
    </div>
    <div class="tc-all" {{if nodeSepecial}}_uniqueid="{{= app_uniqueid}}"{{/if}} >
    	{{if settings && settings.length }}
        <label><input type="checkbox" checked class="check-pz"/>配置</label>
        {{/if}}
        {{if !nodeSepecial}}
	        {{if (node && node.length) || column}}
	        <label><input type="checkbox" checked class="check-all" />{{= extra.name}}</label>
	        {{/if}}
        {{else}}
        	<label><input type="checkbox" checked class="check-all" />模板应用</label>
        	<span class="unique-set" title="点击选择模板应用">设置</span>
        {{/if}}
    </div>
    {{if settings && settings.length }}
    	 <div class="tc-settings tc-inner" data-string="${settingsString}">
    	 	<p>配置：</p>
	    	<div class="tc-box">
		        {{each settings}}
		        <label><input type="checkbox" name="settings[]" value="{{= $value.app_uniqueid}}#{{= $value.mod_uniqueid}}"/>{{= $value.name}}</label>
		        {{/each}}
	        </div>
    	 </div>
    {{/if}}
    {{if !nodeSepecial}}
	    {{if (node && node.length) || column}}
	    <div class="tc-node tc-inner" data-string="-1">
		    {{if node}}
		    	<p>{{= extra.name}}：</p>
		    	<div class="tc-box">
			        {{each node}}
			        <label><input type="checkbox" name="node[]" value="{{= $value.id}}"/>{{= $value.name}}</label>
			        {{/each}}
		        </div>
		    {{else}}
		        {{if column}}
		            {{each column}}
		                <div class="column-group" data-id="{{= $index}}"></div>
		                <div class="column-box">
		                    {{each $value}}
		                        <label><input type="checkbox" name="node[]" value="{{= $value.id}}" data-siteid="{{= $value.site_id}}" data-sitename="{{= $value.site_name}}"/>{{= $value.name}}</label>
		                    {{/each}}
		                </div>
		            {{/each}}
		        {{/if}}
		    {{/if}}
	    </div>
	    {{/if}}
    {{else}}
    <div class="tc-node tc-inner" data-string="1">
		<p>模板应用：</p>
    	<div class="tc-box">
	        <label>暂无已选模板</label>
        </div>
	</div>
	 {{/if}}
    <div class="tc-option"><span class="tc-save">保存</span><span class="tc-cancel">取消</span></div>
</div>
</script>

<script type="text/x-jquery-tmpl" id="publishsys-tpl">
	<label _id="{{= id}}">{{= name}}</label>   
</script>

{template:foot}