(function($){
	var editorCountInfo = {
			template : ''+
//				'<div id="editor-count">'+
					'<ul class="editor-statistics clear">'+
						'<li class="editor-statistics-item"_type="word"><span>{{= wordCount}}</span>字数</li>'+
						'<li class="editor-statistics-item"_type="image"><span>{{= imgCount}}</span>图片</li>'+
						'<li class="editor-statistics-item"_type="attach"><span>{{= attachCount}}</span>附件</li>'+
						'<li class="editor-statistics-item"_type="pageslide"><span>{{= pageCount}}</span>页数</li>'+
						'<li class="editor-statistics-item"_type="pizhu"><span>{{= pizhuCount}}</span>批注</li>'+
					'</ul>'+
//				'</div>'+
				'',
			css : ''+
				'.editor-statistics{position:relative;min-width:220px;color:#a3a3a3;}'+
				'.editor-statistics li{float:left;border-left:1px solid #d3d3d3;padding:0 10px 0 5px;}'+
				'.editor-statistics li:first-child{border:none;}'+
				'.editor-statistics span{display:block;color:#333;font-weight:bold;margin-bottom:8px;}'+
				'.editor-statistics li:hover span{color:#1459a4;cursor:pointer;}'+
				'',
			cssInited : false
	};
	$.widget('ueditor.editorCount',$.ueditor.base, {
        options : {

        },
        _create : function(){
        	this._super();
        	if( this.editorOp.countDom ){
        		this.dom = $( this.editorOp.countDom );
        	}else{
        		this.dom = $('<div id="editor-count"></div>').prependTo('body');
        	}
        	this._template('editor_count_tpl', editorCountInfo, this.dom);
        },
        _init : function(){
        	this._super();
        	var _this = this;
        	this.dom.on('click','.editor-statistics-item',function(event){
        		var self = $(event.currentTarget),
        			type = self.attr('_type');
        		_this._tabEditorWidget( type );
        	});
        },
        _tabEditorWidget : function(type){
        	if( type == 'word' ){
        		return;
        	}
        	switch ( type ){
        		case 'image' : 
        			$.editorPlugin.get(this.editor, 'imgmanage').imgmanage('show');
        			break;
        		case 'attach' : 
        			$.editorPlugin.get(this.editor, 'attach').attach('show');
        			break;
        		case 'pizhu' : 
        			$.editorPlugin.get(this.editor, 'pizhu').pizhu('showAll');
        			break;
        		case 'pageslide' : 
        			$.editorPlugin.get(this.editor, 'page').page('show');
        			break;
        	}
        },
        _widgetCount : function(){
        	var data = {
        			wordCount : this.editor.getContentTxt().length,
        			imgCount : imgList.length,
        			attachCount : attachList.length,
        			pageCount : $(this.editorBody).find('.pagebg').length + $(this.editorBody).find('.pagebg-first').length,
        			pizhuCount : $(this.editorBody).find('.m2o-pizhu-before').length
        	}
        	$.tmpl( 'editor_count_tpl', data ).appendTo( this.dom.empty() );
        },
        //单个刷新，新增或删除时触发
        singleRefresh : function( type ){
        	var theItem = this.dom.find('.editor-statistics-item').filter(function(){
        		return $(this).attr('_type') == type;
        	}).find('span');
        	switch( type ){
        		case 'word' : 
        			theItem.text( this.editor.getContentTxt().length );
        			break;
        		case 'image' :
        			theItem.text( $.editorPlugin.get(this.editor, 'imgmanage').imgmanage('count') );
        			break;
        		case 'attach' :
        			theItem.text( $.editorPlugin.get(this.editor, 'attach').attach('count') );
        			break;
        		case 'pageslide' :
        			var page = $(this.editorBody).find('.pagebg'),
        				pageFirst = $(this.editorBody).find('.pagebg-first');
        			theItem.text( page.length + pageFirst.length);
        			break;
        		case 'pizhu' : 
        			theItem.text( $(this.editorBody).find('.m2o-pizhu-before').length );
        			break;
        	}
        },
        refresh : function( ){
        	this._widgetCount();
        },
    });
	
	
	(function(){
        var init = {};
        (function loop(){
            $.each(UE.instants, function(key, editor){
            	if(!init[key]){
                    init[key] = true;
                	editor.ready(function(){
                		$.editorPlugin.get(editor, 'editorCount').editorCount('refresh');
                		editor.body.addEventListener('keyup',function(){
                			$.editorPlugin.get(editor, 'editorCount').editorCount('singleRefresh','word');
                		});
                	});
            	}
            });
            setTimeout(loop, 500);
        })();
    })();
})(jQuery);