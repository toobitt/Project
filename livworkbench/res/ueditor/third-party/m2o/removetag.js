(function($){
	$.widget('ueditor.removetag', $.ueditor.baseWidget, {
        options : {
        	title : '清除格式'
        },
        _create : function(){
            this._super();
        },
        _init : function(){
            this._super();
        },
        
        refresh : function( editor ){
        	var _this = this;
        	this._tooltip( 'edui-for-removetag','正在执行格式化请稍候...' );
        	setTimeout( function(){
        		_this._removeFormat( editor );
        		_this._centerImg( editor );
        	},50 );
        },
        
        /*清楚p标签嵌套*/
        _clearPnested : function(body){
        	var root_p = body.find('p').filter( function(){
        		var _parent_tagname = $(this).parent()[0].tagName.toLowerCase();
        		return _parent_tagname == 'body';
        	} );
        	if( root_p.find('p').length ){
        		var childs = root_p.children();
        		childs.unwrap( root_p );
        	}
        },
        
        _removeFormat : function( editor ){
            var body = $(editor.document.body);
            this._clearPnested( body );
            body.find("img.before-biaozhu-ok, img.after-biaozhu-ok").remove();
            body.find("img").each(function(){
            	$(this).removeAttr('style');
                var clone = $(this).clone();
                var div = $("<div></div>");
                var imgHtml = div.html(clone).html();
                $(this).replaceWith("{{{"+  encodeURIComponent(imgHtml) +"}}}");
                div.remove();
            });
            body.find("br").each(function(){
                $(this).replaceWith("{{{br}}}");
            });
            body.find("span[style]").filter(function(){
                return $(this).css("font-weight") == "bold";
            }).add(body.find("b, strong")).each(function(){
                $(this).replaceWith("{{{strong}}}" + $.trim($(this).text()) + "{{{/strong}}}");
            });
            body.find("p").each(function(){
                $(this).replaceWith("{{{p}}}" + $.trim($(this).text()) + "{{{/p}}}");
            });
            var string = body.text();
            string = string.replace(/({{{p}}}){1,}/g, "<p>");
            string = string.replace(/({{{\/p}}}){1,}/g, "</p>");
            string = string.replace(/({{{br}}}){1,}/g, "<br/>");
            string = string.replace(/({{{strong}}}){1,}/g, "<strong>");
            string = string.replace(/({{{\/strong}}}){1,}/g, "</strong>");
            string = string.replace(/{{{([^}]*)}}}/g, function(all, match){
                return decodeURIComponent(match);
            });
            body.html(string);
            body.find("img.pagebg").unwrap();
            body.contents().filter(function(){
                return this.nodeType == 3;
            }).wrap("<p></p>");
            body.find('p').filter(function(){
                var text = $.trim($(this).text()),
                    img = $(this).has('img');
                return  text== "" && !img.length;
            }).remove();

            body.find("br").filter(function(){
                var self = $(this),
                    parent = self.parent(),
                    prev = self.prev(),
                    next = self.next(),
                    parent_prev = parent.prev(),
                    parent_next = parent.next();
                return ( ( prev.is("p") && next.is("p") ) || ( parent_prev.is("p") && parent_next.is("p") ) );
            }).remove();
            
            editor.sync();
            
        },
        
        _centerImg : function( editor ){
			var _this = this,
				editor_document = $(editor.document);
			var	imgs = editor_document.find('img');
			var needformat_imgs = imgs.filter( function(){
				var is_need = true,
					src = $(this).attr('src'),
					parent = $(this).parent(),
					text = $.trim( parent.text() ),
					is_body = parent.is('body'),
					is_p = parent.is('p');
				if( !src ){
					$(this).remove();
					return;
				}
				if( !is_body && is_p && !text ){
					is_need = false;
					is_p && parent.css('text-align','center');
				}
				return is_need;
			} );
			needformat_imgs.each( function(){
				$(this).wrap('<p style="text-align:center"></p>');
			} );
			this._tooltipend('格式化完成');
			
			this._fixformat( editor);
			this._clear(editor_document);
			editor.sync();
        },
        
        _clear : function( editor_document ){
        	var _this = this;
        	editor_document.find('p').each( function(){
        		var self = $(this),
        			strong = self.find('strong'),
        			b = self.find('b');
        		$.each( [self,strong,b], function( key, value ){
        			_this._clearByelement( value );
        		} );
        	} );
        },
        
        _clearByelement : function( $el ){
        	var text = $.trim( $el.text() ),
        		img = $el.find('img');
        	if( !img.length && !text ){
    			$el.remove();
    		}
        },
        
        
        _fixformat : function(editor){
        	var range = this.range();
			range.selectNode($(editor.document).find('body')[0]);
			range.select();
			editor.execCommand( 'source');		//调用切换源码模式来修复标签嵌套与闭合问题
			editor.execCommand( 'source');
        }
        
  });

   $.ueditor.m2oPlugins.add({
        cmd : 'removetag',
        title : '清除格式',
        click : function(editor){
            $.editorPlugin.get(editor, 'removetag').removetag('refresh',editor);
        }
    });
})(jQuery);