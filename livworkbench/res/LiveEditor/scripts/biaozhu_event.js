function BiaozhuEvent(number, slide){
    this.number = number;
    this.slide = slide;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = null;
    this.option = null;
    this.slideManage = window['slideManage' + number];
    this.init();
}

jQuery.extend(BiaozhuEvent.prototype, {
    init : function(){
        this.slide.html(this.content());

        var self = this;
        this.slide.addInitFunc(function(){
            $(self.editorWindow.document).find('.before-biaozhu-ok, .after-biaozhu-ok').css('display', 'inline');
        });
        this.slide.addCloseFunc(function(){
            $(self.editorWindow.document).find('.before-biaozhu-ok, .after-biaozhu-ok').css('display', 'none');
        });

        this.box = $('#edit-slide-biaozhu' + this.number);
        var self = this;
        this.box.on('set', function(event, data){
            var html = '';
            var m = 0;
            $.each(data, function(i, n){
                var replyInfo = createReply(n['reply']);
                html += '<div class="biaozhu-item" rand="'+ n['rand'] +'">'+
                '<span class="biaozhu-item-number">'+ (++m) +'：</span>'+
                '<div class="biaozhu-item-content" _title="'+ n['allContent'] +'" _id="'+ n['id'] +'" _name="'+ n['name'] +'">'+
                    '<span class="biaozhu-item-title">' + n['content'] + '</span>' + '<span class="biaozhu-item-reply-number">('+ replyInfo['number'] +')</span>' +
                    '<div class="biaozhu-item-reply">' +
                    replyInfo['content'] +
                    '</div>' +
                '</div>'+
                '<div class="biaozhu-option"><span class="biaozhu-option-zankai"></span><span class="biaozhu-option-del">删</span></div>' +
                '</div>';
            });
            $(this).find('.edit-slide-biaozhu-content').html(html);
            $(this).find('.biaozhu-textarea').textareaAuto();
        }).on({
            mouseenter : function(){
                if($(this).hasClass('current-option')) return;
                !$(this).hasClass('current') && $(this).addClass('current');
            },
            mouseleave : function(){
                if($(this).hasClass('current-option')) return;
                $(this).hasClass('current') && $(this).removeClass('current');
            }
        }, '.biaozhu-item').on('click', '.biaozhu-option-del', function() {
            var item = $(this).closest('.biaozhu-item');
            var rand = item.attr('rand');
            var body = $(self.editorWindow.document.body);
            var before = body.find('.before-biaozhu-ok[rand="'+ rand +'"]');
            //body.scrollTop(before.offset().top - 80);
            item.animate({
                width : 0,
                height : 0
            }, 500, function(){
                before.remove();
                body.find('.after-biaozhu-ok[rand="'+ rand +'"]').remove();
                self.editorWindow.getSelection().removeAllRanges();
                self.set();
            });
        }).on('click', '.biaozhu-item-title', function(){
            var item = $(this).closest('.biaozhu-item');
            var rand = item.attr('rand');
            var body = $(self.editorWindow.document.body);
            var before = body.find('.before-biaozhu-ok[rand="'+ rand +'"]');
            body.animate({
                scrollTop : before.offset().top - 80 + 'px'
            }, 500, function(){
                before.trigger('click', [true]);
            });
            //body.scrollTop(before.offset().top - 80);
        }).on('click', '.biaozhu-option-zankai', function(){
            var item = $(this).closest('.biaozhu-item');
            if(item.hasClass('current-option')){
                item.removeClass('current-option');

                //var rand = $(this).closest('.biaozhu-item').attr('rand');
                //$(self.editorWindow.document).find('.before-biaozhu-ok[rand="'+ rand +'"]').trigger('click', [true]);
            }else{
                item.parent().find('.biaozhu-item.current-option').find('.biaozhu-option-zankai').click();
                item.addClass('current-option');
                $(this).closest('.biaozhu-item').find('.biaozhu-textarea').focus();

                item.find('.biaozhu-item-title').trigger('click');
            }
        }).on('setReply', '.biaozhu-item', function(){
            var rand = $(this).attr('rand');
            var reply = getReply($(this));
            $(self.editorWindow.document.body).find('.after-biaozhu-ok[rand="'+ rand +'"]').attr('reply', reply);
        });

        var id = gAdmin['admin_id'], name = gAdmin['admin_user'] || '我';

        this.box.on('blur', '.biaozhu-textarea', function(){
            var each = $(this).closest('.biaozhu-item-reply-each');
            var val = $.trim($(this).val());
            if(!val) return;
            var data = {
                id : id,
                name : name,
                reply : val
            };
            each.before(createEachReply(data));
            $(this).val('').height($(this).attr('_height'));

            var item = $(this).closest('.biaozhu-item');
            item.find('.biaozhu-item-reply-number').html('(' + (item.find('.biaozhu-item-reply-each').length - 1) + ')');
            item.trigger('setReply');
        });

        function createReply(data, json){
            data = json ? data : (data ? $.parseJSON(decodeURIComponent(data)) : '');
            var reply = '', number = 0;
            $.each(data, function(i, n){
                number++;
                reply += createEachReply(n);
            });
            reply += '<div class="biaozhu-item-reply-each">'+
                '<span class="biaozhu-item-reply-name" _id="'+ id +'" _name="'+ name +'">我：</span>'+
                '<span class="biaozhu-item-reply-content"><textarea class="biaozhu-textarea" style="height:18px;" _height="18"></textarea></span>'+
                '</div>';
            return {content : reply, number : number};
        }

        function createEachReply(data){
            return '<div class="biaozhu-item-reply-each">'+
                '<span class="biaozhu-item-reply-name" _id="'+ data['id'] +'" _name="'+ data['name'] +'">'+ (data['id'] == id ? '我' : data['name']) +'：</span>'+
                '<span class="biaozhu-item-reply-content">'+ data['reply'] +'</span>'+
                '</div>';
        }

        function getReply(which){
            var replys = [];
            which.find('.biaozhu-item-reply-each').not(':last').each(function(){
                var nameBox = $(this).find('.biaozhu-item-reply-name');
                replys.push({
                    id : nameBox.attr('_id'),
                    name : nameBox.attr('_name'),
                    reply : $(this).find('.biaozhu-item-reply-content').html()
                });
            });
            return encodeURIComponent(JSON.stringify(replys));
        }
    },
    content : function(){
        return '<div id="edit-slide-biaozhu' + this.number +'" class="edit-slide-html-each">'+
        '<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>批注面板</div>'+
        '<div class="edit-slide-biaozhu-content edit-slide-content"></div>'+
        '</div>';
    },
    set : function(){
        var body = $(this.editorWindow.document.body);
        var all = body.find('.before-biaozhu-ok, .after-biaozhu-ok');
        var befores = body.find('.before-biaozhu-ok');
        var afters = body.find('.after-biaozhu-ok');
        var blen = befores.length, alen = afters.length;
        var json = {};
        var del = [], delWhich = '';
        if(blen <= alen){
            befores.each(function(){
                var rand = $(this).attr('rand');
                var id = $(this).attr('_id');
                var name = $(this).attr('_name');
                json[rand] = {
                    'rand' : rand,
                    'id' : id,
                    'name' : name
                };
            });
            afters.each(function(){
                var rand = $(this).attr('rand');
                if(json[rand]){
                    var reply = $(this).attr('reply');
                    json[rand]['reply'] = reply;
                }else{
                    del.push(rand);
                }
            });
            delWhich = 'after';
        }else{
            afters.each(function(){
                var rand = $(this).attr('rand'), reply = $(this).attr('reply');
                json[rand] = {
                    'reply' : reply,
                    'rand' : rand
                };
            });
            befores.each(function(){
                var rand = $(this).attr('rand');
                var id = $(this).attr('_id');
                var name = $(this).attr('_name');
                if(!json[rand]){
                    del.push(rand);
                }else{
                    json[rand]['id'] = id;
                    json[rand]['name'] = name;
                }
            });
            delWhich = 'before';
        }
        if(del.length){
            var cssSelect = '', step = '';
            $.each(del, function(i, n){
                cssSelect += step + '.'+ delWhich +'-biaozhu-ok[rand="' + n + '"]';
                step = ',';
            });
            body.find(cssSelect).remove();
        }
        var biaozhuNumber = 0;
        if(json){
            var range = this.editorWindow.document.createRange();
            $.each(json, function(i, n){
                biaozhuNumber++;
                var before = body.find('.before-biaozhu-ok[rand="'+ i +'"]');
                var after = body.find('.after-biaozhu-ok[rand="'+ i +'"]');
                range.setStartAfter(before[0]);
                range.setEndBefore(after[0]);
                var string = range.toString();
                json[i]['allContent'] = string;
                if(string.length >= 10){
                    string = string.substr(0, 7) + '...';
                }
                json[i]['content'] = string;
            });
        }
        this.box.trigger('set', [json]);
        window['statistics' + this.number] && window['statistics' + this.number].biaozhuNumber(biaozhuNumber);
    }
});
