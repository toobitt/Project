{template:head}
{css:survey_result}
{code}
$alphabets = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
{/code}
<style>
body{padding: 10px;}
</style>
<div class="result-info-box">
	<div class="title"><p>{$formdata['title']}</p></div>
	<div class="result-info">
	{if !$formdata['problems']}
	   <div class="m2o-each-list">
	       <p class="common-list-empty" style="text-align: center;font-size: 14px;font-family: 'Microsoft YaHei';color: #da2d2d;">没有相关问卷信息！</p>
	   </div>
	{/if}
	{foreach $formdata['problems'] as $k => $v}
		{if $v['type'] == 1 || $v['type'] == 2}
		<div class="result">
			<span class="question-title">{code} echo $k+1; {/code}.{$v['title']}</span>
			{if $v['type'] == 2}<a style="color:#bababa">(多选)</a>{/if}
			{if $v['is_required']}<em style="color:red">*</em>{/if}
			{foreach $v['options'] as $kk => $vv}
			<div class="info-contain">
				<div class="info type">
					<p class="option" title="{$vv['name']}">{$alphabets[$kk]}.{$vv['name']}</p>
				</div>
				<div class="info progress">
					<span class="progress-bar" style="width:{$vv['percent']}"></span>
				</div>
				<div class="info count">
					<div class="total-info">
						<p class="total-num">{$vv['total']}</p>
						<p class="line">|</p>
						<p class="precent">{$vv['percent']}</p>
					</div>
				</div>
			</div>
			 {if $vv['is_other'] == 1} 
			<div class="more">
	        <a href="./run.php?mid={$_INPUT['mid']}&a=show_other_result&problem_id={$v['id']}&infrm=1" need-back>{$vv['other_total']}个回答，点击查看</a></div>
			{/if} 
			{/foreach}
		</div>
		{else if $v['type']==3}
		<div class="result">
		{foreach $v['options'] as $kk => $vv}
			<span>{if $kk==0}{code} echo $k+1; {/code}.{/if}{$vv[name]}:<input type="text" name="" disabled/></span>
	    {/foreach}
	        {if $v['is_required']}<em style="color:red">*</em>{/if}
	        <div class="more">
	        <a {if $v['answer_count'] != 0} href="./run.php?mid={$_INPUT['mid']}&a=show_other_result&problem_id={$v['id']}&infrm=1"{/if} need-back>{$v['answer_count']}个回答，点击查看</a></div>
		</div>
		{else if $v['type']==4}
			<div class="result">
				<span class="question-title">{code} echo $k+1; {/code}.{$v['title']}</span>{if $v['is_required']}<em style="color:red">*</em>{/if}
				<div class="more">
				<a {if $v['answer_count'] != 0}  href="./run.php?mid={$_INPUT['mid']}&a=show_other_result&problem_id={$v['id']}&infrm=1" {/if} need-back>{$v['answer_count']}个回答，点击查看</a></div>
			</div>
		{/if}
		{/foreach}
	</div>
</div>