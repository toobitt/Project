<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    {if is_array($menuData[0])}
        {foreach $menuData as $k=>$v}
             <span type="button" class="button_6 {$v['class']}" {$v['attr']}>{$v['innerHtml']}</span>
        {/foreach}
    {else}
	    <span type="button" class="button_6 {$menuData['class']}" {$menuData['attr']}>{$menuData['innerHtml']}</span>
    {/if}
</div>