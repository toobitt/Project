{template:head} {css:ad_style} {css:column_node} {js:column_node} {code}
if($formdata) { $optext="更新"; $a="updatepm25"; } else { $optext="添加";
$a="createpm25"; } {/code}
<style>
.weather-img {
	float: left;
	margin-right: 20px;
	width: 76px;
	height: 68px;
	position: relative;
	border: 1px dashed #CCC;
}

.weather-img span {
	position: absolute;
	right: 0;
	cursor: pointer;
	display: none;
}

.weather-img img,.weather-pic-item img {
	width: 70px;
	height: 65px;
}

.weather-img-box {
	clear: left;
	margin-left: 72px;
}

.weather-img-box .weather-pic-item {
	float: left;
	margin: 10px 10px 0 0;
	text-align: center;
}

.weather-pic-item span {
	display: block;
}
</style>

<div id="channel_form" style="margin-left: 40%;"></div>
<div class="wrap clear">

	<div class="ad_middle">
		{if $formdata}
		<form action="" method="post" enctype="multipart/form-data"
			class="ad_form h_l">
			<h2>{$formdata['area']}</h2>
			<!--  
		 	<div class="ext-tab">
                    <a href="javascript:void(0)" onclick="show_form('edit')" class="ext-current">编辑天气信息 </a>
                    <a href="javascript:void(0)" onclick="show_form('config')">配置天气信息 </a>
			</div>
			-->
			{code}
			$pm=array('aqi'=>'空气质量指数(AQI)','area'=>'城市名称','position_name'=>'监测点名称','station_code'=>'监测点编码','so2'=>'二氧化硫1小时平均',
			'so2_24h'=>'二氧化硫24小时滑动平均','no2'=>'二氧化氮1小时平均','no2_24h'=>'二氧化氮24小时滑动平均','pm10'=>'颗粒物（粒径小于等于10μm）1小时平均','pm10_24h'=>'颗粒物（粒径小于等于10μm）24小时滑动平均',
			'co'=>'一氧化碳1小时平均','co_24h'=>'一氧化碳24小时滑动平均','o3'=>'臭氧1小时平均','o3_24h'=>'臭氧24小时滑动平均','o3_8h'=>'臭氧8小时滑动平均','o3_8h_24h'=>'臭氧8小时滑动平均的24小时均值',
			'pm2_5'=>'颗粒物（粒径小于等于2.5μm）1小时平均','pm2_5_24h'=>'颗粒物（粒径小于等于2.5μm）24小时滑动平均','primary_pollutant'=>'首要污染物','quality'=>'空气质量指数类别','time_point'=>'数据发布的时间');
			$pm25data_id=$formdata['id']; 
			unset($formdata['id']);
			unset($formdata['area']);
			$formdata['time_point']=date('Y-m-d H:i:s',$formdata['time_point']);
			$pm25data_time_point=$formdata['time_point'];
			unset($formdata['time_point']); 
			{/code}
			<ul class="form_ul" id='edit' style="display: block">

			{foreach $formdata as $key=>$val}


				<li class="i">
					<div class="form_ul_div">
						<span>{$pm[$key]}：</span> <input type="text" name="{$key}"
							/ value="{$val}">
					</div>
				</li> {/foreach}
				<li class="i">
					<div class="form_ul_div">
						<span>{$pm['time_point']}：</span> {$pm25data_time_point}
					</div>
				</li>
			</ul>

			<div id='config' style="display: none"></div>


			<input type="hidden" name="a" value="{$a}" /> <input type="hidden"
				name="pm25_id" value="{$pm25data_id}" /> <input type="hidden"
				name="referto"
				value="./run.php?mid={$_INPUT['mid']}&infrm=1" /> <input
				type="hidden" name="infrm" value="{$_INPUT['infrm']}" /> <br /> <input
				type="submit" name="sub" value="{$optext}" class="button_6_14" />
		</form>
		{else} <br /> <br /> <br /> <br />
		<p
			style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 20px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
		<div id='config' style="display: none"></div>
		<br /> {/if}
	</div>
	<div class="right_version">
		<h2>
			<a href="./run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a>
		</h2>
	</div>
</div>
{template:foot}
