$(function($){
    $.widget('caiji.config', {
        options : {
            tpl : '',
            tname : 'config-each',
            url : ''
        },

        _create : function(){
            $.template(this.options.tname, this.options.tpl);
            this.addBox = this.element.find('.add');
        },

        _init : function(){
            this._on({
                'click .del' : '_del',
                'click .del-site' : '_delSite',
                'click .save' : '_save',
                'click .add-domain' : '_addDomain',
                'click .save-domain' : '_saveDomain'
            });
        },

        _delPost : function(data, callback){
            $.post(
                this.options.del,
                data,
                function(){
                    callback && callback();
                }
            );
        },

        _del : function(event){
            if(!confirm('确定删除？')){
                return false;
            }
            var target = $(event.currentTarget);
            var li = target.closest('li');
            var domain = li.data('val');
            if(!domain) return;
            var _this = this;
            this._delPost({domain : domain}, function(){
                li.remove();
                _this._post();
            });
        },

        _delSite : function(event){
            if(!confirm('确定删除？')){
                return false;
            }
            var site = $(event.currentTarget).closest('li');
            var domains = [];
            site.find('li:not(.add-domain)').each(function(){
                domains.push($(this).data('val'));
            });
            var _this = this;
            if(domains.length){
                this._delPost({'domain[]' : domains}, function(){
                    site.remove();
                    _this._post();
                });
            }else{
                site.remove();
                _this._post();
            }
        },

        _save : function(){
            var name = $.trim(this.addBox.find('._name').val());
            var domains = [];
            this.addBox.find('._domain').each(function(){
                var val = $.trim($(this).val());
                val && domains.push(val);
            });
            if(!name || !domains.length){
                return false;
            }
            $.tmpl(this.options.tname, {
                name : name,
                domains : domains
            }).insertAfter(this.addBox);
            this._post();
            this.addBox.find('input').val('');
        },

        _post : function(){
            var allConfigs = [];
            this.element.find('>li:not(.add)').each(function(){
                var $this = $(this);
                allConfigs.push({
                    name : $this.find('>div').data('val'),
                    domains : (function(){
                        var _domains = [];
                        $this.find('li:not(.add-domain)').each(function(){
                            _domains.push($(this).data('val'));
                        });
                        return _domains;
                    })()
                });
            });
            $.post(
                this.options.url,
                {data : JSON.stringify(allConfigs)},
                function(){

                }
            );
        },

        _addDomain : function(event){
            var target = $(event.currentTarget);
            var input = $('li:first', this.addBox).clone().insertBefore(target).find('input').val('');
            if(!target.closest('.add').length){
                input.after('<span class="save-domain">确定</span>');
            }
        },

        _saveDomain : function(event){
            var target = $(event.currentTarget);
            var domain = target.prev().val();
            target.closest('li').attr('data-val', domain).html('<a target="_blank" href="?a=domain&domain=' + domain + '">' + domain + '</a>');
            this._post();
        },

        init : function(configs){
            $.tmpl(this.options.tname, configs).appendTo(this.element);
        },

        _destroy : function(){

        }
    });

    $('.box').config({
        tpl : $('#tpl').html(),
        del : '?a=deleteDomain',
        url : '?a=saveConfig'
    }).config('init', configs);
});