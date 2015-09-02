jQuery(function($){
    $.userCache = function(){
        var cache = {};
        return {
            add : function(user){
                cache[user['id']] = user;
            },
            fetch : function(id){
                return cache[id];
            },
            more : function(users){
                var _this = this;
                $.each(users || [], function(i, n){
                    _this.add(n);
                });
            },
            info : function(){
                return cache;
            }
        };
    }();
    $.each(groups, function(i, n){
        $.userCache.more(n['user']);
    });
    var options = {
        tpl : '#group-tpl',
        'child-url' : 'run.php?mid='+ gMid +'&a=get_org',
        'create' : 'run.php?mid='+ gMid +'&a=create_org',
        'update' : 'run.php?mid='+ gMid +'&a=update_org',
        'delete' : 'run.php?mid='+ gMid +'&a=delete_org',
        'user-update-bid' : 'run.php?mid='+ gMid +'&a=update_admin_org',
        'user-update' : 'run.php?mid='+ gMid +'&a=update',
        'user-form' : '#user-form'
    }
    $('.item').item(options);
    $('.item-child').append($('#group-tpl').tmpl({groups : groups, fid : 0})).show();
    $('.item').filter(function(){
        return !$(this).is(':auth-item');
    }).item(options);

    $('#user-form').userform({
        'create-url' : 'run.php?mid='+ gMid +'&a=create_admin',
        'update-url' : 'run.php?mid='+ gMid +'&a=update_admin',
        'avatar-url' : 'run.php?mid='+ gMid +'&a=create_avatar',
        'prms-url' : 'run.php?mid='+ gMid +'&a=get_roles_prms',
        'user-prms-url' : 'run.php?mid='+ gMid +'&a=get_user_prms',
        'save' : function(event, json, target, id){
            json = json[0];
            var avatar = json['avatar'];
            //json['_avatar'] = avatar['host'] + avatar['dir'] + avatar['filepath'] + avatar['filename'];
            json['_avatar'] = $.globalImgUrl(avatar);
            $.userCache.add(json);
            var obj = $('#user-tpl').tmpl(json);

            if(!id){
                obj.insertBefore(target).hide().fadeIn(500);
            }else{
                $(target).replaceWith(obj).hide().fadeIn(500);
            }
        }
    });

    $('.add-first-department').on({
        _init : function(){
            $(this).triggerHandler('_initA');
            $(this).triggerHandler('_initInput');
        },

        _handlers : function(event, name){
            return $.proxy($._data(this).events[name][0].handler, this);
        },

        _initA : function(){
            $(this).find('a').on({
                'click' : $(this).triggerHandler('_handlers', ['_clickA'])
            });
        },

        _clickA : function(event){
            var target = $(event.currentTarget);
            target.hide().next().show().focus();
        },

        _initInput : function(){
            $(this).find('input').on({
                'focus' : $(this).triggerHandler('_handlers', ['_focusInput']),
                'blur' : $(this).triggerHandler('_handlers', ['_blurInput'])
            });
        },

        _focusInput : function(){

        },

        _blurInput : function(event){
            var target = $(event.currentTarget);
            var callback = function(){
                target.hide().val('').prev().show();
            };
            var name = target.val();
            if(!name || !name.length){
                callback();
                return false;
            }
            var guid = $.globalAjaxLoad.bind(target);
            var xhr = $.post(
                options['create'],
                {fid : 0, name : name},
                function(){

                },
                'json'
            );
            xhr.guid = guid;
            xhr.done(function(json){
                $('#root').item('append', json, true);
                callback();
            });
        }
    }).triggerHandler('_init');
});