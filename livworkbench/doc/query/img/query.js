$(function(){
	var defaultOptions = {
		url : 'http://yqzw.wifiyq.com/yqzw.php?f=getInfoJsonFromProjidAndPwd',
		fields : {
			'serviceid' : '事项编码',
			'servicename' : '事项名称',
			'servicetype' : '服务事项类型',
			'promiseday' : '承诺时间',
			'itemcode' : '项目编号',
			'infotype' : '办件类型',
			'projid' : '办件编号',
			'handlestate' : '办理状态',
			'projectname' : '项目名称',
			'applyname' : '单位名称/人',
			'receive_time' : '收件时间',
			'prejudge_time' : '预审时间',
			'accept_time' : '受理时间',
			'transact_time' : '办结时间'
		}
	}
	function Query( option ){
		this.page = $('.page');
		this.form = this.page.find('.query-form');
		this.button = this.page.find('.button-submit');
		this.op = $.extend({}, defaultOptions, option);
		this.data = {									//测试数据(正式时请删除)
			"accept_time":"2014-08-19 08:59:27",
	 		"address":"乐清经济开发区",
	 		"applycount":1,
	 		"applyfrom":"内网",
	 		"applyname":"温州成大电气科技有限公司",
	 		"contactman":"高松桂",
	 		"create_time":"2014-08-19 08:59:16",
	 		"create_userid":"AD8504F65539D03FED641CC2FD5556C4",
	 		"create_username":"郑建克",
	 		"deleteflag":"",
	 		"effistate":"",
	 		"effivalue":0,
	 		"email":"",
	 		"formid":"","get_time":"",
	 		"green_way":"N",
	 		"handlestate":"在办",
	 		"idcard":"",
	 		"infotype":"单体",
	 		"itemcode":"",
	 		"legalman":"",
	 		"memo":"",
	 		"mobile":"13706667777",
	 		"node_etime":"2014-08-19",
	 		"node_stime":"2014-08-19 11:20:22","parentid":"","phone":"",
	 		"postcode":"",
	 		"prejudge_time":"2014-08-19 08:59:16",
	 		"projectname":"生产及辅助用房",
	 		"projid":"330382221408191000009",
	 		"projpwd":"364636",
	 		"promise_etime":"2014-09-03",
	 		"promiseday":11,
	 		"promisetype":"",
	 		"receive_deptid":"619D3D9E9B75C2D3B88D29793FE7BF3C",
	 		"receive_deptname":"人民防空（民防局）",
	 		"receive_time":"2014-08-19 08:59:16",
	 		"receive_userid":"AD8504F65539D03FED641CC2FD5556C4",
	 		"receive_username":"郑建克",
	 		"result":"",
	 		"result_code":"",
	 		"serviceid":"B9728F8F7E594B649D907E2C7A4EAB25",
	 		"servicename":"人防工程竣工验收许可",
	 		"servicetype":"承诺件",
	 		"transact_time":"",
	 		"unid":"BA49805A7BE560A2FB8E1787F915F4C6",
	 		"unite_notice_code":"",
	 		"unite_opinion_content":"",
	 		"unite_opinion_datetime":"",
	 		"unite_opinion_username":"",
	 		"unite_opinion_userunid":"",
	 		"unite_project_info":""
 		};
	}
	Query.prototype = {
		init : function(){
			this.tips = this.append();
			this.bindEvent();
		},
		
		bindEvent : function(){
			var _this = this,
				form = this.form;
			this.button.click(function(){
				form.find('.query-item input').removeClass('error');
				var query_num = form.find('.query-num input').val(),
		 			query_psd = form.find('.query-psd input').val();
				if( !query_num ){
					_this.showTips('申报号未填写');
					form.find('.query-num input').addClass('error');
					return false;
				}else if( !query_psd ){
					_this.showTips('查询密码未填写');
					form.find('.query-psd input').addClass('error');
					return false;
				}
				_this.drawQuery( _this.data );		//测试调用(正式时请注释)
				// _this.ajaxUrl({					//正式请求数据
					// projid : query_num,
					// pwd : query_psd
				// });
			});
		},
		
		ajaxUrl : function( param ){
			var _this = this;
			$.ajax({
				url : this.op.url,
				data : param,
				dataType : 'json',
				type : 'get',
				success : function( data ){
					_this.drawQuery( data );
				}
			});
		},
		
		drawQuery : function( data ){
			if( data ){
				var arr = $.map(this.op.fields, function(key, value){
		 			return {
		 				vv : data[ value ],
		 				label : key,
		 				kk : value,
		 			}
		 		});
				var html = template('query-detail', {list : arr}),
					page = $('.page');
				$('.navbar-inner').find('.center').html('办件详情');
				var queryDetail = $( html ).appendTo( $('.page').removeClass('page-index') );
				this.initDetail( queryDetail );
			}else{
				this.showTips('申报号或查询密码有误');
			}
		},
		
		initDetail : function( queryDetail ){
			queryDetail.on('click', '.button-submit', function(){
				location.reload();
			});
		},
		
		showTips : function( msg ){
			var tipDom = this.tips;
	 		tipDom.removeClass('fadeOut').addClass('fadeIn').html( msg );
	 		var setTime = setTimeout(function(){
	 			tipDom.removeClass('fadeIn').addClass('fadeOut');
	 		}, 800);
		},
		
		append : function(){
			return $('<div class="popDiv fadeOut"/>').appendTo( this.form ).css({
	 			position : 'absolute',
	 			left : '50%',
	 			top : '50%',
	 			height : '46px',
	 			color : '#fff',
	 			padding : '0 20px',
	 			'border-radius' : '3px',
	 			'z-index' : 999,
	 			'margin-top' : '-50px',
	 			'line-height' : '46px',
	 			'background-color':'rgba(51, 51, 51, 0.8)',
	 			'transition' : 'opacity 0.3s'
	 		});
		}
	}
	var query = new Query();
	query.init();
});
