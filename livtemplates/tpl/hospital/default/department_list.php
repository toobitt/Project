{template:head}
{code}
	//print_r( $list );
	$detail = $list[0]['hospital_info'];
	//print_r( $_configs['doctor_level'] );
{/code}

{css:2013/form}
{css:2013/button}
{css:hospital_depart}
{js:2013/ajaxload_new}
{js:jqueryfn/jquery.paginate.min}
{js:jqueryfn/jQselect}
{js:page/page}
{js:hospital/hospital_detail}
{js:hospital/hospital_pop}
{js:hospital/hospital}

<div class="main-box wrap">
	<header class="m2o-header">
		<div class="m2o-inner">
			<div class="hospital-header m2o-flex m2o-flex-center">
				<div class="hospital-title m2o-flex-one">
					{code}
						$logo = $detail['logo'] && $detail['logo']['filename'] ? hg_bulid_img($detail['logo'], 46, 46) : $RESOURCE_URL.'hospital/default_logo.png';
					{/code}
					<div class="img-box"><img src="{$logo}" /></div>
					<h2>{$detail['name']}</h2>
					<a href="./run.php?mid={$_INPUT['mid']}&a=schedu_form&id={$detail['id']}&infrm=1" target="formwin" class="share-button btn-primary edit-detail">查看预约信息</a>
				</div>
				<div class="m2o-r">
					<a class="del_hospital" _id="{$detail['id']}">删除医院</a>
					<a class="back_hospital">返回医院</a>
				</div>
			</div>
		</div>
	</header>
	<div class="m2o-inner">
		<div class="m2o-main m2o-flex">
			<aside class="m2o-l">
				<div class="m2o-title m2o-flex title-info">
					<h3 class="m2o-flex-one">基本信息</h3>
					<a href="./run.php?mid={$_INPUT['mid']}&a=hospital_form&id={$detail['id']}&infrm=1" target="formwin" class="share-button btn-primary edit-detail">编辑</a>
				</div>
				<form class="common-list" action="" method="post">
					{code}
					$indexpic = $detail['indexpic'] && $detail['indexpic']['filename']  ? hg_bulid_img($detail['indexpic'], 280, 170) : $RESOURCE_URL.'hospital/default_logo.png';
					{/code}
					{if $indexpic}
					<div class="m2o-cont m2o-pic">
						<img src="{$indexpic}" />
					</div>
					{/if}
					<div class="m2o-cont">
						<label>医院等级：</label><input type="text" name="level" value="{$detail['level']}">
						<span>{$detail['level']}</span>
					</div>
					<div class="m2o-cont">
						<label>医保定点：</label><input type="text" name="yibao_point" value="{$detail['yibao_point']}" />
						{code}
							$yibao_point = $detail['yibao_point'] ? '是' : '否'
						{/code}
						<span>{$yibao_point}</span>
					</div>
					<div class="m2o-cont">
						<label>网址：</label>
						<span>{$detail['website']}</span>
						<input type="text" name="website" value="{$detail['website']}" />
					</div>
					<div class="m2o-cont">
						<label>简介：</label>
						<span class="item"><span class="line-wrap">{$detail['content']}</span></span>
						<input type="text" name="content" value="{$detail['content']}" />
					</div>
					<div class="m2o-cont">
						<label>特色科室：</label>
						<span class="item"><span class="line-wrap">{$detail['special_depart']}</span></span>
						<input type="text" name="special_depart" value="{$detail['special_depart']}" />
					</div>
					<div class="m2o-cont">
						<label>重点学科：</label>
						<span class="item"><span class="line-wrap">{$detail['important_depart']}</span>
						</span>
						<input type="text" name="important_depart" value="{$detail['important_depart']}" />
					</div>
					<!-- 
					<div class="m2o-cont">		<!-- 字段没对 -->
					<!-- 	<label>医学环境：</label>
						<span>坐机场班车在以下停靠站下车</span>
						<input type="text" name="brief" value="中国人民解放局南京，六朝故都" />
					</div>
					 -->
					<div class="m2o-cont">
						
						<label>电话：</label>
						{if $detail['telephone']}
						{foreach $detail['telephone'] as $val_tel}
						<span>{$val_tel['tel']}</span>({$val_tel['telname']})
						<!-- <input type="text" name="telephone[]" value="{$val_tel['tel']}" /> -->
						{/foreach}
						{/if}
					</div>
					<div class="m2o-cont">
						<label>地址：</label>
						<span class="item"><span class="line-wrap">{$detail['address']}</span></span>
						<input type="text" name="address" value="{$detail['address']}" />
					</div>
					<div class="m2o-cont">
						<label>坐标：</label>
						<span>{$detail['baidu_longitude']}, {$detail['baidu_latitude']}</span>
						<input type="text" name="baidu_longitude" value="{$detail['baidu_longitude']}" />
						<input type="text" name="baidu_latitude" value="{$detail['baidu_latitude']}" />
					</div>
					<div class="m2o-cont">
						<label>交通：</label>
						<span class="item"><span class="line-wrap">{$detail['traffic']}</span></span>
						<input type="text" name="traffic" value="{$detail['traffic']}" />
					</div>
				</form>
			</aside>
			<div class="m2o-depart" hospital_id="{$detail['hospital_id']}">
				{template:unit/add_depart}
				<div class="m2o-title m2o-flex title-depart">
					<h3 class="m2o-flex-one">科室<em class="num">0</em></h3>
					<span class="add-button-pure add-depart">添加科室</span>
				</div>
				<div class="depart-box m2o-flex">
					
				</div>
			</div>
			<div class="m2o-doctor m2o-flex-one" _id="{$detail['id']}" hospital_id="{$detail['hospital_id']}">
				<div class="m2o-title m2o-flex title-doctor">
					<h3 class="m2o-flex-one">医生<em class="num">0</em></h3>
					<form class="" name="searchform" id="searchform" action="./run.php?mid={$_INPUT['mid']}&a=get_doctor" method="get">
						<div class="type-item transition-width">
							<a class="btn-icon select">刷选</a>
							<div class="select-item">
								<select name="level">
									<option value="-1">请选择等级</option>
									{foreach $_configs['doctor_level'] as $n=>$m}
										<option value="{$n}">{$m}</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="type-item transition-width type-search-item">
							<a class="btn-icon search">搜索</a>
							<input type="text" name="k" placeholder="关键字搜索" value=""/>
							<em class="btn-icon del-btn">清空</em>
						</div>
						<input type="hidden" name="department_id" value=""/>
						<input type="hidden" name="fid" value="" />
					</form>
					<a class="add-button-pure btn-disable add-doctor" _fid="0" _depart="0" _depart_name="0">添加医生</a>
				</div>
				<div class="m2o-list" id="doctor">
			    </div>
			    <div class="page_size"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/x-jquery-tmpl" id="depart-tpl">
	<div class="depart-part departsort-{{= level}}">
		<ul class="list-depart{{if level == 'second'}} list-secondary{{/if}}" id="{{= level}}">
			{{if hassort}}
				{{each list}}
				<li class="{{= level}}-depart{{if _index == (hassort - 1) }} noborderbottom{{/if}}" _fid="{{= $value['fid']}}" _depart="{{= $value['department_id']}}_{{= $value['name']}}" _selfid="{{= $value['id']}}">
					<span class="{{= level}}">{{= $value['name']}}<a class="edit">编辑</a></span>
				</li>
				{{/each}}
			{{else}}
				<li class="{{= level}}-depart nodepart"><span class="{{= level}}">暂无科室分类</span></li>
			{{/if}}
		</ul>
		<div id="{{= level}}-pagination" class="pagination">
			<a id="{{= level}}-previous" class="prev" href="#">&laquo; Previous</a> 
		    <a id="{{= level}}-next" class="next" href="#">Next &raquo;</a>
		</div>
	</div>
</script>

<script type="text/x-jquery-tmpl" id="departli-tpl">
	<li class="{{= level}}-depart" _fid="{{= fid}}" _departid="{{= department_id}}">
		<span class="{{= level}}">{{= name}}<a class="edit">编辑</a></span>
	</li>
</script>

<script type="text/x-jquery-tmpl" id="popdepart-tpl">
	<ul class="item-tab clear">
		<li class="tab current" attr="1">一级科室</li>
		<li class="tab" attr="2">二级科室</li>
	</ul>
  	<div class="pop-content clear">
	    <div class="depart-first">
	    	<form class="pop-form" attr="1" action="./run.php?mid={$_INPUT['mid']}" method="post">
			    <div class="pop-item name-item">
			   	  <label>一级科室名称</label><input type="text" name="name" placeholder="科室名称" value="{{= current['name']}}" />
			    </div>
			    <div class="pop-item name-item">
			   	  <label>科室id</label><input type="text" name="department_id" placeholder="科室id" value="{{= current['department_id']}}"  />
			    </div>
			    <input type="hidden" name="a" value="{{= method}}" />
			    <input type="hidden" name="id" value="{{= current['id']}}" />
			    <input type="hidden" name="ajax" value="1" />
			    <input type="hidden" name="hospital_id" value="{{= current['hospital_id']}}" />
	   		 </form>
	    </div>
	    <div class="depart-secondary">
	    	<form class="pop-form" attr="2" action="./run.php?mid={$_INPUT['mid']}" method="post">
	    	 	<div class="pop-item">
			   	  	<label>选择一级科室</label><div class="item select-item">
			   	  		<select name="fid">
			   	  			<option value="-1">请选择</option>
			   	  			{{each depart}}
			   	  				<option value="{{= $value['department_id']}}" {{if $value['department_id'] == current['fid']}}selected{{/if}}>{{= $value['name']}}</option>
			   	  			{{/each}}
			   	  		</select>
			   	  	</div>
			    </div>
			    <div class="pop-item name-item">
			   	  	<label>二级科室名称</label><input type="text" name="name" placeholder="科室名称" value="{{= current['name']}}" />
			    </div>
			    <div class="pop-item name-item">
			   	  <label>科室id</label><input type="text" name="department_id" placeholder="科室id" value="{{= current['department_id']}}" />
			    </div>
			    <div class="pop-item">
			   	  	<label>位置</label><input type="text" name="position" placeholder="位置" value="{{= current['position']}}" />
			    </div>
			    <div class="pop-item">
			   	  	<label>描述</label><textarea name="introduction" placeholder="描述" row="5" cols="40">{{= current['introduction']}}</textarea>
			    </div>
			    <div class="pop-item envir-item">
			      	<label>环境</label>
			      	<input type="file" name="photos" style="display:none;" class="images-file" />
			      	<ul class="item pic-list">
			      		{{if current['pic_info']}}
			      			<input type="hidden" name="del_img" class="del_img" value="" />
				      		{{each current['pic_info']}}
				      		<li class="pic clear" data-id="{{= $value['id']}}">
		        				<div class="img-box img-add has-images">
		        					添加图片
		        					<img src="{{= $value['img']}}">
		        				</div>
		        				<textarea placeholder="图片描述" name="des[]">{{= $value['brief']}}</textarea>
		        				<input type="hidden" name="material_id[]" value="{{= $value['id']}}">
		        				<span class="set del">增加</span>
		        			</li>
		        			{{/each}}
			      		{{/if}}
	        			<li class="pic clear">
							<div class="img-box img-add">
								添加图片
								<img>
							</div>
							<textarea placeholder="图片描述" name="des[]"></textarea>
							<input type="hidden" name="material_id[]" value="">
							<span class="set add">增加</span>
						</li>
			      	</ul>
			     </div>
			     <input type="hidden" name="a" value="{{= method}}" />
			     <input type="hidden" name="id" value="{{= current['id']}}" />
			     <input type="hidden" name="ajax" value="1" />
			     <input type="hidden" name="hospital_id" value="{{= current['hospital_id']}}" />
	    	</form>
	     </div>
     </div>	
     <div class="pop-save">
	  		{{if method == save_method}}
	  			<a class="save-button btn-info">保存添加</a>
	  		{{/if}}
	  		{{if method == update_method}}
	  			<a class="cancel-button btn-cancel">删除科室</a>
	  			<a class="save-button btn-primary">保存编辑</a>
  			{{/if}}
	  </div>
</script>

<script type="text/x-jquery-tmpl" id="departenvir-tpl">
	<li class="pic clear">
		<div class="img-box img-add">
			添加图片
			<img>
		</div>
		<input type="file" name="photos[]" style="display:none;" class="images-file">
		<textarea placeholder="图片描述" name="des[]"></textarea>
		<input type="hidden" name="material_id[]" value="">
		<span class="set add">增加</span>
	</li>
</script>

<script type="text/x-jquery-tmpl" id="doctor-tpl">
	<div class="m2o-each" _id="{{= id}}" _departid="{{= department_id}}">
		<div class="m2o-each-inner m2o-flex m2o-flex-center ">
			<div class="m2o-item avatar-item">
				<div class="img-box{{if img}} has-images{{/if}}">
					<img src="{{= img}}" />
				</div>
			</div>
			<div class="m2o-item m2o-name m2o-overflow">{{= name}}</div>
		    <div class="m2o-item m2o-overflow">{{= title}}</div>
		    <div class="m2o-item">{{= level}}</div>
		    <div class="m2o-item m2o-special m2o-overflow m2o-flex-one">{{= speciality}}</div>
		    <div class="m2o-item m2o-ibtn"><a class="del">删除</a></div>
		    <div class="m2o-item m2o-ibtn"><a href="./run.php?mid={$_INPUT['mid']}&a=form&infrm=1&{{= search}}">编辑</a></div>
		</div>
	</div>
</script>