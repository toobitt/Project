<?php ?>
{template:head}
{css:2013/form}
{css:common/common}
{css:jquery.lightbox-0.5}
{css:hg_sort_box}
{css:lottery_form}
{js:2013/ajaxload_new}
{js:ajax_upload}
{js:hg_sort_box}
{js:common/common_form}
{js:lottery/my-ohms}
{js:lottery/lottery_form}
{code}
echo '<pre>';
//print_r($formdata);
echo '</pre>';
$currentSort[$sort_id] = $formdata['sort_id'] ? $formdata['sort_name'] : '选择分类';
$personal_auth = $personal_auth[0];
if($formdata['id'])
{
	$optext="更新";
	$ac="update";
}
else
{
	$optext="添加";
	$ac="create";
}
{/code}
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<script type="text/x-jquery-tmpl" id="add-basic-pic-tpl" _name="新增基本信息图片">
<div class="pic-box" _id="{{= id}}">
    <div class="pic-img">
        <img src="{{= src}}">
	    <a class="set-fm">设为封面</a>
    </div>
	<span class="del-btn underline">删除</span>
	<input type="hidden" name="img_id[]" value="{{= id}}" />
</div>
</script>        				
	        				
<script type="text/x-jquery-tmpl" id="add-no-awards-tpl" _name="新增未中奖反馈">
<li class="m2o-flex content-list-list">
	<span class="index">{{= index}}</span>
	<div class="detail-wrap">
		<input type="text" name="no_lottery_feedback[]" placeholder="抽奖反馈" class="db wd500"/>
	</div>
	<a class="del-btn"></a>
</li>
</script>

<script type="text/x-jquery-tmpl" id="add-awards-tpl" _name="新增奖项">
<li class="m2o-flex content-list-list">
	<span class="index">{{= index}}</span>
	<div class="detail-wrap m2o-flex">
		<div class="mr10 pic-box">
			<img src="">
			<input type="hidden" name="award_indexpic[]" value="" />
		</div>
		<input type="file" />
		<div>
			<div class="mb10 m2o-flex">
				<input class="db wd150" name="award_name[]" value="" placeholder="奖项名称"/>
				{code}
					$type_source = array(
						'class' 	=> 'down_list i',
						'show' 		=> 'type_show_{{= index}}',
						'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
						'is_sub'	=>	1,
						'width'    => '90',
						'more'     =>'{{= index}}' 
					);
					if($vl['type']){
						$type_default = $vl['type'];
					}
					else
					{
						$type_default = 1;
					}
				{/code}
				{template:form/search_source,award_type,$type_default,$_configs['prize_type'],$type_source}
				<input class="db wd140" name="award[]" value="" placeholder="选择奖品"/>
			</div>
			<div class="m2o-flex">
				<input class="db wd70" name="seller_id[]" value="" placeholder="商家ID"/>
				<input class="db wd70" name="award_num[]" value="" placeholder="中奖人数"/>
				<input class="db wd70" name="award_probability[]" value="" placeholder="中奖概率"/>
				<input class="db wd150" name="award_feedback[]" value="" placeholder="抽奖反馈"/>
			</div>
		</div>
		<input type="hidden" name="award_id[]" value="add" />
	</div>
  	<a class="del-btn"></a>
</li>
</script>
<body>
<form class="m2o-form" name="editform" enctype="multipart/form-data" action="run.php?mid={$_INPUT['mid']}" method="post" >
     <div id="ohms-instance" style="position:absolute;display:none;"></div>
    <header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}抽奖</h1>
            <div class="m2o-m m2o-flex-one">
                <input placeholder="填写标题"  class="m2o-m-title"  required value="{$title}" name="title"/>
            </div>
            <div class="m2o-btn m2o-r">
            	<span class="m2o-save save-as">另存为</span>
                <input type="submit" value="保存抽奖" class="m2o-save" name="sub" id="sub" />
                 <input name="a" value="{$a}" type="hidden" />
                  <input name="id" value="{$id}" type="hidden" />
                <span class="m2o-close option-iframe-back"></span>
                <em class="prevent-do"></em>
            </div>
        </div>
      </div>  
    </header>
    <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
    	<!-- 左侧栏 -->
        <aside class="m2o-l">
			<div class="m2o-item">
				<div class="suoyin-box">
					<img src="{if $formdata['indexpic']}{$formdata['indexpic']['host']}{$formdata['indexpic']['dir']}{$formdata['indexpic']['filepath']}{$formdata['indexpic']['filename']}{/if}"/>
					<span class="suoyin-flag {if $indexpic_id}current{/if}"></span>
				</div>
				<input type="file" class="indexpic-file">
				<input type="hidden" name="indexpic_id" value="{$indexpic_id}">
			</div>
			<div class="m2o-item form-dioption-sort"  id="sort-box">
                <label style="color:#9f9f9f;">分类： </label>
                <p style="display:inline-block;" class="sort-label" _multi="sort">{if $sort_name}{$sort_name}{else}请选择分类{/if}<img class="common-head-drop" src="{$RESOURCE_URL}survey/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
            </div>
			<div class="m2o-item">
				<textarea name="brief" class="brief" placeholder="添加描述">{$brief}</textarea>
			</div>
			<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item ip-limit">
                  <input type="checkbox" class="limit-checkbox" {if $ip_limit}checked="checked"{/if} name="ip_limit" value="1">
                    <span class="limit-ip">IP限制</span>
                    <div class="ip-limit-hour limit-hour" {if $ip_limit}style="display: block;"{/if}>
                    	<input type="text" name="ip_limit_time" value="{$ip_limit_time}"><a>小时</a>
                   	 	<input type="text" name="ip_limit_num" value="{$ip_limit_num}"><a>次</a>
                    </div>
                </div>
        	</div>
			<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item device-limit">
                  <input type="checkbox" class="limit-checkbox" {if $device_limit}checked="checked"{/if}name="device_limit" value="1">
                    <span class="limit-device">设备限制</span>
                    <div class="device-limit-hour limit-hour" {if $device_limit}style="display: block;"{/if}>
                    	<input type="text" name="device_limit_time" value="{$device_limit_time}"><a>小时</a>
                    	<input type="text" name="device_num_limit" value="{$device_num_limit}"><a>次</a>
                    </div>
                </div>
        	</div>
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item win-limit">
                  <input type="checkbox" class="limit-checkbox" {if $win_limit}checked="checked"{/if}name="win_limit" value="1">
                    <span class="limit-device">中奖次数限制</span>
                    <div class="device-limit-hour limit-hour" {if $win_limit}style="display: block;"{/if}>
                    	<!-- <input type="text" name="win_time_limit" value="{$win_time_limit}"><a>天</a> -->
                    	1天<input type="text" name="win_num_limit" value="{$win_num_limit}"><a>个</a>
                    </div>
                </div>
        	</div>
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item exchange-switch">
                  <input type="checkbox" class="limit-checkbox" {if $exchange_switch}checked="checked"{/if} name="exchange_switch" value="1">
                    <span class="limit-ip">兑换码(开启后,中奖记录会生成兑换码)</span>
                </div>
        	</div>
			<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item lottery-limit">
                  <input type="checkbox" class="limit-checkbox" {if $lottery_limit}checked="checked"{/if} name="lottery_limit" value="1">
                    <span class="limit-ip">中奖限制(开启后,活动有效期内用户只能中一次奖)</span>
                </div>
        	</div>
        </aside>
        <!-- 左侧栏end -->
        <section class="m2o-m m2o-flex-one tab-wrap">
        	<ul class="tab-btns m2o-flex">
        		<li class="current" _index="0">基本信息</li>
        		<li _index="1">时间</li>
        		<li _index="2">积分</li>
        		<li _index="3">抽奖次数</li>
        		<li _index="4">区域</li>
        		<li _index="5">奖项</li>
        	</ul>
        	<div class="tab-content">
	        	<div class="tab-item current info-tab" _index="0">
	        		<div class="setting-item m2o-flex">
	        			<span class="title">抽奖类型:</span>
	        			<div class="m2o-flex-one">
	        				{code}
							    	$lottery_source = array(
							             'class' 	=> 'down_list i',
							             'show' 		=> 'lottery_show',
							             'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
							             'is_sub'	=>	1,
							             'width'    => '160'
							        );
							        if($formdata['type']){
							         	$lottery_default = $formdata['type'];
							        }
							        else
							        {
							            $lottery_default = 1;
							        }
							        foreach($_configs['lottery_type'] as $k =>$v)
							        {
							            $lottery_sort[$k] = $v;
							        }
							    {/code}
							    {template:form/search_source,type_id,$lottery_default,$lottery_sort,$lottery_source}
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">抽奖背景:</span>
	        			<div class="m2o-flex-one">
	        				<span class="lottery-bg-btn">
	        					 <em>上传背景</em>
	        					<input type="hidden" name="lottery_bg" />
	        				</span>
	        				<input type="file" style="display:none;" />
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">图片:</span>
	        			<div>
	        				{if $pic_info}
	        				{foreach $pic_info as $k => $v}
	        				<div class="pic-box" _id="{$v['id']}">
        						<div class="pic-img">
        							<img src="{$v['host']}{$v['dir']}{$v['filepath']}{$v['filename']}">
	        						<a class="set-fm">设为封面</a>
        						</div>
        						<input type="hidden" name="img_id[]" value="{$v['id']}" />
	        					<span class="del-btn underline">删除</span>
	        				</div>
	        				{/foreach}
	        				{/if}
	        				<div class="pic-box pic-add-btn">
	        					<div class="pic-img">
	        						<a class="add-btn"></a>
	        						<input type="file" multiple="multiple"/>
	        					</div>
	        				</div>
	        				<input type="hidden" name="del_id" value="" />
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">使用规则:</span>
	        			<div class="m2o-flex-one">
	        				<textarea name="rule" placeholder="请填写使用规则">{$rule}</textarea>
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">未开始提示:</span>
	        			<div class="m2o-flex-one">
	        				<textarea name="notstartdesc" placeholder="活动未开始提示">{$notstartdesc}</textarea>
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">结束提示:</span>
	        			<div class="m2o-flex-one">
	        				<textarea name="finish_desc" placeholder="活动结束提示">{$finish_desc}</textarea>
	        			</div>
	        		</div>
	        	</div>
	        	<div class="tab-item" _index="1">
	        		<div class="setting-item m2o-flex">
	        			<span class="title">时间限制:</span>
	        			<div class="m2o-flex-one">
	        				<div class="common-list-item m2o-switch" _status="{$time_limit}" style="position:relative;">
						    		<div class="common-switch {if $time_limit}common-switch-on{/if}" style="bottom:0px;">
						           		<div class="switch-item switch-left" data-number="0"></div>
						           		<div class="switch-slide"></div>
						           		<div class="switch-item switch-right" data-number="100"></div>
						        	</div>
						        	<input type="hidden" name="time_limit" value="{$time_limit}"/>
						    </div>
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">日期条件:</span>
	        			<div class="m2o-flex-one">
	        				<input type="text" class="date-picker time-select" name="start_time" value="{$start_time}"  placeholder="开始日期" style="margin-right:5px;"/>-
	        				<input type="text" class="date-picker time-select" name="end_time" value="{$end_time}"  placeholder="结束日期"/>
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">时间条件:</span>
	        			<div class="m2o-flex-one">
	        				<span>
	        				<input type="text" class="way-time start" name="start_hour" value="{$start_hour}"  placeholder="开始时间" style="margin-right:5px;"/>-
	        				<input type="text" class="way-time end" name="end_hour" value="{$end_hour}"  placeholder="结束时间"/>
	        				</span>
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">周期:</span>
	        			<div class="m2o-flex-one">
	        				<span class="cycle-input-item">
	        					{code}
	        					if($cycle_type == 1){
	        						$cycle_type = 'week';
	        						$week_day = explode(',',$cycle_value);
	        					}
	        					if($cycle_type == 2){
	        						$cycle_type = 'month';
	        						$cycle_values = $cycle_value;
	        					}
	        					
	        					{/code}
	        					<input type="radio" class="cycle-input" name="cycle_type" value="week" {if $cycle_type=='week'}checked="checked"{/if} />
	        					<label>星期条件</label>
	        				</span>
	        				<span class="cycle-input-item">
	        					<input type="radio" class="cycle-input" name="cycle_type" value="month" {if $cycle_type=='month'}checked="checked"{/if} />
	        					<label>月条件</label>
	        				</span>
	        				
	        			<div id="week_date" class="cycle-value-box {if $cycle_type=='week'}show{/if}">
	                        {code}
	                        $week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
	                        {/code}
	                        <label>
	                            <input class="n-h" type="checkbox"  id="every_day" class="every_day" name="every_day" {if count($week_day)==7}checked{/if}/><span>每天</span>
	                        </label>
	                        {foreach $week_day_arr as $key => $value}
	                        <label>
	                            <input class="n-h each-week" type="checkbox" name="week_day[]" id="week_day_{$key}" {foreach $week_day as $k => $v}{if $v == $key}checked{/if}{/foreach} value="{$key}" /><span>{$value}</span>
	                        </label>
	                        {/foreach}
	                	</div>
                	
	                	<div id="month_date" class="cycle-value-box {if $cycle_type=='month'}show{/if}">
	                		<input type="text" value="{$cycle_values}"class="month-value" /><span class="tip">逗号分割如1,4,8,指定数字为日期</span>
	                	</div>
                    	<input type="hidden" name="cycle_value" value="{$cycle_value}" />
	        		</div>
	        	</div>
	        		
	        		<!-- 
	        		<div class="setting-item m2o-flex">
	        			<span class="title">活动周期:</span>
	        			<div class="m2o-flex-one">
	        				<input type="text" name="activity_cycle"/>
	        			</div>
	        		</div>
	        		 -->
	        		<div class="setting-item m2o-flex">
	        			<span class="title">注册时间:</span>
	        			<div class="m2o-flex-one">
	        				<input type="text" class="date-picker" _time="true" value="{$register_time}" name="register_time" placeholder="注册时间"/>
	        			</div>
	        		</div>
	        	</div>
	        	<div class="tab-item" _index="2">
	        		<div class="setting-item m2o-flex">
	        			<span class="title">积分限制:</span>
	        			<div class="m2o-flex-one">
	        				<div class="common-list-item m2o-switch" _status="{$score_limit}" style="position:relative;">
						    		<div class="common-switch {if $score_limit}common-switch-on{/if}" style="bottom:0px;">
						           		<div class="switch-item switch-left" data-number="0"></div>
						           		<div class="switch-slide"></div>
						           		<div class="switch-item switch-right" data-number="100"></div>
						        	</div>
						        	<input type="hidden" name="score_limit" value="{$score_limit}"/>
						    </div>
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">消耗积分:</span>
	        			<div class="m2o-flex-one">
	        				<input type="text"  value="{$need_score}" name="need_score" style="margin-right:5px;"/>分
	        			</div>
	        		</div>
	        	</div>
	        	<div class="tab-item " _index="3">
	        		<div class="setting-item m2o-flex">
	        			<span class="title">抽奖次数限制:</span>
	        			<div class="m2o-flex-one">
	        				<div class="common-list-item m2o-switch" _status="{$num_limit}" style="position:relative;">
						    		<div class="common-switch {if $num_limit}common-switch-on{/if}" style="bottom:0px;">
						           		<div class="switch-item switch-left" data-number="0"></div>
						           		<div class="switch-slide"></div>
						           		<div class="switch-item switch-right" data-number="100"></div>
						        	</div>
						        	<input type="hidden" name="num_limit" value="{$num_limit}"/>
						    </div>
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">单个账号限:</span>
	        			<div class="m2o-flex-one">
	        				<input type="number" min="0" name="account_limit" class="short" value="{$account_limit}" />次
	        			</div>
	        		</div>
	        		<div class="setting-item m2o-flex">
	        			<span class="title">版本限制:</span>
	        			<div class="m2o-flex-one">
	        				<input type="text" value="{$version_limit}" name="version_limit"/>
	        			</div>
	        		</div>
	        		<!-- 
	        		<div class="setting-item m2o-flex">
	        			<span class="title">单个设备限</span>
	        			<div class="m2o-flex-one">
	        				<input type="number" name="device_number_limit" class="short" value="5" min="1"/>次
	        			</div>
	        		</div>
	        		 -->
	        	</div>
	        	<div class="tab-item area-tab" _index="4">
	        		<div class="setting-item m2o-flex">
	        			<span class="title">区域限制:</span>
	        			<div class="m2o-flex-one">
	        				<div class="common-list-item m2o-switch" _status="{$area_limit}" style="position:relative;">
						    		<div class="common-switch {if $area_limit}common-switch-on{/if}" style="bottom:0px;">
						           		<div class="switch-item switch-left" data-number="0"></div>
						           		<div class="switch-slide"></div>
						           		<div class="switch-item switch-right" data-number="100"></div>
						        	</div>
						        	<input type="hidden" name="area_limit" value="{$area_limit}"/>
						    </div>
	        			</div>
	        		</div>
	        		<div class="map-box">
	        			<div class="setting-box">
							<ul class="area-info-list">
	        					<li class="row position m2o-flex">
	        						<span>经度:</span>
	        						<input type="text" name="baidu_longitude" id="baidu_longitude" value="{$baidu_longitude}"/>
	        					</li>
	        					<li class="row position m2o-flex">
	        						<span>纬度:</span>
	        						<input type="text" name="baidu_latitude" id="baidu_latitude" value="{$baidu_latitude}"/>
	        					</li>
	        					<li class="row distance m2o-flex">
	        						<span>范围:</span>
	        						<input type="text" name="distance" value="{$distance}"/>
	        						<span>米</span>
	        					</li>
	        				</ul>
        					<div class="row m2o-flex">
        						<span>地址:</span>
        						<textarea name="address" class="specific-address">{$address}</textarea>
        					</div>
	        			</div>
	        			{code}
							$hg_bmap = array(
								'height' => 322,
								'width'  => 545,
								'longitude' => $baidu_longitude ? $baidu_longitude : '0', 
								'latitude'  => $baidu_latitude ? $baidu_latitude : '0',
								'zoomsize'  => 13,
								'areaname'  => $_configs['areaname'] ? $_configs['areaname'] : '盐城',
								'is_drag'   => 1,
							);
						{/code}
						{template:map/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
	        		</div>
	        	</div>
	        	<div class="tab-item awards-tab" _index="5">
	        		<div class="setting-block">
	        			<p class="title">未中奖反馈</p>
	        			<div class="block-content">
	        				<ul class="content-list">
	        				{if $feedback}
	        				{foreach $feedback as $k => $v}
	        				{code}
	        				$index_num = $k + 1;
	        				{/code}
	        					<li class="m2o-flex content-list-list">
	        						<span class="index">{$index_num}</span>
	        						<div class="detail-wrap">
	        							<input name="no_lottery_feedback[]" placeholder="抽奖反馈" value="{$v}" class="db wd500"/>
	        						</div>
	        						<a class="del-btn"></a>
	        					</li>
	        				{/foreach}
	        				{/if}
	        				</ul>
	        				<div class="add-btn add-no-awards">新增未中奖反馈</div>
	        			</div>
	        		</div>
	        		<div class="setting-block">
	        			<p class="title">奖项设置</p>
	        			<div class="block-content">
	        				<ul class="content-list">
	        				{if $prize}
	        				{code}
	        				$index_num = 0;
	        				{/code}
	        				{foreach $prize as $k => $vl}
	        				{code}
	        				$index_num += 1;
	        				{/code}
	        					<li class="m2o-flex content-list-list">
	        						<span class="index">{$index_num}</span>
	        						<div class="detail-wrap m2o-flex">
	        							<div class="mr10 pic-box">
	        								<img src="{if $vl['host']}{$vl['host']}{$vl['dir']}98x64/{$vl['filepath']}{$vl['filename']}{/if}">
	        								<input type="hidden" name="award_indexpic[]" value="{$vl['indexpic_id']}" />
	        							</div>
	        							<input type="file" />
	        							<div>
	        								<div class="mb10 m2o-flex">
	        									<input class="db wd150" name="award_name[]" value="{$vl['name']}" placeholder="奖项名称"/>
	        									{code}
	        										$type_show = 'type_show'.$index_num;
											    	$type_source = array(
											             'class' 	=> 'down_list i',
											             'show' 		=> $type_show,
											             'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
											             'is_sub'	=>	1,
											             'width'    => '90',
											             'more'     => $index_num
											        );
											        if($vl['type']){
											         	$type_default = $vl['type'];
											        }
											        else
											        {
											            $type_default = 1;
											        }
											    {/code}
											    {template:form/search_source,award_type,$type_default,$_configs['prize_type'],$type_source}
	        									<input class="db wd140" name="award[]" value="{$vl['prize']}" placeholder="选择奖品"/>
	        								</div>
	        								<div class="m2o-flex">
	        									<input class="db wd70" name="seller_id[]" value="{$vl['seller_id']}" placeholder="商家ID"/>
	        									<input class="db wd70" name="award_num[]" value="{$vl['prize_num']}" placeholder="中奖人数"/>
	        									<input class="db wd70" name="award_probability[]" value="{$vl['chance']}" placeholder="中奖概率"/>
	        									<input class="db wd150" name="award_feedback[]" value="{$vl['tip']}" placeholder="抽奖反馈"/>
	        								</div>
	        							</div>
	        						</div>
	        						<input type="hidden" name="award_id[]" value="{$vl['id']}" />
	        						<a class="del-btn"></a>
	        					</li>
	        				{/foreach}
	        				{/if}
	        				</ul>
	        				<div class="add-btn add-awards">新增奖项</div>
	        			</div>
	        		</div>
	        	</div>
        	</div>
        </section>
        <input name="a" value="{$ac}" type="hidden" />
   	 	<input name="id" value="{$id}" type="hidden" />
        <aside class="m2o-r">
			<iframe id="lottery-iframe" src="./run.php?mid={$_INPUT['mid']}&a=preview&id={$formdata['id']}" frameborder="no" style="width:100%;height:568px;" />
        </aside>
    </div>
    <div class="media-box"></div>
    </div>
</form>
</body>
