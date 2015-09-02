<?php
/**
 * Created by livtemplates.
 * User: wangleyuan
 * Date: 14-5-7
 * Time: 上午10:54
 */
?>

{template:head}
{css:vod_style}

<div id="hg_page_menu" class="head_op_program controll-area" {if $_INPUT['infrm']} style="display:none"{/if}>

</div>

{template:unit/sort, node, $node_list}

{template:foot}

 