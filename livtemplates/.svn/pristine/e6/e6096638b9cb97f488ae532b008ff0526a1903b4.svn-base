{foreach $formdata as $k=>$v}
{code}$$k = $v;{/code}
{/foreach}
<h2 data-id="{$id}" style="color:#498adb;" title="{$title}"><span class="b" onclick="hg_closeQuestionTpl();"></span>
{$title}
</h2>
<ul class="mood-result clear">
{foreach $result AS $k => $v}
	<li class="mood-item">
		<div class="content-list m2o-flex"> 
			<div class="content-num">{code}echo ++$k; {/code}</div>
			<div class="content-img" ><img src="{$v['mood_picture']}"></div>
            {code} $con = round($v['counts']/$total_count,4)*100;{/code}
            <div class="program">
            <span href="#" style="width:{$con}%;"></span>
            </div>
     		<div class="detail-info">{$con}%</div>
         </div>
	</li>
{/foreach}
</ul>
<div class="more-total">参与总人数：{$total_count}</div>