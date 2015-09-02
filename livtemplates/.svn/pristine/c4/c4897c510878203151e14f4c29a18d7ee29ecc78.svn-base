{code}
$list = $formdata['info'];
{/code}
{js:2013/ajaxload_new}
{js:publish/publish}
{css:2013/list}
{css:2013/iframe}
{css:common/publish_sys}
<div class="m2o-search clear">
	{template:publish/publish_search}
</div>
<div class="publish-list">
	<div class="m2o-each-list">
			{if $list}
			{foreach $list as $k => $v}
				{foreach $formdata['column_info'] as $kk => $vv}
				{code}
				if($vv['bundle']==$v['bundle_id'])
				{
					$bundle_name = $vv['name'];
				}
				{/code}
				{/foreach}
	            <div class="m2o-each m2o-flex-center m2o-flex" _id="{$v['id']}">
				   	   	 	<div class="m2o-item m2o-flex-one m2o-bt">
				   	   	 		<div class="m2o-title-transition max-wd">
					            	<a class="m2o-title-overflow">
					                  {if $v['indexpic']}<img class="index-pic" width="40" height="30" src="{$v['indexpic']['host']}{$v['indexpic']['dir']}{$v['indexpic']['filepath']}{$v['indexpic']['filename']}">{/if}
				                      <span class="title">{$v['title']}</span>
					                </a>
					             </div>
				   	   	 	</div>
				   	   	 	<div class="m2o-item m2o-column">
				   	   	 		{$v['column_name']}
				   	   	 	</div>
				   	   	 	<div class="m2o-item m2o-quanzhong">
				   	   	 	  <div class="m2o-weight-box">
							      <div class="m2o-weight" style="background:{code}echo create_rgb_color($v['weight']);{/code}">
							     	 <span class="m2o-weight-label">{$v['weight']}</span>
						     	  </div>
					       	  </div>
			   	   	 		</div>
				   	   	 	<div class="m2o-item m2o-modules">
				   	   	 		{$bundle_name}
				   	   	 	</div>
				   	   	 	<div class="m2o-item m2o-time">
					                <span class="name">{$v['publish_user']}</span>
					                <span class="time">{$v['publish_time']}</span>
				   	   	 	</div>
				   	   	 	<div class="m2o-item m2o-add">
				   	   	 		<div class="m2o-add-btn"></div>
				   	   	 	</div>
				 </div>
	         {/foreach}
	  		 {/if}
	
	  		   	 
	</div>
	<div class="m2o-bottom">{$formdata['pagelink']}</div>
</div>
<div class="publish-form">
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
								<span class="outlink-select">选取添加</span>
							</div>
						</li>
						<li>
						   <input type="submit" class="save" />
						</li>
			   </ul>
			   <div class="indexpic"></div>
			   <input type="file" style="display:none;" name="picture"  class="index-pic-file"/>
			   <input type="hidden" name="ajax" value="1"  />