{template:head}
{css:2013/list}
{css:2013/iframe}
{css:workload_list}
{js:2013/ajaxload_new}
{js:jqueryfn/highcharts}
{js:jqueryfn/exporting}
{js:workload/search}
{js:workload/workload_department_list}
<script>
var relate_module_id = {$relate_module_id};
</script>
<div class="wrap">
	<div class="total-wrap-chart">
		<div class="line-chart">
			<div class="department-chart">
				<span class="chart-title">趋势图</span>
				{code}
					$url = 1;
					$flag = 1;
					$toggle = 0;
					$muti = 1;
				{/code}
				{template:unit/search, $url , $flag , $toggle ,$muti}
			</div>
			<div id="chart-area-line" style="width:395px;height:170px"></div>
		</div>
		<div class="bar-chart">
			<div class="department-chart">
				<span class="chart-title">部门</span>
				{code}
					$url = 2;
					$flag = 1;
					$toggle = 0;
					$muti = 1;
				{/code}
				{template:unit/search, $url , $flag , $toggle ,$muti}
			</div>
			<div id="chart-area-bar" style="width:415px;height:170px;"></div>
		</div>
		<div class="pie-chart">
			<div class="department-chart">
				<span class="chart-title">类型</span>
				{code}
					$url = 3;
					$flag = 0;
					$toggle = 0;
					$muti = 1;
				{/code}
				{template:unit/search, $url , $flag , $toggle ,$muti}
			</div>
			<div id="chart-area-pie" style="width:165px;height:165px;margin-left: -10px;"></div>
			<div class="pie-all">
				<span>全部</span>
				<span class="pie-all-num">4567</span>
			</div>
			<ul class="pie-info-list"></ul>
		</div>
	</div>
	<div class="department-wrap">
		<div class="department-wrap-nav">
			<div class="nav-list">
				<span class="active" _index="0">人员统计</span>
				<span class="divide-line">|</span>
				<span _index="1">部门统计</span>
			</div>
			{code}
					$url = 4;
					$flag = 1;
					$toggle = 1;
					$muti = 1;
				{/code}
				{template:unit/search, $url , $flag , $toggle ,$muti}
		</div>
		<ul class="department-list staffs-list" >
			<li class="more-department" _type="1">
				<span>更多人员</span>
			</li>
		</ul>
		<ul class="department-list divisions-list" style="display:none;">
			<li class="more-department" _type="2">
				<span>更多部门</span>
			</li>
		</ul>
		
	</div>
</div>
{template:foot}
<script type="text/x-jquery-tmpl" id="list-tpl">
<li class="divisions-list-each" _id="{{= id}}">
	<div class="chart-title-wrap">
			<div class="department-chart">
			<span class="chart-title">{{= name}}</span>
			<a class="more-detail" href="{{= href}}" target="mainwin" need-back>详情</a>
		</div>
		<div id="chart-area-line_{{= id}}" class="chart-area-line toggle-chart-area"  style="width:192px;height:115px;"></div>
		<div id="chart-area-bar_{{= id}}" class="chart-area-bar toggle-chart-area"  style="width:192px;height:115px;"></div>
	</div>
</li>
</script>
<script>
$(function(){
	$('.search-box').search({
		callback_1 : function( target , param ){
			$.control.getTotal( param );
		},
		callback_2 : function( target,  param ){
			$.control.getOrgAppPre( param );
		},
		callback_3 : function( target ,param ){
			$.control.getTotalPre( param );
		},
		callback_4 : function( target , param ){
			$.control.getOrgTotal( target , param , false , false);
		}
	});
})

</script>
