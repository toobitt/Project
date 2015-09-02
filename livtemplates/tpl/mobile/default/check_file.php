{template:head}
{css:2013/form}
{css:common/common}
{css:mobile_form}
{css:ad_style}
<style>
.ad_middle textarea{min-height: 250px;}
</style>
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
 <header class="m2o-header">
  <div class="m2o-inner">
    <div class="m2o-title m2o-flex m2o-flex-center">
        <h1 class="m2o-l">查看文件</h1>
        <div class="m2o-l m2o-flex-one">
        </div>
        <div class="m2o-btn m2o-r">
            <span class="m2o-close option-iframe-back"></span>
        </div>
    </div>
  </div>
</header>
<div class="m2o-inner">
	<div class="m2o-main m2o-flex">
		 <section class="m2o-m m2o-flex-one">
		 	<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">文件内容：</span><textarea name="file_res" style="width: 1000px;height:auto;min-height:420px;">{$formdata}</textarea>
					</div>
				</li>
			</ul>
		 </section>
	</div>
</div>
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
{template:foot}