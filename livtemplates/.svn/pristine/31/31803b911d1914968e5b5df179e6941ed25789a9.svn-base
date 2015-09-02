(function($){
    $.widget('auth.create', {
        options : {
            fid : 0,
            'save-url' : '',
            'form-tpl' : '<form class="item-hd-create" onsubmit="return false;"><input type="text" class="name"/><button class="save" style="display:none;">保存</button><button class="cancel" style="display:none;">取消</button></form>',
            'tpl' : ''
        },

        _create : function(){
            this._createForm();
            this.form = this.element.find('form');
            this.add = this.element.find('.add');
            this.show();
            this.name = this.element.find('.name').focus();
        },

        _createForm : function(){
            this.element.append(this.options['form-tpl']);
        },

        _init : function(){
            this._on({
                'click .save' : '_save',
                'click .cancel' : '_cancel'
            });

            this._on({
                'blur input' : '_blur'
            });
        },

        _save : function(event){
            var _this = this;
            var target = event ? $(event.currentTarget) : _this.name;
            var name = _this.name.val();
            if(!name || !name.length){
                _this._cancel();
                return false;
            }
            var guid = $.globalAjaxLoad.bind(target);
            var xhr = $.post(
                _this.options['save-url'],
                {fid : _this.options['fid'], name : name},
                function(){

                },
                'json'
            );
            xhr.guid = guid;
            xhr.done(function(json){
                _this._saveBack(json);
            });
        },

        _saveBack : function(json){
            if(json[0] && parseInt(json[0]['id'])){
                this._trigger('save', null, [json]);
                this.hide(1000);
            }else{
                this._error(json['error']);
            }
        },

        _cancel : function(){
            this.hide();
            return false;
        },

        _error : function(error){
            var next = this.form.next();
            if(!next[0]){
                next = $('<span/>').insertAfter(this.form).addClass('error');
            }
            next.html(error).stop().show();
            this._delay(function(){
                next.animate({
                    opacity : 0
                }, 500, function(){
                    $(this).hide().css('opacity', 1);
                });
            }, 2000);
        },

        _blur : function(){
            var val = $.trim(this.element.find('input').val());
            if(!val && !val.length){
                this._cancel();
            }else{
                this._save();
            }
        },

        show : function(delay){
            var _this = this;
            _this.form.show();
            _this.add.hide();
            this._delay(function(){
                _this.element.closest('.department-name').addClass('focus');
            }, delay || 0);

        },

        hide : function(delay){
            var _this = this;
            _this.form.hide();
            _this.add.show();
            _this.name.val('');
            this._delay(function(){
                _this.element.closest('.department-name').removeClass('focus');
            }, delay || 0);
        },

        _destroy : function(){

        }
    });

    $.widget('auth.edit', {
        options : {
            fid : 0,
            id : 0,
            name : '',
            'can-delete' : false,
            'form-tpl' : '<form onsubmit="return false;"><input type="text" class="name"/><button class="save">保存</button><button class="cancel">取消</button></form>',
            'del-tpl' : '<a class="delete">删除该部门？</a>',

            'save-url' : '',
            'delete-url' : ''
        },

        _create : function(){
            this._createForm();
            var root = this.element;
            root.find('.title, .option-box').hide();
            root.find('.name').val(this.options['name']).focus();
        },

        _createForm : function(){
            this.element.append('<div class="item-hd-edit clearfix">' + this.options['form-tpl'] + (this.options['can-delete']? this.options['del-tpl'] : '') + '</div>');
        },

        _init : function(){
            this._on({
                'click .save' : '_save',
                'click .cancel' : '_cancel',
                'click .delete' : '_delete'
            });
        },

        _save : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var name = $.trim(target.prev().val());
            if(!name || _this.options['name'] == name){
                _this._cancel();
                return false;
            }
            var guid = $.globalAjaxLoad.bind(target);
            var xhr = $.post(
                _this.options['save-url'],
                {fid : _this.options['fid'], id : _this.options['id'], name : name}
            );
            xhr.guid = guid;
            xhr.done(function(json){
                _this._saveBack(name, json);
            });
        },

        _saveBack : function(name, json){
            this.element.find('.title').html(name);
            this._trigger('save', null, [this.options['id'], name]);
            this._cancel();
        },

        _cancel : function(){
            this.element.edit('destroy');
            return false;
        },

        _delete : function(event){
            var _this = this;
            var guid = $.globalAjaxLoad.bind(event.currentTarget);
            var xhr = $.get(
                _this.options['delete-url'],
                {fid : _this.options['fid'], id : _this.options['id']}
            );
            xhr.guid = guid;
            xhr.done(function(){
                _this._cancel();
                _this._trigger('delete', null, [_this.options['id'], _this.options['fid']]);
            });
        },

        _destroy : function(){
            this._off(this.element, 'click');
            this.element.find('.title, .option-box').show();
            this.element.find('.item-hd-edit').remove();
        }
    });

    $.widget('auth.item', {
        options : {
            tpl : '',
            'child-url' : '',
            'create' : '',
            'update' : '',
            'delete' : '',
            'user-update-bid' : '',
            'user-update' : '',
            'user-form' : ''
        },

        _create : function(){
            var root = this.element;
            this.fid = root.data('fid');
            this.id = root.data('id');
            this.name = root.data('name');
            this.depth = root.data('depth');
            this.isLast = root.data('last') > 0 ? true : false;
            this.childBox = root.find('.item-child');
            this.toggle = root.find('.item-child-toggle');
            this.add = root.find('.add');
            this.userForm = $(this.options['user-form']);
        },

        _init : function(){
            var root = this.element;
            this._on(root.find('.rename'), {
                'click' : '_edit'
            });

            this._on(this.toggle, {
                'click' : '_child'
            });

            this._on(this.add, {
                'click' : '_add'
            });

            this._on(root.find('.member'), {
                click : '_userEdit'
            });

            this._on(root.find('.member-add'), {
                'click' : '_userAdd'
            });

            root.find('.members').userdd({
                'update-url' : this.options['user-update-bid'],
                bid : this.id
            });

            $.globalAjaxLoadImg = false;
            this.toggle.trigger('click');
            $.globalAjaxLoadImg = true;
        },

        _add : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var add = target.closest('.item-child-add');
            if(add.is(':auth-create')){
                add.create('show');
            }else{
                add.create({
                    fid : _this.id,
                    'save-url' : _this.options['create'],
                    tpl : _this.options['tpl'],
                    save : function(event, json){
                        $('.item[data-id="' + json[0]['fid'] + '"]').item('append', json);
                    }
                });
            }
        },

        _edit : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var hd = target.closest('.department-name');
            hd.edit({
                fid : _this.fid,
                id : _this.id,
                name : _this.name,
                'can-delete' : _this._checkDelete(),
                'save-url' : _this.options['update'],
                'delete-url' : _this.options['delete'],
                'save' : function(event, id, name){console.log(id, name);
                    $('.item[data-id="' + id + '"]').item('rename', name);
                },
                'delete' : function(event, id, fid){
                    $('.item[data-id="' + id + '"]').item('destroy').remove();
                    $('.item[data-id="' + fid + '"]').item('checkChild');
                }
            });
        },

        _child : function(event){
            var _this = this;
            var target = _this.toggle || $(event.currentTarget);
            var childBox = _this.childBox;
            if(childBox.data('ajax')){
                var isVisible = target.data('state');
                _this[isVisible ? '_hideChild' : '_showChild']();
                return;
            }
            if(groups_obj[_this.id]){
                childBox.data('ajax', true);
                _this._showChild();
                _this._appendChild(groups_obj[_this.id]);
                return;
            }
            var guid = $.globalAjaxLoad.bind(target);
            var xhr = $.getJSON(
                _this.options['child-url'],
                {fid : _this.id, depath : _this.depth + 1},
                function(){
                    childBox.data('ajax', true);
                    _this._showChild();
                }
            );
            xhr.guid = guid;
            $.when(xhr).done(function(json){
                if(json && json[0] && json[0]['info']){
                    _this._appendChild(json[0]['info']);
                }
            });
            return xhr;
        },

        append : function(info, root){
            var _this = this;
            _this.toggle.show();
            if(!_this.childBox.data('ajax') && !root){
                _this._child();
            }else{
                info[0]['is_last'] = 1;
                _this._showChild();
                _this._appendChild(info);
            }
        },

        _showChild : function(){
            this.toggle.addClass('item-child-toggle-up').html(this.toggle.data('hide')).data('state', true);
            this.childBox.show();
        },

        _hideChild : function(){
            this.toggle.removeClass('item-child-toggle-up').html(this.toggle.data('show')).data('state', false);
            this.childBox.hide();
        },

        _appendChild : function(info){
            $.each(info, function(i, n){
                $.userCache.more(n['user']);
            });
            var _this = this;
            $(_this.options['tpl']).tmpl({groups : info, fid : _this.id}).appendTo(_this.childBox);
            _this._bindChild();
            _this.isLast = false;
        },

        _bindChild : function(){
            var _this = this;
            _this.childBox.find('.item').filter(function(){
                return !$(this).is(':auth-item');
            }).item({
                tpl : _this.options['tpl'],
                'child-url' : _this.options['child-url'],
                'create' : _this.options['create'],
                'update' : _this.options['update'],
                'delete' : _this.options['delete'],
                'user-update-bid' : _this.options['user-update-bid'],
                'user-update' : _this.options['user-update'],
                'user-form' : _this.options['user-form']
            });
        },

        checkChild : function(){
            if(!this.element.find('.item-child').children().length){
                this._hideChild();
                this.toggle.hide();
                this.isLast = true;
            }
        },

        _checkDelete : function(){
            return this.isLast && this.element.find('>.item-bd .member').length == 0;
        },

        rename : function(name){
            this.name = name;
        },


        _userAdd : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            if(target.hasClass('on')) return;
            $('.member-add.on, .member.on').removeClass('on');
            _this.userForm.userform('show', {
                bid : _this.id,
                target : target
            });
        },

        _userEdit : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            if(target.hasClass('on')) return;
            $('.member-add.on, .member.on').removeClass('on');
            var id = target.data('id');
            var info = $.userCache.fetch(id);
            _this.userForm.userform('show', {
                bid : _this.id,
                target : target,
                userInfo : info
            });
        },

        _destroy : function(){
            this._off(this.element, 'click mouseenter mouseleave');
        }
    });

})(jQuery);