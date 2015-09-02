<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:2013/iframe}
{css:auth}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/ajaxload}
{js:common/ajax_upload}
{js:auth/auth_item}
{js:auth/auth_user}
{js:auth/auth}
{code}

$list = $list[0];
$groups = $list['info'];
$groups_bak = array();
foreach($groups as $kk => $vv){
    if(!isset($groups_bak[$vv['fid'] . ''])){
        $groups_bak[$vv['fid'] . ''] = array();
    }
    $groups_bak[$vv['fid'] . ''][] = $vv;
}
$groups = array_values($groups_bak['0']);
$groups = json_encode($groups);
$groups_obj = json_encode($groups_bak);

$role = json_encode($list['role'] ? $list['role'] : array());
{/code}
<script>
var groups = {$groups};
var groups_obj = {$groups_obj};
</script>
<div class="wrap">
    <div class="item" id="root" data-fid="0" data-id="0" data-name="" data-depth="0" data-last="0">
        <div class="item-child"></div>
    </div>
    <div class="add-first-department">
        <a>添加新部门</a>
        <input type="text" placeholder="输入新部门的名称" class="add-department-area">
    </div>
</div>

<div id="user-form" class="user-form clearfix">
	<div class="user-form-content">
	<div class="user-form-left">
		<div class="user-info">
			<div class="user-head"></div>
			<input type="file" style="display:none;" id="user-head-upload"/>
			<input type="text" placeholder="输入名称" class="username">
			<input type="text" placeholder="输入密码" class="password">
		</div>
		<div class="role">
		    <div class="role-tip">
                <p class="em1">角色</p>
                <p class="em2">从右栏选择</p>
			</div>
			<ul class="role-list"></ul>
		</div>
		 <div class="buttons">
		 	<input type="button" value="保存" class="save">
		 	<input type="button" value="取消" class="cancel button2">
		 </div>
	</div>
	<div class="user-form-right">
		 <ul class="clearfix">
        {foreach $list['role'] as $kk => $vv}
           	<li data-id="{$kk}" class="role-each">{$vv}</li>
        {/foreach}
        </ul>
	</div>
	<div class="root" style="overflow:auto;">
	    <div class="root-user"></div>
	    <div class="root-current"></div>
	</div>
	</div>
</div>

<script type="text/x-jquery-tmpl" id="group-tpl">
    {{each groups}}
    <div class="item {{if fid > 0}}department-second{{else}}department-first{{/if}} clearfix" data-fid="${fid}" data-id="{{= $value.id}}" data-name="{{= $value.name}}" data-depth="{{= $value.depath}}" data-last="{{= $value.is_last}}">
        <div class="department-name first-name clearfix">
            <span class="title">{{= $value.name}}</span>
			<div class="option-box">
				<div class="item-child-toggle" style="{{if $value.is_last > 0}}display:none;{{/if}}" data-show="展开子部门" data-hide="隐藏子部门">展开子部门</div>
				<div class="add-dep item-child-add"><span class="add">添加子部门</span></div>
				<div class="rename">重命名</div>
			</div>
        </div>
        <div class="department-content">
			<ul class="members clearfix">
                 {{if $value.user}}
                 {{each $value.user}}
                <li class="member {{if $value.is_admin > 0}}member-admin{{/if}}" data-id="{{= $value.id}}">
                    <a class="member-avatar"><img src="{{html $value.avatar_url}}"/></a>
                    <span class="member-name">{{= $value.user_name}}</span>
                </li>
                {{/each}}
                {{/if}}
                <li class="member-add"><span class="member-avatar"></span></li>
            </ul>
            <div class="item-child">
            </div>
        </div>
    </div>
    {{/each}}
</script>
<script type="text/x-jquery-tmpl" id="user-tpl">
<li class="member" data-id="${id}">
    <a class="member-avatar"><img src="${_avatar}"/></a>
    <span class="member-name">${user_name}</span>
</li>
</script>
<script type="text/x-jquery-tmpl" id="prms-tpl">
<div class="prms-list">
    {{each list}}
    <p class="root-name">{{= $value.name}} 权限</p>
    <ul>
        {{each $value.func_prms}}
        <li>{{= $value}}</li>
        {{/each}}
    </ul>
    {{/each}}
</div>

<div class="prms-site">
    <p class="root-name">站点：</p>
    <ul>
        {{each site}}
        <li>{{= $value}}</li>
        {{/each}}
    </ul>
</div>

<div class="prms-publish">
    <p class="root-name">发布：</p>
    <ul>
        {{each publish}}
        <li>{{= $value}}</li>
        {{/each}}
    </ul>
</div>

</script>

{template:foot}