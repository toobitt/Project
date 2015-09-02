{template:head}
{css:2013/list}
{css:2013/iframe}
{css:workload_list}
{js:2013/ajaxload_new}
{js:page/page}
{js:jqueryfn/highcharts}
{js:jqueryfn/exporting}
{js:workload/search}
{js:workload/workload_department_info_list}
<script type="text/javascript">
var orgid = {$_INPUT['org_id']};
</script>
<div class="wrap">
	<div class="title">部门统计-<span>{$_INPUT['Der_Name']}</span></div>
	<div class="content-wrap m2o-flex">
		<div class="left-aside">
			<div class="list-pie m2o-flex">
				<ul class="list-pie-info"></ul>
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
						<span class="pie-all-num"></span>
					</div>
					<ul class="pie-info-list"></ul>
				</div>
			</div>
			<div class="line-chart">
				<div class="department-chart">
					<span class="chart-title">趋势图</span>
					{code}
						$url = 2;
						$flag = 1;
						$toggle = 0;
						$muti = 1;
					{/code}
					{template:unit/search, $url , $flag , $toggle ,$muti}
				</div>
				<div id="chart-area-line" style="width:467px;height:170px;"></div>
			</div>
			<div class="advance-person">
				<span class="top10">TOP10</span>
				<ul class="top10-list">
				</ul>
			</div>
		</div>
		<div class="info-list m2o-flex-one">
			<div class="m2o-title m2o-flex m2o-flex-center">
				<div class="m2o-flex-one">姓名</div>
				<div class="list-top">近7天排名</div>
				<div class="list-publish">近7天发稿</div>
				<div class="list-audit">已审核</div>
				<div class="list-back">打回</div>
				<div class="list-news">文稿</div>
				<div class="list-pic">图集</div>
				<div class="list-video">视频</div>
			</div>
			<div class="info-each-list"></div>
			<div class="m2o-flex m2o-flex-center info-list-bottom">
				<div class="page_size" style="width: 100%;"></div>
			</div>
		</div>
	</div>
</div>
{template:foot}
<script type="text/x-jquery-tmpl" id="list-info-tpl">
<li><span>部门人数</span><span>{{= person_count}}</span></li>
<li><span>累计发稿</span><span>{{= total}}</span></li>
<li><span>{{= date_title}}发稿</span><span>{{= count}}</span></li>
<li><span>{{= date_title}}已审核</span><span>{{= statued}}</span></li>
<li><span>{{= date_title}}打回</span><span>{{= unstatued}}</span></li>
<li><span>{{= date_title}}浏览量</span><span>{{= click_num}}</span></li>
</script>
<script type="text/x-jquery-tmpl" id="list-tpl">
{{each options}}
<div class="info-each m2o-flex m2o-flex-center" _id="{{= $value['user_id']}}">
	<div class="m2o-flex-one list-title" title="{{= $value['user_name']}}">
		<a href="run.php?mid={$relate_module_id}&user_name={{= $value['user_name']}}&user_id={{= $value['user_id']}}&top_index={{= $index +1}}&needback=true" target="mainwin" need-back>
		{{if $value['avatar']['host']}}
		<img class="list-img" src="{{= $value['avatar']['host']}}{{= $value['avatar']['dir']}}30x30/{{= $value['avatar']['filepath']}}{{= $value['avatar']['filename']}}"/>
		{{else}}
		<img class="list-img" src="{$RESOURCE_URL}pic_detail.png"/>
		{{/if}}
		<span>{{= $value['user_name']}}</span>
		</a>
	</div>
	<div class="list-top">{{if $value['top']}}{{= $value['top']}}{{else}}0{{/if}}</div>
	<div class="list-publish">{{if $value['count']}}{{= $value['count']}}{{else}}0{{/if}}</div>
	<div class="list-audit">{{if $value['statued']}}{{= $value['statued']}}{{else}}0{{/if}}</div>
	<div class="list-back">{{if $value['unstatued']}}{{= $value['unstatued']}}{{else}}0{{/if}}</div>
	<div class="list-news">{{if $value['appcount']['news']}}{{= $value['appcount']['news']}}{{else}}0{{/if}}</div>
	<div class="list-pic">{{if $value['appcount']['tuji']}}{{= $value['appcount']['tuji']}}{{else}}0{{/if}}</div>
	<div class="list-video">{{if $value['appcount']['livmedia']}}{{= $value['appcount']['livmedia']}}{{else}}0{{/if}}</div>
</div>
{{/each}}
</script>
<script>
$(function(){
	var infoUrl = {};
	infoUrl.listUrl = './run.php?mid=' + gMid + '&a=list&org_id=' + orgid;
	infoUrl.getOneOrgUrl = './run.php?mid=' + gMid + '&a=getOneOrg&org_id=' + orgid;
	infoUrl.getOneOrgTotalUrl = './run.php?mid=' + gMid + '&a=getOneOrgTotal&org_id=' + orgid;
	$.control.init( infoUrl );
 	$('.search-box').search({
 		callback_2 : function( target , param ){
 			var url = './run.php?mid=' + gMid + '&a=getOneOrgTotal&org_id=' + orgid;
 			$.control.getOneOrgTotal( param , url);
 		},
 		callback_3 : function( target , param ){
 			var url = './run.php?mid=' + gMid + '&a=getOneOrg&org_id=' + orgid;
 			$.control.getOneOrg( param , url );
 		}
 	});
})
</script>
