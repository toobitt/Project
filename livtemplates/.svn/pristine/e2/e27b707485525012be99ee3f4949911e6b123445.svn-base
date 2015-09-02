$(function(){
	var MC = $('.wrap');
	
	$.control = {
		init : function( infoUrl ){
			this.infoUrl = infoUrl;
			this.getList( null , null , infoUrl.listUrl );
			this.getOneOrg( null , infoUrl.getOneOrgUrl )
			this. getOneOrgTotal( null , infoUrl.getOneOrgTotalUrl );
		},
		
		getList : function( param , page , url ){
			var item = MC.find('.info-each-list'),
				_this = this,
				search_box = MC.find('.info-list'); 
			var info = param || {};
			if(page){
				info.page = page
			}else{
				info.page = 1
			}
			if( search_box.find('input[name="date_search"]').val() != 5){
				info.date_search = search_box.find('input[name="date_search"]').val();
			}else{
				info.start_time = search_box.find('input[name="start_time"]').val();
				info.end_time = search_box.find('input[name="end_time"]').val();
			}
			info.app = search_box.find('input[name="app"]').val();
			this.ajax( item , url , info , function( json ){
				_this.getPersonList( json[0].data );
				_this.getInfopage( json[0].page_info );
			})
		},
		
		getOneOrg : function( param , url ){
			var item = MC.find('.pie-chart'),
				_this = this;
			this.ajax( item , url , param , function( data ){
				_this.initDepartmentInfo( data[0].info );
				_this.initPie( data  );
				if( data[0].sort ){
					_this.initTop( data[0].sort );
				}
			})
		},
		
		getOneOrgTotal : function( param , url ){
			var item = MC.find('.line-chart'),
				_this = this;
			this.ajax( item , url , param , function( data ){
				_this.initLine( data );
			})
		},
		
		getPersonList : function( data ){
			var box = MC.find('.info-each-list').empty(),
			    info = {};
			info.options = data;
			if( data.length ){
				$('#list-tpl').tmpl( info ).appendTo( box );
			}else{
				var noData = '<div class="info-each no-data">暂时没有数据</div>';
				box.html( noData );
			}
		},
		
		getInfopage : function( option ){								/*分页*/
        	var page_box = MC.find('.page_size'),
                _this = this;
            option.show_all = true;
            if (page_box.data('init')) {
                page_box.page('refresh', option);
            } else {
                option['page'] = function (event, page, page_num) {
                    _this.refresh( null , page);
                }
                page_box.page(option);
                this.page_num = option.page_num;
                page_box.data('init', true);
            }
	     },

         refresh: function(data ,page) {
             this.getList( data, page ,this.infoUrl.listUrl );
         },
		
		initLine : function( data ){
			var myChart =  MC.find('#chart-area-line');
			var label = [],
				count = [],
				statued = [];
			$.each( data[0].count , function(key , value){
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
		
		initDepartmentInfo : function( data ){
			var box = MC.find('.list-pie-info').empty();
			$('#list-info-tpl').tmpl( data ).appendTo( box );
		},
		
		initPie : function( data ){
			var myChart = MC.find('#chart-area-pie');
			var pieData =[],
				all = data[0].info.statued,
				html = '';
			$.each( data[0].apps , function( key , value ){
				var percent = all ? ((value.statued/all)*100).toFixed(0) + '%' : 0 ;
				pieData.push( { y : JSON.parse(value.statued) , color: value.color ,name : value.name } );
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
			total.text( all );
			box.empty().html( html );
		},
		
		initTop : function( data ){
			var html = '';
			$.each( data , function( key , value ){
				html += '<li title="'+ value.user_name +'" id="'+ value.user_id +'" _count="'+ value.count +'">'+ (key+1) +'. '+ value.user_name +'</li>';
			})
			MC.find('.top10-list').empty().html( html );
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
})