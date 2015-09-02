(function(d,c,e){var f=c.fx.cssPrefix,a=c.fx.transitionEnd,b=" translateZ(0)";d.define("Slider",{options:{loop:false,speed:400,index:0,selector:{container:".ui-slider-group"}},template:{item:'<div class="ui-slider-item"><a href="<%= href %>"><img src="<%= pic %>" alt="" /></a><% if( title ) { %><p><%= title %></p><% } %></div>'},_create:function(){var i=this,g=i.getEl(),h=i._options;i.index=h.index;i._initDom(g,h);i._initWidth(g,i.index);i._container.on(a+i.eventNs,c.proxy(i._tansitionEnd,i));c(window).on("ortchange"+i.eventNs,function(){i._initWidth(g,i.index)})},_initDom:function(j,l){var g=l.selector,k=l.viewNum||1,i,h;h=j.find(g.container);if(!h.length){h=c("<div></div>");if(!l.content){if(j.is("ul")){this.$el=h.insertAfter(j);h=j;j=this.$el}else{h.append(j.children())}}else{this._createItems(h,l.content)}h.appendTo(j)}if((i=h.children()).length<k+1){l.loop=false}while(l.loop&&h.children().length<3*k){h.append(i.clone())}this.length=h.children().length;this._items=(this._container=h).addClass("ui-slider-group").children().addClass("ui-slider-item").toArray();this.trigger("done.dom",j.addClass("ui-slider"),l)},_createItems:function(h,j){var k=0,g=j.length;for(;k<g;k++){h.append(this.tpl2html("item",j[k]))}},_initWidth:function(h,g,k){var j=this,i;if(!k&&(i=h.width())===j.width){return}j.width=i;j._arrange(i,g);j.height=h.height();j.trigger("width.change")},_arrange:function(l,j){var h=this._items,k=0,m,g;this._slidePos=new Array(h.length);for(g=h.length;k<g;k++){m=h[k];m.style.cssText+="width:"+l+"px;left:"+(k*-l)+"px;";m.setAttribute("data-index",k);this._move(k,k<j?-l:k>j?l:0,0)}this._container.css("width",l*g)},_move:function(j,l,k,i){var g=this._slidePos,h=this._items;if(g[j]===l||!h[j]){return}this._translate(j,l,k);g[j]=l;i&&h[j].clientLeft},_translate:function(h,k,j){var g=this._items[h],i=g&&g.style;if(!i){return false}i.cssText+=f+"transition-duration:"+j+"ms;"+f+"transform: translate("+k+"px, 0)"+b+";"},_circle:function(i,h){var g;h=h||this._items;g=h.length;return(i%g+g)%h.length},_tansitionEnd:function(g){if(~~g.target.getAttribute("data-index")!==this.index){return}this.trigger("slideend",this.index)},_slide:function(n,l,g,h,k,j){var i=this,m;m=i._circle(n-g*l);if(!j.loop){g=Math.abs(n-m)/(n-m)}this._move(m,-g*h,0,true);this._move(n,h*g,k);this._move(m,0,k);this.index=m;return this.trigger("slide",m,n)},slideTo:function(m,k){if(this.index===m||this.index===this._circle(m)){return this}var j=this._options,h=this.index,l=Math.abs(h-m),g=l/(h-m),i=this.width;k=k||j.speed;return this._slide(h,l,g,i,k,j)},prev:function(){if(this._options.loop||this.index>0){this.slideTo(this.index-1)}return this},next:function(){if(this._options.loop||this.index+1<this.length){this.slideTo(this.index+1)}return this},getIndex:function(){return this.index},destroy:function(){this._container.off(this.eventNs);c(window).off("ortchange"+this.eventNs);return this.$super("destroy")}})})(gmu,gmu.$);(function(b,a,c){a.extend(true,b.Slider,{template:{dots:'<p class="ui-slider-dots"><%= new Array( len + 1 ).join("<b></b>") %></p>'},options:{dots:true,selector:{dots:".ui-slider-dots"}}});b.Slider.option("dots",true,function(){var d=function(g,f){var e=this._dots;typeof f==="undefined"||b.staticCall(e[f%this.length],"removeClass","ui-state-active");b.staticCall(e[g%this.length],"addClass","ui-state-active")};this.on("done.dom",function(h,f,g){var i=f.find(g.selector.dots);if(!i.length){i=this.tpl2html("dots",{len:this.length});i=a(i).appendTo(f)}this._dots=i.children().toArray()});this.on("slide",function(f,h,g){d.call(this,h,g)});this.on("ready",function(){d.call(this,this.index)})})})(gmu,gmu.$);(function(c,b,e){var d={touchstart:"_onStart",touchmove:"_onMove",touchend:"_onEnd",touchcancel:"_onEnd",click:"_onClick"},h,g,f,a;b.extend(c.Slider.options,{stopPropagation:false,disableScroll:false});c.Slider.register("touch",{_init:function(){var j=this,i=j.getEl();j._handler=function(k){j._options.stopPropagation&&k.stopPropagation();return d[k.type]&&j[d[k.type]].call(j,k)};j.on("ready",function(){i.on("touchstart"+j.eventNs,j._handler);j._container.on("click"+j.eventNs,j._handler)})},_onClick:function(){return !a},_onStart:function(m){if(m.touches.length>1){return false}var l=this,n=m.touches[0],k=l._options,i=l.eventNs,j;g={x:n.pageX,y:n.pageY,time:+new Date()};f={};a=false;h=e;j=k.viewNum||1;l._move(k.loop?l._circle(l.index-j):l.index-j,-l.width,0,true);l._move(k.loop?l._circle(l.index+j):l.index+j,l.width,0,true);l.$el.on("touchmove"+i+" touchend"+i+" touchcancel"+i,l._handler)},_onMove:function(o){if(o.touches.length>1||o.scale&&o.scale!==1){return false}var j=this._options,r=j.viewNum||1,k=o.touches[0],m=this.index,l,n,p,q;j.disableScroll&&o.preventDefault();f.x=k.pageX-g.x;f.y=k.pageY-g.y;if(typeof h==="undefined"){h=Math.abs(f.x)<Math.abs(f.y)}if(!h){o.preventDefault();if(!j.loop){f.x/=(!m&&f.x>0||m===this._items.length-1&&f.x<0)?(Math.abs(f.x)/this.width+1):1}q=this._slidePos;for(l=m-r,n=m+2*r;l<n;l++){p=j.loop?this._circle(l):l;this._translate(p,f.x+q[p],0)}a=true}},_onEnd:function(){this.$el.off("touchmove"+this.eventNs+" touchend"+this.eventNs+" touchcancel"+this.eventNs,this._handler);if(!a){return}var s=this,j=s._options,w=j.viewNum||1,r=s.index,v=s._slidePos,o=+new Date()-g.time,n=Math.abs(f.x),k=!j.loop&&(!r&&f.x>0||r===v.length-w&&f.x<0),m=f.x>0?1:-1,l,u,p,q,t;if(o<250){l=n/o;u=Math.min(Math.round(l*w*1.2),w)}else{u=Math.round(n/(s.perWidth||s.width))}if(u&&!k){s._slide(r,u,m,s.width,j.speed,j,true);if(w>1&&o>=250&&Math.ceil(n/s.perWidth)!==u){s.index<r?s._move(s.index-1,-s.perWidth,j.speed):s._move(s.index+w,s.width,j.speed)}}else{for(p=r-w,q=r+2*w;p<q;p++){t=j.loop?s._circle(p):p;s._translate(t,v[t],j.speed)}}}})})(gmu,gmu.$);(function(b,a){a.extend(true,b.Slider,{options:{autoPlay:true,interval:4000}});b.Slider.register("autoplay",{_init:function(){var c=this;c.on("slideend ready",c.resume).on("destory",c.stop);c.getEl().on("touchstart"+c.eventNs,a.proxy(c.stop,c)).on("touchend"+c.eventNs,a.proxy(c.resume,c))},resume:function(){var d=this,c=d._options;if(c.autoPlay&&!d._timer){d._timer=setTimeout(function(){d.slideTo(d.index+1);d._timer=null},c.interval)}return d},stop:function(){var c=this;if(c._timer){clearTimeout(c._timer);c._timer=null}return c}})})(gmu,gmu.$);(function(a){a.Slider.template.item='<div class="ui-slider-item"><a href="<%= href %>"><img lazyload="<%= pic %>" alt="" /></a><% if( title ) { %><p><%= title %></p><% } %></div>';a.Slider.register("lazyloadimg",{_init:function(){this.on("ready slide",this._loadItems)},_loadItems:function(){var g=this._options,c=g.loop,f=g.viewNum||1,d=this.index,e,b;for(e=d-f,b=d+2*f;e<b;e++){this.loadImage(c?this._circle(e):e)}},loadImage:function(c){var d=this._items[c],b;if(!d||!(b=a.staticCall(d,"find","img[lazyload]"),b.length)){return this}b.each(function(){this.src=this.getAttribute("lazyload");this.removeAttribute("lazyload")})}})})(gmu);(function(a){a.Slider.options.imgZoom=true;a.Slider.option("imgZoom",function(){return !!this._options.imgZoom},function(){var e=this,b=e._options.imgZoom,c;b=typeof b==="string"?b:"img";function d(){c&&c.off("load"+e.eventNs,f)}function g(){d();c=e._container.find(b).on("load"+e.eventNs,f)}function f(i){var h=i.target||this,j=Math.min(1,e.width/h.naturalWidth,e.height/h.naturalHeight);h.style.width=j*h.naturalWidth+"px"}e.on("ready dom.change",g);e.on("width.change",function(){c&&c.each(f)});e.on("destroy",d)})})(gmu);
