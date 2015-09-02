(function($){
	var Months = ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		weeks = ['周日','周一','周二','周三','周四','周五','周六'];
	Highcharts.setOptions( {
		lang : {
			months : Months,
			shortMonths : Months,
			weekdays : weeks,
			resetZoom : '恢复显示范围'
		}
	} );
	$.renderChart = function(param){
		var param = param,
			interval = parseInt( param['interval'] );
		var	chart = new Highcharts.Chart({
		      chart: {
		         renderTo: 'bandwidth-chart',
		         zoomType: 'x',
		         spacingRight: 20
		      },
		      credits : {
		    	  enabled : false
		      },
		       title: {
		         text: param['start_time'] + '~ ' + param['end_time'] + ' 带宽图('+ ( interval/60 ) + '分钟平均)'
		      },
		       subtitle: {
		         text: document.ontouchstart === undefined ?
		            '选择区域放大查看' :
		            ''
		      },
		      xAxis: {
		         type: 'datetime',
		         maxZoom: 3600, // fourteen days
		         title: {
		            text: null
		         }
		      },
		      yAxis: {
		         title: {
		            text: '带宽'
		         },
		         min: 0.6,
		         startOnTick: false,
		         showFirstLabel: false
		      },
		      tooltip: {
				 formatter: function() {
							if(this.series.name == '带宽')
							return '<b>'+Highcharts.dateFormat((interval==300?'%Y/%m/%d, %H:%M : ':'%Y/%m/%d, %H点 '), this.x)+ Math.round(this.y/1024/1024*100)/100 +'Mb</b>';
							else
							return '<b>'+Highcharts.dateFormat((interval==300?'%Y/%m/%d, %H:%M : ':'%Y/%m/%d, %H点 '), this.x)+ Math.round(this.y/1802*100)/100 +'次请求</b>';
						}
		      },
		      legend: {
		         enabled: false
		      },
		      plotOptions: {
		         area: {
					color: '#6BBEEA',
		            fillColor: {
		               linearGradient: [0, 0, 0, 300],
		               stops: [
		                  [0, '#6BBEEA'],
		                  [1, '#caebff']
		               ]
		            },
		            lineWidth: 1,
		            marker: {
		               enabled: false,
		               states: {
		                  hover: {
		                     enabled: true,
		                     radius: 3
		                  }
		               }
		            },
		            shadow: false,
		            states: {
		               hover: {
		                  lineWidth: 1
		               }
		            }
		         },
				 line: {
					color: '#dddddd',
					marker: {
		               enabled: false,
		               states: {
		                  hover: {
		                     enabled: true,
		                     radius: 2
		                  }
		               }
		            },
					shadow: false,
					lineWidth: 1,
					states: {
		               hover: {
		                  lineWidth: 1
		               }
		            }
				 }
		      },
		   
		      series: [{
		         type: 'area',
		         name: '带宽',
		         pointInterval: interval *1000,
		         pointStart: (param['start_point']+8*3600)*1000,
		         data: param.bandwidth_data
		      },{
				type: 'line',
		         name: '请求数',
		         pointInterval: interval*1000,
		         pointStart: (param['start_point']+8*3600)*1000,
		         data: param.reqs_data
		      }]
		   });
		
		
		var discharge_chart = new Highcharts.Chart({
		      chart: {
		          renderTo: 'discharge-chart',
		          defaultSeriesType: 'column',
		       },
		       title: {
		          text: param['start_time'] + '~ ' + param['end_time'] + '流量图 '+( interval==300?'(小时)':'(天)')
		       },
		 	  xAxis: {
		          type: 'datetime',
		          title: {
		             text: null
		          },
		       },
		       yAxis: {
		          min: 0,
		          title: {
		             text: '流量',
		             align: 'middle'
		          }
		       },
		       credits : {
			    	  enabled : false
			  },
		 	  legend: {
		          enabled: false
		       },
		 	  plotOptions: {
		 		column: {
		 			color:'#6BBEEA',
		 			shadow: false,
		 			borderWidth:1,
		 		}
		 	  },
		       tooltip: {
		          formatter: function() {
		 			if(Math.round(this.y/1024/1024) < 1024)
		 				return '<b>'+Highcharts.dateFormat((interval==300?'%Y/%m/%d, %H:%M : ':'%Y/%m/%d '), this.x)+this.series.name +': '+ Math.round(this.y/1024/1024*100)/100 +' MB</b>';
		 			else if(Math.round(this.y/1024/1024/1024) < 1024)
		 				return '<b>'+Highcharts.dateFormat((interval==300?'%Y/%m/%d, %H:%M : ':'%Y/%m/%d '), this.x)+this.series.name +': '+ Math.round(this.y/1024/1024/1024*100)/100 +' GB</b>';
		 			else
		 				return '<b>'+Highcharts.dateFormat((interval==300?'%Y/%m/%d, %H:%M : ':'%Y/%m/%d '), this.x)+this.series.name +': '+ Math.round(this.y/1024/1024/1024/1024*100)/100 +' TB</b>';
		          }
		       },
		       series: [{
		          name: '流量',
		 		 pointInterval: (interval==300?3600:3600*24)*1000,
		 		 pointStart: (param['start_point']+8*3600)*1000,
		          data: param.discharge_data
		       }]
		    });
	};
})(jQuery);