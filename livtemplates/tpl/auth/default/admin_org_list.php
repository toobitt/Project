{template:head}
{css:vod_style}
{js:jquery-ui-1.8.16.custom.min}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
{template:unit/sort, admin_org, $admin_org_list}
</body>
{template:foot}