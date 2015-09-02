$(function(){
	(function($){
		var controll = {
			auditchajax : function(id, obj, method, status){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
				var audit;
				if(status == 1){
					audit = 2;
				}else{
					audit = 1;
				}
				$.getJSON(url,{id : id, audit : audit}, function(data){
					if(data['callback']){
						eval( data['callback'] );
						$('.load-img').remove();
						return;
					}else{
						var data = data[0];
						status = data.status;
						audit = data.audit;
						color = status_color[audit];
						obj.text(audit).css('color',color).attr('_status',status);
					}
				});
			},
			delajax : function( ids, obj ,method ){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1',
					tip = "您确定删除该条内容吗？", 
					message;
				if(!obj.data("magid")){
					message = "请先删除该杂志下的期刊";
				}else{
					message = "请先删除该期刊下的文章";
				}
				jConfirm( tip , '删除提醒' , function( result ){
					if( result ){
						$.getJSON(url,{id : ids},function( data ){
							if( data == "-1" ){
								jAlert( message , "删除提醒");
								return false;
							}else if(data['callback']){
								eval( data['callback'] );
								return;
							}else{
								obj.remove();
							}
						});
					}
				});
			},
		};
		
		var status_color = {
			'待审核' : '#8ea8c8',
			'已审核' : '#17b202',
			'已打回' : '#f8a6a6'
		};
		var loading = '<img src="' + RESOURCE_URL + 'loading2.gif" class="load-img" style="width:25px; position:absolute; left:7px; top:-2px;"/>';
		$.widget('mag.magcommon',{
			options : {
				add : '.pop-add',
				box : '.pop-add-mag',
				hide : 'pop-hide',
				textset : '.text-set',
				cover : '.period-cover',
				hiddenfile : '#cover-file'
			},
			_create : function(){
				
			},
			_init : function(){
				var op = this.options,
					handlers = {};
				handlers['click ' + op['add'] ] = '_addMag';
				handlers['click ' + op['textset'] ] = '_setSort';
				handlers['click ' + op['cover'] ] = '_uploadImg';
				handlers['change' + op['hiddenfile'] ] = '_addImg';
				this._on(handlers);
				this._initData();
				this._initcForm();
			},
			
			_initData : function(){
				 $('.common-list').find('.m2o-cont').each(function(){
					var title = $(this).find('input').val();
					if(!title){
						$(this).find('input').css('border-color','#cfcfcf');
					}
				});
			},
			
			_addMag : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					magid = self.data('magaid'),
					cont = self.closest('.magazine-profile').find('h2').html(),
					type = self.data('type');
				if(type == 'issue'){
					if(self.is('.save-button')){
						this.element.find('#maga_id').val(magid);
						this.element.find('#display_item-sort').val(cont);
						var total_issue = parseInt($('.magazine-menu').find('input[name=volume]').val(),10) + 1,
							issue = parseInt($('.magazine-menu').find('input[name=current_nper]').val(),10) + 1;
						$('#add-issue').data('magid', magid);
					}else{
						var total_issue = parseInt($('.magazine-add').find('input[name=volume]').val(),10) + 1,
							issue = parseInt($('.magazine-add').find('input[name=current_nper]').val(),10) + 1;
					}
					$('#add-issue').find('.issue').val(issue);
					$('#add-issue').find('.total_issue').val(total_issue);
					$('#add-issue').removeClass( op['hide'] );
				}else{
					if(self.is('.add-button')){
						$('#add-magazine').data('mag','1');
					}
					$('#add-magazine').removeClass( op['hide'] );
					var mid = $(op['box']).find( '.input_middle' );
					mid.find('.overflow').html('选择分类');
				}
			},
			
			_setSort : function( event ){
				var self = $(event.currentTarget);
				if(self.hasClass('text-add')){
					if(self.closest('li').hasClass('m2o-cont')){
						$('#leftcontadd-tpl').tmpl().appendTo( '.cont-area' );
						
					}else{
						$('#sortadd-tpl').tmpl().appendTo( '.pop-box' );
						
					}
					self.addClass('text-del').removeClass('text-add');
				}else{
					var box = self.closest('ul'),
						obj = self.closest('li'),
						sort_id = obj.attr('_id');
						issue_id = box.data('issueid');
					var url = "./run.php?mid=" + gMid + '&a=del_article_sort&id=' + sort_id + '&issue_id=' + issue_id;  
					if(obj.attr('_id')){
						$.getJSON(url,function(data){
							if( data == "-1" ){
								jAlert('请先删除该分类下的文章','删除提醒');
								return false;
							}else{
								obj.remove();
							}
						});
					}else{
						obj.remove();
					}
				}
			},
			
			_uploadImg : function( event ){
				var op = this.options;
			    $( op['hiddenfile'] ).click();
			},
			
			_addImg : function(event){
				var op = this.options,
					self = event.currentTarget;
				    info = {};
			   var  file=self.files[0];
			        reader=new FileReader();
			   reader.onload=function(e){
					imgData=e.target.result;	
				var	img	= $( op['cover'] ).find('img');		
		            img.attr('src', imgData);
				}  
				reader.readAsDataURL(file);        			
			},
			
			_initcForm : function(){
				var _this = this,
					cform = this.element.find( '.common-list' ),
					isIssue = cform.hasClass('issue-edit');
				cform.submit(function(){
					$(this).ajaxSubmit({
						beforeSubmit:function(){
							$('.loading').show();
						},
						dataType : 'json',
						success:function( data ){
							$('.loading').hide();
							var data = data[0];
							if( data ){
								var obj=$('.result-tip' );
								if(isIssue){
									var tip="期刊编辑成功";
								}else{
									var tip="杂志编辑成功";
								}
								_this._ajaxTip(obj, tip);
								_this._initData();
							}
						},
						error:function(){
							var obj=$( '.result-tip' );
							if(isIssue){
								var tip="期刊编辑失败";
							}else{
								var tip="杂志编辑失败";
							}
							_this._ajaxTip(obj, tip);
						}
					});
					return false;
				});
			},
			
			_ajaxTip:function(obj,tip){
				obj.html(tip).css({'opacity':1,'z-index':100001});
				setTimeout(function(){
					obj.css({'opacity':0,'z-index':-1});
				},2000);
			},
			
			addone : function(){
				var issue = $('.pop-add-mag').data('issue');
					total_issue = $('.pop-add-mag').data('total_issue');
				$('.magazine-add').find('input[name=volume]').val(total_issue);
				$('.magazine-add').find('input[name=current_nper]').val(issue);
			},
			
		});
		
		$.widget('mag.magadd',{
			options : {
				box : '.pop-add-mag',
				hide : 'pop-hide',
				close : '.pop-close-button2',
				data : '.mustdata',
				num : '.num' 
			},
			
			_create : function(){

			},
			
			_init : function(){
				var op = this.options,
				handlers = {};
			handlers['click ' + op['close'] ] = '_closeMag';
			handlers['keyup ' + op['data'] ] = '_mustData';
			this._on(handlers);
			this._initForm();
			},
			
			_mustData : function( event ){
				var self = $(event.currentTarget),
					title = self.val();
				if(event.KeyCode < 48 || event.keyCode > 57){
					self.val('');
				}
			},
			
			_closeMag : function(){
				var op = this.options,
					widget = this.element,
					area = widget.find( '.m2o-tent' );
					text = widget.find('.m2o-text');
				area.find('input').val(' ');
				area.find('textarea').val(' ');
				widget.find('.pop-date').children('input').val(' ');
				text.find('input').val(' ');
				widget.find('img').attr('src','');
				widget.addClass( op['hide'] );
			},
			
			initMagData : function(){
				$('.magazine-each:first-child').geach({
					checkbox : '.mag-img',
					needInfoBtn : false,
					'audit' : function( event, _this ){
						var self = $(event.currentTarget),
							status = self.attr('_status'),
							id = self.attr('_id'),
							method = self.data('method');
						$(loading).appendTo(self);
						controll.auditchajax( id, self , method, status );
					}
				});
				$('.magazine-each:first-child').find('.del').click(function(){
					var self = $(this),
						obj = self.closest('.magazine-each'),
						id = obj.data('id'),
						method = self.data('method');
					controll.delajax( id, obj ,method );
				});
			},
			
			addMagData : function( data ){
				if( data ){
					var info = {};
					info.id = data.id;
					info.tname = data.name;
					info.release_cycle = data.release_cycle;
					info.sort_name = data.sort_name || '未分类';
					info.create_time = data.create_time;
					info.user_name = data.user_name;
					info.current_nper = data.current_nper;
					info.issue_id = 0;
					info.volume = data.volume;
					info.year = data.year;
					$('#magadd-tpl').tmpl(info).prependTo('.magazine-list');
					this._closeMag();
				}
			},
			
			addIssueData : function( data ){
				var widget = this.element;
				if(data){
					var info = {};
					info.id = data.id;
					info.total_issue = data.total_issue;
					info.issue = data.issue;
					info.create_time = data.create_time;
					info.user_name = data.user_name;
					info.year = data.year;
					info.magazine_id = data.magazine_id;
					info.maga_name = widget.find('.overflow').html();
					info.url = $.createImgSrc(data['img_info'],{'width' : '150', 'height' : '195'});
					$('#issueadd-tpl').tmpl(info).prependTo('.magazine-list');
					this._closeMag();
					widget.data('issue', info.issue);
					widget.data('total_issue', info.total_issue);
					widget.find('.issue').val(info.issue);
					widget.find('.total_issue').val(info.total_issue);
				}
			},
			
			_initForm : function(){
				var _this = this,
					op = this.options,
					widget = this.element,
					mform =this.element.find( '.common-list-form' ); 
				var isMag = mform.parent().is('#add-magazine'),
					isIssue =  mform.parent().is('#add-issue');
				mform.submit(function(){
					$(this).ajaxSubmit({
						beforeSubmit : function(){
							if( isMag ){
								var title = $.trim(mform.find('#required_2').val());
								if(!title){
									jAlert('请输入新增的杂志名称','输入信息提醒');
									return false;
								}
							}
							if( isIssue ){
								var img = $.trim(mform.find('.cover-img').attr('src')),
									data = $.trim(mform.find('.date-picker').val());
								if(data && img){
									
								}else{
									jAlert('请填写完整信息','输入信息提醒');
									return false;
								}
							}
						},
						dataType : 'json',
						success : function(data){
							if(data['callback']){
								eval( data['callback'] );
								return;
							}else{
								var data = data[0];
								if( isMag ){
									var mag = mform.parent('.pop-add-mag').data('mag');
									_this.addMagData( data );
									if( mag ){
										$('.m2o-lastest').left_list('initAjax');
									}else{
										_this.initMagData();
									}
								}else{
									var magid = mform.parent('.pop-add-mag').data('magid');
									if( magid ){	//最新列表页新增期刊
										$('.m2o-lastest').left_list('updataIssuedata', data, magid);
										_this._closeMag();
									}else{  //期刊页新增期刊
										_this.addIssueData( data );
										_this.initMagData();
										$('.common-list-content').magcommon('addone');
									}
								}
							}
						}
					});
					return false;
				});
			}
		});
	})($);
	$('.common-list-content').magcommon();
	$('.pop-add-mag').magadd();
});
