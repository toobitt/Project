function ImageEvent(number, slide){
    this.number = number;
    this.slide = slide;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = null;
    this.option = null;
    this.styles = null;
    this.suoWidth = 160;
    this.bigWidth = 640;
    this.init();
}

jQuery.extend(ImageEvent.prototype, {
    init : function(){
        this.slide.html(this.content());
        this.box = $('#edit-slide-image' + this.number);
        var self = this;

        var isdelete = false;

        this.box.on('mouseenter', '.item-box', function(){
            if($(this).attr('_nooption')){
                return;
            }
            if(isdelete){
                return;
            }
            !$(this).hasClass('current') && $(this).addClass('current');
            $(this).append(self.option);
            $(this).find('.image-suoyintu').show();
            self.option.show();
        }).on('mouseleave', '.item-box', function(){
            if($(this).attr('_nooption')){
                return;
            }
            if(isdelete){
                return;
            }
            $(this).removeClass('current');
            $(this).find('.image-suoyintu').hide();
            self.option.hide();
        }).on('click', '.image', function(event){
            var info = {
                id : $(this).attr('imageid'),
                src : $(this).attr('bigsrc')
            };
            var html = '<img class="image" src="'+ info['src'] +'" oldwidth="'+ self.bigWidth +'" imageid="'+ info['id'] +'"/>';
            var tip = $('#image-tip-box' + self.number), currentImage;
            if(tip[0] && (currentImage = tip.data('current-image'))){
                mysaveForUndo();  //保存撤销
                var body = currentImage.closest('body');
                var index = body.find('img').index(currentImage[0]);
                currentImage.replaceWith(html);
                currentImage = body.find('img').eq(index);
                tip.data('current-image', currentImage);
                tip.triggerHandler('resize');
            }else{
                window['globalSlideInsertHtml' + self.number]('image', {
                    src : info['src'],
                    oldwidth : self.bigWidth,
                    id : info['id']
                });
            }
        }).on('set', function(event, data){
            var html = '';
            var initBigSrc = [];
            $.each(data, function(i, n){
                var suosrc = self.getSrc(n, 'suo');
                var bigsrc = self.getSrc(n, 'big');
                html += '<div class="item-box">'+
                '<div class="item-inner-box">'+
                '<img class="image" imageid="'+ n['material_id'] +'" _src="'+ suosrc +'" bigsrc="'+ bigsrc +'" path="'+ n['path'] +'" dir="'+ n['dir'] +'" filename="'+ n['filename'] +'" style="display:none;"/>'+
                '<img class="image-loading" src="' + RESOURCE_URL + 'loading2.gif" width="50"/>'+
                '</div>'+
                '<div class="nooption-mask"></div>'+
                '<div class="image-suoyintu"></div>'+
                '</div>';
                initBigSrc.push(bigsrc);
            });
            var content = $(this).find('.edit-slide-image-content').html(html);

            var box = content.find('.item-box:first');
            if(box[0]){
                var boxWidth = box.width();
                var boxHeight = box.height();
                content.find('.image').each(function(){
                    var image = $(this), src = image.attr('_src');
                    self.suoImg(src, boxWidth, boxHeight, function(type){
                        image.attr(type, type == 'width' ? boxWidth : boxHeight).attr('src', src).removeAttr('_src').show().next().hide();
                    });
                });
            }
            content.append('<div class="image-option">'+
            '<div class="image-option-mask"></div>'+
            '<div class="image-option-box">'+
            '<span class="image-option-del image-option-item">删</span>'+
            '<span class="image-option-left image-option-item">左</span>'+
            '<span class="image-option-right image-option-item">右</span>'+
            '</div>'+
            '</div>');
            self.option = content.find('.image-option');
            self.box.trigger('bind');

            self.preloadImg(initBigSrc);

            var indexpic = $('#indexpic');
            if(indexpic[0]){
                var imageid = indexpic.val();
                var image = content.find('.image[imageid="'+ imageid +'"]');
                if(image[0]){
                    image.closest('.item-box').find('.image-suoyintu').removeClass().addClass('image-suoyintu-current').show();
                }
            }

        }).bind('before.add', function(event, data){
            var parent = $(this).find('.edit-slide-image-content');
            var first = parent.find('.item-box:first');
            var src = data['src'];
            var html = '<div class="item-box" _nooption="'+ data['index'] +'">'+
            '<div class="item-inner-box">'+
            '<img class="image" _src="'+ src +'"/>'+
            '<img class="image-loading" src="' + RESOURCE_URL + 'loading2.gif"/>'+
            '</div>'+
            '<div class="nooption-mask"></div>'+
            '<div class="image-suoyintu"></div>'+
            '</div>';
            if(first[0]){
                first.before(html);
            }else{
                self.option.before(html);
                first = parent.find('.item-box:first');
            }
            var boxWidth = first.width();
            var boxHeight = first.height();
            var image = parent.find('.item-box[_nooption="'+ data['index'] +'"] .image');
            self.suoImg(src, boxWidth, boxHeight, function(type){
                image.attr(type, type == 'width' ? boxWidth : boxHeight).attr('src', src).removeAttr('_src').show();
            });
        }).bind('before-other.add', function(event, data){
            var parent = $(this).find('.edit-slide-image-content');
            var first = parent.find('.item-box:first');
            var html = '<div class="item-box" _nooption="'+ data['index'] +'">'+
            '<div class="item-inner-box">'+
            '<img class="image"/>'+
            '<img class="image-loading" src="' + RESOURCE_URL + 'loading2.gif"/>'+
            '</div>'+
            '<div class="nooption-mask"></div>'+
            '<div class="image-suoyintu"></div>'+
            '</div>';
            if(first[0]){
              first.before(html);
            }else{
                self.option.before(html);
            }
            self.statisctis();
        }).bind('after.add', function(event, data, cb){
            var itemBox = $(this).find('.item-box[_nooption="'+ data['index'] +'"]');
            var boxWidth = itemBox.width();
            var boxHeight = itemBox.height();
            var image = itemBox.removeAttr('_nooption').find('.image');
            var src = self.getSrc(data, 'suo');
            var bigsrc = self.getSrc(data, 'big');
            self.suoImg(src, boxWidth, boxHeight, function(type){
                 image.attr(type, type == 'width' ? boxWidth : boxHeight).attr({
                     'src' : src,
                     'bigsrc' : bigsrc
                 }).next().hide();
            });

            image.attr({
                'imageid' : data['id'],
                'bigsrc' : bigsrc,
                'path' : data['path'],
                'dir' : data['dir'],
                'filename' : data['filename']
            });
            self.box.trigger('bind');
            self.statisctis();

            self.preloadImg(bigsrc, function(){
                cb && cb();
            });


            //此处绑定图片编辑工具功能
            window.bindEditTool && $.type(window.bindEditTool) == 'function' && window.bindEditTool();
        }).on('bind', function(){
            $(this).find('.item-box').each(function(){
                if($(this).data('has-bind')) return;
                $(this).data('has-bind', true);
                $(this).on('transform', function(event, angle, callback){
                    var me = $(this);
                    var innerBox = me.find('.item-inner-box').removeClass('transform-left transform-right').addClass(angle > 0 ? 'transform-right' : 'transform-left');
                    var image = me.find('.image');
                    var mask = me.find('.nooption-mask').show();
                    var loading = me.find('.image-loading').show();
                    var imageid = image.attr('imageid');
                    $.post(
                        gUrl.transform + "&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
                        {
                            material_id : imageid,
                            direction : angle > 0 ? 2 : 1
                        },
                        function(json){
                            json = json[0];
                            setTimeout(function(){

                                var src = self.getSrc(json, 'suo', true);
                                var bigsrc = self.getSrc(json, 'big', true);
                                var boxWidth = me.width();
                                var boxHeight = me.height();
                                self.suoImg(src, boxWidth, boxHeight, function(type){
                                     image.removeAttr('width').removeAttr('height').attr(type, type == 'width' ? boxWidth : boxHeight).attr({
                                        'src' : src,
                                        'bigsrc' : bigsrc
                                     });
                                     innerBox.removeClass('transform-left transform-right');
                                     mask.hide();
                                     loading.hide();
                                });

                                self.preloadImg(bigsrc, function(){
                                    self.iframeImageRefresh(imageid, bigsrc);
                                    if(callback){
                                        callback();
                                    }
                                });

                            }, 300);
                        },
                        'json'
                    );

                }).on('delete', function(){
                    isdelete = true;
                    var me = $(this);
                    var imageid = me.find('.image').attr('imageid');
                    var images = self.iframeImageGet(imageid);
                    var remove = function(){
                        self.option.appendTo(self.box.find('.edit-slide-image-content').eq(0));
                        me.animate({
                            opacity : 0,
                            height : 0,
                            width : 0
                        }, 500, function(){
                            $(this).remove();
                            isdelete = false;
                        });
                        self.iframeImageRemove(imageid, images);
                        self.refresh();
                        var hidden = $('#material_'+ imageid);
                        if(hidden[0]){
                            hidden.remove();
                        }
                        self.statisctis();
                    }
                    if(images.length){
                        jConfirm('编辑器中已经插入此图片了，如果删除将连同一起删除，是否确定删除？', '删除提示', function(result){
                            if(result){
                                remove();
                            }else{
                                isdelete = false;
                            }
                        }).position(this);
                    }else{
                        remove();
                    }
                });
            });
        }).on('click', '.image-option', function(event){
            var target = event.target;
            if(!(target && (target = $(target)) && target.hasClass('image-option-item'))){
                return;
            }
            var box = $(this).parents('.item-box').eq(0);
            if(target.hasClass('image-option-left')){
                box.trigger('transform', [-90]);
            }else if(target.hasClass('image-option-right')){
                box.trigger('transform', [90]);
            }else if(target.hasClass('image-option-del')){
                box.trigger('delete');
            }
        }).on('click', '.image-suoyintu', function(){
            var image = $(this).closest('.item-box').find('.image'),
                url = image.attr('src') || image.attr('_src'),
                imageid = image.attr('imageid'),
                indexpic_url = $('#indexpic_url');
            if(indexpic_url[0]){
                indexpic_url.trigger('iload', [url, imageid]);
            }
            self.box.find('.image-suoyintu-current').removeClass().addClass('image-suoyintu');
            $(this).removeClass().addClass('image-suoyintu-current');
        }).on('click', '.image-suoyintu-current', function(){
            var indexpic_url = $('#indexpic_url');
            if(indexpic_url[0]){
                indexpic_url.trigger('idelete');
            }
            $(this).removeClass().addClass('image-suoyintu');
        });

        this.box.find(".image-upload-button").data('number', 0).on('change', function(event){
            var i, len = this.files.length, file, reader, formdata;
            for(i = 0; i < len; i++){
                file = this.files[i];
                if(!file.type.match(/image.*/)){
                    continue;
                }
                (function(ii){
                    if(window.FileReader){
                        reader = new FileReader();
                        reader.onloadend = function(event){
                            var target = event.target;
                            self.box.trigger('before', [{
                                src : target.result,
                                index : ii
                            }]);
                        };
                        reader.readAsDataURL(file);
                    }else{
                        self.box.trigger('before-other', [{
                            index : ii
                        }]);
                    }
                    if(window.FormData){
                        formdata = new FormData();
                        formdata.append('Filedata', file);
                        formdata.append( 'water_config_id', $('#water_config_id').val() );
                        $.ajax({
                            url : gUrl.upload + "&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
                            type : 'POST',
                            data : formdata,
                            processData : false,
                            contentType : false,
                            dataType : 'json',
                            success : function(data){
                                /*data = data[0] || {};
                                self.box.trigger('after', [{
                                    id : data['id'],
                                    path : data['path'],
                                    dir : data['dir'],
                                    filename : data['filename'],
                                    index : ii
                                }]);
                                $('.material-box').eq(0).append('<div id="material_'+ data['id'] +'">'+
                                    '<input type="hidden" name="material_id[]" value="'+ data['id'] +'" />'+
                                    '<input type="hidden" name="material_name[]" value="'+ data['filename'] +'"/>'+
                                '</div>');
                                $("#material_history").val(function(){
                                    var space = '', val;
                                    if(val = $(this).val()){
                                        space = ',';
                                    }
                                    return val + space + data['id'];
                                });*/
                                data = data[0] || {};
                                self.ajaxBack(data, ii);
                            }
                        });
                    }
                })( $(this).data('number') );
				$(this).data( 'number', $(this).data('number') + 1 );
            }

        });

        this.box.find('.image-water').on('click', function(){
            window['EditorWatermark' + self.number]();
        });

        this.box.find('.edit-slide-sort').on({
            click : function(){
                var $this = $(this);
                var state = $this.attr('state');
                state = state == 'shang' ? 'xia' : 'shang';
                $this.html($this.attr(state));
                $this.attr('state', state);
                var imgContent = $this.closest('.edit-slide-html-each').find('.edit-slide-image-content');
                var imgs = imgContent.find('.item-box');
                if(!imgs.length) return;
                var lastImg = null;
                imgs.each(function(){
                    if(!lastImg){
                        $(this).appendTo(imgContent);
                    }else{
                        $(this).insertBefore(lastImg);
                    }
                    lastImg = this;
                });
                lastImg = null;
            }
        });

    },
    content : function(){
        return '<div id="edit-slide-image'+ this.number +'" class="edit-slide-html-each">'+
        '<div class="edit-slide-title"><span class="edit-slide-sort" shang="排序↑" xia="排序↓" state="shang">排序↑</span><span class="edit-slide-close">关闭</span>图片管理</div>'+
        '<div class="edit-slide-button"><span class="edit-slide-button-item image-upload">添加图片<input type="file" multiple class="image-upload-button"/></span></div>'+
        '<div class="edit-slide-image-content edit-slide-content"></div>'+
        '</div>';
    },
    set : function(json){
        var self = this;
        self.box.trigger('set', [json.reverse()]);
    },
    insertHTML : function(html){
        var self = this;
        var body = $(this.editorWindow.document.body);
        this.editor.insertHTML(html);
        this.refresh();
    },
    refresh : function(){
        window['contentWindow' + this.number]('refresh');
    },
    getSrc : function(data, type, force){
        if(type == 'suo'){
            type = this.suoWidth + 'x';
        }else{
            type = this.bigWidth + 'x';
        }
        return $.globalImgUrl(data, type, force);
    },
    iframeImageGet : function(imageid){
        return $(this.editorWindow.document.body).find('img.image[imageid="'+ imageid +'"]');
    },
    iframeImageRefresh : function(imageid, src){
        var images = this.iframeImageGet(imageid);
        if(images.length) images.attr('src', src);
    },
    iframeImageRemove : function(imageid, images){
        !images && (images = this.iframeImageGet(imageid));
        if(images.length) images.remove();
    },
    preloadImg : function(src, callback){
        if($.type(src) == 'array'){
            $.each(src, function(i, n){
                var img = new Image();
                img.src = n;
            })
        }else{
            var img = new Image();
            img.onload = function(){
                callback && callback();
            };
            img.src = src;
        }
    },
    suoImg : function(src, boxWidth, boxHeight, callback){
        var img = new Image();
        img.onload = function(){
            var pw = this.width / boxWidth;
            var ph = this.height / boxHeight;
            var type;
            if(pw >= 1 && pw >= ph ){
                type = 'width';
            }
            if(ph >= 1 && ph >= pw){
                type = 'height';
            }
            callback && callback(type);
        }
        img.src = src;
    },
    rotate : function(imageid, direct, callback){
        var image = this.box.find('.image[imageid="'+ imageid +'"]');
        if(image[0]){
            image.closest('.item-box').trigger('transform', [direct, callback]);
        }
    },
    statisctis : function(){
        var num = this.box.find('.image').length;
        window['statistics' + this.number].imgNumber(num);
    },
    local : function(callback, oneSrc){
        if(!this.localIndex){
            this.localIndex = +new Date();
        }
        var self = this;
        var doc = $(this.editorWindow.document);
        var outerImgs = [];
        var outerSrcs = [];
        var processImgs = [];
        var uploadOK = [];
        var totalLen = 0;

        var local = {
            start : function(){
                $.officeBoxMask(self.number).trigger('show');
            },
            end : function(){
                $.officeBoxMask(self.number).trigger('hide');
            }
        };

        if(oneSrc){
            outerImgs.push({
                src : oneSrc,
                hash : ++self.localIndex
            });
            totalLen = 1;
            doLocalUpload(onlyUpload);
        }else{
            doc.find('img').each(function(){
                var me = $(this), src;
                if(me.attr('imageid') || me.hasClass('pagebg') || me.hasClass('before-biaozhu-ok') || me.hasClass('after-biaozhu-ok') || me.hasClass('image-refer')){

                }else{
                    src = $.trim(me.attr('src'));
                    if(src && (src.indexOf('http') == 0) && ($.inArray(src, outerSrcs) == -1)){
                        outerSrcs.push(src);
                        outerImgs.push({
                            src : src,
                            hash : ++self.localIndex
                        });
                        processImgs.push(me);
                    }
                }
            });
            totalLen = outerImgs.length;
            if(!totalLen){
                callback(false);
                return;
            }

            local.start();
            var cloneOuterImgs = $.extend({}, outerImgs);

            (function(){
                var len = processImgs.length;
                var index = 0;
                var officeBox = $.officeBox(self.number).trigger('html', ['']);
                (function process(){
                    var img = processImgs.shift();
                    if(!img){
                        //doLocalUpload();
                        return false;
                    }
                    var imgInfo = cloneOuterImgs[index];
                    officeBox.trigger('insert', [img, imgInfo, function(){
                        $(this).css({
                            left : index * 10 + 'px',
                            'z-index' : len--
                        });
                    }]).css({
                        width : index * 10 + 30 + 'px'
                    });
                    index++;
                    setTimeout(process, 500);
                })();
            })();

            doLocalUpload();
            moreUpload();
        }

        function doLocalUpload(cb){
            window['slideManage' + self.number].openOne('image');
            var oknum = 0;
            (function loop(){
                var val = outerImgs.shift();
                if(val){
                    var src = val['src'];
                    var hash = val['hash'];
                    self.box.trigger('before', [{
                        src : src,
                        index : hash
                    }]);
                    $.post(
                        gUrl.imgLocal + "&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
                        {url : src},
                        function(data){
                            data = data[0] || {};
                            data = data[src];
                            data['hash'] = hash;
                            data['oldurl'] = src;
                            uploadOK.push(data);
                            cb && cb();
                        },
                        'json'
                    );
                    oknum++;
                }
                if(oknum >= totalLen){
                    return false;
                }
                setTimeout(loop, 100);
            })();
        }

        function moreUpload(){
            var oknum = 0;
            var officeBox = $.officeBox(self.number);
            (function loop(){
                var data = uploadOK.shift();
                if(data){
                    if(data['error']){
                        officeBox.trigger('error', [data['hash']]);
                    }else{
                        self.ajaxBack(data, data['hash'], function(which){
                            officeBox.trigger('remove', [data['hash'], which]);
                            if(oknum >= totalLen){
                                officeBox.trigger('close', [local.end]);
                            }
                        });
                    }
                    oknum++;
                }
                if(oknum >= totalLen){
                    return false;
                }
                setTimeout(loop, 500);
            })();
        }

        function onlyUpload(){
            var data = uploadOK.shift();console.log('only');
            self.ajaxBack(data, data['hash'], function(which){
                callback();
            });
        }
    },
    ajaxBack : function(data, index, cb){
        var me = this;
        me.box.trigger('after', [{
            id : data['id'],
            path : data['path'],
            dir : data['dir'],
            filename : data['filename'],
            index : index
        }, callback]);
        $('.material-box').eq(0).append('<div id="material_'+ data['id'] +'">'+
            '<input type="hidden" name="material_id[]" value="'+ data['id'] +'" />'+
            '<input type="hidden" name="material_name[]" value="'+ data['filename'] +'"/>'+
            '</div>');
        $("#material_history").val(function(){
            var space = '', val;
            if(val = $(this).val()){
                space = ',';
            }
            return val + space + data['id'];
        });

        function callback(){
            var doc = $(me.editorWindow.document);
            var suoImg = me.box.find('.image[imageid="'+ data['id'] +'"]');
            var suoBox = suoImg.closest('.item-box');
            var suoBoxOffset = suoBox.offset();
            var suoParent = suoBox.parent();
            var suoParentOffset = suoParent.offset();
            var suoParentScrollTop = suoParent.scrollTop();
            suoParent.animate({
                scrollTop : suoBoxOffset.top + suoParentScrollTop - suoParentOffset.top + 'px'
            }, 200, function(){
                cb && cb(suoImg);
            });
            var bigsrc = suoImg.attr('bigsrc');
            var img = doc.find('img[src="'+ data['oldurl'] +'"]');
            img.each(function(){
                var width = $(this).width();
                $(this).attr({
                    'class' : 'image',
                    src : bigsrc,
                    oldWidth : me.bigWidth,
                    imageid : data['id']
                }).width(width);
            });
        }
    }
});