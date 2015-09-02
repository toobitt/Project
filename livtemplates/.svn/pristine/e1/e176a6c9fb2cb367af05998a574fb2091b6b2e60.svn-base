$(function(){
	(function($){
		$.widget('survey.survey_form',{
			options : {
				textTpl : '',
				packTpl : '',
				moreTpl : '',
				selTpl : '',
				addoptionTpl : ''
			},
			
			_create : function(){
				this.index = 0;
				this.editid = '';
				this.delid = '';
			},
			
			_init : function(){
				this._on({
					'click .question-types li' : '_type',
					'click .close-question' : '_close',
					'click .edit' : '_edit',
					'click .delete' : '_delete',
					'click .copy' : '_copy',
					'click .moveup' : '_moveup',
					'click .movedown' : '_movedown',
					'click .add-more' : '_addmore',
					'click .used-tag p' : '_addtag',
					'blur .add-tag input' : '_blur',
					'click .used-tag a' : '_managetag',
					'click .tag-list p' : '_deltag',
					'click .tag-list li span' : '_seltag',
					'click .tag-info .del-tag' : '_deltaginfo',
					'click .btn-add' : '_btnadd',
					'click .btn-del' : '_btndel',
					'click .btn-up' : '_btnup',
					'click .btn-down' : '_btndown',
					'click .limit-input' : '_limit',
					'click .saveonly' : '_saveonly',
					'click .saveadd' : '_saveadd',
					
				});
				this.initinfo();
				this.initdata();
			},
			
			/*实例化数据*/
			initinfo : function(json){
				var data =json || $.globalData.problems,
					item = this.element.find('.info'),
					_this = this;
				if(!data){
					return false;
				}
				$.each(data , function(key , value){
					var type = data[key].type;
					var info = {};
					info.type = type;
					info.id = data[key].id;
					info.type_name = data[key].type_name;
					info.option = data[key].options;
					
//					info.initnum = (data[key].options)[key].initnum;
					info.select = data[key].is_required;
					if(type==1 || type==2){
						info.title = data[key].title;
						info.other = data[key].is_other;
						info.max = data[key].max_option;
						info.min = data[key].min_option;
						info.brief = data[key].description;
						info.more = data[key].more;
						var tip;
						if(info.max && info.min && info.max!=info.min && info.min!=0) {
							tip = '请选择'+ info.min +'-'+info.max+'项';
						}
						else if(info.max && info.min && info.max==info.min && info.min!=0){
							tip = '请选择'+ info.max+'项';
						}
						else if((!info.min || info.min == 0) && info.max && info.max!=0){
							tip = '最多选择'+ info.max+'项';
						}
						else if((!info.max || info.max == 0) && info.min && info.min!=0){
							tip = '最少选择'+ info.min+'项';
						}
						else{
							tip = '多选';
						}
						info.tip = tip;
						$('#presel-tpl').tmpl(info).appendTo(item);
					}else if(type == 3){
						$('#prepack-tpl').tmpl(info).appendTo(item);
					}else if(type == 4){
						info.title = data[key].title;
						info.tips = data[key].tips;
						info.max = data[key].max_option;
						info.min = data[key].min_option;
						$('#pretext-tpl').tmpl(info).appendTo(item);
					}
					_this._index();
				})
			},
			
			/*实例化隐藏域数据*/
			initdata : function(){
				var obj = this.element.find('.cite-question'),
					type = obj.map(function(){
						return $(this).data('type');
					}).get().join(','),
					ids = obj.map(function(){
						return $(this).data('id');
					}).get().join(','),
					/*单选题*/
					item = obj.filter(function(){
		        	    return $(this).data('type') == 1;
		            }),
		        	dtitle = item.map(function(){
		        		return $(this).find('.type-question').text();
		        	}).get().join('@'),
		        	dbrief = item.map(function(){
		        		return $(this).find('.brief').text();
		        	}).get().join('@'),
		        	dmore = item.map(function(){
		        		return $(this).find('.more').text();
		        	}).get().join('@'),
		        	doption = item.map(function(){
		            	return $(this).find('.check').map(function(){
            						return $.trim($(this).find('.sign').text());
		            		   }).get().join('|');
		        	}).get().join('@'),
		        	dinitnum = item.map(function(){
		            	return $(this).find('.check').map(function(){
            						return $.trim($(this).find('span').attr('_initnum'));
		            		   }).get().join('|');
		        	}).get().join('@'),
		        	
		        	danswer = item.map(function(){
		        		return $(this).data('select');
		        	}).get().join(','),
		        	dother = item.map(function(){
		        		return $(this).data('other');
		        	}).get().join(','),
		        	/*多选题*/
		        	aitem = obj.filter(function(){
		        	    return $(this).data('type') == 2;
		            }),
		        	mtitle = aitem.map(function(){
		        		return $(this).find('.type-question').text();
		        	}).get().join('@'),
		        	mbrief = item.map(function(){
		        		return $(this).find('.brief').text();
		        	}).get().join('@'),
		        	mmore = item.map(function(){
		        		return $(this).find('.more').text();
		        	}).get().join('@'),
		        	moption = aitem.map(function(){
		            	return $(this).find('.check').map(function(){
            						return $.trim($(this).find('.sign').text());
		            		   }).get().join('|');
		        	}).get().join('@'),
		        	minitnum = aitem.map(function(){
		            	return $(this).find('.check').map(function(){
            						return $.trim($(this).find('span').attr('_initnum'));
		            		   }).get().join('|');
		        	}).get().join('@'),
		        	manswer = aitem.map(function(){
		        		return $(this).data('select');
		        	}).get().join(','),
		        	mmax = aitem.map(function(){
						return $(this).data('max');
					}).get().join(','),
					mmin = aitem.map(function(){
						return $(this).data('min');
					}).get().join(','),
					mother = aitem.map(function(){
		        		return $(this).data('other');
		        	}).get().join(','),
					/*填空题*/
		        	Titem = obj.filter(function(){
		        	    return $(this).data('type') == 3;
		            }),
		            Ttitle = Titem.map(function(){
		            	return $(this).find('.fill-blank').map(function(){
		            				return $.trim($(this).find('.fill-name').text());
		            		   }).get().join('|');
		            }).get().join('@'),
		            Tanswer = Titem.map(function(){
		        		return $(this).data('select');
		        	}).get().join(','),
		        	Tnum = Titem.map(function(){
		        		return $(this).find('.fill-blank').map(function(){
            				return $.trim($(this).data('num'));
	            		   }).get().join('|');
		        	}).get().join('@'),
		        	/*问答题*/
		        	witem = obj.filter(function(){
		        	   return $(this).data('type') == 4;
		        	}),
		        	wtitle = witem.map(function(){
		        		return $(this).find('.type-question').text();
		        	}).get().join('@'),
		        	tip = witem.map(function(){
		        		return $(this).find('.text-tip').text();
		        	}).get().join('@');
					wanswer = witem.map(function(){
						return $(this).data('select');
					}).get().join(','),
					wmax = witem.map(function(){
						return $(this).data('max');
					}).get().join(','),
					wmin = witem.map(function(){
						return $(this).data('min');
					}).get().join(','),
		        this.element.find('input[name="type"]').val(type); /*题目类型*/
		        this.element.find('input[name="type_id"]').val(ids); /*题目id*/
		        this.element.find('input[name="1_title"]').val(dtitle); /*单选题标题*/
		        this.element.find('input[name="1_option"]').val(doption); 
		        this.element.find('input[name="1_initnum"]').val(dinitnum); 
		        this.element.find('input[name="1_required"]').val(danswer); 
		        this.element.find('input[name="1_other"]').val(dother);
		        this.element.find('input[name="1_brief"]').val(dbrief); 
		        this.element.find('input[name="1_more"]').val(dmore); 
		        this.element.find('input[name="2_title"]').val(mtitle); /*多选题标题*/
		        this.element.find('input[name="2_option"]').val(moption);
		        this.element.find('input[name="2_initnum"]').val(minitnum);
		        this.element.find('input[name="2_required"]').val(manswer);
		        this.element.find('input[name="2_max"]').val(mmax);
		        this.element.find('input[name="2_min"]').val(mmin);
		        this.element.find('input[name="2_other"]').val(mother);
		        this.element.find('input[name="2_brief"]').val(mbrief); 
		        this.element.find('input[name="2_more"]').val(mmore); 
		        this.element.find('input[name="3_title"]').val(Ttitle); /*填空题标题*/
		        this.element.find('input[name="3_required"]').val(Tanswer);
		        this.element.find('input[name="3_num"]').val(Tnum);
		        this.element.find('input[name="4_title"]').val(wtitle); /*问答题标题*/
		        this.element.find('input[name="4_tip"]').val(tip);
		        this.element.find('input[name="4_required"]').val(wanswer);
		        this.element.find('input[name="4_max"]').val(wmax);
		        this.element.find('input[name="4_min"]').val(wmin);
			},

			/*新增问题*/
			_type : function(event){
				var self = $(event.currentTarget),
					type = self.data('type'),
					title= self.data('title'),
					item = this.element.find('.question-box'),
					info = {};
				info.title = title;
				info.type = type;
				info.ac = '添加';
				info.optext = 0;
				self.addClass('color').siblings().removeClass('color');
				this._switch(type , info ,  item);
			},
			
			_switch : function(type , info , item){
				var op = this.options;
				item.empty();
				switch( type ){
					case 1:
						op.moreTpl.tmpl(info).appendTo(item);
						break;
					case 2:
						op.moreTpl.tmpl(info).appendTo(item);
						break;
					case 3:
						var url = 'run.php?mid='+ gMid +'&a=show_tags';
						$.globalAjax(item, function(){
					        return $.getJSON(url,function(json){
					        	info.tag = json;
					        	op.packTpl.tmpl(info).appendTo(item);
					        });
					    });
						break;
					case 4:
						op.textTpl.tmpl(info).appendTo(item);
						break;
				};
				item.css('left' , '0px');
			},
			
			_close : function(){
				this.element.find('.question-box').css('left' , '-1000px');
			},
			
			/*编辑问题*/
			_edit: function(event){
				var self = $(event.currentTarget),
					obj = self.closest('.cite-question');
				this.index = obj.index();
				this.editid += obj.data('id')+ ','; /*编辑题目id*/
				this.element.find('input[name="edit_proid"]').val(this.editid);
				this._editinfo(obj);	
			},
			
			_editinfo : function(obj){
				var item = this.element.find('.question-box'),
					type = obj.data('type'),
					title = obj.data('title'),
					answer = obj.data('select'),
					more = obj.data('more'),
					brief = obj.data('brief'),
					info = {};
				info.title = title;
				info.more = more;
				info.brief = brief;
				
				info.type = type;
				info.ac = '编辑';
				info.optext = 1;
				info.question = obj.find('.type-question').text();
				info.answer = answer;  
				info.id = obj.data('id');
				if(type == 1 || type == 2){
					var other = obj.data('other');
					other && (info.isother = true);
					info.max = obj.data('max');
					info.min = obj.data('min');
					info.option = obj.find('.check').map(function(){
						return $.trim($(this).find('p').text());
					}).get();
					info.initnum = obj.find('.check').map(function(){
						return $.trim($(this).find('span').attr('_initnum'));
					}).get();
				}else if(type == 3){
					info.tags = obj.find('.fill-blank').map(function(){
						return $(this).find('.fill-name').text();
					}).get();
					info.num = obj.find('.fill-blank').map(function(){
						return $(this).data('num');
					}).get();
				}else if(type == 4){
					info.tip = obj.find('.text-tip').text();
					info.max = obj.data('max');
					info.min = obj.data('min');
				}
				this._switch(type , info ,item);
			},
			
			_delete : function(event){
				var self = $(event.currentTarget),
					item = self.closest('.cite-question'),
					_this = this;
				var method = function(){
					item.remove();
					_this._index();
					_this.delid += item.data('id') + ',';  /*删除的题目id*/
					_this.element.find('input[name="delete_proid"]').val(_this.delid);
				}
				this._remind( '您确认删除此选项吗？', '删除提醒' , method ,self );	
			},
			
			/*复制*/
			_copy : function(event){
				var self = $(event.currentTarget),
					item = self.closest('.cite-question');
				citem = item.clone();
				citem.data('id' , ' ');
				citem.insertAfter(item);
				this._index();
				this.index = citem.index();
				this._editinfo(citem);	
				this.element.find('.question-box .add-new-question').attr('_sign' , 1);
			},
			
			/*上移*/
			_moveup : function(event){
				var self = $(event.currentTarget),
				item = self.closest('.cite-question'),
				index = item.find('.index').text();
				this._up(self , item , index);
				this._index();
			},
			
			_up : function(self ,item , index){
				if(index == 1){
					var tip = '已经是最前面了！';
					this.myTip(self , tip);
					return false;
				}else{
					citem = item.clone();
					citem.insertBefore(item.prev());
					item.remove();
				}
			},
			
			/*下移*/
			_movedown : function(event){
				var self = $(event.currentTarget),
				item = self.closest('.cite-question'),
				index = item.find('.index').text(),
				num = this.element.find('.cite-question').index();
				this._down(self ,item , num, index);
				this._index();
			},
			
			_down : function(self ,item , num ,index){
				if(index == num+1){
					var tip = '已经是最后面了！';
					this.myTip(self , tip);
					return false;
				}else{
					citem = item.clone();
					citem.insertAfter(item.next());
					item.remove();
				}
			},
			
			/*在此题后增加一题*/
			_addmore : function(event){
				var self = $(event.currentTarget),
					item = self.closest('.cite-question');
				type = item.data('type')-1;
				this.index = item.index();
				this.element.find('.question-types li:eq('+ type +')').click();
				this.element.find('.question-box .add-new-question').attr('_sign' , 1);
			},
			
			/*填空题 增加标签*/
			_addtag : function(){
				this.element.find('.add-tag input').show().focus();
			},
			
			_blur : function(){
				var obj = this.element.find('.add-tag input'),
					val = obj.val(),
					url = 'run.php?mid='+ gMid +'&a=add_tags',
					data = {
						tag_name : val
					};
				if(val){
					$.globalAjax(obj, function(){
				        return $.getJSON(url,data,function(json){
				        	info = {};
							info.tag = json[0].tag_name;
							info.id = json[0].id;
				        	$('#tag-tpl').tmpl(info).prependTo('.tag-list');
				            });
				    });
				};
				this.element.find('.add-tag input').hide();
				obj.val('');
			},
			
			/*填空题 管理标签*/
			_managetag : function(event){
				var item = this.element.find('.tag-list li'),
					self = $(event.currentTarget);
				if(self.data('init')){
					item.removeClass('animate');
					item.find('p').hide();
					self.data('init', false).html('管理');
				}else{
	            	item.addClass('animate');  
	            	item.find('p').show();
	            	self.data('init', true).html('取消');
				}
			},
			
			/*填空题 删除标签*/
			_deltag : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('li'),
					url = 'run.php?mid='+ gMid +'&a=delete_tags',
					data = {
						tag_id  : obj.attr('_id')
					};
				$.globalAjax(obj, function(){
			        return $.getJSON(url,data,function(json){
			            	obj.remove();
			            });
			    });
			},
			
			_deltaginfo : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('.tag-info');
					obj.remove();
			},
			
			_seltag : function(event){
				var self = $(event.currentTarget),
					txt = $.trim(self.text()),
					op = this.options,
					info = {};
				info.title = txt;
				op.selTpl.tmpl(info).appendTo('.pack-info');
			},
			
			/*填空题 增加选项*/
			_btnadd : function(event){
				var self = $(event.currentTarget),
					item = self.closest('.select-more'),
					op = this.options,
					html = op.addoptionTpl.html();
				item.append(html);
			},
			
			/*填空题 删除选项*/
			_btndel : function(event){
				var self = $(event.currentTarget),
					item = self.closest('.more-option'),
					length = this.element.find('.more-option').length;
				if(length == 1){
					var tip = "至少保留一项";
					this.myTip(self , tip);
					flag = false;
				}else{
					item.remove();
				}
			},
			
			/*填空题 上移选项*/
			_btnup : function(event){
				var self = $(event.currentTarget),
					item = self.closest('.more-option'),
					index = item.index() + 1;
				this._up(self , item ,index);
			},
			
			/*填空题 下移选项*/
			_btndown : function(event){
				var self = $(event.currentTarget),
					item = self.closest('.more-option'),
					index = item.index(),
					num = this.element.find('.more-option').index()-1;
				this._down(self , item ,num , index);
			},
			
			_limit : function(event){
				var self = $(event.currentTarget),
					select = self.prop('checked'),
					item = this.element.find('.limit-word');
				select ? item.show() : item.hide();
			},
			
			/*保存*/
			_saveonly : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('.question-box'),
					type = obj.find('.add-new-question').data('type');
				this._tip(self ,type);
				if(!flag){
					return false;
				}
				this._getquestioninfo(self);
				this._close();	
			},
			
			/*保存并继续添加*/
			_saveadd : function(event){
				var self = $(event.currentTarget),
				 	obj = self.closest('.question-box'),
				 	title = obj.find('.input-title').val(),
				 	type = obj.find('.add-new-question').data('type');
				this._tip(self ,type);
				if(!flag){
					return false;
				}
				this._getquestioninfo(self);
				obj.find('.new-question').attr('_mark' , 0);
				obj.find('input[type="text"]').val('');
				obj.find('input[type="checkbox"]').prop('checked' , false);
				obj.find('.pack-info').empty();
			},
			
			/*保存验证*/
			_tip : function(self ,type){
				var obj = self.closest('.question-box');
				if(type == 3){
					flag = true;
					var tag = obj.find('.pack-question p').text();
					var wordcount = $.trim(obj.find('.word-count').val());
					var reg =  /^(0|[1-9]\d*)$/;
					if(wordcount){
						if(!wordcount.match(reg)){
							var tip = "必须为非负整数";
							this.myTip(self , tip);
							obj.find('.word-count').val('');
							flag = false;
						}
					}
					if(!tag){
						var tip = "问题不能为空！";
						this.myTip(self , tip);
						flag = false;
					}
				}else{
					var title = obj.find('.input-title').val(),
						_this = this;
					if(!title){
						var tip = "问题标题不能为空！";
						this.myTip(self , tip);
						flag = false;
					}else{
						flag = true;
					}
					
					if(title.indexOf("@")>=0){
						var tip = "标题不能含@符号！";
						this.myTip(self , tip);
						flag = false;
					}
					
					if(type == 1 || type == 2){
						 obj.find('.more-option').map(function(index){
								var oval = $(this).find('input').val();
								if(oval.indexOf("@")>=0){
									var tip = "选项不能含@符号！";
									_this.myTip(self , tip);
									flag = false;
								}
						 })
					}
					
					if(type == 4){
						var notice = $.trim(obj.find('.tip input').val());
						if(notice.indexOf("@")>=0){
							var tip = "提示不能含@符号！";
							this.myTip(self , tip);
							flag = false;
						}
					}
					
					if(type == 2 || type == 4){
						var min = $.trim(obj.find('input[name="is_min"]').val()),
							max = $.trim(obj.find('input[name="is_max"]').val()),
							reg =  /^(0|[1-9]\d*)$/,
							select = obj.find('input[name="is_answer"]').prop('checked');
						if(min){
							if(!min.match(reg)){
								var tip = "必须为非负整数";
								this.myTip(self , tip);
								obj.find('input[name="is_min"]').val('');
								flag = false;
							}
						}
						if(max){
							if(!max.match(reg)){
								var tip = "必须为非负整数";
								this.myTip(self , tip);
								obj.find('input[name="is_max"]').val('');
								flag = false;
							}
						}
						if(max && min){
							if(!min.match(reg)){
								var tip = "必须为非负整数";
								this.myTip(self , tip);
								obj.find('input[name="is_min"]').val('');
								flag = false;
							}else if(!max.match(reg)){
								var tip = "必须为非负整数";
								this.myTip(self , tip);
								obj.find('input[name="is_max"]').val('');
								flag = false;
							}else if(parseInt(max) < parseInt(min)){
								var tip = "最多不能小于最小";
								this.myTip(self , tip);
								flag = false;
							}
						}
					}
				}
			},
			
			/*保存 插入模板*/
			_getquestioninfo : function(self){
				var obj = self.closest('.question-box'),
				  	type = obj.find('.add-new-question').data('type'),
					isselect = obj.find('input[name=is_answer]').prop('checked'),/*必填*/
					mark = obj.find('.new-question').attr('_mark'),/*判断是新增还是编辑*/
					sys = obj.find('.add-new-question').attr('_sign'),
					item = this.element.find('.cite-question:eq('+ this.index +')'),
					aitem = this.element.find('.info'),
					info = {},
					_this = this;
				info.type_name = obj.find('.question-type').text();
				info.sign = true ;			/*判断是编辑还是实例化*/
				info.type = type;
				info.id = obj.find('.add-new-question').data('id');
				isselect ? (info.select = 1) : (info.select = 0);
				if(type == 1 || type==2){
					var max = $.trim(obj.find('input[name="is_max"]').val()),
						min = $.trim(obj.find('input[name="is_min"]').val()),
						other = obj.find('input[name=other-option]').prop('checked');
					other ? (info.other = 1) :(info.other = 0);
					info.title = obj.find('.input-title').val();
					info.brief = obj.find('.input-brief').val();
					info.more = obj.find('.input-more').val();
					info.option = obj.find('.more-option').map(function(){
						var value = {};
						value.name = $(this).find('.option').val();
						value.initnum = $(this).find('.initnum').val();
						return value;
					}).get();
					info.max = max;
					isselect && (info.min = min);
					var tip;
					if(info.max && info.min && info.max!=info.min && info.min!=0) {
						tip = '请选择'+ info.min +'-'+info.max+'项';
					}
					else if(info.max && info.min && info.max==info.min && info.min!=0){
						tip = '请选择'+ info.max+'项';
					}
					else if((!info.min || info.min == 0) && info.max && info.max!=0){
						tip = '最多选择'+ info.max+'项';
					}
					else if((!info.max || info.max == 0) && info.min && info.min!=0){
						tip = '最少选择'+ info.min+'项';
					}
					else{
						tip = '多选';
					}
					info.tip = tip;
					if(mark == 1 || sys ==1){
						$('#presel-tpl').tmpl(info).insertAfter(item).css('background' , '#eee');
					}else{
						$('#presel-tpl').tmpl(info).prependTo(aitem).css('background' , '#eee');
					}
				}else if(type == 3){
					info.option = obj.find('.tag-info .pack-question').map(function(){
						return $(this).find('p').text();
					}).get();
					info.num = obj.find('.tag-info .word-count').map(function(){
						return $.trim($(this).val());
					}).get();
					if(mark == 1 || sys ==1){
						$('#prepack-tpl').tmpl(info).insertAfter(item).css('background' , '#eee');
					}else{
						$('#prepack-tpl').tmpl(info).prependTo(aitem).css('background' , '#eee');
					}
				}else if(type == 4){
					var max = obj.find('input[name="is_max"]').val(),
						min = obj.find('input[name="is_min"]').val(),
						reg = /^[0-9]*[1-9][0-9]*$/;
					info.title = obj.find('.input-title').val();
					info.tips = obj.find('.tip input[type="text"]').val();
					info.max = max;
					isselect && (info.min = min);
					if(mark == 1 || sys ==1){
						$('#pretext-tpl').tmpl(info).insertAfter(item).css('background' , '#eee');
					}else{
						$('#pretext-tpl').tmpl(info).prependTo(aitem).css('background' , '#eee');
					}
				}
				if(mark == 1){
					item.remove();
				}
				this._index();
				setTimeout(function(){
	            	_this.element.find('.cite-question').css('background' , 'none');
	        	},3000);
			},
			
			myTip : function(self , tip ){
				self.myTip({
					string : tip,
					delay: 1000,
					width : 150,
					dtop : 0,
					dleft : 80,
				});
			},
			
			/*排序*/
			_index : function(){
				this.element.find('.cite-question').each(function(index){
					$(this).find('.index').text(index+1);
				});
			},
			
			_remind : function( title , message , method , obj){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{}
				}).position(obj);
			},
		});
		$.widget('survey.survey_addinfo',{
			options : {
				picTpl : ''
			},
			
			_create : function(){
				var _this = this;
				$.pop({
					title : '引用内容',
					className : 'pubLib-pop-box',
					widget : 'pubLib',
					clickCall : function(event , info ,widget){
						_this._clickCall( info, widget );
					}
				});
	            this.datasource = $('.pubLib-pop-box');
	            this.datasource.pubLib('hide');
			},
			
			_init : function(){
				var _this = this;
				this._on({
					'click .additional-information li' : '_addinfo',
					'click .pic-default' : '_uploadfile',
					'click .video-default' : '_uploadvideo',
					'click .cite-default' : '_cite',
					'click .more-site p' : '_toggle',
					'click .attach-del' : '_attachdel',
					'click .play-button' : '_playvideo',
					'click .vedio-back-close' : '_closeBox',
					'blur .date-picker' : '_blurtime',
					'blur .num-limit input[type="text"]' : '_blurplus',
					'click input[name="is_verifycode"]' : '_showcode',
					'click .indexpic' : '_setIndexPic',
					'change .upload-file' :'_changefile'
 				});
				this._initids();
			},
			
			_setIndexPic : function(event){
				var self=$(event.currentTarget),
				    img=self.find('img'),
				    _indexFile=self.next('.upload-file'),
				    flag=true;
				var flagobj=self.find('.indexpic-suoyin');
				_indexFile.trigger('click');
				_indexFile.data({imgk:img,flagk:flag,suoyink:flagobj})
			},
			
			_changefile:function(event,img,flag,flagobj){
				var _this=this,
				    self=event.currentTarget,
					file=self.files;
				var data=$(self).data(),
				    img=data.imgk,
				    flag=data.flagk,
				    flagobj=data.suoyink;
					_this._handleFiles(file,img,flag,flagobj);
			},
			_handleFiles:function(files,img,flag,flagobj){
				var _this=this,
				    imgData;
				for(var i=0;i<files.length;i++){
					var file=files[i];
					var imageType=/image.*/;
					if(!file.type.match(imageType)){
						alert("请上传图片文件");
						continue;
					}
					var reader=new FileReader();
					reader.onload=function(e){
						imgData=e.target.result;
						img.attr('src',imgData);
						img.hasClass('hide') && img.removeClass('hide');
						if(flag){
							flagobj.addClass('indexpic-suoyin-current');
						}
					}
					reader.readAsDataURL(file);
				}
				return imgData;
			},
			
			/*图片，音频，视频上传*/
			upload : function(file ,self , a , pkey , type){
				var op = this.option;
				var _this = this;
		        file.ajaxUpload({
						url : 'run.php?mid='+ gMid +'&a=' + a,
						phpkey : pkey,
						type : type,
						before : function( info ){
							_this._loading(self);
						},
						after : function( json ){
							_this._getattachinfo(json.data ,self);
						}
					});
			},
			
			_loading : function(self){
				$('<li class="attach-info load"><img src="' + RESOURCE_URL + 'loading2.gif" style="background:#fff"/></li>').insertBefore(self);
			},
			
			_getattachinfo : function(data , self ){
				this.element.find('.load').remove();
				var op = this.options,
					info = {};
				info.id = data.id;
				if(data.expand_id){
					info.img = data.url
				}else{
					info.img = data.img_info;
				}
				if(data.m3u8){
					info.playurl = data.m3u8;
				}
				op.picTpl.tmpl(info).insertBefore(self);
				this._initids();
			},
			
			_addinfo : function(event){
				var self = $(event.currentTarget),
					index = self.index(),
					obj = this.element.find('.attach-box:eq('+ index +')');
				self.addClass('color').siblings().removeClass('color');
				obj.removeClass('none').siblings('.attach-box').addClass('none');
				this.element.find('.information-box em').css('left' , index*90);
			},
			
			_uploadfile : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('ul'),
					file = obj.find('.upload-file'),
					a = 'upload_image',
					pkey = 'pic',
					type = 'video';
				file.trigger('click');
				this.upload(file ,self , a , pkey , type);
			},
			
			_uploadvideo : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('ul'),
					file = obj.find('.uploadvod-file'),
					a = 'upload_video',
					pkey = 'videofile',
					type = 'video';
				file.trigger('click');
				this.upload(file ,self , a , pkey , type);
			},
			
			/*删除附加信息*/
			_attachdel : function(event){
				var self = $(event.currentTarget),
					item = self.closest('li');
				item.remove();
				this._initids();
			},
			
			/*引用*/
			_cite : function(event){
				this.self = $(event.currentTarget);
				this.showPop();
			},
			
			showPop : function(){
				this.datasource.pubLib('show', {
					top : 0 + 'px',
					'margin-top' : 0,
				});
			},
			
			_clickCall : function( info ,widget ){
				this._getattachinfo(info[0] ,this.self);
				this._initids();
				widget.element.pubLib('hide');
			},
			
			/*新增图片，视频，音频，引用取id ，删除id*/
			_initids : function(){
				var pic = this.element.find('.pic-box'),
					file = this.element.find('.file-box'),
					audio = this.element.find('.audio-box'),
					cite = this.element.find('.cite-box'),
					inputpic = this.element.find('input[name="attach_pic"]'),
					inputfile = this.element.find('input[name="attach_video"]'),
					inputaudio = this.element.find('input[name="attach_audio"]'),
					inputcite = this.element.find('input[name="attach_cite"]'),
					getids = [pic , file , audio , cite ],
					input = [inputpic , inputfile , inputaudio , inputcite];
				this._getidinfo(getids , input);
			},
			
			_getidinfo : function(getids , input){
				$.each(getids , function(key , value){
					var ids = getids[key].find('.attach-info').map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					input[key].val(ids);
				})
			},
			
			/*播放器*/
			_playvideo : function(event){
				var self = $(event.currentTarget),
					url = self.data('url'),
					top = $(window).scrollTop(),
					box = this.element.find('.video-box' );
				box.removeClass( 'video-show' );
				box.html('');
				var info = { video_url : url };
				$( '#vedio-tpl' ).tmpl(info).prependTo( box );
				box.addClass( 'video-show' ).attr({'_type':'m_video'}).css('top' , top + 'px');
			},
			
			/*关闭播放器*/
			_closeBox : function(){
				this._closeVideo();
			},
			
			_closeVideo : function(){
				var op = this.options,
					box = $('.video-box');
				box.removeClass( 'video-show').css('top' ,-1000 + 'px');
				setTimeout(function(){
					box.html('');
				},500)
			},
			
			/*更多设置*/
			_toggle : function(event){
				var self = $(event.currentTarget),
					obj = this.element.find('.info-box');
				if(obj.data('init')){
					obj.slideUp();
					self.removeClass('up').addClass('down');
					obj.data('init',false);
				}else{
					obj.slideDown();
					self.removeClass('down').addClass('up');
					obj.data('init',true);
				}
			},
			
			_blurtime : function(event){
				var self = $(event.currentTarget),
					start_time = this.element.find('input[name="start_time"]').val(),
					end_time = this.element.find('input[name="end_time"]').val(),
//					reg = /^((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-)) (20|21|22|23|[0-1]?\d):[0-5]?\d$/;
//				if(start_time[0] && !reg.test(start_time)){
//					tip = "时间格式不正确";
//					$.MC.section.survey_form('myTip' , self , tip);
//					self.val('');
//					return false;
//				}
//				if(end_time[0] && !reg.test(end_time)){
//					tip = "时间格式不正确";
//					$.MC.section.survey_form('myTip' , self , tip);
//					self.val('');
//					return false;
//				}
					start_time = start_time.replace(/-/g,'/'),
					end_time = end_time.replace(/-/g,'/'),
					start_time = new Date(start_time),
					end_time = new Date(end_time),
					end_time = end_time.getTime(),
					start_time = start_time.getTime(),
					tip = '';
				if(start_time > end_time){
					tip = "初始时间不能大于结束时间";
					$.MC.section.survey_form('myTip' , self , tip);
					self.val('');
					return false;
				}
			},
			
			_blurplus : function(event){
				var self = $(event.currentTarget),
					value = $.trim(self.val());
				if(value < 0 || isNaN(value)){
					tip = "只能为非负数";
					$.MC.section.survey_form('myTip' , self , tip);
					self.val(0);
					return false;
				}
			},
			
			_showcode : function(event){
				var self = $(event.currentTarget),
					select = self.prop('checked'),
					code = this.element.find('.verify_type');
				select ? code.show() : code.hide();
			},
		});
		
		$.widget('survey.survey_submit',{
			options : {
				picTpl : ''
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'click .save_as' : '_saveas',
					'click .other_save' : '_othersave',
					'click .cancel' : '_cancel'
				});
				this._cover();
				this._submit();
			},
			
			_cover : function(){ /*表单发布使用的覆盖层*/
				var height = $(document).height();
				this.element.find('.cover').css({'height':height});
			},
			
			/*另存为 相当于创建*/
			_saveas : function(){
				this.element.find('.m2o-save').prop('disabled' , false);
				this.element.attr('_publish' , 0);
				this.element.find('input[name="a"]').val('create');
				this.element.find('input[name="sub"]').trigger('click');
			},
			
			/*保存*/
			_othersave : function(){
				this.element.find('.m2o-save').prop('disabled' , false);
				this.element.attr('_publish' , 0);
				this.element.find('input[name="a"]').val('update');
				this.element.find('input[name="sub"]').trigger('click');
			},
			
			/*取消*/
			_cancel : function(){
				var _this = this;
				this.element.attr('_publish' , 1);
				this.element.find('input[name="a"]').val('update');
				this.element.find('.notice-box').animate({height:'0px',width:'0px'},100,function(){
					_this.element.find('.notice-box').hide();
				});
				this.element.find('.cover').hide();
				this.element.find('.m2o-save').prop('disabled' , false);
			},
			
			_submit : function(){
				var sform = this.element,
					_this = this;
				sform.submit(function(){
					var savebtn = sform.find('.m2o-save'),
						publish = sform.attr('_publish');
					savebtn.prop('disabled' , true);
					if(publish == 1){
						sform.find('.cover').show();
						sform.find('.notice-box').show().animate({height:'118px',width:'280px'},200);
						return false;
					}
					$.MC.section.survey_form('initdata');
				//	return false;
				});
			},
		});
	})($);
		$.MC ={
				section : $('.m2o-survey'),
				aside : $('.m2o-aside'),
				form : $('.m2o-form'),
		};
		$.MC.section.survey_form({
			textTpl : $('#text-tpl'),
			packTpl : $('#pack-tpl'),
			moreTpl : $('#more-tpl'),
			selTpl : $('#sel-tpl'),
			addoptionTpl : $('#addoption-tpl')
		});
		$.MC.aside.survey_addinfo({
			picTpl : $('#attachpic-tpl')
		});
		$.MC.form.survey_submit();
		$(window).scroll(function(){                        /*滚动条事件 根据滚动条位置定位编辑框位置*/
			var scrollTop = $(this).scrollTop();
			if(scrollTop<130){
				$.MC.form.find('.question-box').css('top' , 45);
			}else{
				$.MC.form.find('.question-box').css('top' , scrollTop-105);
			}
			
		})
});
