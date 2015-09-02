<h2 data-id="{$formdata['id']}" style="color:#498adb;"><span class="b" onclick="hg_closeQuestionTpl();"></span>
{$formdata['title']}
</h2>
<div class="total_info">
	<div class="allvote">总票数：<span>{$formdata['question_total_ini']}</span>&nbsp;&nbsp;&nbsp;&nbsp; 总人数：<span>{$formdata['person_total']}</span>（含初始值）</div>
	<div class="acturlvote">总票数：<span>{$formdata['question_total']}</span>&nbsp;&nbsp;&nbsp;&nbsp; 总人数：<span>{$formdata['preson_count']}</span>（不含初始值）</div>
</div>
<div class="is_ini">隐藏初始值</div>
<ul class="vote-result clear">
{foreach $formdata['options'] AS $k => $v}
	<li class="vote-item">
		<div class="content-list m2o-flex"> 
     		<div class="content-index" >{code}echo ++$k;{/code}</div>
     		<div class="content-img" ><img src="{$v['option_img']}"></div>
     		<div class="detail-info">
     			<p>{$v['title']}</p>
     			<ul class="detail-vote">
     				<li class="fact-vote">实际票数:<label>{$v['single_total']}</label></li>
     				<li class="total-vote">总票数:<label>{$v['ini_single']}</label></li>
   					<li class="ini-vote">初始票数:<label>{$v['ini_num']}</label></li>
   				</ul>
     		</div>
         </div>
         <div class="allvote"> 
	         <div class="program">
	         	{code}
	         	$left = (round($v['ini_single']/$formdata['question_total_ini'],4))*340;
	         	{/code}
	         	<span class="" href="#" style="width:{$left}px"></span>
	         </div>
	         <span>{code} echo (round($v['ini_single']/$formdata['question_total_ini'],4))*100;{/code}%</span>
         </div>
         <div class="acturlvote"> 
	          <div class="program">
	         	{code}
	         	$left = (round($v['single_total']/$formdata['question_total'],4))*340;
	         	{/code}
	         	<span class="" href="#" style="width:{$left}px"></span>
	         </div>
	         <span>{code} echo (round($v['single_total']/$formdata['question_total'],4))*100;{/code}%</span>
          </div>
	</li>
{/foreach}
</ul>
<style>
.allvote{display:block;}
.acturlvote{display:none;}
.total_info{line-height: 30px;display: inline-block;font-size: 14px;margin: 5px 20px;min-width: 65%;}
.total_info span{color: #e12728;}
.is_ini{opacity:0.7;display: inline-block;cursor:pointer;line-height: 30px;background: #0091db;color: white;width: 100px;text-align: center;border-radius: 5px;}
.is_ini:hover{opacity:1;}
.single_upload h2{height:auto;}
</style>
<script>
$('.is_ini').toggle(function(){
	$('.allvote').hide();
	$('.acturlvote').show();
	$(this).text('包含初始值');
},function(){
	$('.allvote').show();
	$('.acturlvote').hide();
	$(this).text('隐藏初始值');
})
</script>