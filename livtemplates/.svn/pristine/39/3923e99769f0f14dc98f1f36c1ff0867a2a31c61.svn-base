<?php 
/* $Id: fast_publish.php 12269 2012-09-21 05:47:41Z zhuld $ */
?>
<form name="recommendform" id="recommendform" action="run.php" method="post" class="form">
<div style="margin-bottom: 10px;">

{template:unit/node, 1, $formdata[0]['column_id']}
</div>
<script>
jQuery(function($){
    var timeid = setInterval(function(){
        if($.fn.commonNode){
            $('#publish-1').commonNode({
                column : 2,
                maxcolumn : 2,
                height : 224,
                absolute : false
            });
            clearInterval(timeid);
        }
    }, 100);
});
</script>
<input type="hidden" name="a" value="" />
<input type="hidden" name="mid" value="{$pub_module_id}" />
<input type="hidden" name="id" value="{$_INPUT[$primary_key]}" />
{if $formdata[0]['column_id']}
<input type="hidden" name="hg_recomend" value="{$formdata['hg_recommed_id']}" />
<span class="label">&nbsp;</span><input type="submit" name="rsub" value="更新节点" class="button_4" />
{else}
<span class="label">&nbsp;</span><input type="submit" name="rsub" value="确定"  class="button_4" />
{/if}
</form>