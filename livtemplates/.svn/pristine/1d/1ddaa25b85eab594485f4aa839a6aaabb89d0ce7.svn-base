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
            return $(this).attr(order_name) || $(this).attr('orderid');
        }).get();
    }
    function setOrderId(old_order_ids) {
        el.find("li").each(function(i) {
            $(this).attr(order_name, old_order_ids[i])
            $(this).attr('orderid', old_order_ids[i])
        });
    }
    function getIds() {
        return el.find("li").map(function() {
            return $(this).attr("_id") || this.id.slice(2);
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
            order_id: old_order_ids.join(','),
            table_name: table_name
        });
        setOrderId(old_order_ids);
        destructState();
        closeDragView("排序保存成功");
    }

    var el = $(".hg_sortable_list").eq(0),
    table_name = el.data("table_name"),
    order_name = el.data("order_name") || 'order_id',
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