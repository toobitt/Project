(function($){
    var pluginInfo = {
        templateName : 'plugin-imginfo-template',
        template : '' +
            '<div class="pz-item" data-hash="{{= hash}}">' +
                '<div class="pz-option"><span class="pz-icon pz-oc pz-more">↓</span><span class="pz-icon pz-del">↑</span></div>' +
                '<div class="pz-content">' +
                	'<label>{{= index}}</label>：<span class="pizhu-content" title="{{= pizhuname}}">{{= pizhuname}}</span>' +
                	'<span class="reply-num">({{= num}})</span>' +
                '</div>' +
                '<div class="pz-reply">' +
                    '{{each reply}}' +
                    '<div class="pz-reply-item"><label class="name">{{= bname}}</label>：<span class="reply-content">{{= content}}</span></div>' +
                    '{{/each}}' +
                    '<div class="pz-reply-item"><label class="name">我</label>：<input class="pz-input"/></div>' +
                '</div>' +
            '</div>'+
            '',
	    reply_item_tpl : ''+
	    		'<div class="pz-reply-item"><label class="name">{{= bname}}</label>：<span class="reply-content">{{= content}}</span></div>' +
	    	'',
        css : '' +
        	'.pizhu-list{overflow-y:auto;}'+
            '.pz-item{padding:10px;margin:0 10px;position:relative;border-bottom:1px solid #e7e7e7;}' +
            '.pizhu-content{white-space:nowrap;display:inline-block;max-width:80px;overflow:hidden;text-overflow:ellipsis;vertical-align: middle;}'+
            '.pz-item.open{background: #f0eff5;}'+
            '.pz-item.open .pz-content{color:red;}'+
            '.pz-item .reply-num{color:#a0a0a0;margin-left:5px;}'+
            '.pz-item .pz-option{position:absolute;right:10px;top:10px;}' +
            '.pz-icon{color:transparent;cursor:pointer;display:inline-block;width:20px;height:20px;}'+
            '.pz-more{background:url('+$.ueditor.pluginDir+'/arrow_down.png) no-repeat center;}'+
            '.pz-item.open .pz-more{background-image:url('+$.ueditor.pluginDir+'/arrow_up.png);}'+
            '.pz-item .pz-del{background:url('+$.ueditor.pluginDir+'/del_grey.png) no-repeat center;}' +
            '.pz-item .pz-del:hover{background-image:url('+$.ueditor.pluginDir+'/del_hover.png);}' +
            '.pz-item .pz-reply{display:none;}' +
            '.pz-item.open .pz-reply{display:block;}'+
            '.pz-reply-item{margin:5px;}'+
            '.pz-reply-item .name{color:#a0a0a0;}'+
            '',
        cssInited : false
    };

    var pizhuInfo = function(){
        var dir = $.ueditor.pluginDir + '/slide/';
        var style = 'cursor:pointer;position:absolute;margin-top:-10px;width:10px;';
        return {
            before : '<img class="m2o-pizhu m2o-pizhu-before" src="' + dir + 'before-pizhu' + ($.ueditor.gPixelRatio > 1 ? '-2x' : '') + '.png" style="' + style + 'margin-left:-10px;"/>',
            after : '<img class="m2o-pizhu m2o-pizhu-after" src="' + dir + 'after-pizhu' + ($.ueditor.gPixelRatio > 1 ? '-2x' : '') + '.png" style="' + style + 'margin-right:-10px;"/>'
        }
    }();
    var gAdminInfo = {
        id : gAdmin['admin_id'],
        name : gAdmin['admin_user']
    };
    $.widget('ueditor.pizhu', $.ueditor.baseWidget, {
        options : {
            //selfPluginInfo : pluginInfo,
            //selfTemplateName : 'plugin-imginfo-template',
            initData : null,
            uid : gAdminInfo['id'],
            uname : gAdminInfo['name'],
            pizhuInfo : pizhuInfo
        },
        _create : function(){
            this._super();
            this.initData = this.options.initData || [];
            this._selfTemplate(this.initData);
            this.setTitle('批注设置');
            this.gPizhu = {};		//全局变量，存放pizhu data，键名为pizhu的hash值
            this.gPizhuCount = 0;	//批注的个数，用于批注列表的index
            this.pizhuInit = false;	
        },
        _init : function(){
            this._super();
            this._on({
                'click .pz-del' : '_del',
                'click .pz-more' : '_open',
                'blur .pz-input' : '_reply'
            });
            $.template('list_item_tpl', pluginInfo.template);
            $.template('reply_item_tpl', pluginInfo.reply_item_tpl);
            this.list = $('<div class="pizhu-list"></div>').appendTo(this.body);
            this.list.height( this.element.height() - this.title.height() - 10 );
        },
        /** 新增批注 */
        _createEditorPizhu : function( hash ){
        	var selection = this.editor.selection,
            	range = selection.getRange().select(),
            	content = selection.getText(),
            	cloneRange = range.cloneRange();
            range.setCursor(true);
            this.gPizhu[hash].pizhuname = content;
            var pizhuParam = this.gPizhu[hash];
            range.insertNode($(this.options.pizhuInfo.after).attr( pizhuParam )[0]);
            cloneRange.select().insertNode($(this.options.pizhuInfo.before).attr( pizhuParam )[0]);
            if( this.editorOp.needCount ){
        		$('.editor-statistics-item[_type="pizhu"]').find('span').text( $(this.editorBody).find('.m2o-pizhu-before').length );
        	}
        },
        _createListPizhu : function( hash ){
        	this.gPizhu[hash].num = 0;
        	this.gPizhu[hash].index = this.gPizhuCount;
        	var data = this.gPizhu[hash];
        	$.tmpl('list_item_tpl', data).appendTo(this.list);
        },
        /** 新增回复 */
        _reply : function(event){
        	var self = $(event.currentTarget),
	        	parent = self.closest('.pz-item'),
	        	hash = parent.data('hash'),
	        	reply = $.trim(self.val());
	        if(reply){
	        	var itemInfo = {
	        			name : this.options.uname,
	        			bname : '我',
	        			content : reply,
	        	};
	        	$.tmpl('reply_item_tpl', itemInfo).insertBefore(self.parent());
	        	self.val('');
	        	this._refreshListData( hash, parent );
	        	this._refreshEditorPizhu( parent );
	        	var len = this.gPizhu[hash].reply.length;
	        	this.gPizhu[hash].num = len;
	        	parent.find('.reply-num').text('('+ len +')');
	        	this.editor.focus();
	        }
        },
        _refreshListData : function( hash, parent ){
        	var arr = [],
        		_this = this,
        		replyItems = parent.find('.pz-reply-item').not(':last-child');
        	$( replyItems ).each(function(){
        		var self = $(this),
        			obj = {};
    			obj.bname = self.find('.name').text(),
    			obj.content = self.find('.reply-content').text();
    			arr.push( obj )
        	});
        	this.gPizhu[hash].reply = arr;
        },
        _refreshEditorPizhu : function( parent ){
        	var replyItems = parent.find('.pz-reply-item').not(':last-child'),
	        	hash = parent.data('hash');
	        var dataStr = this._replyDataToStr( replyItems ),
	        	pizhuFlag = this._getEditorBeforePZ( hash );
	        pizhuFlag.attr('_pzdata', dataStr);
        },
        _replyDataToStr : function( domList ){
        	var arr = [];
        	$.each( domList, function(k,v){
        		var v = $(this),
        			name = v.find('.name').text(),
        			content = v.find('.reply-content').text();
//        		arr.push({
//        			name : name,
//        			content : content
//        		});
        		var item = '_bname_:_'+ name + '_,_content_:_'+ content +'_';
        		arr.push(item);
        	});
//        	var str = encodeURIComponent(JSON.stringify(arr));
        	var str = arr.join('|');
        	return str;
        },
        /** 展开回复 */
        _open : function(event){
            var self = $(event.currentTarget),
            	parent = self.closest('.pz-item');
            parent.toggleClass('open').siblings().removeClass('open');
        },
        /** 删除批注 */
        _del : function(event){
            var item = $(event.currentTarget).closest('.pz-item'),
            	hash = item.data('hash');
            this._delEditorPZ(hash);
            this.gPizhuCount--;
            var _this = this;
            item.slideUp(function(){
            	item.remove();
            	_this._refreshItemIndex();
            	_this.editor.focus();		//让编辑器获得焦点，以同步内容，否则删除无效
            	if( _this.editorOp.needCount ){
            		$('.editor-statistics-item[_type="pizhu"]').find('span').text( $(_this.editorBody).find('.m2o-pizhu-before').length );
            	}
            });
        },
        _refreshItemIndex : function(){
            this.body.find('.pz-item').each(function(index, val){
                $(this).find('.pz-content label').html(1 + index);
            });
        },
        _getEditorBeforePZ : function(hash){
            return $(this.editor.document).find('img.m2o-pizhu-before[hash="' + hash + '"]');
        },
        _getEditorPZ : function(hash){
            return $(this.editor.document).find('img.m2o-pizhu[hash="' + hash + '"]');
        },
        _delEditorPZ : function(hash){
            this._getEditorPZ(hash).remove();
        },
        _selfTemplate : function(data){
            this._template(pluginInfo.templateName, pluginInfo, this.body, data);
        },
        /** 遍历已保存的批注 */
        _getHistoryData : function(){
        	var html = $(this.editor.document.body),
        		bPi = html.find('.m2o-pizhu-before'),
        		_this = this;
        	$.each( bPi, function(k, v){
        		var self = $(this),
        			hash = self.attr('hash'),
        			dataStr = self.attr('_pzdata') || '',
        			dataObj = dataStr.length ? _this._strToArr( dataStr ) : [];
        		_this.gPizhu[hash] = {
        				hash : hash,
        				index : k+1,
        				pizhuname : self.attr('pizhuname'),
        				reply : dataObj,
        				num : dataObj.length
        		}
        		_this.gPizhuCount++;
        		var dom = $.tmpl('list_item_tpl', _this.gPizhu[hash]).appendTo( _this.list );
        	});
        },
        refresh : function(){
            this.showAll();
            var hash = this.hash();
            this.gPizhuCount++;
            this.gPizhu[ hash ] = {};
            this.gPizhu[ hash ].hash = hash;
            this._createEditorPizhu( hash );
            this._createListPizhu( hash );
        },
        ok : function(){
            this._super();
        },
        no : function(){
            this._super();
            this.hideAll();
        },
        showAll : function(){
            this.show();
            var flag = this.element.hasClass('pop-show');
            $(this.editorBody).find('img.m2o-pizhu').css('display', flag ? 'inline' : 'none');
            if( !this.pizhuInit ){
            	this._getHistoryData();
            	this.pizhuInit = true;
            }
        },
        _regFunc : function( str, model ){
			for( var k in model ){
				var re = new RegExp( k, 'g' );
				str = str.replace( re, model[k] );
			}
			return str;
		},
        _strToArr : function(str){
        	var arr = str.split('|'),
        		newArr = [];
        	for( var i=0,len = arr.length;i<len;i++ ){
        		var s = '{' + this._regFunc( arr[i], {
        			'_' : '"'
        		}) + '}';
        		newArr[i] = JSON.parse(s);
        	}
//        	var newArr = $.parseJSON(decodeURIComponent(str));
        	return newArr;
        },
        hideAll : function(){
            $(this.editor.document).find('img.m2o-pizhu').hide();
            this.hide();
        },

        focusHide : function(){
            this.hideAll();
        },

        _destroy : function(){

        },
    });
    
    $.ueditor.m2oPlugins.add({
        cmd : 'pizhu',
        title : '批注',
        click : function(editor){
            $.editorPlugin.get(editor, 'pizhu').pizhu('showAll');
        }
    });
    
    (function(){
        var init = {};
        var c = setInterval(function(){
        	$.each(UE.instants, function(key, editor){
            	if(!init[key]){
            		init[key] = true;
                	editor.ready(function(){
                		$(editor.body).find('.m2o-pizhu').hide();
                	});
            	}else{
            		clearInterval(c);
            	}
            });
        },50);
    })();
})(jQuery);