(function($){
    $.allRolePrms = null;
    $.widget('auth.userform', {
        options : {
            bid : 0,
            target : null,
            'create-url' : '',
            'update-url' : '',
            'avatar-url' : '',
            'prms-url' : '',
            'user-prms-url' : ''
        },

        _create : function(){
            var root = this.element;
            this.name = root.find('.username');
            this.password = root.find('.password');
            //this.role = root.find('.role-each');
            this.roleList = root.find('.role-list');
            this.roleTip = root.find('.role-tip');
            this.save = root.find('.save');
            this.cancel = root.find('.cancel');
            this.upload = root.find('.user-head');

            this.id = 0;
        },

        _init : function(){
            var _this = this;
            _this._on({
                'click .save' : '_save',
                'click .cancel' : '_cancel',
                'click .role-each' : '_role',
                'click .close' : '_close',
                'mouseenter .role-each' : '_roleOver',
                'mouseleave .role-each' : '_roleOut'
            });

            _this._on(_this.upload, {
                click : '_upload'
            });

            _this.element.find('#user-head-upload').ajaxUpload({
                url : _this.options['avatar-url'],
                phpkey : 'Filedata',
                before : function(info){
                    _this._uploadBefore(info['data']['result']);
                },
                after : function(json){
                    _this._uploadAfter(json);
                }
            });
        },

        _upload : function(){
            this.element.find('#user-head-upload').click();
        },

        _uploadBefore : function(src){
            this._avatar(src);
        },

        _uploadAfter : function(json){
            this.avatarInfo = json.data;
        },

        _save : function(event){
            var _this = this;
            var name = $.trim(this.name.val());
            if(!name || !name.length){
                return _this._error('name');
            }
            var password = $.trim(this.password.val());
            if(!_this.id && !password.length){
                return _this._error('password');
            }
            var role =[];
            this.roleList.find('li').each(function(){
                role.push($(this).data('id'));
            });
            if(!role.length){
                return _this._error('role');
            }
            var postData = {
                user_name : name,
                password : password,
                father_org_id : _this.options['bid'],
                'admin_role_id[]' : role
            };
            if(_this.avatarInfo){
                $.extend(postData, {
                    'avatar[host]' : _this.avatarInfo['host'],
                    'avatar[dir]' : _this.avatarInfo['dir'],
                    'avatar[filepath]' : _this.avatarInfo['filepath'],
                    'avatar[filename]' : _this.avatarInfo['filename']
                });
            }
            var postUrl = _this.options['create-url'];
            if(_this.id > 0){
                postData['id'] = _this.id;
                postUrl = _this.options['update-url'];
            }
            var guid = $.globalAjaxLoad.bind(event.currentTarget);
            var xhr = $.post(
                postUrl,
                postData,
                function(){

                },
                'json'
            );
            xhr.guid = guid;
            xhr.done(function(json){
                if(json['error']){
                    _this._error(json['error']);
                    return;
                }
                _this._trigger('save', null, [json, _this.options['target'], _this.id]);
                _this.hide();
            });
        },

        _cancel : function(){
            this.hide();
        },

        _error : function(error){
            var _this = this;
            var errors = {
                name : '缺少名字',
                password : '缺少密码',
                role : '请选择角色'
            };
            error = errors[error] ? errors[error] : error;
            if(!_this.error){
                _this.error = $('<div/>').appendTo(_this.element.find('.buttons')).addClass('user-error');
            }
            _this.error.html(error).stop().show();
            _this._delay(function(){
                _this.error.animate({
                    opacity : 0
                }, 500, function(){
                    $(this).hide().css('opacity', 1);
                });
            }, 2000);
            return false;
        },

        _avatar : function(src){
            var box = this.upload;
            if(!src){
                box.html('').removeClass('no-bg');
                return;
            }
            var img = box.find('img');
            !img[0] && (img = $('<img/>').appendTo(box.empty()));
            img.attr('src', src);
            box.addClass('no-bg');
        },

        _refresh : function(userInfo){
            this.id = userInfo['id'];
            this.name.val(userInfo['user_name']);
            this._roleList(userInfo['admin_role_id']);
            var avatar = userInfo['avatar'];
            var src = '';
            if(avatar['host'] && avatar['dir'] && avatar['filepath'] && avatar['filename']){
                //src = avatar['host'] + avatar['dir'] + '90x90/' + avatar['filepath'] + avatar['filename'];
                src = $.globalImgUrl(avatar, '90x90');
            }
            this._avatar(src);
            this._userRole(userInfo['admin_role_id']);
        },

        _roleList : function(ids){
            var _this = this;
            var roleIds = ids.split(',');
            var lis = '';
            $.each(roleIds, function(i, n){
                lis += _this._roleLi(n, _this._getRoleName(n, true));
            });
            if(lis){
                _this.roleList.html(lis).show();
                _this.roleTip.hide();
            }
        },

        _role : function(event){
            var target = $(event.currentTarget);
            var id = target.data('id');
            var name = target.text();
            if(target.hasClass('on')){
                return;
                this._roleRemove(id);
                target.removeClass('on');
            }else{
                this._roleAdd(id, name);
                target.addClass('on');
            }
        },

        _roleIds : function(){
            var ids = [];
            this.element.find('.role-list li').each(function(){
                ids.push($(this).data('id'));
            });
            return ids.join(',');
        },

        _roleAdd : function(id, name){
            var _this = this;
            _this.roleList.append(_this._roleLi(id, name)).show();
            _this.roleTip.hide();
            _this._userRole(_this._roleIds());
        },

        _roleRemove : function(id){
            var _this = this;
            this.roleList.find('li[data-id="' + id + '"]').remove();
            if(!_this.roleList.find('li').length){
                _this.roleList.hide();
                _this.roleTip.show();
            }
            _this._userRole(_this._roleIds());
        },

        _roleOver : function(event){
            var roleId = $(event.currentTarget).data('id');
            var prms = $.allRolePrms[roleId];
            var currentRoot = this.element.find('.root-current').show().empty();
            currentRoot.prev().hide();
            if(prms && prms['app_prms']){
                $('#prms-tpl').tmpl({
                    list : prms['app_prms']
                }).appendTo(currentRoot);
            }

        },

        _roleOut : function(event){
            this.element.find('.root-current').hide().prev().show();
        },

        _userRole : function(roleIds){
            var _this = this;
            var userRoot = _this.element.find('.root-user').empty();
            if(!roleIds) return;
            var guid = $.globalAjaxLoad.bind(userRoot);
            var xhr = $.getJSON(
                _this.options['user-prms-url'],
                {role_id : roleIds},
                function(json){
                    json = json[0];
                    var list = {};
                    $.each(json['app_prms'], function(i, n){
                        list[i] = {
                            name : n['app_name'],
                            func_prms : n['action']
                        };
                    });
                    $('#prms-tpl').tmpl({
                        list : list,
                        site : json['site_prms'],
                        publish : json['publish_prms']
                    }).appendTo(userRoot);
                }
            );
            xhr.guid = guid;
        },

        _close : function(event){
            var li = $(event.target).closest('li');
            this._getRole(li.data('id')).removeClass('on');
            li.remove();
            var _this = this;
            if(!_this.roleList.find('li').length){
                _this.roleList.hide();
                _this.roleTip.show();
            }
        },

        _roleLi : function(id, name){
            return '<li data-id="'+ id +'">' + name + '<a class="close"></a></li>';
        },

        _getRole : function(id){
            return this.element.find('.role-each[data-id="'+ id +'"]');
        },

        _getRoleName : function(id, addOn){
            var each = this._getRole(id);
            addOn && each.addClass('on');
            return each.text();
        },

        _getAllPrms : function(){
            if(!$.allRolePrms){
                var _this = this;
                var roles = _this.element.find('.user-form-right').css('opacity', 0);
                var guid = $.globalAjaxLoad.bind(roles);
                var xhr = $.getJSON(
                    _this.options['prms-url'],
                    function(json){
                        $.allRolePrms = json;
                    }
                );
                xhr.guid = guid;
                xhr.done(function(){
                    roles.css('opacity', 1);
                });
            }
        },

        show : function(options){
            this._getAllPrms();
            $.extend(this.options, options);
            this.offset();
            this._mask(true);
            this.element.show();
            if(options['userInfo']){
                this._refresh(options['userInfo']);
            }else{
                this._reset();
            }
        },

        hide : function(){
            this.element.hide();
            this._mask(false);
            $(this.options['target']).removeClass('on');
            this.options['target'] = null;
            this.options['bid'] = 0;
            this._reset();
        },

        _mask : function(state){
            var _this = this;
            if(!this.mask){
                this.mask = $('<div/>').addClass('user-form-mask').appendTo('body').on({
                    dblclick : function(){
                        _this.hide();
                    }
                });
            }
            if(state){
                var doc = $(document);
                this.mask.css({
                    width : doc.width() + 'px',
                    height : doc.height() + 'px'
                });
            }
            this.mask[state ? 'show' : 'hide']();
        },

        _reset : function(){
            this.id = 0;
            this.avatarInfo = null;
            this.name.val('');
            this.password.val('');
            this.roleTip.show();
            this.roleList.html('').hide();
            this.upload.removeClass('no-bg');
            this.element.find('.role-each.on').removeClass('on');
            this._avatar();
        },

        offset : function(){
            var target = $(this.options['target']).addClass('on');
            var targetInfo = {
                width : target.outerWidth(),
                height : target.outerHeight()
            };
            $.extend(targetInfo, target.offset());
            var win = $(document);
            var winInfo = {
                width : win.width(),
                height : win.height()
            };
            var root = this.element;
            var formInfo = {
                width : root.outerWidth(true),
                height : root.outerHeight(true)
            };
            var left = targetInfo.left;
            if(left + formInfo.width > winInfo.width){
                left = winInfo.width - formInfo.width;
            }
            var top = targetInfo.top + 130;
            if(top + formInfo.height > winInfo.height){
                top = winInfo.height - formInfo.height;
            }
            root.css({
                left : left + 'px',
                top : top + 'px'
            });
        },

        _destroy : function(){

        }
    });


    $.widget('auth.userdd', {
        options : {
            bid : 0,
            'update-url' : ''
        },

        _create : function(){

        },

        _init : function(){
            var _this = this;
            _this.element.droppable({
                accept : '.member',
                activeClass : 'members-active',
                hoverClass : 'members-hover',
                drop : function(event, ui){
                    if(ui.draggable.parent()[0] == this){
                        return;
                    }
                    var clone = ui.helper.clone();
                    ui.helper.remove();
                    var member = ui.draggable.insertBefore($(this).find('.member-add')).css('opacity', 0);
                    var cssLeft = parseInt(member.css('margin-left'));
                    var cssTop = parseInt(member.css('margin-top'));
                    var offset = member.offset();
                    clone.appendTo('body').animate({
                        left : offset.left - cssLeft + 'px',
                        top : offset.top - cssTop + 'px'
                    }, 500, function(){
                        $(this).remove();
                        member.css('opacity', 1);
                    });
                    _this._update(member.data('id'));
                }
            });

            _this.element.find('.member').draggable({
                helper : 'clone',
                appendTo : 'body',
                revert : true,
                delay : 200,
                start : function(){

                }
            });
        },

        _update : function(id){
            var _this = this;
            $.when($.post(
                _this.options['update-url'],
                {id : id, father_org_id : _this.options['bid']}
            )).done(function(json){
                if(json['error']){

                }
            });
        },

        _destroy : function(){

        }
    });
})(jQuery);