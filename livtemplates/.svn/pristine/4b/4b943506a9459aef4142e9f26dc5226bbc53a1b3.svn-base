$(function(){
	//新增一期
	(function($){
		$.widget("epaper.epaperform",{
			options : {},
			_create : function(){
					this.epaper_id = $('input[name="epaper_id"]').val();
					this.period_id = $('input[name="period_id"]').val();
					this.orderIdArr = [];
			},
			_init : function(){
				this._on({
					'click .upload-jpg' : '_addEpaper',
					'click .upload-pdf' : '_addEpaper',
					'click .update-jpg' : '_update',
					'click .update-pdf' : '_update',
					'click .batch-jpg' : '_uploadJpg',
					'click .batch-pdf' : '_uploadPdf',
					'click .control-item' : '_changeStack',
					'dblclick .control-item' : '_resetStack',
					'mouseenter .control-item' : '_detect',
					'mouseleave .control-item' : '_removeDel',
					'click .del-stack' : '_delStack',
					'click .del' : '_del',
					'click .item-title' : '_getTitle',
					'blur .item-title' : '_setTitle',
					'click .batdelete' : '_batDelete',
					'click .checkAll' : '_checkAll',
					'click .sortBtn' : '_sort',
					'click .add-stack' : '_addStack',
					'click .edit-btn' : '_editStackFlag',
					'click .edit-stack' : '_getStackFlag',
					'blur  .edit-stack' : '_setStackFlag',
					'click .pageNum' : '_editPageFlag',
					'click .edit-page' : '_getPageFlag',
					'blur  .edit-page' : '_setPageFlag',
					'click .save-button' : '_submit',
				});
				this._default();
			},
			_default : function(){
				var _this = this;
				this.title = '';
				this.url = './run.php?mid='+gMid;
				this.addEpaper = this.element.find('.add-epaper-file');
				this.uploadPdf = this.element.find('.pdf-file');
				this.addEpaper.ajaxUploadWithUrl( {
					url : "./run.php?mid=" + gMid + "&a=addEpaper",
					type : 'media',
					before : function(){
						$('#loading').show();
					},
					after : function(json){
						var newNum = json['fileIndex'];
						$('#loading').hide();
						_this._ajaxJpgAfter(json,newNum);
					}
				} );
				this.uploadPdf.ajaxUploadWithUrl({
					url : './run.php?mid=' + gMid + '&a=upload_pdf',
					type : 'pdf',
					before : function(){
						$('#loading').show();
					},
					after : function( json ){
						$('#loading').hide();
						_this._ajaxPdfAfter( json );
					}
				});
				
				$('.control-item').eq(0).click();		//默认展示第一叠
			},
			_submit:function( e ){
				var self = $(e.currentTarget),
					form = self.closest('form'),
					periodDateInp = form.find('[name="period_date"]'),
					periodNumInp = form.find('[name="period_num"]'),
					periodDateVal = periodDateInp.val().trim(),
					periodNumVal = periodNumInp.val().trim();
				var errorStr = '',
					target = null;
				if( !periodDateVal ){
					errorStr = '日期不能为空';
				}else{
					var reg = /^(\d{4})-(\d{1,2})-(\d{1,2})$/;
					if( !reg.test(periodDateVal) ){
						errorStr = '日期格式不符合规范'
					}else{
						var cacheArr = periodDateVal.split('-');
						for( var i=0;i<cacheArr.length;i++ ){
							cacheArr[i] = parseInt( cacheArr[i] );
						}
						if( cacheArr[1] < 1 || cacheArr[1] > 12 ){
							errorStr = '月份应在1-12之间';
						}
						if( cacheArr[2] < 1 || cacheArr[2] > 31 ){
							errorStr = '日期应在1-31之间';
						}
					}
				}
				if( !(parseInt( periodNumVal ) == periodNumVal && parseInt( periodNumVal ) > 0) ){
					errorStr = '期数应为正整数';
					target = periodNumInp;
				}
				if( errorStr.length ){
					target = target ? target : periodDateInp;
					target.myTip({
						string : errorStr,
						width : errorStr.length * 14,
						delay : '2000',
						color : '#ee8176',
					});
					return false;
				}
				var	url = './run.php?mid=' + gMid + '&a=check_period_date',
					param = {
						epaper_id : this.epaper_id,
						period_id : this.period_id,
						period_date : $('.hasDatepicker').val()
					}
				$.globalAjax(self, function(){
					return $.getJSON( url, param, function( json ){
						var data = json[0];
						if( data.error ){
							jAlert('今日报刊已存在','保存提示');
//							$('.hasDatepicker').val('').focus();
						}else{
							self.closest('form').submit();
						}
					});
				});
			},
			_ajaxJpgAfter : function( json,newNum ){	
				var currentStack = $('.each-list.active'),
					eventObj = currentStack.find('.update'),
					data = json['data'];
				if( $.MC.target.hasClass('target-update') ){
					this._updateJpgInfo(data);
				}else{
					this._addJpgInfo(data,currentStack);
				}
			},
			_ajaxPdfAfter : function( json ){
				var currentStack = $('.each-list.active'),
					eventObj = currentStack.find('.update'),
					data = json['data'];
				if( $.MC.target.hasClass('target-update') ){
					this._updatePdfInfo(data,eventObj,currentStack);
				}else{
					this._addPdfInfo(data,currentStack);
				}
			},
			_updatePdfInfo : function( data ){
				var updateItem = $.MC.target.closest('li');
				updateItem.find('.pdf-icon').addClass('has-pdf')
				updateItem.find('.pdf_id_hid').val( data['img_id'] );
			},
			//点击编辑  更新 jpg
			_updateJpgInfo : function(data){
				var updateItem = $.MC.target.closest('li');
				$.each(data,function(k,v){
					$.each(v,function(kk,vv){
						updateItem.find('.jpg').attr({
							'_id' : vv['img_id'],
							'src' : $.createImgSrc(vv['img_info'],{'width':'93','height':'140'})
						});
						updateItem.find('.jpg_id_hid').val(vv['img_id']);
					});
				});
			},
			//新增 jpg
			_addJpgInfo : function(data,currentStack){
				var flag = currentStack.attr('_flag'),
					_this = this;
				$.each(data,function(k,v){
					$.each(v,function(kk,vv){
						var flagTotle = flag + vv['page_num'];
						//判断是否有相等
						var sameInfo = _this._hasSameEpaper(flagTotle,currentStack),
							hasSame = sameInfo['hasSame'],
							theSame = sameInfo['theSame'];
//						console.log( flagTotle, sameInfo );
//						if( hasSame ){
//							_this._coverEpaper(theSame,vv,'jpg');
//						}else{
							_this._enlargeStack(vv,currentStack,'jpg');
//						}
					});
				});
			},
			_addPdfInfo : function(data,currentStack){
				var flag = currentStack.attr('_flag'),
					_this = this,
					flagTotle = flag + data['page_num'];
				//判断有无对应 jpg
				var sameInfo = _this._hasSameEpaper(flagTotle,currentStack);
					hasSame = sameInfo['hasSame'],
					theSame = sameInfo['theSame'];
//				if( hasSame ){
//					_this._coverEpaper(theSame,data,'pdf');
//				}else{
					_this._enlargeStack(data,currentStack,'pdf');
//				}
			},
			//上传 判断
			_coverEpaper : function(theSame,vv,type){
				var sameFlag = theSame.find('.pageNum').text();
				if( type == 'jpg' ){
					var jpgid = theSame.find('.jpg').attr('_id');
					if( jpgid != '0' ){
//						var	words = sameFlag + ' 下 ' + type.toLocaleUpperCase() + '已存在，新上传的文件已用于更新';
//						jAlert( words ,'更新提示');
					}
					var newSrc = $.createImgSrc(vv['img_info'],{'width':'93','height':'140'});
					theSame.find('.jpg').attr({'src':newSrc, '_id':vv['img_id']});
					theSame.find('.jpg_id_hid').val(vv['img_id']);
				}else if(type == 'pdf'){
					var pdfid = theSame.find('.pdf').attr('_id');
					if( pdfid == '0' ){
						theSame.find('.pdf-icon').addClass(' has-pdf');
						theSame.find('.pdf').attr('_id',vv['img_id']);
						theSame.find('.pdf_id_hid').val(vv['img_id']);
					}else{
						var	words = sameFlag + ' 下 ' + type.toLocaleUpperCase() + '已存在，新上传的文件已用于更新';
						jAlert( words ,'更新提示');
					}
				}
			},
			_enlargeStack : function(vv,currentStack,fileType){
				$('.prev-lists-area').attr('_needsave',true);
				var theNewPage = vv['page_num'],
					insertInfo = this._calInsertPos(theNewPage,currentStack),
					type = insertInfo['type'],
					i = insertInfo['arrIndex'];
				var	info = {
						'flag' : currentStack.attr('_flag'),
						'page_num' : vv['page_num'],
						'page_id' : vv['page_id'],
						'order_id' : vv['order_id'],
						'img_id' : fileType == 'jpg' ? vv['img_id'] : 0,
						'pdf_id' : fileType == 'jpg' ? 0 : vv['img_id']
				};
				if( fileType == 'jpg' ){
					info['jpgSrc'] = $.createImgSrc(vv['img_info'],{'width':'93','height':'140'});
				}else{
					info['hasPdf'] = 'has-pdf';
				}
				var tpl = $('#new-epaper-tpl').tmpl(info);
				switch ( type ){
					case 'prependTo':
						tpl.prependTo(currentStack);
						this._resetOrderIdArr('add',tpl,-1);
						break;
					case 'insertAfter' : 
						tpl.insertAfter(currentStack.find('li').eq(i));
						this._resetOrderIdArr('add',tpl,i);
						break;
				}
			},
			_hasSameEpaper : function(flag,currentStack){
				var items = currentStack.find('li'),
					theSame = items.filter(function(){
						return ( $(this).find('.pageNum').text() == flag ) ;
					}),
					hasSame = theSame.length ? true : false;
				return {
					'hasSame' : hasSame,
					'theSame' : theSame
				};
			},
			_calInsertPos : function( theNewPage, currentStack ){
				var items = currentStack.find('li'),
					newNum = parseInt(theNewPage),
					m = 0,
					has = 0,
					insertInfo = {};
				$.each(items,function(key,value){
					var flag = parseInt($(this).attr('_flag'));
					if( flag < newNum ){
						m = key;
						has = 1;
					}
				});
				if( m == 0 && has == 0 ){
					insertInfo.type = 'prependTo';
					insertInfo.arrIndex = -1;
				}else{
					insertInfo.type = 'insertAfter';
					insertInfo.arrIndex = m;
				}
				return insertInfo;
			},
			_calculatePos : function( newNum,info,ul,type ){			
				var pos;
				var items = $('.each-list.active').find('li');
				var theSame = items.filter(function(){
					return parseInt( $(this).attr('_flag') ) == newNum;
				});
				var sameIndex = theSame.index();
				if( theSame.length ){
					var flag = theSame.attr('_flag');
					jConfirm('第'+ flag + '版已存在,是否要覆盖?','上传提示',function(result){
						if(result){
							var i = theSame.index();
							theSame.find('.del').click();
							setTimeout(function(){
								tmpl.insertAfter(items.eq(i-1));
								_this._resetOrderIdArr('cover',tmpl,sameIndex);
							},300);
						}else{
							$('#new-epaper-tpl').tmpl(info).insertAfter(theSame);
							_this._resetOrderIdArr('add',tmpl,sameIndex);
						}
					});
				}else{
					var m = 0,
						has = 0;
					$.each(items,function(key,value){
						var flag = parseInt($(this).attr('_flag'));
						if( flag < newNum ){
							m = key;
							has = 1;
						}
					});
					if( m == 0 && has == 0 ){
						$('#new-epaper-tpl').tmpl(info).prependTo( ul );
						_this._resetOrderIdArr('add',tmpl,-1);
					}else{
						$('#new-epaper-tpl').tmpl(info).insertAfter( items.eq(m) );
						_this._resetOrderIdArr('add',tmpl,m);
					}
				}
			},
			_hasSelectStack : function(){
				var controlItem = $('.control-item');
				if( !controlItem.length ){
					jAlert('请先创建叠','提示');
					return false;
				}else{
					var activeItem = $('.control-item.active');
					if( !activeItem.length ){
						jAlert('请先选择一叠','提示');
						return false;
					}
				}
				return true;
			},
			//新增一版
			_addEpaper : function( event ){
				var re = this._hasSelectStack();
				if( !re ){
					return;
				}
				var self = $(event.currentTarget),
					type = self.attr('_type');
				$.MC.target = self;
				this.element.find('.each-list').find('li').removeClass('update');
				if( type == 'jpg' ){
					this.addEpaper.click();
				}else if(type == 'pdf'){
					this.uploadPdf.click();
				}
			},
			//更新一版
			_update : function( event ){
				var self = $(event.currentTarget),
					parent = self.closest('li'),
					type = self.attr('_type');
				$.MC.target = self;
				parent.addClass('update').siblings().removeClass('update');
				if( type == 'jpg' ){
					this.addEpaper.click();
				}else if(type == 'pdf'){
					this.uploadPdf.click();
				}
			},
			//批量上传jpg
			_uploadJpg : function(e){
				if( this._hasSelectStack() ){
					$.MC.target = $(e.currentTarget);
					this.addEpaper.attr('multiple','multiple');
					this.element.find('.each-list').find('li').removeClass('update');
					this.addEpaper.click();
				}
			},
			//批量上传pdf
			_uploadPdf : function(e){
				if( this._hasSelectStack() ){
					$.MC.target = $(e.currentTarget);
					this.uploadPdf.attr('multiple','multiple').click();
				}
			},
			//新增一叠
			_addStack : function(event){
				if ( $('.edit-btn').attr('_command') == 'save' ){
					jAlert('修改还未保存，请先保存再添加','保存提示');
					return;
				}
				var self = $(event.currentTarget),
					prev = self.prev(),
					firstLetter = $.trim( prev.find('.stack-flag').text() ).substring(0,1),
					isEng = firstLetter.search(/[A-z]/),
					info = {},
					newFlag;
				if(!prev.length){
					var zimu = 'A',
						chin = '叠',
						newFlag = zimu + chin;
					info.flag = zimu;
					info.chin = chin;
					$('#control-item-tpl').tmpl(info).insertBefore(self).addClass('active').siblings().removeClass('active');
					$( '.each-list' ).removeClass('active');
					$( '#new-stack-tpl' ).tmpl().insertBefore('.add-epaper').addClass('active');
					this._getStackInfo($('.control-item.active'), 'A叠');
					$('.control-item.active').dblclick();
					return;
				}
				if( isEng != -1 ){
					var zimu = String.fromCharCode( firstLetter.charCodeAt(0) + 1 ),
						chin = '叠',
						newFlag = zimu + chin;
					info.flag = zimu;
					info.chin = chin;
				}
				$('#control-item-tpl').tmpl(info).insertBefore(self).addClass('active').siblings().removeClass('active');
				$( '.each-list' ).removeClass('active');
				$( '#new-stack-tpl' ).tmpl().insertBefore('.add-epaper').addClass('active');
				if( isEng != -1 ){
					this._getStackInfo($('.control-item.active'), newFlag);
				}
				$('.control-item.active').dblclick();
			},
			//设置版面标题
			_getTitle : function(event){
				var self = $(event.currentTarget);
				title = self.val();
				this.title = title;
			},
			_setTitle : function(event){
				var self = $(event.currentTarget),
					page_id = self.closest('li').attr('_id'),
					title = self.val();
				var url =  './run.php?mid=' + gMid + '&a=update_page_title&page_id=' + page_id + '&title=' + title;
				if ( this.title != title ){
					$.globalAjax(self,function(){
						return $.get(url);
					});
				}
				this.title = title;
			},
			//切换叠
			_changeStack : function(event){
				if( $('.sortBtn').text() == '保存排序' ){
					jAlert('排序尚未保存！','提示');
					return;
				}
				var _this = this,
					obj = $('.each-list'),
					self = $(event.currentTarget),
					i = self.index(),
					currentStack = obj.eq(i),
					length = currentStack.find('li').length;
				var needAjax = self.attr('_needajax');
				var stack_id = self.attr('_id'),
					period_id = $('input[name="period_id"]').val();
				self.addClass('active').siblings().removeClass('active');
				currentStack.addClass('active').siblings().removeClass('active');
				if ( period_id && (needAjax=='true')){	//新增
					var	url = './run.php?mid=' + gMid + '&a=get_page_by_stack&stack_id=' + stack_id + '&period_id=' + period_id;
					$.globalAjax(self,function(){
						return $.getJSON(url,function(data){
							var data = data[0],
							aInfo = [];
							$.each(data,function(key,value){
								var info = {};
								info['jpgSrc'] = $.createImgSrc(this,{width:93,height:140});
								info['flag'] = currentStack.attr('_flag');
								info['page_num'] = value['page_num'];
								info['order_id'] = value['order_id'];
								info['img_id'] = value['jpg_id'];
								info['pdf_id'] = value['pdf_id'];
								info['page_id'] = value['page_id'];
								info['stack_id'] = value['stack_id'];
								info['title'] = value['title'];
								if( value['pdf_id'] != '0'){
									info['hasPdf'] = 'has-pdf';
								}
								aInfo.push(info);
							});
							$( '#new-epaper-tpl' ).tmpl(aInfo).appendTo(currentStack);
							var items = currentStack.find('li');
							_this.orderIdArr = items.map(function(){
								return $(this).attr('_orderid');
							}).get();
						});
					});
					self.attr('_needajax','false');
				}
			},
			_resetStack : function( event ){
				var self = $(event.currentTarget);
				self.find('.edit').show().find('input').click().focus();
			},
			_del : function(event){
				var self = $(event.currentTarget),
					_this = this,
					father = self.closest('.item'),
					stackFlag = this.element.find('.each-list').filter('.active').attr('_flag'),
					obj = father.nextAll();
				var nums = $(obj).map(function(){
					return parseInt($(this).find('.pageNum').text().substring(1));
				}).get();
				var length = nums.length;
				var page_id = father.attr('_id');
					url = './run.php?mid=' + gMid + '&a=del_page&page_id=' + page_id;
				$.globalAjax(father,function(){
					return $.getJSON(url,function( data ){
						if ( data == "-1" ){
							jAlert('请先删除该页下的新闻','删除提示');
							return;
						}else{
							father.hide(300);
							setTimeout(function(){
								father.remove();
							},300);
							_this._resetOrderIdArr('del', father);
						}
					});
				});
			},
			_sort : function( event ){
				var self = $(event.currentTarget),
					_this = this,
					text = self.text(),
					wrap = $('.each-list').filter('.active'),
					items = wrap.find('li'),
					tip = $('.tips');
				if(text == '开启排序'){
					var content = "排序模式已开启，鼠标拖动进行排序";
					tip.text(content).css({'opacity':1,'z-index':100001});
					setTimeout(function(){
						tip.css({'opacity':0,'z-index':-10});
					},1600);
					items.css('cursor','move');
					wrap.sortable({
						start : function( event, ui ){
							var item = $(ui.item);
							_this.sortItemIndex = item.index();
							self.text('保存排序').css('background','orange');
						},
						stop : function( event,ui ){
							var self = $(ui.item),
								index = self.index();
							_this._changeFlag(wrap, self, index);
							_this._resetOrderIdArr('change',self,index);
						}
					});
				}else if(text == '保存排序'){
					var id = items.map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					var order_id = items.map(function(){
						return $(this).attr('_orderid');
					}).get().join(',');
					var page_num = items.map(function(){
						return $(this).attr('_pagenum');
					}).get().join(',');
					var info = {
							'a' : 'sort',
							'id' : id,
							'order_id' : order_id,
							'page_num' : page_num
					};
					$.globalAjax(self,function(){
						return $.get(_this.url,info,function(){
							items.css('cursor','default');
							var content = "保存成功";
							tip.text(content).css({'opacity':1,'z-index':100001});
							setTimeout(function(){
								tip.css({'opacity':0,'z-index':-10});
							},1600);
							self.text('开启排序').css('background','#1bbc9b');
						});
					});
				}
			},
			_resetOrderIdArr : function(es,aim,index){
				var arr = this.orderIdArr;
				switch ( es ){
					case 'change':
						var wrap = aim.closest('ul'),
							items = wrap.find('li');
						$.each(items,function(key, value){
							$(this).attr('_orderid',arr[key]);
						});
						break;
					case 'del' : 
						var i = aim.index();
						arr.splice(i,1);
						break;
					case 'add' :
						var order_id = aim.attr('_orderid');
						arr.splice(index+1,0,order_id);
						break;
					case 'cover' : 
						var order_id = aim.attr('_orderid');
						arr.splice(index,0,order_id);
						break;
				}
			},
			_changeFlag : function( wrap, self, index){
				var _this = this;
				if( index == _this.sortItemIndex){
					return;
				}
				var items = wrap.find('li'),
					prev = self.prev(),
					next = self.next(),
					selfIndex = self.index(),
					prevNum = parseInt(prev.attr('_pagenum')),
					nextNum = parseInt(next.attr('_pagenum')),
					selfNum = parseInt(self.attr('_pagenum'));
				if( !prev.length ){
					this._changePageNum(1, self);
				}else if( (prevNum+1) != nextNum ){
					this._changePageNum(2, self);
				}else{
					this._changePageNum(3, self);
				}
			},
			_changePageNum : function( ec, self){
				switch( ec ){
					case 1 :	//拖至最前
						this._changeItemsInfo( self, 1 );
						var needChange = this._needChange(self);
						if( needChange ){
							var breakInfo = this._searchBreakpoints(self),
								startNum = 1;
							this._changeBreak(breakInfo,startNum);
						}
						break;
					case 2 :	//拖至断点
						var prev = self.prev(),
							prevNum = parseInt( prev.attr('_pagenum') );
						this._changeItemsInfo( self, prevNum+1 );
						break;
					case 3 : 	//中间插入
						var prev = self.prev(),
							prevNum = parseInt( prev.attr('_pagenum') );
						this._changeItemsInfo( self, prevNum+1 );
						var breakInfo = this._searchBreakpoints(self),
							startNum = prevNum+1;
						this._changeBreak(breakInfo,startNum);
						break;
				}
			},
			//排序 拖动后 更改信息
			_changeItemsInfo : function(aim, num){
				var currentStack = $('.each-list.active'),
					flag = currentStack.attr('_flag');
				aim.attr({
					'_flag' : num,
					'_pagenum' : num
				});
				aim.find('.pageNum').text( flag + num );
				aim.find('.edit-page').val( flag + num );
			},
			_changeBreak : function( breakInfo, startNum ){
				var _this = this,
					items = breakInfo['aims'];
				$.each(items,function(key, value){
					_this._changeItemsInfo($(this),startNum + 1 + key);
				});
			},
			_needChange : function( self ){
				var next = self.next(),
					n = parseInt(self.attr('_pagenum')),
					m = parseInt(next.attr('_pagenum'));
				var need = (m-n) < 1;
				return need;
			},
			_searchBreakpoints : function( self ){
				var nextAll = self.nextAll(),
					item;
				$.each(nextAll,function(key,value){
					var n = parseInt( $(this).attr('_pagenum') ),
						m = parseInt( $(this).next().attr('_pagenum') );
					if( (n+1) != m ){
						item = $(this);
						return false;
					}
				});
				var i = item.index();		//断点
				var aims = nextAll.filter(function(){	//中间段
					return ( $(this).index() <= i );
				});
				var breakInfo = {
						breakPoint : i,
						aims : aims
				};
				return breakInfo ;
			},
			_detect : function( event ){
				var self = $(event.currentTarget);
				$('<span />').appendTo(self).attr('class','del-stack').css({
					'display' : 'block',
					'z-index' : '10'
				});
			},
			_removeDel : function(){
				$('.del-stack').remove();
			},
			_delStack : function( event ){
				var self = $(event.currentTarget),
					parent = self.closest('li'),
					i = parent.index();
					id = parent.attr('_id'),
					period_id = $('input[name="period_id"]').val(),
					url = './run.php?mid=' + gMid + '&a=del_stack';
				if( $('.control-item').length <= 1 ){
					jAlert('已达到最小叠数，不可再删除','提示');
					return;
				}
				var posStack = $('.each-list').filter(function(){
					return ( $(this).attr('_flag') == parent.attr('_flag') )
				});
				var paper = posStack.find('li');
				if(paper.length){
					jAlert('请先删除该叠下的版','提示');
				}else{
					$.globalAjax(self,function(){
						return $.get(url,{period_id:period_id, stack_id:id},function(){
							parent.remove();
							posStack.remove();
						});
					});
				}
				event.stopPropagation();
			},
			_checkAll : function( event ){ 		
				var me = event.currentTarget,
					currentStack = $('.each-list').filter('.active');
				if(me.checked){
					currentStack.find('li,.prev').addClass('active');
				}else{
					currentStack.find('li,.prev').removeClass('active');
				}
			},
			_batDelete : function( e ){
				var target = $(e.currentTarget),
					currentStack = $('.each-list').filter('.active'),
					aim = currentStack.find('li').filter('.active');
				if( !aim.length ){
					target.myTip({
						string : '请选择要删除的内容',
						delay : 2000
					});
					return;
				}
				jConfirm('确定要删除么？','删除提示',function(result){
					if(result){
						$.each(aim,function(){
							$(this).find('.del').click();
						});
						$('.checkAll').attr('checked',false);
					}
				});
			},
			_editStackFlag : function( event ){
				var self = $(event.currentTarget),
					_this = this,
					command = self.attr('_command'),
					obj = $('.control-item').filter('.active'),
					edit = obj.find('.edit');
				if (command == 'edit'){
					edit.show();
					self.attr('_command','save');
					self.text('保存');
				}else if(command == 'save'){
					var currentStack = $('.each-list').filter('.active'),
						items = currentStack.find('li');
					var ids = items.map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					var info = {
							a : 'update_page_stack',
							stack : obj.find('.stack-flag').text(),
							page_ids : ids
					};
					$.globalAjax(self,function(){
						return $.get(_this.url, info, function(){
							edit.hide();
							self.text('修改');
							self.attr('_command','edit');
							_this._showTips('保存成功');
						});
					});
				}
			},
			_getStackFlag : function( event ){
				var self = $(event.currentTarget);
				this.stackFlag = self.val();
				event.stopPropagation();
			},
			_setStackFlag : function( event ){
				var self = $(event.currentTarget),
					_this = this,
					oldFlag = this.stackFlag,
					newFlag = self.val().toLocaleUpperCase();
				self.val(newFlag);
				if( newFlag == ''){
					jAlert('叠名称不能为空！','提示');
					self.hide();
					return;
				}
				if( oldFlag !=  newFlag){
					var sameInfo = this._isSame(newFlag, $('.tab-control'), '.stack-flag'),
						isSame = sameInfo['isSame'];
					if( isSame ){
						jAlert(newFlag+'已存在，请重新命名','提示');
						self.focus();
					}else{
						var father = self.closest('li'),
							stackId = father.attr('_id');
						if( !stackId ){		//新增一叠
							this._getStackInfo(father, newFlag);
						} else{			//更新一叠
							this._updateStackInfo( father, newFlag );
						}
						self.closest('li').find('.stack-flag').text(newFlag);
					}
				}
				self.closest('.edit').hide();
			},
			_getStackInfo : function( aim, newFlag ){
				var info = {'a' : 'create_stack', 'stack' : newFlag, 'epaper_id':this.epaper_id, 'period_id':this.period_id};
				var _this = this;
				$.globalAjax(aim,function(){
					return $.getJSON(_this.url,info,function( json ){
						$.each(json,function(k,v){
							var id = v['id'],
								zm = v['zm'];
							aim.attr({'_id' : id, '_flag' : zm });
							aim.find('.stack_id_hid').val(id);
							$('.each-list').eq( aim.index() ).attr({ '_id' : id, '_flag' : zm  });
	//						aim.find('.edit').hide();
						});
					});
				});
			},
			_updateStackInfo : function( aim, newFlag ){
				var stackId = aim.attr('_id'),
					info = {'a':'update_stack_db','stack_id':stackId,'stack':newFlag }
					_this = this;
				$.globalAjax(aim,function(){
					return $.getJSON(_this.url,info,function( json ){
						var firstLetter = json[0],
						currentStack = $('.each-list.active');
						_this._resetEpaperNum( firstLetter, currentStack);
						aim.attr('_flag',firstLetter);
						aim.find('.stack-flag').text(newFlag);
						aim.find('.edit-stack').val(newFlag);
						aim.find('.edit').hide();
					});
				});
			},
			_resetEpaperNum : function(stackFlag,currentStack){
				currentStack.attr('_flag',stackFlag);
				//更改叠下所有页
				var items = currentStack.find('li');
				$.each(items,function(){
					var text = $(this).find('.pageNum').text(),
						num = text.slice(1);
					$(this).find('.pageNum').text(stackFlag + num);
					$(this).attr('_belong',stackFlag);
					currentStack.attr('_flag',stackFlag);
				});
			},
			_editPageFlag : function( event ){
				var self = $(event.currentTarget),
					aim = self.siblings('.edit-page');
				aim.show().click().focus();
			},
			_getPageFlag : function( event ){
				var self = $(event.currentTarget);
				this.pageFlag = self.val();
				event.stopPropagation();
			},
			_setPageFlag : function( event ){
				var self = $(event.currentTarget),
					parent = self.closest('li'),
					page_id = parent.attr('_id'),
					_this = this,
					oldFlag = this.pageFlag,
					newFlag = self.val().toLocaleUpperCase();
				if( oldFlag !=  newFlag){
					var newPageNum = newFlag.substring((newFlag.indexOf(parent.attr('_belong')))+1);
					var currentStack = $('.each-list').filter('.active');
						sameInfo = _this._isSame(newFlag, currentStack, '.pageNum')
						isSame = sameInfo['isSame'],
						theSame = sameInfo['theSame'];
					if( isSame ){
						jConfirm(newFlag+'版已存在，是否要覆盖？','提示',function( result ){
							if( result ){
								var page_id_cz = theSame.closest('li').attr('_id');
								var info = {
										a : 'update_page_num',
										page_id : page_id,
										page_num : newPageNum,
										page_id_cz : page_id_cz
									};
								$.globalAjax(self,function(){
									return $.get(_this.url, info, function(){
										var aim = self.siblings('.pageNum');
										aim.text(newFlag);
										self.hide();
//									theSame.closest('li').find('.del').click();
										theSame.closest('li').hide(300);
									});
								});
							}else{
								self.val(oldFlag);
								self.hide();
							}
						});
					}else{
						var info = {
								a : 'update_page_num',
								page_id : page_id,
								page_num : newPageNum,
							}
						$.globalAjax(self,function(){
							return $.get(_this.url, info,function(){
								var aim = self.siblings('.pageNum');
								aim.text(newFlag);
								self.hide();
							});
						});
					}
				}else{
					self.hide();
				}
			},
			_isSame : function(source, ul, aim){	//数据，所在序列，要比较的类名
				var aims = ul.find(aim),
					theSame = aims.filter(function(){
						return source == $(this).text();
					});
				var	isSame = theSame.length ? true : false;
				return {
					isSame : isSame,
					theSame : theSame
				};
			},
			_showTips : function(content){
				var tip = $('.tips');
				tip.text(content).css({'opacity':1,'z-index':100001});
				setTimeout(function(){
					tip.css({'opacity':0,'z-index':-10});
				},1600);
			}
		});
	})($);
	$('.epaper-wrap').epaperform();
	$.MC = {};
});