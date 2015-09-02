$(function(){	//弹窗
    $.widget('m2o.pop',{
        options : {
            'tab-item' : '.tab-item',
            'control-item' : '.control-item',
            'tab-item' : '.tab-item',
            'active' : 'active',

            titleTpl : null,
            titleTname : 'pop-title-tpl',

            tabUrl : '',
            tabContentUrl : '',
            tabControlTpl : null,
            tabContentBoxTpl : null,
            tabContentTpl : null,
            tabContentTname : 'pop-tab-content-tpl',

            saveUrl : ''
        },

        _create : function(){
            $.template(this.options.titleTname, this.options.titleTpl);
            $.template(this.options.tabContentTname, this.options.tabContentTpl);
            var widget = this.element;
            this.titleBox = widget.find('.title-list');
            this.tabControl = widget.find('.tab-control');
            this.tabContent = widget.find('.tab');
            this.typeBox = widget.find('.info-list');
            this.typeItems = this.typeBox.find('li');
        },

        _init : function(){
            var op = this.options,
                handlers = {};
            handlers['click' + op['control-item']] = '_tabControl';
            this._on(handlers);
            this._drop();

            this._on({
                'click .pop-save-button' : '_save',
                'click .pop-close-button2' : '_close',
                'click .del' : '_delTitleClick',
                'dblclick .info-list .title' : '_resetType'
            });
        },

        _initTemplate : function(){
            if(this._initTemplateState){
                return;
            }
            this._initTemplateState = true;
            var _this = this;
            $.globalAjax(this.tabControl, function(){
                return $.getJSON(
                    _this.options.tabUrl,
                    function(json){
                    	  if( !json ){
                    	  	_this.tabControl.find('ul').text('无');
                    	  	return;
                    	  }
                		 _this._initTabControl(json);
                         _this._initTabContentBox(json);
                         _this.tabControl.find('li:first').trigger('click');
                    }
                );
            });
        },

        _initTabControl : function(json){
            $(this.options.tabControlTpl).tmpl(json).appendTo(this.tabControl.find('ul'));
        },

        _initTabContentBox : function(json){
            $(this.options.tabContentBoxTpl).tmpl(json).appendTo(this.tabContent);
        },

        _ajaxTabContent : function( self , id ){
            var _this = this;
            if(!id){
            	$('.switch_list ul').empty().html('<li style="text-align:left;">无</li>');
            	return false;
            }
            var wait = $.globalLoad( self );
            var item = this._getWhichTabContent(id),
            	target = item.closest('.tab');
            $.globalAjax(target, function(){
                return $.getJSON(
                        _this.options.tabContentUrl,
                        {sort_id : id},
                        function(json){
                            _this._tabContent(id, json);
                            wait();
                        }
                    );
            });
        },

        _tabContent : function(id, list){
            var item = this._getWhichTabContent(id);
            var box = item.find('ul').empty();
            var _this = this;
            if(list[0]){
//                $.map(list, function(obj){
//                    obj['img'] = (obj['pic'] && !$.isArray(obj['pic'])) ? $.globalImgUrl(obj['pic'], '90x108') : '';
//                    var ext = 'site_id=' + siteId + '&page_id=0&page_data_id=0&content_type=0&template_id=' + obj['id'];
//                    obj['yushe'] = './magic/main.php?gmid=168&ext=' + encodeURIComponent(ext) + '&bs=p';
//                    obj['yulan'] = './magic/magic.php?a=preview&ispreset=1&site_id=1&page_id=0&page_data_id=0&content_type=0&template_id='+ obj['id'] +'&uniqueid=publishsys';
//                });
//                $.tmpl(this.options.tabContentTname, list).appendTo(box);
//                this._switchable(item);
//                this._drag(item);
//                item.find().hide();
                	var doList = function(){
        		　　　　var dtd = $.Deferred(); //在函数内部，新建一个Deferred对象
        		　　　　var tasks = function(){
        		　　　　　　 $.map(list, function(obj){
        			 			obj['img'] = (obj['pic'] && !$.isArray(obj['pic'])) ? $.globalImgUrl(obj['pic'], '90x108') : '';
                    			var ext = 'site_id=' + siteId + '&page_id=0&page_data_id=0&content_type=0&template_id=' + obj['id'];
                    			obj['yushe'] = './magic/main.php?gmid=168&ext=' + encodeURIComponent(ext) + '&bs=p';
                    			obj['yulan'] = './magic/magic.php?a=preview&ispreset=1&site_id=1&page_id=0&page_data_id=0&content_type=0&template_id='+ obj['id'] +'&uniqueid=publishsys';
                		   });
        		 		   $.tmpl(_this.options.tabContentTname, list).appendTo(box);
        		　　　　　　 dtd.resolve(); // 改变Deferred对象的执行状态
        		　　　　};
        			　 tasks();
        		　　　　return dtd.promise(); // 返回promise对象
        			};
        		　　$.when(doList())
        		　　.done(function(){ 
        				if( box.find('li').length > 6 ){
        					_this._switchable(item);
        				}
    	                _this._drag(item);
        		　　})
        　　		   .fail(function(){ alert("error！"); });
            }else{
                box.html('<li style="text-align:left;">无</li>');
            }
        },
	
        _getWhichTabContent : function(id){
            return this.tabContent.find(this.options['tab-item'] + '[data-id="' + id + '"]');
        },

        _switchable : function(container){
            container.hg_switchable({
                autoplay:true,
                triggers:true,
                steps:6,
                visible:6,
                loop : false,
                end2end : true
            });
        },

        _tabControl : function(event){
            var target = $(event.currentTarget);
            var op = this.options;
            var id = target.data('id');
            var active = op['active'];
            target.addClass( active ).siblings().removeClass( active );
            var item = this._getWhichTabContent(id);
            item.show().siblings().hide();
            if(!item.data('ajax')){
                item.data('ajax', true);
                this._ajaxTabContent( target , id );
            }
        },

        _drag : function(item){
            (item || this.element).find('.switch_list li').draggable({
                appendTo : 'body',
                helper : function(){
                    var $this = $(this);
                    return '<img data-id="' + $this.data('id') + '" data-title="' + $this.data('title') + '" src="' + $this.find('img').attr('src') + '" class="drag-helper"/>';
                },
                revert : true
            });
        },

        _drop : function(){
            var _this = this;
            this.element.find('.info-list li').droppable({
                activeClass : 'active',
                tolerance : 'pointer',
                drop : function(event, ui){
                    var $hr = ui.helper;
                    var id = $hr.data('id');
                    var title = $hr.data('title');
                    var $this = $(this);
                    _this._refreshType($this, {
                        id : id,
                        title : title
                    });
                    $hr.remove();
                    $this.triggerHandler('_class', [false]);
                },
                over : function(event, ui){
                    $(this).triggerHandler('_class', [true]);
                },
                out : function(){
                    $(this).triggerHandler('_class', [false]);
                }
            }).on({
                    _class : function(event, which){
                        $(this)[which ? 'addClass' : 'removeClass']('on');
                    }
                });
        },

        _save : function(event){
            var _this = this;
            var titles = [];
            //var pageIds = [];
            this.titleBox.find('li').each(function(){
                var id = $(this).data('id');
                var info = _this.titles[id];
                id = id.replace(/_[^_]*$/, '');

                //pageIds.push(id);
                titles.push({
                    id : id,
                    title : info['title'],
                    full_title : info['allTitle']
                });
            });

            var types = [];
            this.typeItems.each(function(){
                var $this = $(this);
                var tid = $this.attr('data-tid') || '';
                if(!tid) return;
                var id = $this.data('id');
                types.push({content_type : id, sign : tid});
            });

            var postData = {
                title : titles,
                page : types
            };
            if(MC.groupId){
                postData['group_id'] = MC.groupId;
            }
            var _this = this;
            var cb = function(gid, isg){
                return function(info){
                    /*if(isg){
                        MC.list.list('updateGlobal', info);
                    }else{
                        MC.list.list('update', gid, info, titles);
                    }*/
                	if( info['page_title'] && info['page'] ){
                		 MC.list.list('refresh', info['page_title'], info['page']);
                	}
                    if( info['site'] && info['site'][clientType]){
                    	MC.list.list('updateGlobal', info['site'][clientType]);
                    }
                };
            }(MC.groupId, MC.isGlobal);
            $.globalAjax(event.currentTarget, function(){
                return $.post(
                    _this.options.saveUrl,
                    {data : JSON.stringify(postData)},
                    function(json){
                        cb(json[0]);
                        _this._close();
                    },
                    'json'
                );
            });

        },

        _close : function(){
            this._clearTitles();
            this._clearTypes();
            this.hide();
        },

        addTitle : function(id, title, allTitle){
            this._addTitle(id, title, allTitle);
            this.show();
        },

        delTitle : function(id){
            this._delTitle(id);
        },

        _titles : function(titles){
            var _this = this;
            titles && $.each(titles, function(index, item){
                _this._addTitle(item['id'], item['title'], item['allTitle']);
            });
        },

        _clearTitles : function(){
            this.titles = {};
            this.titleBox.empty();
            this.options.nav.nav('reset');
        },

        _addTitle : function(id, title, allTitle){
            this.titles = this.titles || {};
            if(this.titles[id]) return;
            this.titles[id] = {
                title : title,
                allTitle : allTitle
            };
            $.tmpl(this.options.titleTname, {id : id, title : title, allTitle : allTitle}).appendTo(this.titleBox);
            this.refreshPP();
        },

        _delTitle : function(id, item){
            delete this.titles[id];
            if($.isEmptyObject(this.titles)){
                this.hide();
            }
            (item || this.titleBox.find('li[data-id="' + id + '"]')).remove();
        },

        _delTitleClick : function(event){
            var item = $(event.currentTarget).closest('li');
            var id = item.data('id');
            this._delTitle(id, item);
            this.options.nav.nav('unselected', id);
        },

        _refreshTypes : function(info){
            var _this = this;
            this.typeItems.each(function(){
                var $this = $(this);
                var id = $this.data('id');
                var _info = info ? info[id] : '';
                var tmpInfo = {
                    id : _info ? _info['id'] : '',
                    title : _info ? _info['title'] : ''
                };
                _this._refreshType($this, tmpInfo);
            });
        },

        _refreshType : function(item, info){
            item.attr('data-tid', info['id']).attr('title', info['title']);
            item.find('input').val(info['title']);
        },

        _resetType : function(event){
            this._refreshType($(event.currentTarget).closest('li'), {id : '', title : ''});
        },

        _getType : function(id){
            return this.typeItems.find('li[data-id="' + id + '"]');
        },

        _clearTypes : function(){
            this._refreshTypes();
        },

        _pages : function(pages){
            this._refreshTypes(pages);
        },

        refresh : function(titles, pages){
            !titles.length && (titles = [titles]);
            this._clearTitles();
            this._titles(titles);
            this._pages(pages);
            this.refreshPP();
            this.show();
        },

        refreshPP : function(){
            var topDoc = $(window.top.document);
            var topDocT = topDoc.scrollTop();
            var mainIframe = topDoc.find('#mainwin');
            var mainT = mainIframe.offset().top;
            var mainH = mainIframe.height();
            var selfH = this.element.outerHeight(true);
            var top = 100;
            if(topDocT > mainT + top){
                top = topDocT - mainT;
                if(top + selfH > mainH){
                    top = mainH - selfH;
                }
            }
            this.element.css({
                top : top + 'px'
            });
        },

        show : function(){
            this.element.show();
            this._initTemplate();
        },

        hide : function(){
            this.element.hide();
            MC.groupId = 0;
            MC.isGlobal = false;
        },
        
        _destroy: function() {
        }
        
    });



    $.widget('deploy.nav', {
        options : {
            tpl : null,
            tname : 'nav-tpl',
            url : ''
        },

        _create : function(){
            $.template(this.options.tname, this.options.tpl);
            this._root();
        },

        _init : function(){
            this._on({
                'click .hook' : '_stretch',
                'click .title' : '_selected',
                'click .title a' : '_magic'
            });
        },

        _root : function(){
            var _this = this;
            var parent = _this.element.find('ul').eq(0);
            $.globalAjax(parent, function(){
                return _this._ajax('site' + siteId, parent);
            });
        },

        _ajax : function(fid, parent, cb){
            var _this = this;
            return $.getJSON(
                _this.options.url,
                {fid : fid},
                function(json){
                    _this._ajaxBack(json, parent);
                    cb && cb();
                }
            );
        },

        _ajaxBack : function(info, parent){
            info && $.tmpl(this.options.tname, info).appendTo(parent.empty());
            this._refreshHasset();
        },

        _stretch : function(event){
            var item = $(event.currentTarget).closest('li');
            var cname = 'stretch-list';
            if(item.hasClass(cname)){
                item.removeClass(cname);
                item.find('ul').hide();
            }else{
                item.addClass(cname);
                if(item.data('ajax')){
                    item.find('ul').show();
                }else{
                    item.data('ajax', true);
                    this._appendBox(item);
                    this._ajax(item.data('id'), item.find('ul'), function(){

                    });
                }
            }
        },

        _appendBox : function(parent){
            $('<ul><li class="no-child"><img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/></li></ul>').appendTo(parent);
        },

        _selected : function(event){
            if(!(event.ctrlKey || event.metaKey)){
                return;
            }
            if(MC.isGlobal){
                return;
            }
            var target = $(event.currentTarget);
            var item = target.closest('li');
            var id = item.data('id');
            var hasSet = this.options.list.list('getById', id);
            if(hasSet[0]){
                hasSet.trigger('click');
                return false;
            }
            var name = item.data('name');
            var pname = this.getParentsName(item);
            pname.push(name);
            var namelj = pname.join(' > ');
            var cname = 'selected';
            if(!target.hasClass(cname)){
                target.addClass(cname);
                this.options.pop.pop('addTitle', id, name, namelj);
            }else{
                target.removeClass(cname);
                this.options.pop.pop('delTitle', id);
            }
        },

        _magic : function(event){
            var target = $(event.currentTarget);
            MC.magic.triggerHandler('show', [target]);
            target.closest('.title').addClass('on');
            return false;
        },

        emptyMagic : function(){
            this.element.find('.on').removeClass('on');
        },

        unselected : function(id){
            this.element.find('li[data-id="' + id + '"]').find('.title').removeClass('selected');
        },

        getParentsName : function(item){
            var names = [];
            item.parents('li').each(function(){
                names.push($(this).data('name'));
            });
            return names.reverse();
        },

        reset : function(){
            var cname = 'selected';
            this.element.find('.' + cname).removeClass(cname);
        },

        refreshHasset : function(ids){
            this.hasIds = ids;
            this._refreshHasset();
        },

        _refreshHasset : function(){
            var ids = this.hasIds;
            if(!ids) return;
            var widget = this.element;
            var cname = 'hasset';
            widget.find('li.' + cname).removeClass(cname);
            widget.find('li').each(function(){
                var $this = $(this);
                var id = $this.data('id');
                if($.inArray(id, ids) != -1){
                    $this.addClass(cname);
                }
            });
        },

        _destroy : function(){

        }
    });

    $.widget('deploy.list', {
        options : {
            titles : null,
            pages : null,
            tpl : null,
            tname : 'list-each-tpl'
        },

        _create : function(){
            var _this = this;
            $.template(this.options.tname, this.options.tpl);
            this.listBox = this.element.find('#list-box');
            this._refresh();
        },

        _refresh : function(){
            var _this = this;
            var titles = this.options.titles;
            var pages = this.options.pages;
            var infos = this.infos = {};
            $.each(titles, function(i, n){
                var info =infos[i] = {};
                info['key'] = i;
                info['titles'] = n;
                info['pages'] = pages[i];
                $.tmpl(_this.options.tname, info).appendTo(_this.listBox);
            });
            var ids = [];
            $.each(titles, function(i, n){
                n && $.each(n, function(ii, nn){
                    ids.push(nn['id'] + '_' + nn['title']);
                });
            });
            this.options.nav.nav('refreshHasset', ids);
        },

        refresh : function(titles, pages){
            this.listBox.empty();
            this.options.titles = titles;
            this.options.pages = pages;
            this._refresh();
        },

        _init : function(){
            this._on({
                'click .temp-title>a' : '_edit',
                'click .temp-item:not(.temp-global) .temp-title li' : '_danEdit'
            });

            this.element.tooltip({
                items : '.temp-item:not(.temp-global) .temp-title span',
                content : '点击单独设置',
                //show : false,
                //hide : false,
                track : true
                /*position : {
                    my : 'left top-30',
                    at : 'left top'
                }*/
            });
        },

        _createItem : function(info){
            return $.tmpl(this.options.tname, info);
        },

        _edit : function(event){
            var item = $(event.currentTarget).closest('.temp-item');
            if(item.attr('type') == 'global'){
                MC.isGlobal = true;
            }else{
                MC.isGlobal = false;
            }
            var key = item.data('key');
            MC.groupId = key;
            var titles = this._getTitle(item);
            var pages = this._getPage(item);
            this.options.pop.pop('refresh', titles, pages);
        },

        _getTitle : function(item, only){
            var titles = [];
            (only ? item : item.find('.temp-title li')).each(function(){
                titles.push({
                    id : $(this).attr('data-id'),
                    title : $(this).attr('data-title'),
                    allTitle : $(this).attr('data-allTitle')
                });
            });
            return titles;
        },

        _getPage : function(item){
            var pages = {};
            item.find('.temp-content li').each(function(){
                pages[$(this).attr('data-id')] = {
                    id : $(this).attr('data-tid'),
                    title : $(this).attr('data-tname')
                };
            });
            return pages;
        },

        _danEdit : function(event){
            var target = $(event.currentTarget);
            var titles = this._getTitle(target, true);
            var pages = this._getPage(target.closest('.temp-item'));
            MC.groupId = 0;
            MC.isGlobal = false;
            this.options.pop.pop('refresh', titles, pages);
        },

        update : function(gid, info, titles){
            if(!gid){
                this._updateAdd(info, titles);
            }else{
                this._updateEdit(gid, info, titles);
            }
        },

        _updateAdd : function(info, titles){
            this._createItem({
                key : 0,
                titles : titles,
                pages : info
            }).appendTo(this.listBox);
        },

        _updateEdit : function(gid, info, titles){
            this.listBox.find('.temp-item[data-key="' + gid + '"]').replaceWith(this._createItem({
                key : gid,
                titles : titles,
                pages : info
            }));
        },

        updateGlobal : function(info){
            this.element.find('.temp-global .temp-content li').each(function(){
                var $this = $(this);
                var id = $this.attr('data-id');
                var _info = info ? info[id] : '';
                var sign = _info ? _info['template_sign'] : '';
                var name = _info ? _info['template_name'] : '';
                $this.attr('data-tid', sign).attr('data-tname', name);
                $this.find('span').html(name);
            });
        },

        getById : function(id){
            return this.element.find('.temp-title li[data-id="' + id + '"]');
        },

        _destroy : function(){

        }
    });


    var MC = {
        pop : $('#pop'),
        nav : $('.temp-nav'),
        list : $('.temp-box'),
        magic : $('#magic-box'),
        template : $('.template-main'),
        search : $('.search-area'),
        search_result : $('.search-result'),
        groupId : 0,
        isGlobal : false
    };
    MC.pop.pop({
        titleTpl : $('#pop-title-item-tpl').html(),
        tabUrl : 'run.php?mid=' + gMid + '&a=get_tem_sort&site_id=' + siteId + '&client_type=' + clientType,
        tabContentUrl : 'run.php?mid=' + gMid + '&a=get_tem&site_id=' + siteId + '&client_type=' + clientType,
        tabControlTpl : '#pop-tab-control-tpl',
        tabContentBoxTpl : '#pop-tab-content-box-tpl',
        tabContentTpl : $('#pop-tab-content-tpl').html(),
        nav : MC.nav,
        saveUrl : 'run.php?mid=' + gMid + '&a=update_tem',
        list : MC.list
    });
    MC.nav.nav({
        tpl : $('#nav-item-tpl').html(),
        url : 'run.php?mid=' + gMid + '&a=get_deploy_node',
        pop : MC.pop,
        list : MC.list
    });
    MC.list.list({
        titles : titles,
        pages : pages,
        tpl : $('#list-each-tpl').html(),
        pop : MC.pop,
        nav : MC.nav
    });
    

    MC.magic.on({
        show : function(event, target){
            var $target = $(target);
            var $this = $(this).show();
            var pp = $target.offset();
            var tHeight = $target.outerHeight();
            var sHeight = $this.outerHeight();
            var left = pp.left;
            var top = pp.top + tHeight;
            var dHeight = $(document).height();
            if(top + sHeight > dHeight){
                top = pp.top - sHeight;
            }
            $this.css({
                left : left + 'px',
                top : top + 'px'
            });

            /*!$this.data('dclick') && $this.data('dclick', function(event){
                var target = $(event.target);
                if(target[0] == MC.magic[0] || target.closest('#magic-box').length){
                    return false;
                }
                MC.magic.triggerHandler('hide');
                $(document).off('mousedown.dclick');
            });
            $(document).on('mousedown.dclick', $this.data('dclick')); */

            var _this = this;
            $(document).one({
                'mousedown' : function(event){
                    if(event.target != _this && !$.contains(_this, event.target)){
                        $(_this).triggerHandler('hide');
                    }
                },

                'click' : function(event){
                    if(event.target == _this || $.contains(_this, event.target)){
                        $(_this).triggerHandler('hide');
                    }
                }
            });

            var info = $target.closest('li').data('id');
            var match = (info || '').match(/\d+_\d*/);
            var pageid = 0;
            var pagedataid = 0;
            if(match){
                match = match[0].split('_');
                pageid = match[0];
                pagedataid = match[1] || 0;
            }
            var ext = 'site_id=' + siteId + '&page_id=' + pageid + '&page_data_id=' + pagedataid;
            $this.find('a').each(function(){
            	var index = $(this).data( 'index' );
                $(this).attr('href', './magic/' + index + '.php?gmid=412&ext=' + encodeURIComponent(ext + '&content_type=' + $(this).data('type')));
            });
        },

        hide : function(){
            $(this).hide();
            MC.nav.nav('emptyMagic');
        }
    });
    
    MC.template.pop({
        tabUrl : 'run.php?mid=' + gMid + '&a=get_tem_sort&site_id=' + siteId + '&client_type=' + clientType,
        tabContentUrl : 'run.php?mid=' + gMid + '&a=get_tem&site_id=' + siteId + '&client_type=' + clientType,
        tabControlTpl : '#pop-tab-control-tpl',
        tabContentBoxTpl : '#pop-tab-content-box-tpl',
        tabContentTpl : $('#pop-tab-content-tpl').html(),
        nav : MC.nav,
        list : MC.list
    });
    
    MC.template.pop( 'refresh',[] );
    
    MC.template.on( 'click','.toggle-btn',function(){
    	var self = $(this),
    		tab = self.closest('.tab-control');
    	if( self.hasClass('up') ){
    		tab.removeAttr('style');
    		self.removeClass('up');
    	}else{
    		self.addClass('up');
    		tab.css( {'height': 'auto'} );
    	}
    } );
    
    MC.template.on( 'click','.search-btn',function(){
    	var key = MC.search.find( 'input' ).val(),
    		tabContentUrl = 'run.php?mid=' + gMid + '&a=get_tem&site_id=' + siteId + '&client_type=' + clientType + '&k=' + key;
    	if( $.trim( key ) ){
        	MC.template.addClass('search-model');
        	MC.search_result.empty();
    		$('#pop-tab-content-box-tpl').tmpl( {id:''} ).appendTo( MC.search_result );
        	$.getJSON( tabContentUrl, function( json ){
        		var box = MC.search_result.find('ul').empty();
        		if(json){
                    $.map(json, function(obj){
                        obj['img'] = (obj['pic'] && !$.isArray(obj['pic'])) ? $.globalImgUrl(obj['pic'], '90x108') : '';
                        var ext = 'site_id=' + siteId + '&page_id=0&page_data_id=0&content_type=0&template_id=' + obj['id'];
                        obj['yushe'] = './magic/main.php?gmid=168&ext=' + encodeURIComponent(ext) + '&bs=p';
                        obj['yulan'] = './magic/magic.php?a=preview&ispreset=1&site_id=1&page_id=0&page_data_id=0&content_type=0&template_id='+ obj['id'] +'&uniqueid=publishsys';
                    });
                    $('#pop-tab-content-tpl').tmpl(json).appendTo(box);
                    MC.search_result.hg_switchable({
                        autoplay:false,
                        triggers:true,
                        steps:8,
                        visible:8,
                        end2end : false
                    });
                }else{
                    box.html('<li style="text-align:left;">无</li>');
                }
        	} );
            
    	}
    	
    } );
    
    MC.template.on( 'click','.search-cancel',function(){
    	var self = $(this);
    	MC.template.removeClass('search-model');
    	MC.search.find( 'input' ).val('');
    	MC.search_result.empty();
    } );


});