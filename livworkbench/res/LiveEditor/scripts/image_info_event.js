function ImageInfoEvent(number, slide, imgdir){
    this.number = number;
    this.slide = slide;
    this.imgdir = imgdir;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = null;
    this.styles = null;
    this.suoWidth = 160;
    this.bigWidth = 640;
    this.init();
}

jQuery.extend(ImageInfoEvent.prototype, {
    init : function(){
        this.slide.html(this.content());
        this.initStyles();

        this.box = $('#edit-slide-image-info'+ this.number);
        var self = this;
        this.box.on('set', function(event, data){
            $(this).data('imageInfo', data);
            var suosrc, bigsrc;

            var isLocalNotAttach = false; //此图片已经本地话，但是已经不在图片附件中，可能被删除了
            if(data['id']){
                if(data['src']){
                    isLocalNotAttach = true;
                    suosrc = bigsrc = data['src'];
                }else{
                    suosrc = self.getSrc(data, 'suo', true);
                    bigsrc = self.getSrc(data, 'big');
                }
            }else{
                suosrc = bigsrc = data['src'];
            }

            var html = '';
            html += '<div class="image-info-item">'+
            '<div class="item-box" style="background:transparent;">'+
            '<div class="item-inner-box">'+
            '<img class="image" _src="'+ suosrc + Math.ceil(Math.random() * 10000) +'" '+ (data['id'] ? 'imageid="'+ data['id'] +'"' : '') + ' />'+
            '</div>'+
            '</div>';

            /*'<div class="image-info-cdbox">'+
            '<button class="image-info-change">换图片</button>'+
            '<button class="image-info-delete">删除</button>'+
            '</div>'+*/
            if(!data['id']){
                html += '<div class="image-info-outer">外部图片</div>';
            }else if(isLocalNotAttach){
                html += '<div class="image-info-outer">此图片已经经过本地化，但是却不在图片附件中，可能被删除了</div>';

                setTimeout(function(){
                    $('#image-tip-box' + self.number).find('.image-tip-left, .image-tip-right').hide();
                }, 100);

            }else{
                html += '<div class="image-self-edit" style="background:rgba(0, 0, 0, .5);color:#fff;width:100px;text-align:center;padding:5px 0;cursor:pointer;margin:0 auto;">编辑图片</div>';
            }

            html += '</div>';

            html += '<div class="image-info-item image-info-jiben">'+
            '<div><span>图片：</span><input type="text" name="image-info-src" class="image-info-input '+ (!data['id'] ? 'image-info-outer-tip' : '') +'" value="'+ bigsrc +'"/></div>'+
            '<div><span>链接：</span><input type="text" name="image-info-href" class="image-info-input" value="'+ (data['href'] || '') +'"/></div>'+
            '<div><span>标题：</span><input type="text" name="image-info-alt" class="image-info-input" value="'+ (data['alt'] || '') +'"/><br/><label class="image-info-set-all" style="margin:8px 0 0 35px;display:inline-block;color:green;">标题应用到全部图</label></div>'+
            '</div>';

            html += '<div class="image-info-item">'+
            '<div><span>缩放：</span><span class="width-slider"></span><span class="image-info-width">'+ data['width'] +'</span>x<span class="image-info-height">'+ data['height'] +'</span></div>'+
            '</div>';

            var position = '';
            $.each(['middle', 'left', 'right'], function(i, n){
                position += '<img '+ (n == data['position'] ? 'class="current"' : '') +' src="'+ self.imgdir +'position-'+ n +'.png" type="'+ n +'"/>';
            });
            html += '<div class="image-info-item image-info-position">'+
            '<div><span>版式：</span>'+ position +'</div>'+
            '</div>';

            html += '<div class="image-info-item">'+
            '<div><span>间距：</span><span class="margin-slider"></span><span class="image-info-margin">值：<input type="text" readonly="readonly" style="width:25px;" value="0"/></span></div>'+
            '</div>';

            var styles = '';
            $.each(self.styles, function(i, n){
                styles += '<td '+ (i == data['_style'] ? 'class="current"' : '') +'><img class="image-info-style" src="'+ suosrc + '" _style="'+ i +'" style="'+ n +'" width="45"/></td>';
            });
            html += '<div class="image-info-item"><div><span style="display:none;">边框：</span>'+
            '<table class="image-info-style-table"><tr>'+ styles +'</tr></table>'+
            '</div></div>';

            var content = $(this).find('.edit-slide-image-info-content').html(html);

            var box = content.find('.item-box:first');

            var boxWidth = box.width();
            var boxHeight = box.height();
            content.find('.image').each(function(){
                var self = this, src = $(this).attr('_src');
                var img = new Image();
                img.onload = function(){
                    var width = this.width;
                    var height = this.height;
                    var pw = this.width / boxWidth;
                    var ph = this.height / boxHeight;
                    if(pw >= 1 && pw >= ph ){
                        self.width = boxWidth;
                    }
                    if(ph >= 1 && ph >= pw){
                        self.height = boxHeight;
                    }
                    self.src = src;
                    $(self).show().next().remove();
                }
                img.src = src;
            });

            content.find('.image-info-input').each(function(){
                $(this).data('oldval', $(this).val());
            });

            var widthSlider = content.find('.width-slider').slider({
                animate: true,
                min: 10,
                max: data.oldwidth,
                step: 10,
                value: data.width,
                slide: function(event, ui) {
                    var width = ui.value;
                    var max = widthSlider.data('max');
                    var height = parseInt(max['maxHeight'] * (width / max['maxWidth']), 10);
                    var parent = $(this).parent();
                    parent.find('.image-info-width').text(width);
                    parent.find('.image-info-height').text(height);

                    currentSet(function(current){
                        var style = current.attr('style');
                        var newStyle = '';
                        if(style){
                            $.each(style.split(';'), function(i, n){
                                n = n.split(':');
                                var s = $.trim(n[0]);
                                var p = $.trim(n[1]);
                                if(s && p && s != 'width' && s != 'height'){
                                    newStyle += s + ':' + p + ';';
                                }
                            });
                        }
                        if(newStyle){
                            current.attr('style', newStyle);
                        }else{
                            current.removeAttr('style');
                        }
                        current.removeAttr('width height').attr('width', width);
                    });
                }
            }).data('max', {
                maxWidth : data.oldwidth,
                maxHeight : data.oldwidth / data.width * data.height
            });

            content.find('.margin-slider').slider({
                animate: true,
                min: 0,
                max: 50,
                step: 1,
                value: 0,
                slide: function(event, ui) {
                    var value = ui.value;
                    $(this).parent().find('input:text').val(value);
                    setMargin(value);
                }
            });

            /*content.find('.image-info-input').focus(function(){
                var me = $(this);
                setTimeout(function(){
                    me.select().attr('title', me.val());
                }, 0);
            });*/

            self.slide.open(true);

            self.editPic();

        }).on('blur', '.image-info-input', function(){
            var val = $(this).val();
            if($(this).data('oldval') == val){
                return;
            }
            $(this).data('oldval', val);
            var name = $(this).attr('name').replace('image-info-', '');
            currentSet(function(current){
                if(name == 'href'){
                    if(current.parent().is('a')){
                        current.parent().attr('href', val);
                    }else{
                        current.wrap('<a href="'+ val +'" target="_blank"></a>');
                    }
                }else{
                    current.attr(name, val);
                    if(name == 'alt'){
                        current.attr('title', val);
                    }
                }
            });
        }).on('click', '.image-info-position img', function(){
            var type = '';
            if($(this).hasClass('current')){
                $(this).removeClass('current');
                type = 'none';
            }else{
                $(this).siblings('.current').removeClass('current');
                $(this).addClass('current');
                type = $(this).attr('type');
            }
            $(this).closest('.image-info-position').data('type', type);
            setPosition(type);
        }).on('click', '.image-info-style-table td', function(){
            var style = '', _style = '', _defaultStyle = 'none';
            if($(this).hasClass('current')){
                $(this).removeClass('current');
            }else{
                $(this).siblings('.current').removeClass('current');
                var img = $(this).find('img');
                style = img.attr('style');
                _style = img.attr('_style');
                $(this).addClass('current');
            }
            currentSet(function(current){
                current.attr('_style', (_style || _defaultStyle));
                current.removeAttr('style').attr('style', style);
                setPosition();
                setMargin();
            });
        });

        this.box.on({
            click : function(){
                var title = $(this).parent().find('.image-info-input').val();
                $(self.editorWindow.document).find('img.image').attr('alt', title).attr('title', title);
            }
        }, '.image-info-set-all');

        function setPosition(type){
            if(type === undefined){
                var position = self.box.find('.image-info-position img.current');
                if(position[0]){
                    type = position.attr('type');
                }else{
                    type = 'none';
                }
            }
            currentSet(function(current){
                if(type == 'left' || type == 'right'){
                    current.css({
                        'float' : type
                    });
                }else if(type == 'middle'){
                    var parent = current.parent();
                    if(!parent[0]){
                        current.wrap('<div style="text-align:center;"></div>');
                    }else{
                        parent.css('text-align', 'center');
                    }
                    current.css({
                        'float' : 'none'
                    });
                }else if(type == 'none'){
                    current.css({
                        'float' : 'none'
                    });
                }
            });
        }

        function setMargin(value){
            if(value === undefined){
                value = self.box.find('.margin-slider').slider('value');
                if(!value) return;
            }
            currentSet(function(current){
                var type = self.box.find('.image-info-position').data('type') || 'none';
                if(type == 'middle'){
                    current.css({
                        'margin-top' : value + 'px',
                        'margin-bottom' : value + 'px'
                    });
                }else if(type == 'left'){
                    current.css({
                        'margin-right' : value + 'px',
                        'margin-bottom' : value + 'px'
                    });
                }else if(type == 'right'){
                    current.css({
                        'margin-left' : value + 'px',
                        'margin-bottom' : value + 'px'
                    });
                }else if(type == 'none'){
                    current.css({
                        'margin-left' : value + 'px',
                        'margin-right' : value + 'px'
                    });
                }
            });
        }

        function currentSet(callback){
            var tip = $('#image-tip-box' + self.number),
                current = tip.data('current-image');
            if(current && callback){
                callback(current);
                tip.trigger('resize.image-tip');
            }
        }
    },
    initStyles : function(){
        /*this.styles = {
            style1 : '',
            style2 : 'border:#fff 7px solid;-webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);-moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);',
            style3 : 'border:#fff 7px solid;-webkit-box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);-moz-box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);',
            style4 : '-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;',
            style5 : 'border:#fff 7px solid;-webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);-moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);-moz-border-radius:7px;border-radius:7px;',
            style6 : 'border:#fff 7px solid;-webkit-box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);-moz-box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);-moz-border-radius:7px;border-radius:7px;',
            style7 : 'padding: 5px;border: solid 1px #ddd;',
            style8 : 'padding: 5px;border: solid 1px #ddd;-webkit-border-radius: 50em;-moz-border-radius: 50em;border-radius: 50em;',
            style9 : 'padding: 5px;border: solid 1px #ddd;-webkit-box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5);-moz-box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5);box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5);'
        }*/
        this.styles = {
            style1 : 'padding: 5px;border: solid 1px #ddd;',
            style2 : '-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;',
            style3 : 'margin:1px 0 10px 0;border:#fff 5px solid;-webkit-box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);-moz-box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);'
        };
    },
    content : function(){
        return '<div id="edit-slide-image-info' + this.number +'" class="edit-slide-html-each">'+
        '<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>图片属性</div>'+
        '<div class="edit-slide-image-info-content edit-slide-content"></div>'+
        '</div>';
    },
    set : function(data){
        window['slideManage' + this.number].close("image-info");
        this.box.trigger('set', [data]);
    },
    bind : function(){
        /*var self = this;
        var body = $(this.editorWindow.document.body);
        if(!body.data('init-image-info')){

            $(this.editorWindow).on('scroll.image-tip', function(){
                $('#image-tip-box' + self.number).trigger('close');
            });

            body.on('mousedown', function(event){
                return;
                var target = event.target;
                target && (target = $(target));
                if(target.is('img')){
                    var cname = target.attr('class');
                    if(cname == 'pagebg' || cname == 'before-biaozhu-ok' || cname == 'after-biaozhu-ok'){

                    }else{
                        target.css('-webkit-user-select', 'none');
                    }
                }
            });

            body.on('click', function(event){
                var target = event.target;
                if(target){
                    target = $(target);
                    if(target.is('img') && !target.hasClass('pagebg') && !target.hasClass('before-biaozhu-ok') && !target.hasClass('after-biaozhu-ok') && !target.hasClass('image-refer')){
                        if(target.hasClass('image')){
                            self.set(clickImageIn(target));
                        }else{
                            self.set(clickImageOut(target));
                        }
                        $('#image-tip-box'+ self.number).data('current-image', target).show();
                        return;
                    }
                }
                $('#image-tip-box'+ self.number).data('current-image', null).hide();
                window['slideManage' + self.number].closeOne('image-info');
            });

            function clickImageIn(target){
                var info = {
                    id : target.attr('imageid'),
                    title : target.attr('title'),
                    width : parseInt(target.css('width'), 10),
                    height : parseInt(target.css('height'), 10),
                    oldwidth : target.attr('oldwidth')
                };
                if(target.parent().is('a')) {
                    info['href'] = target.parent().attr('href');
                }
                if(target.css('float') == 'left' || target.css('float') == 'right'){
                    info['position'] = target.css('float');
                }else{
                    if(target.css('display') == 'block'){
                        info['position'] = 'middle';
                    }
                }
                info['_style'] = target.attr('_style') || 'none';
                info['margin'] = {
                    top : parseInt(target.css('margin-top'), 10),
                    right : parseInt(target.css('margin-right'), 10),
                    bottom : parseInt(target.css('margin-bottom'), 10),
                    left : parseInt(target.css('margin-left'), 10)
                };
                var classImg = $('#edit-slide-image' + self.number).find('img.image[imageid="'+ info['id'] +'"]');
                info['path'] = classImg.attr('path');
                info['dir'] = classImg.attr('dir');
                info['filename'] = classImg.attr('filename');
                return info;
            }

            function clickImageOut(target){
                var info = {
                    title : target.attr('title'),
                    width : parseInt(target.css('width'), 0),
                    height : parseInt(target.css('height'), 0),
                    src : target.attr('src')
                };
                info['oldwidth'] = info['width'];
                if(target.parent().is('a')) {
                    info['href'] = target.parent().attr('href');
                }
                if(target.css('float') == 'left' || target.css('float') == 'right'){
                    info['position'] = target.css('float');
                }else{
                    if(target.css('display') == 'block'){
                        info['position'] = 'middle';
                    }
                }
                info['_style'] = target.attr('_style') || 'none';
                info['margin'] = {
                    top : parseInt(target.css('margin-top'), 10),
                    right : parseInt(target.css('margin-right'), 10),
                    bottom : parseInt(target.css('margin-bottom'), 10),
                    left : parseInt(target.css('margin-left'), 10)
                };
                return info;
            }

            body.on('click', 'img', function(event, only){
                var imageTip = $('#image-tip-box' + self.number);
                if(!only && imageTip.data('current-image') == $(this)){
                    return;
                }
                var me = $(this);
                if(!imageTip[0]){
                    imageTip = createTipBox();
                }

                imageTip.trigger('init', [(me.attr('imageid') ? true : false), (me.attr('src').indexOf('http://') == 0 ? true : false)]);
                positionTip(me, imageTip);
            });

            function positionTip(me, imageTip){
                var moffset = me.offset();
                var mleft = moffset.left;
                var mtop = moffset.top;
                var mwidth = parseInt(me.width());
                var mheight = parseInt(me.height());
                var offset = $("#idContentoEdit" + self.number).offset();
                var ileft = offset.left;
                var itop = offset.top;
                var stop = body.scrollTop();

                var disTop = mtop - stop;
                var tipTop = itop + disTop;
                if(!imageTip){
                    imageTip = $('#image-tip-box' + self.number);
                }
                imageTip.css({
                    top : tipTop - 1 + 'px',
                    left : ileft + mleft - 1 + (mwidth - 150) + 'px',
                    width : 150 + 'px',
                    height : 0 + 'px',
                    border : 'none'
                }).find('.image-tip-option').css({
                    top : ((disTop < 0) ? -disTop : 10) + 'px'
                });
            }

            function createTipBox(){
                var box = $(
                    '<div class="image-tip-box" id="image-tip-box'+ self.number +'">'+
                        '<div class="image-tip-option" id="image-tip-option'+ self.number +'">'+
                            '<span class="image-tip-left"></span>'+
                            '<span class="image-tip-right"></span>'+
                            '<span class="image-tip-local" style="color:red;">本地化</span>'+
                            '<span class="image-tip-change">换图</span>'+
                            '<span class="image-tip-delete">去除</span>'+
                        '</div>' +
                    '</div>'
                ).appendTo('body');

                box.on('click', '.image-tip-left, .image-tip-right', function(){
                    var me = $(this);
                    var parent = me.closest('.image-tip-box');
                    if(parent.data('loading')){
                        return;
                    }
                    var target = parent.data('current-image');
                    if(target && target[0]){
                        var direction = me.hasClass('image-tip-left') ? -90 : 90;
                        var imageid = target.attr('imageid');
                        me.html('<img src="' + RESOURCE_URL + 'loading2.gif" width="14"/>').addClass('image-tip-loading');
                        parent.data('loading', true);
                        window['EditorImage' + self.number].event.rotate(imageid, direction, function(){
                            me.html('').removeClass('image-tip-loading').trigger('resize');
                            parent.data('loading', null);
                            self.slide.state() && (self.set(clickImageIn(target)));
                        });
                        return;
                    }
                });

                box.on('click', '.image-tip-local', function(){
                    var me = $(this);
                    var parent = me.closest('.image-tip-box');
                    if(parent.data('loading')){
                        return;
                    }
                    var target = parent.data('current-image');
                    if(target && target[0]){
                        var src = target.attr('src');
                        me.append('<img src="' + RESOURCE_URL + 'loading2.gif" width="14"/>').addClass('image-tip-loading');
                        parent.data('loading', true);
                        window['EditorLocalImage' + self.number](src, function(){
                            me.find('img').remove();
                            me.removeClass('image-tip-loading').trigger('resize');
                            parent.data('loading', null);
                            self.slide.state() && (self.set(clickImageIn(target)));
                        });
                    }
                });

                box.on('click', '.image-tip-change', function(){
                    if($(this).closest('.image-tip-box').data('loading')){
                        return;
                    }
                    window['slideManage' + self.number].openOne('image');
                });

                box.on('click', '.image-tip-delete', function(){
                    var parent = $(this).closest('.image-tip-box');
                    if(parent.data('loading')){
                        return;
                    }
                    var target = parent.data('current-image');
                    target && target[0] && target.remove();
                    parent.hide();
                    window['slideManage' + self.number].closeOne('image-info');
                    self.refresh();
                });

                box.on('close', function(){
                    if($(this).is(':visible')){
                        $(this).hide().data('current-image', null);
                        window['slideManage' + self.number].closeOne('image-info');
                    }
                });

                box.on('init', function(event, state, http){
                    $(this).find('.image-tip-left, .image-tip-right')[state ? 'show' : 'hide']();
                    $(this).find('.image-tip-local')[(!state && http) ? 'show' : 'hide']();
                });

                box.on('resize.image-tip', function(event){
                    var target = $(this).data('current-image');
                    if(target){
                        positionTip(target, $(this));
                        self.refresh();
                    }
                });

                box.on('click', function(event){
                    if(event.target != this){
                        return false;
                    }
                    var target = $(this).data('current-image');
                    target && target.trigger('click');
                });

                return box;
            }

            body.data('init-image-info', true);
        }*/
    },
    getSrc : function(data, type, force){
        if(type == 'suo'){
            type = this.suoWidth + 'x';
        }else{
            type = this.bigWidth + 'x';
        }
        //return data['path'] + type + '/' + data['dir'] + data['filename'] + (force ? ('?' + parseInt(Math.random() * 100000)) : '');
        return $.globalImgUrl(data, type, force);
    },

    selfRefresh : function(){
        this.box.trigger('set', [this.box.data('imageInfo')]);
    },

    refresh : function(){
        window['contentWindow' + this.number]('refresh');
    },
    editPic : function(){
        var selfEdit = $('.image-self-edit');
        selfEdit[0] && selfEdit.click(function(){
            var imageid = $(this).prev().find('.image').attr('imageid');
            var image = $('.edit-slide-image-content img.image[imageid="'+ imageid +'"]').trigger('mouseenter');
            $('#pic-edit-btn').trigger('click');
            image.trigger('mouseleave');
        });

        return;
        $('.image-self-edit').click(function(){
            if($(this).data('picEdit')) return;
            $(this).data('picEdit', true);
            var me = $(this);
            var imageId = 'slide-image';
            var imgSrc = me.attr('path') + me.attr('dir') + me.attr('filename');
            $(this).picEdit({
                type : 'click',
                imageId : imageId,
                imgSrc : imgSrc,
                saveAfter : function(){
                    top.$('body').find('img.tmp-edit-top-img').remove();
                    top.$('body').off('_picsave').on('_picsave', function(event, info){
                        try{
                            var topImg = $(this).find('#slide-image');
                            var img = $(this).find('#formwin')[0].contentWindow.$('.edit-slide-image-content img.image[imageid="'+ topImg.attr('imageid') +'"]');
                            info['filename'] += 'zfh';
                            img.attr({
                                path : info['host'] + info['dir'],
                                dir : info['filepath'],
                                filename : info['filename'],
                                src : info['host'] + info['dir'] + '160x/' + info['filepath'] + info['filename'],
                                bigsrc : info['host'] + info['dir'] + '640x/' + info['filepath'] + info['filename']
                            });
                            $(this).find('img.tmp-edit-top-img').remove();
                        }catch(e){}
                    }).append(me.clone().hide().attr('id', imageId).addClass('tmp-edit-top-img')).data('current-edit-image', imageId);
                }
            });
        });
    }
});



