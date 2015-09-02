/**
 * app列表
 */
$(function(){
	var appList = function(){
		this.el = $('body');
//		this.appStoreUrl = 'livsns/app.php?a=shelves  参数id和appstore_address  OK ';
		this.appStoreUrl = './run.php?mid=' + gMid + '&a=shelves';
		this.pop = $('.app-store-pop');
		this.codePop = $('.app-code-pop');
		this.delAppUrl = './run.php?mid='+ gMid +'&a=delete&ajax=1';
		
		this.bindEvent();
	};
	appList.prototype = {
			bindEvent : function(){
				var _this = this;
				this.el.on('click', '.to-appstore', $.proxy(_this.toAppstore, _this));
				this.el.on('click', '.app-store-pop .close-pop', $.proxy(_this.closePop, _this));
				this.el.on('click', '.app-store-pop .save-pop', $.proxy(_this.ajaxAppStore, _this));
				this.el.on('click', '.show-code-pop', $.proxy(_this.showCodePop, _this));
				this.el.on('click', '.del-app', $.proxy(_this.delApp, _this));
				
				this.codePop.on('click', '.download-boxes .btn', function(){
					window.open( $(this).attr('_add') );
				});
			},
			//显示二维码扫描下载弹窗
			showCodePop : function( e ){
				var target = $(e.currentTarget),
					parent = target.closest('.common-list-data'),
					params = {			//app_name, url, uuid, version,versionNum,ipaUrl,apkUrl
						appName : parent.find('.m2o-common-title').text(),
						qrcodeUrl : $.myconfig.qrcode_url,
						uuid : target.attr('_uuid'),
						type : target.attr('_type'),
						versionName : target.attr('_versionname'),
						iosUrl : target.attr('_iosdownload'),
						androidUrl : target.attr('_androiddownload')
					};
				$('#app-code-pop-tpl').tmpl( params ).appendTo( this.codePop.find('.modal-content').empty() );
				this.codePop.modal('show');
			},
			//删除app
			delApp : function( e ){
				var target = $(e.currentTarget),
					parent = target.closest('.common-list-data'),
					id = parent.attr('_id'),
					_this = this;
				jConfirm('确定要删除该APP么？', '删除提示', function( result ){
					if( result ){
						$.globalAjax(target, function(){
							return $.getJSON(_this.delAppUrl, {id:id}, function(json){
								parent.slideUp(function(){
									parent.remove();
								});
							});
						});
					}
				}).position(target);
			},
			ajaxAppStore : function( e ){
				var target = $(e.currentTarget),
					_this = this;
				var params = {
						id : _this.id,
						appstore_address : this.pop.find('.appstore-address').val().trim()
				};
				$.globalAjax( target, function(){
					return $.getJSON(_this.appStoreUrl, params, function( json ){
						var isSuccess = !json.error || json[0]=='success'
						target.myTip({
							string : isSuccess ? '保存成功' : json.msg || '保存失败',
						});
						var time = setTimeout(function(){
							_this.closePop();
							window.location.reload();
						},1000);
					});
				} );
			},
			toAppstore : function( e ){
				var target = $(e.currentTarget),
					bodyHei = $('body').height(),
					top = target.offset().top;
				if( bodyHei - top < 150 ){
					top -= 160
				}
				this.pop.find('.modal-dialog').css('top', top + 'px');
				this.id = target.closest('.common-list-data').attr('_id');
				var add = target.attr('_add') || '';
				this.pop.find('.appstore-address').val( add );
				this.showPop();
			},
			showPop : function(){
				this.pop.modal('show');
			},
			closePop : function(){
				this.pop.modal('hide');
			},
		};
	new appList();
});