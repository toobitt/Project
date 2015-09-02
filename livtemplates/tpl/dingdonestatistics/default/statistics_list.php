{template:head}
{css:2013/m2o}
{code}
$list = $list[0];
$statistics = $list['statistical_figure'];

{/code}
{js:jqueryfn/highcharts}
{js:jqueryfn/exporting}
<style>
	.statistics-total,.statistics-part{margin:30px 70px;}
	.statistics-item{display:table-cell;vertical-align:middle;width:130px;height:98px;text-align:center;color:#fff;font-size:14px;}
	.orange{background-color:#ffad88;}
	.yellow{background-color:#e6c535;}
	.green{background-color:#7bd195;}
</style>
<div class="">
	<div class="m2o-flex">
		<div class="statistics-total m2o-flex m2o-flex-one">
			<div class="statistics-item orange">
				<p>总账户数</p>
				<p>{$list['total_account']}</p>
			</div>
			<div class="statistics-item green">
				<p>总APP数</p>
				<p>{$list['total_app']}</p>
			</div>
			<div class="statistics-item yellow">
				<p>总激活量</p>
				<p>{$list['total_activation']}</p>
			</div>
		</div>
		<div class="statistics-part m2o-flex m2o-flex-one">
			<div class="statistics-item orange">
				<p>本周账户数</p>
				<p>{$list['total_account_week']}</p>
			</div>
			<div class="statistics-item green">
				<p>本周APP数</p>
				<p>{$list['total_app_week']}</p>
			</div>
			<div class="statistics-item yellow">
				<p>本周激活量</p>
				<p>{$list['total_activation_week']}</p>
			</div>
			
		</div>
		
	</div>
	<div id="statistics-svg" style="padding:0px 10px;"></div>
</div>
<script>
	var statistics_data = {code} echo  $statistics ? json_encode($statistics) : '{}';{/code},
		statistics_xAxis = [],
		account_data = [],
		app_data = [],
		activation_data = [],
		series = [];
	$.each( statistics_data, function( key, value ){
		var obj = {};
		var date = value['month'] + '-' + value['day'];
		statistics_xAxis.push( date );
		account_data.push( +value['total_account'] );
		app_data.push( +value['total_app'] );
		activation_data.push( +value['total_activation'] );
	} );
	series = [ 
	      		{
		      		name :'账户数', 
		      		data : account_data , 
		      		color: '#ffad88' , 
		      		fillColor : {
        							linearGradient : [0, 0, 0, 300],
        							stops : [
          										[0, '#ffad88'],
          										[1, Highcharts.Color('#ffad88').setOpacity(0).get('rgba')]
       	 									]
      							}
				},
				{
					name :'APP数', 
					data : app_data , 
					color : '#7bd195',
					fillColor : {
          							linearGradient : [0, 0, 0, 300],
          							stops : [
            									[0, '#7bd195'],
            									[1, Highcharts.Color('#7bd195').setOpacity(0).get('rgba')]
          									]
        						}
				},
				{
					name :'激活量', 
					data : activation_data ,
					color: '#e6c535',
					fillColor : {
            						linearGradient : [0, 0, 0, 300],
            						stops : [
              									[0, '#e6c535'],
              									[1, Highcharts.Color('#e6c535').setOpacity(0).get('rgba')]
            								]
          						}
				} 
			];
	$(function () {
		var len = statistics_xAxis.length,
		step = (len/10).toFixed(0);
		$('#statistics-svg').highcharts({
            chart: {
                type: 'area'
            },
            title: {
                text: '叮当账户数、app数、激活量统计 (个数)',
                x: -20 //center
            },
            subtitle: null,
            xAxis: {
            	labels:{ 
            		x : 15,
            		rotation:1,
            	    step:step,
            	    y : 20,
            	},
                categories:statistics_xAxis
            },
            yAxis: {
                min: 0,
                title: null,
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat:  '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    		  '<td style="padding:0"><b>{point.y} 个</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                area: {
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    },
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            credits: { enabled:false },
	        exporting: { enabled:false },
	        legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 0
            },
            series:series,
        });
    });
</script>
{template:foot}