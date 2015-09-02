$(function ($) {
	var globalData = window.globalData || {};
	var App = window.App;
	
	var form = $('#programForm');
	var box = form.find('.interactive_program');
	
	App.on("add_in_program_id", function (event, id) {
		form.find('input[name=in_program_id]').val(id);
	});
	form
		.on("click", ".addProgram", function () {
			box.append(
				'<div class="programeItem new-programeItem"><p title="点击发布"></p><input name="theme[]" /><input type="hidden" name="status[]" /><input type="hidden" name="ids[]"><span class="delBtn" /></div>'
			);
		})
		.on("click", ".delBtn", function () {
			var btn = $(this);
			var func = function () {
				btn.closest('.programeItem').remove();
			};
			if ( btn.parent().find("input[name*=ids]").val() ) {
				jConfirm('你确定要删除吗？', '删除提醒', function (yes) {
					yes && func();
					if (yes) {
						$.ajax(
							$.format('run.php?mid={0}&a={1}&id={2}', gMid, 'program_delete', btn.next().val())
						);
					}
				}).position(btn);
			} else {
				func();
			}
		})
		.on("click", ".programeItem p", function () {
			form.find(".programeItem p,.programeItem input[name*=status]").removeClass("current").val('');
			$(this).parent().find("p,input[name*=status]").addClass("current").val(1);
		})
		.submit(function () {
			var me = $(this);
			var label = me.find("input:submit").next();
			me.find("input:submit").prop("disabled", true);
			label.html("<span>保存中，请等待...</span>");
			me.ajaxSubmit(function (data) {
				me.find("input:submit").prop("disabled", false);
				try {
					data = $.parseJSON(data)[0];
					label.find("span").text("保存成功").fadeOut(3000, function () { $(this).remove(); });
					var in_program_id = data['in_program_id'];
					var ids = data['ids'];
					var idsEl = me.find("input[name*=ids]");
					$.each(ids, function (i, id) {
						idsEl.eq(i).val(id);
					});
				} catch (e) {
					label.find("span").text("保存失败").fadeOut(3000, function () { $(this).remove(); });
				}
				
				App.trigger("add_in_program_id", [in_program_id]);
			});
			return false;
		});
	
	
	
});

