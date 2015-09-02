var gAjaxQueueHash = new Array();
var gAjaxQueue = new Array();
function hg_remove_from_arr(arr,objValue)
{
   return $.grep(arr, function(cur,i){
		  return cur != objValue;
	   });
}
$(document).ready(function()
{
	hg_get_from_request_queuehash = function ()
	{
		var hash;
		if (top)
		{
			hash = top.gAjaxQueueHash.shift();
		}
		else
		{
			hash = gAjaxQueueHash.shift();
		}
		return hash;
	}

	hg_get_from_request_queue = function (hash)
	{
		var queue;
		if (top)
		{
			queue = top.gAjaxQueue[hash];
		}
		else
		{
			queue = gAjaxQueue[hash];
		}
		return queue;
	}

	hg_delete_from_request_queue = function (hash)
	{
		if (top)
		{
			top.gAjaxQueueHash = hg_remove_from_arr(top.gAjaxQueueHash, hash);//请求完成
			delete(top.gAjaxQueue[hash]);
		}
		else
		{
			gAjaxQueueHash = hg_remove_from_arr(gAjaxQueueHash, hash);//请求完成
			delete(gAjaxQueue[hash]);
		}
	}

	hg_add_request2queue = function (hash, queue)
	{
		if (top)
		{
			if (-1 != $.inArray(hash, top.gAjaxQueueHash))
			{
				return false;
			}
			top.gAjaxQueueHash.push(hash); //加入请求队列
			top.gAjaxQueue[hash] = queue;
		}
		else
		{
			if (-1 != $.inArray(hash, gAjaxQueueHash))
			{
				return false;
			}
			gAjaxQueueHash.push(hash); //加入请求队列
			gAjaxQueue[hash] = queue;
		}
		return true;
	}

	hg_request_to = function (url, data, type, callback, request_clew)
	{
		if (!type)
		{
			type = 'post';
		}
		if(!data)
		{
			data = {};
		}
		var queue = {
			url : url,
			data : data,
			type : type,
			request_clew : request_clew,
			callback : callback
		}
		var hash = md5(url);
		if (!hg_add_request2queue(hash, queue))
		{
			var tqueue = hg_get_from_request_queue(hash);
			hg_msg_show(tqueue.url + '请求正在处理中，请稍后操作' + queue.url);
			setTimeout("hg_delete_from_request_queue('" + hash + "')", 5000);
			return;
		}
		hg_do_ajax(hash);
	}
	hg_do_ajax = function (hash)
	{
		if (!hash)
		{
			hash = hg_get_from_request_queuehash();
			if (!hash)
			{
				return;
			}
		}
		try
		{
			var queue = hg_get_from_request_queue(hash);
			if (!queue)
			{
				return;
			}
			if (!queue.request_clew || queue.request_clew == 0)
			{
				hg_msg_show('正在发送请求......', 1);
			}
		}
		catch (e)
		{
			hg_msg_show('sorry,数据丢失' + e.message, 1);
			return;
		}
		queue.data['ajax'] = 1;
		$.ajax({
			url:queue.url,
			type:queue.type,
			dataType:"html",
			cache:false,
			timeout: TIME_OUT,
			data:queue.data,
			success:function(json){
				if(queue.callback)
				{
					var _json = $.parseJSON(json);
					if((typeof(_json.callback)) != 'string')
					{
						var fn = queue.callback + '(' + json + ')';
						eval(fn);
					}
				}
				hg_call_back(json, queue.request_clew);
				hg_delete_from_request_queue(hash);
			},
			error:function(){
				if (!queue.request_clew || queue.request_clew == 0)
				{
					hg_msg_show('请求失败，<a onclick="hg_do_ajax(\'' + hash + '\');return false;" style="cursor:pointer;color:#000">重试</a>', 1);
					setTimeout("hg_delete_from_request_queue('" + hash + "');", 10000);
				}
			}
		});
	}
})