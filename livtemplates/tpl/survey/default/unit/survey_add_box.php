<div class="survey-box">
	<div class="delete"><span>X</span></div>
	<div class="new create-survey">
		<p>创建空白问卷</p>
		<span>创建一份空白的问卷从头开始添加题目</span>
		<a href="./run.php?mid={$_INPUT['mid']}&a=form&infrm=1" target="formwin" need-back>开始创建</a>
	</div>
	<div class="new cite-survey">
		<p>引用已有问卷</p>
		<span>选择问卷库中已有的问卷直接引用或为模板创建您自己的问卷</span>
		<a href="./run.php?mid={$_INPUT['mid']}&a=cite_form&infrm=1" target="formwin" need-back>开始创建</a>
	</div>
</div>