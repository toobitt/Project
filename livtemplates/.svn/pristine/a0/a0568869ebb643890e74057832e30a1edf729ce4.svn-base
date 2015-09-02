{template:head}
{code}
	//print_r( $formdata );
{/code}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
	$indexImage = $indexpic && $indexpic['filename'] ? hg_bulid_img($indexpic, 230, 176) : '';
	$logo = $logo && $logo['filename'] ? hg_bulid_img($logo, 196, 196) : '';
	$a = $id ? 'doctor_update': 'doctor_create';
	
	/*所有选择控件基础样式*/
	$all_select_style = array(
		'class' 	=> 'down_list',
	    'state'     =>  0,  /*0--正常数据选择列表，1--日期选择*/
	    'is_sub'    =>  1,
        'width' 	=> 227,
	);
{/code}
{css:common/common}
{css:2013/form}
{css:2013/button}
{css:fullcalendar/fullcalendar}
{css:fullcalendar/fullcalendar.custom}
{css:hospital}
{js:2013/ajaxload_new}
{js:fullcalendar/fullcalendar.min}
{js:fullcalendar/hg_fullcalendar}
{js:hospital/doctor_pop}
{js:hospital/doctor_form}

<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" data-id="{$id}" id="doctor-form">
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}医生</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title {if $name}input-hide{/if}" _value="{if $name}{$name}{else}添加医生名称{/if}" name="name" id="title" placeholder="医生名字" value="{$name}" required="required" />
            </div>
            <div class="m2o-btn m2o-r">
                <span class="m2o-close"></span>
                <em class="prevent-do"></em>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
     <div class="m2o-main m2o-flex">
        <aside class="m2o-l">
        	<div class="m2o-item">
        		<div class="item indexpic{if $indexImage} has-images{/if}">
        			<div class="img-box img-add">
        				<img title="{$name}" src="{$indexImage}" />
        			</div>
                    <input type="file" name="indexpic" style="display:none;" class="images-file"/>
                    <input type="hidden" name="indexpic_id" value="{$indexpic_id}" />
                 </div>
        	</div>
        	<div class="m2o-item">
        		<label class="title">科室: </label>
        		{code}
        			$department_name = isset( $department_name ) ? $department_name : $_INPUT['department_name'];
        		{/code}
        		<input type="text" name="department_name" value="{$department_name}" readonly="readonly" />
        	</div>
        	<div class="m2o-item">
        		<label class="title">资历: </label>
        		<input type="text" name="title" placeholder="资历" value="{$title}"/>
        	</div>
        	<div class="m2o-item doctor-id">
        		<label class="title">医生id: </label>
        		<input type="text" name="doctor_id" value="{$doctor_id}" />
        	</div>
        	
        	<div class="m2o-item">
        		<label class="title">等级: </label>
        		{code}
        			$level_source = $all_select_style;
        			$level_source['show'] = 'level_show';
        			$doctor_default = $level ? $level : -1;
        			$_configs['doctor_level'][-1] = '选择等级';
                {/code}
                {template:form/search_source, level, $doctor_default, $_configs['doctor_level'],$level_source}
        	</div>
        	<div class="m2o-item">
            	<label class="title">专家: </label>
            	<div class="common-switch {if $expert}common-switch-on{/if}">
		           <div class="switch-item switch-left" data-number="0"></div>
		           <div class="switch-slide"></div>
		           <div class="switch-item switch-right" data-number="100"></div>
		        </div>
            	<input type="hidden" name="expert" value="{$expert}">
            </div>
            
            <div class="m2o-item">
            	<label class="title">擅长: </label>
            	<textarea name="speciality" cols="30" rows="5" placeholder="擅长">{$speciality}</textarea>
            </div>
        </aside>
         <section class="m2o-m m2o-flex-one">
            <div class="basic-info">
	            <div class="m2o-item brief-item">
	        		<label class="title">简介: </label><textarea name="brief" cols="120" rows="5" placeholder="医生简介">{$brief}</textarea>
	        	</div>
	        	<div class="m2o-item fullcalendar-item">
	        		<label class="title">排班: </label>
	        		<div class="item">
						<div class="fullcalendar-wrapper">
		        		</div>
	        		</div>
	        	</div>
	        	
	        	<div class="m2o-item m2o-hidden">
	        		{code}
	        			$hospital_ids = isset( $hospital_ids ) ? $hospital_ids : $_INPUT['hospital_ids'];
	        		{/code}
	        		<label class="title">&nbsp;</label>
	        		<input type="hidden" name="a" value="{$a}" />
	        		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	        		<input type="hidden" name="hospital_id" value="{$hospital_ids}" />
	        		<input type="hidden" name="department_id" value="{$department_id}" />
					<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	        		<input type="button" value="{$optext}" class="save-button" />
	        	</div>
            </div> 
         </section>
      </div>
     </div>
</form>
{template:unit/add_order}
<script type="text/x-jquery-tmpl" id="popdoctor-tpl">
  	<div class="pop-content">
		<form class="pop-form" attr="1" action="./run.php?mid=817" method="post">        							
			<div class="pop-item name-item">          
				<label>时间</label>
				<span>{{= date}}</span>
				<span><input type="checkbox" name="time" value="all"/>全天</span>
				<span><input type="checkbox" name="time" value="am"/>上午</span>
				<span><input type="checkbox" name="time" value="pm"/>下午</span>
				<span><input type="checkbox" name="time" value="night"/>晚上</span>
				<input type="hidden" name="date" value="{{= date}}" />
				<input type="hidden" name="allDay" value="{{= allDay}}" />
			</div>        
			<div class="pop-item name-item">
				<label>专家</label>
				<input type="text" name="export" placeholder="专家" value="专家">
			</div>  
			<div class="pop-item name-item">
				<label>费用</label>
				<input type="text" name="cost" placeholder="费用" value="">
			</div>       
			<input type="hidden" name="a" value="order_create">        
			<input type="hidden" name="id" value="{{= id}}">        
			<input type="hidden" name="ajax" value="1">        
			<input type="hidden" name="hospital_id" value="{{= hospital_id}}">        
		</form>
	</div>
	<div class="pop-save">
		<a class="save-button btn-info">保存添加</a>
  	</div>
</script>
<script type="text/javascript">
	var form = $('#doctor-form'),
		hidden = form.find('.m2o-hidden');
	$.popdoctorConfig = {
		popdoctortpl : $('#popdoctor-tpl').html(),
		hospital_id : hidden.find('input[name="hospital_id"]').val(),
		department_id : hidden.find('input[name="department_id"]').val(),
		id : hidden.find('input[name="id"]').val()
	}
	var schedules = {code}echo $schedules_info ? json_encode( $schedules_info ) : '[]'{/code};
	if( schedules.length ){
		$.events = $.map( schedules, function( vv ){
			var obj = {};
			var title = [vv.reg_time, vv.call_type + '：', '¥' + vv.price].join(' ');
			obj.title = title;
			obj.start = vv.reg_date;
			obj.eid = vv.schedule_id;
			return obj;
		});
	}else{
		$.events = schedules;
	}
	form.doctor_form({
		schedules : schedules,
		events : $.events
	});
</script>