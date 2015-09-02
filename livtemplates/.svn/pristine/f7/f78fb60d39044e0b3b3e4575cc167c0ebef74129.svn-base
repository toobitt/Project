(function($){
    $.widget('authtip.base', {
        options : {
            duration : 10,
            string : '授权已到期，请联系软件提供商'
        },

        _init : function(){
            this._Init();
        },

        _create : function(){
            this.baseStyle  = '.authtip-box{border:4px solid #f57e36;box-shadow:0 0 5px 0px #1F1919;}' +
                '.authtip-djs{position:absolute;right:0px;top:0px;height:30px;line-height:30px;padding:0 10px;}' +
                '.authtip-djs span{margin:0 8px;color:red;font-size:16px;font-weight:bold;}';
            this._Create();
        },

        _djs : function(){
            this.number = this.options.duration || 10;
            this._djsStart();
            var _this = this;
            var timer = setInterval(function(){
                _this.number--;
                if(_this.number < 0){
                    clearInterval(timer);
                    timer = null;
                    _this._djsEnd();
                }else{
                    _this._djsDo();
                }
            }, 1000);
        },

        _djsStart : function(){
            this.djs = $('<div class="authtip-djs">本次提示将在<span>' + this.number + '</span>秒后关闭</div>').appendTo(this.box);
        },

        _djsDo : function(){
            this.djs.find('span').html(this.number);
        },

        _djsEnd : function(){
            this.stop();
        },

        _destroy : function(){

        }
    });


    $.widget('authtip.tc', $.authtip.base, {
        _Init : function(){

        },

        _Create : function(){
            var html = '<style>' +
                (this.baseStyle) +
                '.authtip-transition{-webkit-transition:all .5s ease-in-out;-moz-transition:all .5s ease-in-out;transition:all .5s ease-in-out;}' +
                '.authtip-tc{position:fixed;z-index:100000000000000;left:50%;top:0%;width:752px;height:292px;margin:-300px 0 0 -380px;background:#fff;text-align:center;color:#828282;}' +
                '.authtip-tc.authtip-tc-open{top:50%;margin-top:-150px;}' +
                '.authtip-yqtx{font-size:16px;margin:15px 0;}' +
                '.authtip-icon{width:116px;margin-top:50px;}' +
                '</style>' +
                '<div class="authtip-tc authtip-box authtip-transition">' +
                    '<img class="authtip-icon" src="' + RESOURCE_URL + '2013/authtip/icon_2-2x.png"/>' +
                    '<div class="authtip-yqtx">友情提醒</div>' +
                    '<div>' + this.options.string + '</div>' +
                '</div>';
            this.element.append(html);
            this.box = this.element.find('.authtip-tc');
            var _this = this;
            this._delay(function(){
                _this.start();
            }, 0);
        },

        start : function(){
            this.box.addClass('authtip-tc-open');
            var _this = this;
            this._delay(function(){
                _this._djs();
            }, 600);
        },

        stop : function(){
            this.box.removeClass('authtip-tc-open');
            var _this = this;
            this._delay(function(){
                _this.box.remove();
            }, 600);
        }
    });


    $.widget('authtip.dj', $.authtip.base, {
        _Init : function(){
            $(document).on({
                contextmenu : function(){
                    return false;
                }
            });

            var _this = this;
            _this.clicking = false;
            $('.app-item').on({
                click : function(){
                    if(_this.clicking){
                        return false;
                    }
                    var $this = $(this);
                    if(!$this.data('clicktc')){
                        $this.data('clicktc', true);
                        _this.clicking = true;
                        _this.start(function(){
                            _this.clicking = false;
                            $this.trigger('click');
                        });
                        return false;
                    }else{
                        $this.data('clicktc', false);
                    }
                }
            });
        },

        _Create : function(){
            var html = '<style>' +
                (this.baseStyle) +
                '.authtip-dj{display:none;position:fixed;z-index:100000000000000;left:50%;top:50%;width:200px;height:100px;margin:-50px 0 0 -100px;background:red;line-height:100px;text-align:center;color:#fff;}' +
                '.authtip-dj.authtip-dj-open{display:block;}' +
                '</style>' +
                '<div class="authtip-dj authtip-box">' +
                '<div>' + this.options.string + '</div>' +
                '</div>';
            this.element.append(html);
            this.box = this.element.find('.authtip-dj');
        },

        start : function(end){
            this.box.addClass('authtip-dj-open');
            this._djs();
            end && this._delay(end, this.options.duration * 1000);
        },

        stop : function(){
            this.box.removeClass('authtip-dj-open');
        },

        _djsStart : function(){
            if(this.djsInit){
                this.djs.find('span').html(this.number);
            }else{
                this.djsInit = true;
                this.djs = $('<div class="authtip-djs"><span>' + this.number + '</span></div>').appendTo(this.box);
            }
        }
    });

    $(function(){
        if(!$.m2oAuth) return;
        var authtip = $.m2oAuth || {
            type : 'dj',
            duration : 3
        };
        try{
            if($.authtip[authtip['type']]){
                $('body')[authtip['type']](authtip);
            }
        }catch(e){}
    });

})(jQuery);