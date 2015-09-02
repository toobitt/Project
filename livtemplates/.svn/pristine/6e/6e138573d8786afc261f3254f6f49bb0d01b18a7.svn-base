$(function(){
    (function($){
        $.widget('interact.tv_interact',{
            options : {
                ohms : null,
            },

            _create : function(){
            },

            _init : function(){
                this._on({
                    'click .icon' : '_upload',
                    'click .indexpic' : '_uploadIndexpic',
                    'change .date-picker' : '_changetime',
                    'click .set-suoyin' : '_setSuoyin',
                    'click #every_day' : '_checkSelect',
                    'click .image-option-del' : '_delImg',
                    'click .pick-way' : '_showPickway'
                });
                //this._default();
                this._initInputFile();
                this.timeGet();
            },

            _default : function(){
                this._initInputFile();
            },    

            /** file change */
            _initInputFile : function(){
                var _this = this;
                //console.log( this.element.find('.upload-file').length );
                this.element.find('.upload-file').ajaxUpload({
                    url : './run.php?mid=' + gMid + '&a=upload',
                    phpkey : 'Filedata',
                    after : function( json ){
                        var obj = [];
                        if( json['data'] instanceof Array ){
                            obj = json['data'];
                        }else{
                            obj = [json['data']];
                        }
                        _this._ajaxUploadAfter(obj);
                    }
                });
            },  

             _ajaxUploadAfter : function(json){
                var data = [],
                    ids_arr = [],
                    _this = this;
                $.each( json, function(k,v){
                    var obj = v;
                    obj._sSrc = $.globalImgUrl( v, '30x30' );
                    obj._mSrc = $.globalImgUrl( v, '80x' );
                    obj._bigsrc = $.globalImgUrl( v );
                    if( !v['id'] ){
                        obj.id = v['material_id']
                    }
                    ids_arr.push( obj['id'] );
                    data.push( obj );
                    var item_tpl  =
                        '<div class="item-box">' +
                            '<span class="del"></span>'+
                            '<div class="item-inner-box">' +
                                '<a class="suoyin set-suoyin"></a>' +
                                '<img class="image" imageid="'+obj.id+'" bigsrc="'+obj._bigsrc+'" src="'+obj._mSrc+'">' +
                            '</div>' +
                            '<div class="nooption-mask"></div>' +
                            '<div class="image-option-box">' +
                            '<span class="image-option-del image-option-item"></span>' +
                            '</div>' +
                            '<input type="hidden" value="'+obj.id+'" name="material_id[]" />' + 
                        '</div>';       
                    $(item_tpl).prependTo("#img-list");                                   
                });

                if( this.externalCall ){    //具体操作实例化时自己写
                    $('.indexpic').find('img').attr('src', data[0]._bigsrc);
                    $('.indexpic').find('input[name="indexpic"]').val(data[0].id);
                    if ($('.indexpic').find('span').hasClass('indexpic-suoyin')) {
                        $('.indexpic').find('span').removeClass('indexpic-suoyin').addClass('indexpic-suoyin-current');   
                    }                
                }    
            },                                   

            _upload : function(event){
                this.externalCall = false;
                var self = $(event.currentTarget);
                self.closest('.img-info').find('input[type="file"]').trigger('click');
            },
            //点击上传索引图时触发
            _uploadIndexpic : function(event){
               this.externalCall = true;
               var self = $(event.currentTarget);
               self.closest('.img-info').find('input[type="file"]').trigger('click');        
            },            


            _changetime : function(event){
                var self = $(event.currentTarget),
                    start_date = this.element.find('input[name="start_date"]').val(),
                    end_date = this.element.find('input[name="end_date"]').val(),
                    start_date = start_date.replace(/-/g,'/'),
                    end_date = end_date.replace(/-/g,'/'),
                    start_date = new Date(start_date),
                    end_date = new Date(end_date),
                    end_date = end_date.getTime(),
                    start_date = start_date.getTime(),
                    tip = '';
                    if(start_date > end_date){
                        tip = "初始日期不能大于结束日期";
                        this._myTip(self , tip);
                        self.val('');
                        return false;
                    }
            },

            //侧滑设索引图
            _setSuoyin : function( event ){
                var self = $(event.currentTarget),
                id = self.siblings('.image').attr('imageid');
                src = self.siblings('.image').attr('bigsrc');
                this.element.find('.set-suoyin').not(self).removeClass('suoyin-current');
                self.toggleClass( 'suoyin-current');
                if(self.hasClass('suoyin-current')) {
                    $('.indexpic').find('img').attr('src', src);
                    $('.indexpic').find('input[name="indexpic"]').val(id);
                    $('.indexpic').find('span').removeClass('indexpic-suoyin').addClass('indexpic-suoyin-current');
                } else {
                    $('.indexpic').find('img').attr('src', '');
                    $('.indexpic').find('input[name="indexpic"]').val(0);
                    $('.indexpic').find('span').removeClass('indexpic-suoyin-current').addClass('indexpic-suoyin');                   
                }
            },


            timeGet : function(){
                var _this = this;
                $('.m2o-form').on({
                    'mousedown' : function(){
                        var disOffset = {left : 0, top : 0};
                        var $this = $(this);
                         _this.options.ohms.ohms('option', {
                            time : $this.is('input') ? $this.val() : $this.html(),
                            target : $this
                        }).ohms('show', disOffset);
                        return false;
                    },
                    'set' : function(event, hms){
                        var $this = $(this);
                        var time = [hms.h, hms.m, hms.s].join(':');
                        if( $this.is('input') ){
                            var box = $this.parent('span'),
                                bool = $this.is('.start'),
                                other = bool ? box.find('input.end') : box.find('input.start'),
                                otherval = other.val();
                            if( otherval ){
                                if( bool && (time >= otherval)){
                                    _this._myTip( $this, '开始时间不能大于或等于结束时间' );
                                    return false;
                                }
                                if( !bool && time <= otherval ){
                                    _this._myTip( $this, '结束时间不能小于或等于开始时间' );
                                    return false;
                                }
                            }
                            $this.val(time);
                        }
                    }
                }, '.way-time');
            },   

            _checkSelect : function (event) {
                var self = $(event.currentTarget);
                var bool = self.is(':checked');
                if (bool) {
                    self.closest('#week_date').find('.n-h').not(self).attr("checked","checked");
                } else {
                    self.closest('#week_date').find('.n-h').not(self).removeAttr('checked');
                }
            },      
            _delImg : function (event) {
                var self = $(event.currentTarget);
                var imgid = self.closest('.item-box').find('.image').attr('imageid');
                var indexpicid = $('.indexpic').find('input[name="indexpic"]').val();
                
                self.closest('.item-box').slideUp(function(){             
                    this.remove();
                    if (imgid == indexpicid) {
                        $('.indexpic').find('img').attr('src', '');
                        $('.indexpic').find('input[name="indexpic"]').val(0);
                        $('.indexpic').find('span').removeClass('indexpic-suoyin-current').addClass('indexpic-suoyin');                        
                    }

                });
            },  
            
            _showPickway : function( event ){
            	var self = $( event.currentTarget ),
            		item = this.element.find('.pick-self'),
            		way = self.val();
            	if( way == 0 ){
            		item.show();
            	}else{
            		item.hide();
            	}
            },

            _myTip : function(self , tip ){
                self.myTip({
                    string : tip,
                    delay: 1000,
                    width : 150,
                    dtop : 0,
                    dleft : 80,
                });
            },
        });
    })($);

    $('.m2o-form').tv_interact({
        ohms: $('#ohms-instance').ohms(),
    });
});