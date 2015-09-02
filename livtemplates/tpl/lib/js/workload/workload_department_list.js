$(function(){
	var MC = $('.wrap');
	
	$.control = {
			
		init : function(){
			this.initData();
			MC
			.on('click' , '.nav-list span' , $.proxy(this.toggleDepartment , this))
			.on('click' , '.toggle-chart span' , $.proxy(this.toggle , this))
			.on('click' , '.more-department' , $.proxy(this.getMore , this))
		},
		
		initData : function(){
			var wait = $.globalLoad( MC );
			var _this = this;
			var doData = function(){
			　　　　var dtd = $.Deferred(); //在函数内部，新建一个Deferred对象
			　　　　var tasks = function(){
			　　　　　　 _this.getTotal( null );
					  _this.getOrgAppPre( null)
					  _this.getTotalPre( null );
					  _this.getOrgTotal( null , null , true , true );
			　　　　　　dtd.resolve(); // 改变Deferred对象的执行状态
			　　　　};
				　 tasks();
			　　　　return dtd.promise(); // 返回promise对象
				};
		　　$.when(doData())
		　　.done(function(){ 
				setTimeout(function(){
					wait();
				},'2000')
		　　})
		　　.fail(function(){ alert("出错啦！"); });
		},
		
		getTotal : function( param ){
			var url = './run.php?mid=' + gMid + '&a=getTotal',
				item = MC.find('.line-chart'),
				_this = this;
			this.ajax( item , url , param , function( data ){
				_this.initLine( data );
			})
		},
		
		getTotalPre : function( param ){
			var url = './run.php?mid=' + gMid + '&a=getTotalPre',
				item = MC.find('.pie-chart'),
				_this = this;
			this.ajax( item , url , param , function( data ){
				_this.initPie( data );
			})
		},
		
		getOrgTotal : function( target , param , bool , flag ){									/*flag用来区分加载更多 还是条件筛选*/
			var url = './run.php?mid=' + gMid + '&a=getOrgTotal',
				item = MC.find('.department-wrap'),
				_this = this;
			this.ajax( item , url , param , function( data ){
				if( data ){
					var index = MC.find('.toggle-chart span.current').index();
					var doList = function(){
						dtd = $.Deferred();
						var getData = function(){
							if( data[0].org ){
								if( data[0].org.length ){
									_this.initOrglist( data[0].org , flag );
								}
							}
							if( data[0].person ){
								if( data[0].person.length ){
									_this.initStafflist( data[0].person , flag );
								}
							}
							dtd.resolve();
						}
						getData();
						return dtd.promise();
					}
					$.when( doList() )
					.done(function(){
						if( index ){
							MC.find('.chart-area-line').hide();
							MC.find('.chart-area-bar').show();
						}else{
							MC.find('.chart-area-line').show();
							MC.find('.chart-area-bar').hide();
						}
					})
				}else{
					_this.tip( target , '没有更多数据了！');
				}
			})
		},
		
		getOrgAppPre : function( param ){
			var url = './run.php?mid=' + gMid + '&a=getOrgAppPre',
				item = MC.find('.bar-chart'),
				_this = this;
			this.ajax( item , url , param , function( data ){
				_this.initBar( data );
			})
		},
		
		toggle : function( event ){
			var self = $( event.currentTarget ),
				parent = MC.find('.department-wrap'),
				hasClass = self.hasClass('current'),
				index = self.index();
			if( !hasClass ){
				self.addClass('current').siblings().removeClass('current');
				parent.find('.toggle-chart-area').toggle();
			}
		},
		
		toggleDepartment : function( event ){
			var target = $( event.currentTarget ),
				index = target.attr('_index');
			if( index ){
				target.addClass('active').siblings().removeClass('active');
				MC.find('.department-list:eq('+ index +')').show().siblings('.department-list').hide();
			}
		},
		
		getMore : function( event ){
			var self = $( event.currentTarget ),
				type = self.closest('li').attr('_type'),
				ul = self.closest('.department-list'),
				wrap = self.closest('.department-wrap');
			var len = ul.find('li').length,
				date_search = wrap.find('input[name="date_search"]').val(),
				app = wrap.find('input[name="app"]').val();
			wrap.find('.chart-area-line').show().end().find('.chart-area-bar').hide();
			var param = {};
			param.offset = len-1;
			param.date_search = date_search;
			param.app = app;
			param.type = type;
			this.getOrgTotal( self , param , false , true);
		},
		
		initLine : function( data ){
			var myChart =  MC.find('#chart-area-line');
			var label = [],
				count = [],
				statued = [];
			$.each( data , function(key , value){
				label.push( value.date );
				count.push( value.count );
				statued.push( value.statued );
			});
			var max_1 = Math.max.apply(null, count),
				max_2 = Math.max.apply(null, statued),
				max_num = (max_1 > max_2 ) ? max_1 : max_2 ;
			myChart.highcharts({
		            title: null,
		            subtitle: null,
		            xAxis: {
		                categories: label
		            },
		            yAxis: {
			            title: null,
			            max : max_num,
			            min : 0,
			            tickPixelInterval: 40  ,
		                plotLines: [{
		                    value: 0,
		                    width: 1,
		                    color: '#808080'
		                }]
		            },
		            tooltip: {
		                valueSuffix: null
		            },
		            credits: { enabled:false },
			        exporting: { enabled:false },
			        legend: { enabled : false },
		            series: [{
		                name: '按发稿量',
		                color : '#f88664',
		                data: count
		            },
		            {
		                name: '按通过率',
		                color : '#548dd4',
		                data: statued
		            }]
		        });
		},
		
		initBar : function( data ){
			var myChart = MC.find('#chart-area-bar');
			var label = [],
				counts = [],
				type = [],
				color =[];
			$.each( data[0].org, function(key , value){
				label.push( value );
			});
			$.each( data[0].count, function(key , value){
				counts.push( value.count );
				color.push( value.color );
				type.push( value.name );
			});
			var barChartDataList =[],
				step =[];
			$.each( counts , function( k , v ){
				var count = [];
				$.each( v , function( kk , vv ){
					step.push( vv );
					count.push( vv );
				})
				barChartDataList.push( { name :type[k] , color : color[k] , data : count } );
			});
			myChart.highcharts({
	            chart: {
	                type: 'column'
	            },
	            title: null,
	            subtitle: null,
	            xAxis: {
	                categories: label
	            },
	            yAxis: {
	                min: 0,
	                title: null
	            },
	            tooltip: {
	                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
	                footerFormat: '</table>',
	                shared: true,
	                useHTML: true
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0,
	                    borderWidth: 1
	                }
	            },
	            credits: { enabled:false },
		        exporting: { enabled:false },
		        legend: { enabled : false },
	            series: barChartDataList
	        });
		},
		
		initPie : function( data ){
			var myChart = MC.find('#chart-area-pie');
			var pieData =[],
				html = '';
			$.each( data[0].app_count , function( key , value ){
				var percent = value.count ? ((value.count/data[0].total)*100).toFixed(0) + '%' : 0 ;
				pieData.push( { y : JSON.parse(value.count) , color: value.color ,name : value.name } );
				html += '<li class="m2o-flex">'+
							'<span class="pie-info-color" style="background:'+ value.color +'"></span>'+
							'<span class="pie-info-title">'+ value.name + '</span>'+
							'<span class="pie-info-num">'+ percent +'</span>'+
						'</li>' ;
			});
			myChart.highcharts({
		        chart: {
		            plotBackgroundColor: null,
		            plotBorderWidth: 0,//null,
		            plotShadow: false
		        },
		        title: null,
		        tooltip: {
		    	    pointFormat: '<b>{point.y}</b>'
		        },
		        credits: { enabled:false },
		        exporting: { enabled:false },
		        legend: {
	                layout: 'vertical',
	                align: 'right',
	                verticalAlign: 'middle',
	                borderWidth: 0
	            },
		        plotOptions: {
		            pie: {
		                allowPointSelect: true,
		                cursor: 'pointer',
		                dataLabels: {
		                    enabled: false,
		                    format: '<b>{point.name}</b>: {point.y}',
		                    style: {
		                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                    }
		                }
		            }
		        },
		        series: [{
		            type: 'pie',
		            name: '',
		            innerSize: '50%',
		            data: pieData
		        }]
		    });
			var item = myChart.closest('.pie-chart'),
				parent = item.find('.pie-all'),
				total = item.find('.pie-all-num'),
				box = item.find('.pie-info-list');
			parent.show();
			total.text( data[0].total );
			box.empty().html( html );
		},
		
		initOrglist : function( data ,flag ){
			var _this = this,
				box = MC.find('.divisions-list');
			if( !flag ){																/*flag用来区分是加载更多 还是进行条件筛选*/
				box.find('.divisions-list-each').remove();
			}
			$.each( data , function( key , value ){
				var info = {};
				info.id = value.id;
				info.name = value.name;
				info.href = 'run.php?mid='+ relate_module_id +'&Der_Name='+ value.name +'&org_id='+ value.id + '&needback=true' ;
				$('#list-tpl').tmpl( info ).insertBefore( box.find('.more-department') );
				_this.initListline( box , key , value );
				_this.initListbar( box , key , value );
			})
		},
		
		initStafflist : function( data , flag ){
			var _this = this,
				box = MC.find('.staffs-list');
			if( !flag ){
				box.find('.divisions-list-each').remove();
			}
			$.each( data , function( key , value ){
				var info = {};
				info.id = value.id;
				info.name = value.name;
				info.href = 'run.php?a=relate_module_show&app_uniq=workload&mod_uniq=workload_person&user_name='+ value.name + '&user_id=' + value.id + '&top_index=' + value.order;
				$('#list-tpl').tmpl( info ).insertBefore( box.find('.more-department') );
				_this.initListline( box , key , value );
				_this.initListbar( box , key , value );
			})
		},
		
		initListline : function( box , key , value ){
			var myChart = box.find('#chart-area-line_' + value.id );
			var label = [],
				count_1 = [],
				count_2 = [];
			$.each( value.count , function( k , v ){
				label.push( parseInt( v.date ) );
				count_1.push( v.count );
				count_2.push( v.statued );
			});
			var max_1 = Math.max.apply(null, count_1),
				max_2 = Math.max.apply(null, count_2),
				max = max_1 > max_2 ? max_1 : max_2;
			myChart.highcharts({
		            title: null,
		            subtitle: null,
		            xAxis: {
		                categories: label
		            },
		            yAxis: {
			            title: null,
			            min : 0,
			            max : max,
			            tickPixelInterval: 40 ,
		                plotLines: [{
		                    value: 0,
		                    width: 1,
		                    color: '#808080'
		                }]
		            },
		            tooltip: {
		                valueSuffix: null
		            },
		            credits: { enabled:false },
			        exporting: { enabled:false },
			        legend: { enabled : false },
		            series: [{
		                name: '按发稿量',
		                color : '#f88664',
		                data: count_1
		            },
		            {
		                name: '按通过率',
		                color : '#548dd4',
		                data: count_2
		            }]
		        });
		},
		
		initListbar : function( box , key , value ){
			var myChart = box.find('#chart-area-bar_' + value.id );
			var label = [],
			count_1 = [],
			count_2 = [];
			var title = myChart.closest('li').find('.chart-title').text( value.name ).attr('_id' , value.id);
			$.each( value.count , function( k , v ){
				label.push( parseInt( v.date ) );
				count_1.push( v.count );
				count_2.push( v.statued );
			});
			myChart.highcharts({
	            chart: {
	                type: 'column'
	            },
	            title: null,
	            subtitle: null,
	            xAxis: {
	                categories: label
	            },
	            yAxis: {
	                min: 0,
	                title: null
	            },
	            tooltip: {
	                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
	                footerFormat: '</table>',
	                shared: true,
	                useHTML: true
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0,
	                    borderWidth: 1
	                }
	            },
	            credits: { enabled:false },
		        exporting: { enabled:false },
		        legend: { enabled : false },
	            series: [{
	                name: '按发稿量',
	                color : '#f88664',
	                data: count_1
	            },
	            {
	                name: '按通过率',
	                color : '#548dd4',
	                data: count_2
	            }]
	        });
		},
		
		tip : function( target , tip ){
			target.myTip({
				string : tip,
				width : 200,
				delay: 1000,
				dtop : 0,
				dleft : -120,
			})
		},
		
		ajax : function( item , url , param , callback){
    		$.globalAjax( item, function(){
    			return $.getJSON( url , param , function( data ){
    				if( $.isFunction( callback ) ){
    					callback( data );
    				}	
    		    });
    		});
	    },
	};
	$.control.init();
})