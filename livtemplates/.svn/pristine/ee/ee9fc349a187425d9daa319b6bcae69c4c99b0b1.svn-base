<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{js:common/ajax_upload}
{js:jqueryfn/jquery.tmpl.min}
{css:2013/form}
{css:hg_sort_box}
{css:2013/button}
{css:news_edit}
{js:2013/ajaxload_new}
{js:hg_sort_box}
{js:epaper/edit_link}
{js:epaper/hotarea}
{js:epaper/form_common}
{js:jqueryfn/jquery.tmpl.min}
{js:epaper/ajax_uploadWithUrl}
{code}
$mid = $_INPUT['mid'];
$cur_date = $_INPUT['cur_date'];
$cur_stage = $_INPUT['cur_stage'];
$epaper_id = $_INPUT['epaper_id'];
$period_id = $_INPUT['period_id'];
$epaper_name = $_INPUT['epaper_name'];
{/code}
<body class="epaper-edit" data-id="10"  style="position:relative;z-index:1"  id="body_content">
<header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">
            	<a class="changeBtn news-show" data-index="1" href="run.php?mid={$mid}&a=news_edit&period_id={$period_id}&cur_stage={$cur_stage}&epaper_name={$epaper_name}&epaper_id={$epaper_id}&cur_date={$cur_date}">新闻编辑</a>
            	<a class="changeBtn link-show active" data-index="2" href="run.php?mid={$mid}&a=link_edit&period_id={$period_id}&cur_stage={$cur_stage}&epaper_name={$epaper_name}&epaper_id={$epaper_id}&cur_date={$cur_date}">链接编辑</a>
            </h1>
            <div class="m2o-m m2o-flex-one">
                <p class="m2o-m-title">{$epaper_name} {$cur_date} {$cur_stage}期</p>
            </div>
            <div class="m2o-btn m2o-r">
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
	</div>  
</header>
<div class="m2o-inner edit-wrap m2o-flex">
<span style="display:none" id="info" _periodId="{$_INPUT['period_id']}" _epaperId="{$_INPUT['epaper_id']}"></span>
	<aside class="num">
		<ul class="stack">
		{if $formdata}
			{foreach $formdata as $k => $v}
			<li class="stack-item"  _id='{$k}' _flag="{$v['zm']}" _needajax='true'>{$v['name']}</li>
			{/foreach}
			{else}
			<li class="stack-item" _id="1" _flag='A' _needajax='true'>A叠</li>
		{/if}
			<li class="add stack-add" style="visibility: hidden;"></li>
		</ul>
		<div class="link-edit-page pagePrev">
			<div class="uploadBtn"  style="visibility: hidden;">
				<a class="addBtn">添加jpg</a>
				<a class="pageNum"></a>
			</div>
			<input type="file" style="display:none" accept="image/jpg" class="uploadFlieBtn">
		</div>
	</aside>

<div class="tips">

</div>
<!--   链接编辑   -->					
<div class="edit-link edit-box m2o-flex-one">
	<div class="">
		<div class="m2o-main m2o-flex">
			<section class="m2o-flex-one edit-group">
				<div class="edit-link-area">
					<div class="m2o-flex ">
						<div class="m2o-flex m2o-flex-one link-area">
							<div class="news-list">
								<div class="sort-btn clear">
									<a class="sort">开启排序</a>
								</div>
								<ul class="link-news-link">
									
								</ul>
							</div>
							<div class="link-prev m2o-flex-one">
							<form class="hotarea-form" method="post">
								<div class="head">
									<a class="save-button save-link-button" >保存热区</a>
									<a class="open-hot">开启热区编辑</a>
									<!-- 
									<a class="orange-button createBtn create-link">创建链接</a>
									 -->
									<div class="select">
										<span>所在叠：</span>
										<div class="choose">
											<label class="belong-stack">A</label>
										</div>
									</div>
									<div class="select">
										<span>版号：</span>
										<div class="choose">
											<label class="belong-paper">A1</label>
										</div>
									</div>
								</div>
								<div class="hotarea-box">
									<div class="intro">
										<p>---&nbsp;在下方拖动可设置热区</p>
										<p>---&nbsp;双击或按ESC键删除热区</p>
									</div>
									<div class="prev-area">
										<img src='http://epaper.yangtse.com/images/2009-05/22/12429141657650522CWX0207PP_b.jpg' style="width:100%;height:100%;">
										<div class="hotarea"></div>	
									</div>
									<div class="mask"></div>
								</div>
								<input type="hidden" class="hot-page_id" name="hot-page_id" value="" />
								<input type="hidden" class="hot-info" name="hot-info" value="" />
							</form>
							</div>
						</div>
					</div>
				</div>
			</section>
			<input type="file" class="uploadFile" style="display:none;">
		</div>
	</div>
</div>
<!-- 左侧栏  pdf预览 -->
<script type="text/x-jquery-tmpl" id="link-prev-tpl">
<li class="link-prev-item" _id="${id}" _pic="${pic}" _flag="${flag}" _realNum="${page_num}">
	<img src='${src}'>
	<a class="pageNum">${stack}${flag}</a>
</li>
</script>

<!--  新闻标题列表  -->
<script type="text/x-jquery-tmpl" id="link-news-tpl">
<li class="m2o-flex" _id='${id}'>
	<span class="news-num" style="background:${color}">${num}</span>
	<p class="m2o-flex-one">${title}</p>
</li>
</script>

<!-- 热区链接选择 -->
<script type="text/x-jquery-tmpl" id="link-choose-area-tpl">
<div class="link-choose-area">
	<div class="choosen">
		<a title="">选择新闻</a>
	</div>
	<ul class="link-choose" style="display:none;">
	</ul>
</div>
</script>

<script>
$(function(){
    $('.hotarea').hotarea();
	$('.epaper-edit').form_common();
});
</script>
</div>
</body>
{template:foot}