{code}
  $id=$list[id];
  if($con_id)
	{
		$operation="update";
	}
	else
	{
		$operation="create";
	}
{/code}
<div class="special-modules">
   <div class="special-modules-title">
     <span class="biaoti">添加内容</span>
     <span class="special-close"></span>
   </div>
   <div class="special-modules-body" data-id="{$speid}">
        <div class="special-top">
            {template:unit/special_modules_search}
            <!--  <div class="define-add" style="float:left;width:100px;margin-left:10px;height:43px;text-align:center;background:#fff;line-height:45px;">自定义添加</div>-->
        </div>
        <div class="special-content-list">
           <form method="post" action="" name="listform" class="common-list-form">
           <div class="m2o-flex">
             	<div class="m2o-nav-box">
             		<ul class="m2o-nav-list">
						<li class="column-list top-item stretch-list" _ajax='false' data-fid="0">
							<span class="hook"></span>
							<span class="title">栏目<a></a></span>
							<ul></ul>
						</li>
					</ul>
             	</div>
	            <div class="m2o-list-box m2o-flex-one">
					<ul class="common-list special-list">
						<li class="common-list-head public-list-head clear">
							<div class="common-list-left">
				                <div class="common-list-item paixu open-close">
			                    </div>
			                </div>
							<div class="common-list-right">
							   <!--  <div class="common-list-item wd80">发布库</div>-->
							   <div class="common-list-item wd80">发布栏目</div>
							   <div class="common-list-item wd60">权重</div>
			                    <div class="common-list-item wd80">类型</div>
			                    <div class="common-list-item wd150">添加人/时间</div>
			                    <div class="common-list-item wd50">添加</div>
			                </div>
			                <div class="common-list-biaoti">
								<div class="common-list-item">标题</div>
							</div>
						</li>
					</ul>
					<!-- 主题，记录的每一行 -->
					<ul class="special-list common-list public-list hg_sortable_list" id="select-conlist" data-table_name="article" data-order_name="order_id" style="max-height:410px;overflow:hidden;">
					</ul>
					<!-- foot，全选、批处理、分页 -->
					<ul class="common-list public-list" style="background:#fff;">
						<li class="common-list-bottom clear" style="background:#fff;border:0;">
							<div class="common-list-left">
								<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" />
								<a style="cursor:pointer;" class="batch-add">添加</a>
							</div>
							<div class="page_area"></div>
						</li>
					</ul>
				</div>
			</div>
			</form>
        </div>
        <div class="special-content-form">
			<form action="./run.php?mid={$_INPUT['mid']}" method="post" id="content-form" enctype="multipart/form-data">
              <ul class="form_ul">
						<li>
							<div class="form_ul_div">
								<span class="title">标题</span>
								<input type="text" name='title' class="content-input" id="con-title">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">描述</span>
								<textarea rows="3" cols="80" name='brief' class="descr-area"></textarea>
							</div>
						</li>  
						<li class="i">
							<div class="form_ul_div">
								<span class="title">链接</span>
								<input type="text" name='outlink' class="link-input">
							</div>
						</li>
						<li>
						   <input type="submit" class="save"/>
						</li>
			   </ul>
			   <div class="user-head special-suolue"></div>
			   <input type="file" style="display:none;" id="user-head-upload"/>
			   <input type="hidden" name="special_column_id" id="column_id" />
			    <input type="hidden" name="a" id="a" value="create" />
				<input type="hidden" name="speid" id= "speid" value="{$_INPUT['speid']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="pic_id" id="pic_id" />
				<input type="hidden" name="pic_info" id="pic_info" />
			 </form>
        </div>
   </div>
   			 <div class="modules-loading"></div>
			 <span class="result-tip"></span>
</div>