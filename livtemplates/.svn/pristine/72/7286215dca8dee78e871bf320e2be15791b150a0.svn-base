{template:head}
{css:2013/list}
{css:2013/iframe}
{css:workload_list}
{js:2013/ajaxload_new}
{js:page/page}
{js:jqueryfn/highcharts}
{js:jqueryfn/exporting}
{js:workload/workload_department_info_list}
{js:workload/search}
<script type="text/javascript">
var userid = {$_INPUT['user_id']};
</script>
<div class="wrap">
	<div class="title">个人统计-<span>{$_INPUT['user_name']}</span></div>
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
					{template:unit/search, $url , $flag , $toggle , $muti}
				</div>
				<div id="chart-area-line" style="width:467px;height:170px;"></div>
			</div>
			<!--<div class="advance-person">
				<span class="top10">TOP10</span>
				<ul class="top10-list">
				</ul>
			</div>-->
		</div>
		<div class="info-list m2o-flex-one">
			{code}
				$url = 1;
				$flag = 1;
				$toggle = 0;
				$muti = 0; 
			{/code}
			{template:unit/search, $url , $flag , $toggle , $muti}
			<div class="m2o-title m2o-flex m2o-flex-center">
				<div class="m2o-flex-one">稿件</div>
				<div class="list-column">栏目</div>
				<div class="list-type">类型</div>
				<div class="list-view">浏览/评论</div>
				<div class="list-time">发表时间</div>
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
<li><span>累计发稿</span><span>{{= total}}</span></li>
<li><span>{{= date_title}}发稿</span><span>{{= count}}</span></li>
<li><span>{{= date_title}}已审核</span><span>{{= statued}}</span></li>
<li><span>{{= date_title}}打回</span><span>{{= unstatued}}</span></li>
<li><span>{{= date_title}}浏览量</span><span>{{= click_num}}</span></li>
<li><span>{{= date_title}}审核量排名</span><span>{{= order}}</span></li>
</script>
<script type="text/x-jquery-tmpl" id="list-tpl">
{{each options}}
<div class="info-each m2o-flex m2o-flex-center" _id="{{= $value['id']}}">
	<div class="m2o-flex-one list-title" title="{{= $value['title']}}">
		{{if $value['indexpic']['host']}}
		<img class="list-indexpic" src="{{= $value['indexpic']['host']}}{{= $value['indexpic']['dir']}}40x30/{{= $value['indexpic']['filepath']}}{{= $value['indexpic']['filename']}}"/>
		{{/if}}
		<span>{{= $value['title']}}</span>
	</div>
	<div class="list-column">
		{{each($kk, $vv) $value['column_info']}}
			<a  target="_blank"  href="{{= $vv['content_url']}}">{{= $vv['name']}}</a>  
		{{/each}}
	</div>
	<div class="list-type">{{= $value['apptype']}}</div>
	<div class="list-view">{{= $value['click_num']}}/{{= $value['comment_num']}}</div>
	<div class="list-time">{{= $value['publish_time']}}</div>
</div>
{{/each}}
</script>
<script>
$(function(){
	var infoUrl = {};
	infoUrl.listUrl = './run.php?mid=' + gMid + '&a=publishcontent&user_name={$_INPUT['user_name']}';
	infoUrl.getOneOrgUrl = './run.php?mid=' + gMid + '&a=detail&user_id=' + userid;
	infoUrl.getOneOrgTotalUrl = './run.php?mid=' + gMid + '&a=getPersonTotal&user_id=' + userid;
	$.control.init( infoUrl );
 	$('.search-box').search({
 		callback_3 : function( target , param ){
 	 		var url = './run.php?mid=' + gMid + '&a=detail&user_id=' + userid;
 	 		$.control.getOneOrg( param , url );
 		},
 		callback_2 : function( target , param ){
 	 		var url =  './run.php?mid=' + gMid + '&a=getPersonTotal&user_id=' + userid;
 	 		$.control.getOneOrgTotal( param ,url );
 		},
 		callback_1 : function( target , param ){
 	 		var url = './run.php?mid=' + gMid + '&a=publishcontent&user_name={$_INPUT['user_name']}';
 	 		$.control.getList( param , null , url );
 	 	},
 	});
})
</script>

