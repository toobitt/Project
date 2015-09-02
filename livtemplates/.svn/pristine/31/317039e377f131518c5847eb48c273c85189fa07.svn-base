<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:role_list}
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&id=-1" target="formwin" class="button_6">新增角色</a>
</div>
{code}
//hg_pre($list);
{/code}

<div class="role-list-bg">
	<ul class="role-list">
	    {foreach $list as $k => $v}

		<li class="each-role {if $v['id']<=3}each-role-disable{/if}">
		    <span class="role-option" data-id="{$v['id']}">详细</span>
			<div class="role">
				<p class="role-name">
				    {if $v['id']<=3}
				        {$v['name']}
				    {else}
				        <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">{$v['name']}</a>
				    {/if}
				</p>
				<div class="auth-list">
					<ul>
					    {if $v['prms']}
						{foreach $v['prms'] as $kk => $vv}
						<li class="{if $vv['is_complete'] > 0}all{else}partial{/if}">{$vv['name']}</li>
						{/foreach}
						{else}
                            <li style="width:auto;">{if $v['id'] > 3}还未设置权限{else}&nbsp;&nbsp;{/if}</li>
						{/if}
					</ul>
				</div>
			</div>
			<div class="edit">
				<div class="title">拥有该角色的用户：</div>
				{if !$v['is_delete']}
				<div class="has-member show">
					<ul class="clearfix">
					</ul>
					<p class="editer">by<span class="name">{$v['user_name']}</span><span>{$v['create_time']}</span></p>
				</div>
				{else}
				<div class="none-member">
					<p class="editer">by<span class="name">{$v['user_name']}</span><span>{$v['create_time']}</span></p>
				    {if $v['id']>3}
					<a class="delete" data-id="{$v['id']}">删除该角色</a>
					{/if}
				</div>
				{/if}
			</div>
		</li>

		{/foreach}

	</ul>
</div>

{js:2013/ajaxload}
<script>
jQuery(function($){
    $('.role-option').on({
        mouseenter : function(){
            var $this = $(this);
            $this.closest('.each-role').addClass('on');

            if($this.data('ajax')){
                return;
            }
            var member = $this.closest('.each-role').find('.has-member');
            if(!member[0]){
                return;
            }
            var guid = $.globalAjaxLoad.bind(member);
            var xhr = $.getJSON(
                'run.php?a=user_in_role&mid=' + gMid,
                {role_id : $this.data('id')},
                function(json){
                    $this.data('ajax', true);
                    var html = '';
                    $.each(json, function(i, n){
                        html += '<li>' + n['user_name'] + '</li>';
                    });
                    member.find('ul').html(html);
                }
            );
            xhr.guid = guid;
        }
    });
    $('.edit').on({
        mouseleave : function(){
            var role = $(this).closest('.each-role');
            !role.hasClass('confirm') && role.removeClass('on');
        }
    });

    $('.each-role-disable a').click(function(){
        return false;
    });

    $('.role-list').on({
        click : function(event){
            var deleteObj = $(event.currentTarget);
            var role = deleteObj.closest('.each-role').addClass('confirm');
            jConfirm('确定要删除该角色？', '删除提醒', function(yes) {
                role.removeClass('confirm on');
                yes && cbOK();
            }).position(deleteObj);

            function cbOK(){
                var guid = $.globalAjaxLoad.bind(deleteObj);
                var xhr = $.getJSON(
                    'run.php?a=delete&mid=' + gMid,
                    {id : deleteObj.data('id')},
                    function(json){
                        deleteObj.closest('.each-role').css('overflow', 'hidden').animate({
                            opacity : 0,
                            height : 0
                        }, 1000);
                    }
                );
                xhr.guid = guid;
            }
        }
    }, '.delete');

    $('.auth-list').hover(function(){
        $(this).css({
            height : 'auto',
            'z-index' : 100
        });
    }, function(){
        $(this).css({
            height : '40px',
            'z-index' : 10
        });
    });
});
</script>

{template:foot}