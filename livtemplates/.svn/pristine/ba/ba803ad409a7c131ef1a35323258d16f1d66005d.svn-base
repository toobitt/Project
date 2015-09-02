{template:head}
{code}
	$detail = $formdata;
{/code}

{css:2013/form}
{css:2013/button}
{css:hospital_depart}
{js:2013/ajaxload_new}
{js:jqueryfn/jquery.paginate.min}
{js:jqueryfn/jQselect}
{js:page/page}
{js:hospital/hospital_detail}
{js:hospital/reservation}

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
				</div>
				<div class="m2o-r">
					<!-- <a class="del_hospital" _id="{$detail['id']}">删除医院</a> -->
					<a class="back_hospital">医院详情</a>
					<a class="option-iframe-back">返回医院</a>
				</div>
			</div>
		</div>
	</header>
	<div class="m2o-inner">
		<div class="m2o-main m2o-flex">
			<div class="m2o-depart" hospital_id="{$detail['hospital_id']}">
				<div class="m2o-title m2o-flex title-depart">
					<h3 class="m2o-flex-one">选择科室<span class="view-all">全部预约信息</span></h3>
				</div>
				<div class="depart-box m2o-flex">
					
				</div>
			</div>
			<div class="m2o-doctor m2o-yuyue m2o-flex-one" _id="{$detail['id']}" hospital_id="{$detail['hospital_id']}">
				<div class="m2o-title m2o-flex title-doctor">
					<h3 class="m2o-flex-one">预约信息<em class="num">0</em></h3>
					<form class="" name="searchform" id="searchform" action="./run.php?mid={$_INPUT['mid']}&a=get_schedules" method="get">
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
						<input type="hidden" name="hospital_id" value="" />
					</form>
					<a class="add-button-pure btn-disable add-doctor" style="display: none;" _fid="0" _depart="0" _depart_name="0">添加医生</a>
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
					<span class="{{= level}}">{{= $value['name']}}<!-- <a class="edit">编辑</a> --></span>
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


<script type="text/x-jquery-tmpl" id="doctor-tpl">
	<div class="m2o-each transition-height{{if index == 0}} current{{/if}}" _id="{{= id}}" _departid="{{= department_id}}">
		<div class="m2o-each-inner yuyue-basic m2o-flex m2o-flex-center">
			<div class="m2o-item m2o-num m2o-overflow" title="{{= yuyue_id}}">预约单号：{{= yuyue_id}}</div>
			<div class="m2o-item m2o-info">{{= reg_date}}</div>
		    <div class="m2o-item m2o-info m2o-overflow">{{= patient_name}}</div>
		</div>
		
		<div class="m2o-each-inner yuyue-man m2o-flex m2o-flex-center">
			<div class="m2o-item m2o-tip">就诊人信息</div>
		    <div class="m2o-item m2o-info">
		    	<p>生日：{{= birthday}}</p>
		    	<p>性别：{{= sex}}</p>
		    </div>
		    <div class="m2o-item m2o-info">
		    	<p class="m2o-overflow">身份证号：{{= id_card}}</p>
		    	<p>就诊卡号：{{= schedule_id}}</p>
		    </div>
		    <div class="m2o-item m2o-info">
		    	<p>手机号：{{= cellphone}}</p>
		    	<p>提交时间：{{= create_time}}</p>
		    </div>
		</div>
		
		<div class="m2o-each-inner yuyue-more m2o-flex m2o-flex-center">
			<div class="m2o-item m2o-tip">就医信息</div>
		    <div class="m2o-item m2o-info">科室：{{= department_id}}</div>
		    <div class="m2o-item m2o-info">医生：{{= doctor_name}}</div>
		    <div class="m2o-item m2o-info">挂号费用：{{= price}}</div>
		</div>
	</div>
</script>