{template:head}
{css:2013/iframe}
{css:2013/m2o}
{css:block_less}
{code}
$infos = $block_list[0];
$pages = $infos['page'];
$blocks = $infos['block'];
$contentType = $infos['content_type'];
$contentType += array(
    0 => array(
        'id' => 0,
        'content_type' => '首页'
    ),
    '-1' => array(
        'id' => -1,
        'content_type' => '列表'
    )
);
{/code}
<script>
$(function($){
    var imgMask, timerId;
    $('.block-list img').hover(function(){
        if(!imgMask){
            imgMask = $('<img/>').css({
                position : 'absolute',
                left : 0,
                top : 0,
                'z-index' : 10000,
                'max-height' : 500,
                'max-width' : 500,
                border : '1px solid #ccc'
            }).appendTo('body');
        }
        var $this = $(this);
        timerId = setTimeout(function(){

            imgMask.attr('src', $this.attr('src')).show().position({
                my : 'left top',
                at : 'right top',
                of : $this,
                within : $(window)
            });

        }, 300);
    }, function(){
        imgMask && imgMask.hide();
        timerId && clearTimeout(timerId);
        timerId = 0;
    });
});
</script>
<div class="wrap">
    <ul class="block-list clear">
    {foreach $pages as $page}
    {code}
    $href = './magic/block.php?gmid=412&ext='. rawurlencode('site_id=' . $page['site_id'] . '&page_id=' . $page['page_id'] . '&page_data_id=' . $page['page_data_id'] . '&content_type=' . $page['content_type']);
    $pageImg = $page['indexpic'];
    $pageImg = $pageImg ? $pageImg['host'] . $pageImg['dir'] . $pageImg['filepath'] . $pageImg['filename'] : '';
    {/code}
    <li>
    	<div class="m2o-flex">
	        <div class="page-sort">
	        	<a class="page" href="{$href}" target="_blank" go-blank title="{$page['expand_name']}({$contentType[$page['content_type']]['content_type']})">
	        		<span title="{$page['expand_name']}({$contentType[$page['content_type']]['content_type']})">{$page['expand_name']}({$contentType[$page['content_type']]['content_type']})</span>
	        		<div class="bjt">
	        			<img src="{$pageImg}">
	        		</div>
	    		</a>
	        </div>
	        <div class="block m2o-flex-one">
		        <span>该页面有<em>{code}echo count($page['page_block']);{/code}</em>个区块</span>
		        <ul class="block-box">
		        {foreach $page['page_block'] as $block}
		        {code}
		            $blockInfo = $blocks[$block];
		            $img = $blockInfo['indexpic'];
	                $img = $img ? $img['host'] . $img['dir'] . $img['filepath'] . $img['filename'] : '';
		        {/code}
		        	<li title="{$blockInfo['name']}">
		        		<div class="jt">
		        			<img src="{$img}">
		        		</div>
		        		<span title="{$blockInfo['name']}">{$blockInfo['name']}</span>
		        	</li>
		        {/foreach}
		        </ul>
	        </div>
        </div>
    </li>
    {/foreach}
    </ul>
</div>

{template:foot}