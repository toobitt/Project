(function(c,b,d){var a={close:'<a class="ui-dialog-close" title="关闭"><span class="ui-icon ui-icon-delete"></span></a>',mask:'<div class="ui-mask"></div>',title:'<div class="ui-dialog-title"><h3><%=title%></h3></div>',wrap:'<div class="ui-dialog"><div class="ui-dialog-content"></div><% if(btns){ %><div class="ui-dialog-btns"><% for(var i=0, length=btns.length; i<length; i++){var item = btns[i]; %><a class="ui-btn ui-btn-<%=item.index%>" data-key="<%=item.key%>"><%=item.text%></a><% } %></div><% } %></div> '};c.define("Dialog",{options:{autoOpen:true,buttons:null,closeBtn:true,mask:true,width:300,height:"auto",title:null,content:null,scrollMove:true,container:null,maskClick:null,position:null},getWrap:function(){return this._options._wrap},_init:function(){var j=this,h=j._options,g,f=0,e=b.proxy(j._eventHandler,j),k={};j.on("ready",function(){h._container=b(h.container||document.body);(h._cIsBody=h._container.is("body"))||h._container.addClass("ui-dialog-container");k.btns=g=[];h.buttons&&b.each(h.buttons,function(i){g.push({index:++f,text:i,key:i})});h._mask=h.mask?b(a.mask).appendTo(h._container):null;h._wrap=b(b.parseTpl(a.wrap,k)).appendTo(h._container);h._content=b(".ui-dialog-content",h._wrap);h._title=b(a.title);h._close=h.closeBtn&&b(a.close).highlight("ui-dialog-close-hover");j.$el=j.$el||h._content;j.title(h.title);j.content(h.content);g.length&&b(".ui-dialog-btns .ui-btn",h._wrap).highlight("ui-state-hover");h._wrap.css({width:h.width,height:h.height});b(window).on("ortchange",e);h._wrap.on("click",e);h._mask&&h._mask.on("click",e);h.autoOpen&&j.open()})},_create:function(){var e=this._options;if(this._options.setup){e.content=e.content||this.$el.show();e.title=e.title||this.$el.attr("title")}},_eventHandler:function(k){var j=this,f,h,i=j._options,g;switch(k.type){case"ortchange":this.refresh();break;case"touchmove":i.scrollMove&&k.preventDefault();break;case"click":if(i._mask&&(b.contains(i._mask[0],k.target)||i._mask[0]===k.target)){return j.trigger("maskClick")}h=i._wrap.get(0);if((f=b(k.target).closest(".ui-dialog-close",h))&&f.length){j.close()}else{if((f=b(k.target).closest(".ui-dialog-btns .ui-btn",h))&&f.length){g=i.buttons[f.attr("data-key")];g&&g.apply(j,arguments)}}}},_calculate:function(){var k=this,j=k._options,i,l,f=document.body,h={},e=j._cIsBody,g=Math.round;j.mask&&(h.mask=e?{width:"100%",height:Math.max(f.scrollHeight,f.clientHeight)-1}:{width:"100%",height:"100%"});i=j._wrap.offset();l=b(window);h.wrap={left:"50%",marginLeft:-g(i.width/2)+"px",top:e?g(l.height()/2)+window.pageYOffset:"50%",marginTop:-g(i.height/2)+"px"};return h},refresh:function(){var g=this,f=g._options,e,h;if(f._isOpen){h=function(){e=g._calculate();e.mask&&f._mask.css(e.mask);f._wrap.css(e.wrap)};if(b.os.ios&&document.activeElement&&/input|textarea|select/i.test(document.activeElement.tagName)){document.body.scrollLeft=0;b.later(h,200)}else{h()}}return g},open:function(e,g){var f=this._options;f._isOpen=true;f._wrap.css("display","block");f._mask&&f._mask.css("display","block");e!==d&&this.position?this.position(e,g):this.refresh();b(document).on("touchmove",b.proxy(this._eventHandler,this));return this.trigger("open")},close:function(){var f,e=this._options;f=b.Event("beforeClose");this.trigger(f);if(f.defaultPrevented){return this}e._isOpen=false;e._wrap.css("display","none");e._mask&&e._mask.css("display","none");b(document).off("touchmove",this._eventHandler);return this.trigger("close")},title:function(f){var e=this._options,g=f!==d;if(g){f=(e.title=f)?"<h3>"+f+"</h3>":f;e._title.html(f)[f?"prependTo":"remove"](e._wrap);e._close&&e._close.prependTo(e.title?e._title:e._wrap)}return g?this:e.title},content:function(f){var e=this._options,g=f!==d;g&&e._content.empty().append(e.content=f);return g?this:e.content},destroy:function(){var e=this._options,f=this._eventHandler;b(window).off("ortchange",f);b(document).off("touchmove",f);e._wrap.off("click",f).remove();e._mask&&e._mask.off("click",f).remove();e._close&&e._close.highlight();return this.$super("destroy")}})})(gmu,gmu.$);