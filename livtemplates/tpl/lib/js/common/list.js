window.App = window.App || $({});

/*info区域*/
$(function($) {
    var get_space = function() {
        var space;
        return window == top ? 0 : (space = parent.$('#livnodewin').offset().top, parent == top) ? space: space + top.$('#mainwin').offset().top;
    };
    /*列表头部顶端与浏览器视口顶端之间的空间*/
    var info = $(".edit_show"),
    /*info区域最外层的元素*/
    arrow = $("#arrow_show"),
    /*箭头，指向当前li的i图标*/
    infoCont = $("#edit_show"),
    /*放内容的元素*/
    isshow = false,
    /*状态变量：info区域是否正在显示*/
    which,
    /*当前记录的id*/
    loadingIcon = '<img src="' + RESOURCE_URL + 'loading2.gif' + '" style="width:50px;height:50px;"/>',
    hg_resize_nodeFrame = window.hg_resize_nodeFrame;
    /*调整显示区域的函数*/

    function hide_info() {
        if (!isshow) return;
        info.stop().animate({
            right: "-440px"
        },
        function() {
            info.hide();
            infoCont.html('');
            hg_resize_nodeFrame(true);
        });
        $("#r_" + which).removeClass("current");

        isshow = false;
        which = null;
    }
    function show_info(id, para) {
        var li, css, animate, h, scrollH = Math.max($(document).scrollTop(), $(window.parent.document).scrollTop(), $(top.parent.document).scrollTop());
        //计算info据ownerDocument顶的高度
        h = scrollH <= get_space() ? 0 : (scrollH - get_space());
        if (isshow) {
            animate = {
                right: 0,
                top: h + "px"
            };
            css = {};
        } else {
            animate = {
                right: 0
            };
            css = {
                top: h + "px"
            };
        }

        infoCont.html(loadingIcon);
        /*等待图标*/
        isshow && $("#r_" + which).removeClass("current");
        which = id;
        li = $("#r_" + which).addClass("current");
        /*调整指向头的高度:*/
        arrow[isshow ? "animate": "css"]({
            "top": (li.offset().top + li.height() / 2 - 5 - h - 7) + "px"
        });
        info.css(css).show().stop().animate(animate);

        var selfId = which;

        $.get(!para ? "run.php": "run.php?" + para, {
            a: "show_opration",
            mid: gMid,
            id: selfId
        },
        function(html) {
            /*用户点了其他记录或是关闭了info区域，则忽略返回*/
            if (selfId != which || !isshow) return;

            setTimeout(function() {
                infoCont.html(html);
                hg_resize_nodeFrame();
            },
            300);
        })

        isshow = true;
    }
    var gDragMode = false;
    function hg_show_opration_info(id, para) {
        if (gDragMode) return;
        if (isshow && id == which) {
            hide_info();
        } else {
            show_info(id, para);
        }
    }
    App.on("openDragMode",
    function() {
        gDragMode = true;
        hide_info();
    }).on("closeDragMode",
    function() {
        gDragMode = false;
    });
    App.on("optionIframeOpen",
    function() {
        hide_info();
    });

    $.extend(window, {
        hg_show_opration_info: hg_show_opration_info,
        hg_close_opration_info: hide_info
    });
});

/*排序*/
$(function($) {
    /*和状态相关的变量*/
    var old_order_ids,
    /*当拖动模式开始时，排序的id*/
    old_ids,
    /*当拖动模式开始时，按顺序记录下id，用于每次拖动停止时计算是否需要保存*/
    gDragMode = false;

    function getOrderId() {
        return el.find("li").map(function() {
            return $(this).attr(order_name);
        }).get();
    }
    function setOrderId(old_order_ids) {
        el.find("li").each(function(i) {
            $(this).attr(order_name, old_order_ids[i])
        });
    }
    function getIds() {
        return el.find("li").map(function() {
            return $(this).attr("_id");
        }).get();
    }
    function constructState() {
        gDragMode = true;
        old_order_ids = getOrderId();
        old_ids = getIds();
    }
    function destructState() {
        gDragMode = false;
    }
    function needSave() {
        return old_ids && old_ids.join(',') != getIds().join(',')
    }
    function hg_save_order() {
        var ids = getIds().join(',');

        $.post("run.php", {
            ajax: 1,
            mid: gMid,
            a: "drag_order",
            content_id: ids,
            order_id: old_order_ids.join(',')
        });
        setOrderId(old_order_ids);
        destructState();
        closeDragView("排序保存成功");
    }

    var el = $(".hg_sortable_list").eq(0),
    table_name = el.data("table_name"),
    order_name = el.data("order_name"),
    tip = $('#infotip'),
    saveBtn = $('<input class="button_4" style="margin-left:10px;" type="button" value="保存排序" onclick="hg_save_order();" />');

    function openDragView() {
        tip.stop().css({
            "opacity": 1,
            "height": 40
        }).show().html('排序模式已开启<span onclick="hg_switch_order();" style="color:#666;margin:0 0 0 5px;font-size:12px;cursor: pointer;">退出</span>');
        el.find("li").find("a[name='alist[]']").addClass('pic_logo').find("input").css('visibility', 'hidden');
        el.addClass("gDragMode").sortable("option", "disabled", false);
        App.trigger("openDragMode");
    }
    function closeDragView(msg) {
        el.find("li").find("a[name='alist[]']").removeClass('pic_logo').find("input").css('visibility', 'visible');
        el.removeClass("gDragMode").sortable("option", "disabled", true);
        tip.html(msg).fadeOut(2000);
        App.trigger("closeDragMode");
    }
    el.sortable({
        revert: true,
        cursor: "move",
        axis: "y",
        scrollSpeed: 100,
        tolerance: 'intersect',
        stop: function(e, ui) {
            /*每次拖动停止时检查是否需要保存*/
            if (needSave()) {
                tip.append(saveBtn);
            } else {
                saveBtn.remove();
            }
        },
        disabled: true
    });

    $.extend(window, {
        hg_switch_order: function() {
            if (gDragMode) {
                if (needSave()) {
                    if (confirm('排序已改变，您确定要放弃此次排序吗？')) {
                        destructState();
                        closeDragView("排序模式已关闭");
                        location.reload();
                    }
                } else {
                    destructState();
                    closeDragView("排序模式已关闭");
                }
            } else {
                openDragView();
                constructState();
            }
        },
        hg_save_order: hg_save_order
    });
});
/*当排序模式时，点击标题打开编辑页的操作去掉*/
$(function($) {
    var els;
    App.on("openDragMode",
    function() {
        els = $(".option-iframe");
        els.removeClass("option-iframe");
    }).on("closeDragMode",
    function() {
        els.addClass("option-iframe");
    });
});

/* 权重，info */
(function() {
    var __slice = [].slice;

    $(function() {
        var ActionBox, Collection, Model, Record, RecordView, Records, View, WeightBox, create_rgb_color, exports, records, _ref;
        exports = window;
        if (! ((_ref = exports.globalData) != null ? _ref.list : void 0)) {
            return;
        }
        Model = Backbone.Model;
        Collection = Backbone.Collection;
        View = Backbone.View;
        RecordView = View.extend({
            options: {
                url: "./run.php?mid=" + gMid + "&a=update_weight"
            },
            events: {
                'click .common-quanzhong': function(e) {
                    return Backbone.trigger('openWeightBox', this.$('.common-quanzhong'), this.model, e, this);
                },
                'click .common-list-biaoti': function(e) {
                    if (e.target == e.currentTarget) {
                        hg_show_opration_info(this.model.id);
                    }
                },
                'click': function(e) {
                    var el = $(e.target);
                    if (!el.is('a')) {
                        el = el.closest('a');
                    }
                    if (el.attr('target') == 'formwin') {
                        hg_close_opration_info();
                    }
                },
                'click .common-list-right .common-list-item': function(e) {
                    var target = $(e.currentTarget);
                    var text = target.text().trim();
                    if (!text && !target.find('img').size()) {
                        if (target.find('.need-switch').size()) return;
                        hg_show_opration_info(this.model.id);
                    }
                }
            },
            initialize: function(options) {
                var _this = this;
                this.model.on('change:weight', this.change_weight, this);
                return this.model.on('change:status',
                function(model, status) {
                    return exports.changeStatusLabel(status, model);
                });
            },
            change_weight: function(record, new_weight, options) {
            	this.$('.common-quanzhong').css('background', create_color_for_weight(new_weight)).find('span').text(new_weight);
            	if (options.nosave) return;
                var data;
                data = {};
                data[this.model.id] = new_weight;
                $.post(this.options.url, {
                    data: JSON.stringify(data)
                });
            }
        });
        Record = Model.extend({
            validate: function(attrs, options) {
                var weight;
                weight = attrs.weight;
                weight = Math.ceil( + weight);
                if (isNaN(weight) || weight < 0 || weight > 100) {
                    return 'weight error';
                } else {
                    return null;
                }
            }
        });
        Records = Collection.extend({
            model: Record
        });
        WeightBox = View.extend({
            events: {
                'click': function(e) {
                    return e.originalEvent['pass?'] = true;
                },
                'click li': function(e) {
                	this.save( $(e.currentTarget).data('weight') );
                    this.close();
                }
            },
            initialize: function() {
                var _this = this;
                var deferSaveFn = hg_defer(function(weight) {
                	this.save(weight);
                }, 500, true, this);
				this.slide = this.$("#listWeightSlider").slider({
					animate: true,
					max: 100,
					min: 0,
					slide: function(e, ui) {
						_this.boss_view.change_weight(_this.boss_model, ui.value, {
							nosave: true
						});
						deferSaveFn(ui.value);
					}
				});
				
                App.on('openDragMode',
                function() {
                    _this.disabled = true;
                }).on('closeDragMode',
                function() {
                    _this.disabled = false;
                });
                Backbone.on('openWeightBox', this.open, this);
                Backbone.on('closeWeightBox', this.close, this);
                $('body').on('click',
                function(e) {
                    if (!e.originalEvent['pass?']) {
                        return _this.close(null, true);
                    }
                });
            },
            save: function(weight) {
                if ((_ref1 = this.boss_model) != null) {
                    _ref1.set('weight', +weight, {
                        validate: true,
                        nosave: false
                    });
                }
            },
            open: function(position_el, model, e, boss_view) {
                if (this.disabled) return;
                var left, top, _ref1;
                e.originalEvent['pass?'] = true;
                if (this.boss_model === model) {
                    return this.close();
                }
                this.boss_model = model;
                this.boss_view = boss_view;
                _ref1 = position_el.offset(),
                left = _ref1.left,
                top = _ref1.top;
                top += position_el.height();
                this.$el.show().css({
                    'left': left,
                    'top': top
                });
                if (top + 257 + 24 > $(document).height()) {
                    this.$el.addClass('box-updown');
                } else {
                    this.$el.removeClass('box-updown');
                }
                
                this.slide.slider('value', model.get('weight'));
            },
            close: function(weight, noresize) {
                var _ref1;
                this.$el.hide();
           
                this.boss_model = null;
            }
        });
        ActionBox = View.extend({
            el: $('#record-edit'),
            events: {
                'click .record-edit-close': 'hide',
                'click .record-edit-confirm a,.record-edit-confirm-close': function(e) {
                    var _base;
                    this.$el.removeClass('confirm-model');
                    if (typeof(_base = this.$(e.target).data('callback')) === "function") {
                        _base(true);
                    }
                    return this.$(e.target).data('callback', null);
                },
                'click .record-edit-back-close': function() {
                    this.$('.record-edit-play').empty();
                    return this.$el.removeClass('play-model');
                },
                'click .record-edit-play-shower': 'render_play',
                'click': function(e) {
                    if ($(e.target).attr('target') == 'formwin') {
                        hg_close_opration_info();
                    }
                },
                'click a': function(e) {
                    var a = $(e.target);
                    if (a.text() == '专题') {
                        special_publish.show(this.model);
                        e.preventDefault();
                    } else if (a.text() == '区块') {
                        block_publish.show(this.model);
                        e.preventDefault();
                    }
                }
            },
            initialize: function() {
                var old_hg_ajax_post, _this = this;

                App.on('openDragMode',
                function() {
                    if (_this.$el.is(':visible')) {
                        _this.hide();
                    }
                    _this.disabled = true;
                }).on('closeDragMode',
                function() {
                    _this.disabled = false;
                });
                old_hg_ajax_post = exports.hg_ajax_post;
                exports.hg_ajax_post = function() {
                    var args, msg, need, obj;
                    args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
                    obj = args[0],
                    msg = args[1],
                    need = args[2];
                    args[2] = false;
                    if (!need) {
                        old_hg_ajax_post.apply(null, args);
                    } else {
                        _this.myConfirm(msg,
                        function(ok) {
                            if (ok) {
                                return old_hg_ajax_post.apply(null, args);
                            }
                        });
                    }
                    return false;
                };
                exports.hg_show_opration_info = function(id) {
                    return _this.show(records.get(id));
                };
                return exports.hg_close_opration_info = function() {
                    return _this.hide();
                };
            },
            myConfirm: function(msg, callback) {
                this.$el.addClass('confirm-model');
                return this.$('.record-edit-confirm-btn a:first').data('callback', callback);
            },
            template: (function() {
                $.template("info", $('#record-edit').html());
                return function(data) {
                    return $.tmpl('info', data);
                };
            })(),
            template_play: (function() {
                $.template("play", _.unescape($('#vedio-tpl').html()));
                return function(data) {
                    return $.tmpl('play', data, {
                        width: this.getWH()[0],
                        height: this.getWH()[1]
                    });
                };
            })(),
            getWH: _.once(function() {
                this.$el.show();
                return [this.$el.width(), this.$el.height()];
            }),
            render_play: function() {
                this.$el.addClass('play-model');
                return this.$('.record-edit-play').html(this.template_play.call(this, this.model.toJSON()));
            },
            render: function() {
                return this.$el.html(this.template(this.model.toJSON()));
            },
            show: function(model) {
                if (!this.model && !model) return;
                if (this.disabled) return;
                var height, left, stopfn, top, width, _ref1, _ref2, _this = this, boundH, attrs;
                if (this.model === model) {
                    return this.hide();
                }
                if (this.model) {
                    $("#r_" + this.model.id).removeClass('current');
                }
                this.model = model;
                this.render().removeAttr('class');
                _ref1 = $("#r_" + this.model.id).addClass('current').find('.common-list-i').offset(),
                left = _ref1.left,
                top = _ref1.top;
                this.$el.css({
                    right: $(window).width() - left,
                    top: top
                });
                _ref2 = this.getWH.call(this),
                width = _ref2[0],
                height = _ref2[1];
                boundH = $(document).height();
                attrs = {
                    width: width,
                    height: height
                };
                if ( top + height >= boundH ) {
                	attrs.top = top - height + 28;
                	this.top = top + 28;
                	this.$el.addClass('upmodel');
                } else {
                	this.$el.removeClass('upmode');
                }
                this.$el.find('.record-edit').hide();
                stopfn = function() {
                    _this.$el.find('.record-edit').removeAttr('style');
                };
                return this.$el.css({
                    height: 0,
                    width: 0
                }).stop(true, true).show().animate(attrs, 200, stopfn);
            },
            hide: function() {
                var stopfn, _this = this, attrs;
                if (this.$el.is(':hidden')) return;
                this.$('.record-edit').hide();
                if (this.model) {
                    $("#r_" + this.model.id).removeClass('current');
                }
                this.model = null;
                stopfn = function() {
                    _this.$el.hide();
                };
                attrs = {
                    width: 0,
                    height: 0
                };
                if ( this.$el.hasClass('upmodel') ) {
                	attrs.top = this.top;
                }
                return this.$el.stop(true, true).animate(attrs, 200, stopfn);
            }
        });

        var Publish_box = View.extend({
            events: {
                'click .common-list-pub-close': 'close', 
                'click .publish-box-save': function() {
                	this.$('form').ajaxSubmit();
                	this.close();
                }
            },
            initialize: function(options) {
            	var _this = this;
            	this.$('.publish-box')[options.plugin]({});
            	App.on('openDragMode', function() { _this.close(); });
            },
            getLi: function() {
            	return $('#r_' + this.model.id);
            },
            adjustPosition: function() {
            	var liTop = this.getLi().offset().top;
            	var t = liTop >= 200 ? liTop - 200 : 0;
            	var pH, dH;
            	var pub = this.$el;
            	if ( t + (pH = pub.outerHeight()) > (dH = $(document).height()) ) {
					t = dH - pH - 50;
				}
				pub.css({ top: t, 'margin-left': pub.outerWidth() / -2 });
            },
            show: function(model) {
                this.model = model;
                var id = model.id;
                this.adjustPosition();
                this.initPublish();
            },
            initPublish: function() {
            	//标题
            	this.$('.common-list-pub-title div p').text( _.unescape(this.model.get('title')) );
                //隐藏域
                this.$('input[name="id"]').val(id);
                var special_id = this.model.get('special_id');
                this.$el.find('.publish-box').data('publish').initForOne(special_id);
            },
            close: function() {
                this.$el.css('top', '');
            }
        });

        if ($('#record-edit').size()) {
            new ActionBox;
        }
        new WeightBox({
            el: $('#weight_box')
        });
        records = new Records;
        records.on('add',
        function(record) {
            var id, li;
            id = record.get('id');
            li = $("#r_" + id);
            if (li.length) {
                return new RecordView({
                    el: li,
                    model: record
                });
            }
        });
        
        var special_publish = new Publish_box({
            el: $('#special_publish'),
            info_url: 'get_special_column.php?a=get_special_column',
            plugin: 'hg_special_publish'
        });
        var block_publish = new Publish_box({
            el: $('#block_publish'),
            plugin: 'hg_block_publish'
        });
        records.add(globalData.list);
        exports.recordCollection = records;
    });

    //把这个给废了
	$(function() {
	    setTimeout(function() {
	        hg_channel_edit_info = function() {};
	    },
	    100);
	});
}).call(this);

$(function() {
	$('#vodpub').on('click', '.publish-box-save', function() { 
		$('#vodpub form').submit(); 
	});
});