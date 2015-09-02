{template:./head}
{js:qingao/base}
{js:qingao/group_groupcreate}
{js:qingao/group_indexmap_b}
{js:qingao/jquery-ui-1.8.23.custom.min}
{js:qingao/jquery.upload}
{js:qingao/qingao_activity}
{js:My97DatePicker/WdatePicker}
{js:ckeditor/ckeditor}
<style type="text/css">
.p_select {float:left; margin-right:50px; margin-top:5px; margin-bottom:20px;}
.p_select em {display:block; color:#7d7d7d; font-size:12px; margin-top:10px;}
//#group_tags {margin-top:-10px; margin-bottom:5px; padding-left:163px;}
#permission_select {float:left; margin-top:10px;}
#group_tags li {float:left; background:#CCC; color:#FFF; padding:3px 5px; margin-right:5px; margin-bottom:5px; cursor:pointer;*margin-right:4px;}
.wrap .upload_img {cursor:pointer; border:none; margin-left:163px; margin-bottom:20px; width:68px; height:28px; line-height:28px; text-align:center; color:#FFF; font-size:12px; background:url({$RESOURCE_URL}qingao/gt6.png) no-repeat 0 -28px;}
#group_tags li.selectTag {background:#369; cursor:default;}

#group_tags li.selectTag{background-color:#e21c56;}

.cke_skin_kama span.cke_browser_webkit, .cke_skin_kama span.cke_browser_gecko18{outline:none;}
</style>
<script type="text/javascript">
//<![CDATA[
var MAP_CENTER_POINT = '32.039665X118.808604';
window.onload = initialize;
//]]>
</script>
<script type="text/javascript">
$(function() {
	var indexValue;
	$('#create_group input[type="text"],textarea').focus(function() {
		indexValue = this.defaultValue;
		if ($(this).val() == indexValue)
		{
			$(this).val('');
		}
	}).blur(function() {
		if ($(this).val() == '')
		{
			$(this).val(indexValue);
		}
	});
	
	/*function hg_select_tags(input_els, tagObj, visitedClass, limit)
	{
		var arr = [];
		var input_dVal = input_els.defaultValue;
		if (input_dVal != input_els.value)
		{
			arr = input_els.value.split(',');
		}
		if (arr.length >= limit || tagObj.attr('class') == visitedClass)
		{
			return false;
		}
		arr.push(tagObj.text());
		input_els.value = arr.join(',');
		tagObj.addClass(visitedClass);
		$('#add_thread_title').select(false);
	}
	
	$('#group_tags li').click(function() {
		var obj = document.getElementById('add_thread_title');
		hg_select_tags(obj, $(this), 'selectTag', 5);
	});
	*/

    (function(){
        var list = [], cname = 'selectTag';
        $('#group_tags').on('click', 'li', function(){
            if($(this).hasClass(cname)){
                $(this).removeClass(cname);
            }else{
                var data = $('#add_thread_title').data('data');
                if(data.length >= 5){
                    return;
                }
                $(this).addClass(cname);
            }
            $('#add_thread_title').trigger('_change', $(this).attr('_text'));
        }).find('li').each(function(){
            var text = $.trim($(this).text());
            list.push(text);
            $(this).attr('_text', text);
        });

        $('#add_thread_title').on({
            blur : function(){
                var val = $.trim($(this).val());
                var tiao = val == $(this).data('_default') ? true : false;
                $('#group_tags li').removeClass('selectTag');
                var data = [];
                if(!tiao){
                    data = val.split(/[,，]/);
                    data = $.grep(data, function(n){
                        n = $.trim(n);
                        return !!n;
                    });
                    if(data.length > 5){
                        data.length = 5;
                    }
                    $.each(data, function(i, n){
                        if($.inArray(n, list) != -1){
                            $('#group_tags li[_text="'+ n +'"]').addClass(cname);
                        }
                    });
                }
                $(this).trigger('_set', [data]);
            },
            _change : function(event, text){
                var data = $(this).data('data');
                var index = $.inArray(text, data);
                if(index == -1){
                    data.push(text);
                }else{
                    data.splice(index, 1);
                }
                $(this).trigger('_set', [data]);
            },
            _set : function(event, data){
                $(this).data('data', data).val(data.join(','));
                if(!data.length){
                    $(this).val($(this).data('_default'));
                }
            },
            init : function(){
                $(this).data('_default', $.trim($(this).val()));
                $(this).trigger('blur');
                $(this).val($(this).data('_default'));
            }
        }).trigger('init');
    })();


});
</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.2&services=true"></script>
	</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain clearfix">
				<h2 class="action_title">发起行动</h2>
				<div class="add_action_steps">
							<div class="action-step1"><span>1</span>选择行动类型</div>
							<div class="action-step2"><span>2</span>填写行动信息</div>
							<div><span>3</span>提交行动页面</div>
				</div>
				<div class="add_action">							
				<form action="activitys.php?a=docreate" method="post" enctype="multipart/form-data" id="actionForm">
					<div class="add_action_step">
						<div class="add_action_field check_btn"><span class="add_action_label">行动类型</span>
							{foreach $action_type as $v}
								<label>
								<input type="radio" name="thread_type" value="{$v['id']}" {if $v['id']==1} checked="checked" {/if} />{$v['name']}
								</label>
							{/foreach}
						</div>
					</div>
					
					<div class="add_action_step">
						<div class="add_action_field"><span class="add_action_label">{$name}标题</span>
						<input value="在这里填写标题" class="action_field_txt" name="action_title" type="text">
						</div>
						<div class="add_action_field"><span class="add_action_label">{$name}宣言</span>
							<div class="action_field_main">写个小广告，为你的行动募集更多志同道合的人。拒绝啰嗦，简洁就是力量！
							<textarea rows="10" cols="50" name="slogan">
							{$slogan}</textarea>
							</div>
							<script type="text/javascript">
							CKEDITOR.replace('slogan', {
								toolbar : 'Basic',
								width : '500',
						        height : '100',
						    });
						    </script>

						    <script type="text/javascript">
                            jQuery(function($){
                                $('.action_field_main textarea').on({
                                    focus : function(){
                                        $(this).css({
                                            'border-color' : 'red',
                                            'box-shadow' : 'none'
                                        });
                                    },
                                    blur : function(){
                                        $(this).removeAttr('style');
                                    }
                                });
                            });
                            </script>
						</div>
						<div class="add_action_field action_content"><span class="add_action_label">{$name}详情</span>
						<div class="action_field_main">具体描述一下这个行动，建议不超过500字。你可以参照以下格式，同样我们也欢迎创意。
						<textarea class="action_field_text"  id="action_content" name="action_content" >
						{$action_content}</textarea>
						</div>
						</div>
						<script type="text/javascript">
						CKEDITOR.replace('action_content', {
							toolbar : 'Basic',
							width : '500',
					        height : '200',
					    });
					    </script>
						<div class="add_aciton_poster"><span class="add_action_label">上传海报</span><input id="upload_img" value="上传海报" type="button">
							<em id="show_img"><img src="" height="50" width="50"></em>
						</div>
						<div class="add_action_field add_thread_time"><span class="add_action_label">行动时间</span>
							<input name="action_start" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate action_field_sel"  type="text"><input name="action_end" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate action_field_sel"  type="text">
						</div>
					</div>
					<div class="add_action_step">
						<label class="add_action_field action_link"><span class="add_action_label">添加视频</span>
							<input class="action_field_txt" name="action_swfurl" type="text" value="上传视频，让你的行动更有吸引力。填入以.swf结尾的FLASH视频地址即可。">
						</label>
						
						<!--
						<div class="add_action_field">
							<span class="add_action_label">行动地址</span>
							<div class="action_field_main">
								<div><select class=""><option value="1">江苏</option></select><select class=""><option value="1">江苏</option></select><select class=""><option value="1">江苏</option></select>添加更多活动地点</div>
								<div><select class=""></select><select class=""></select><select class=""></select></div>
								<div><select class=""></select><select class=""></select><select class=""></select></div>
							</div>
						</div>
						
						<label class="add_action_field">
							<span class="add_action_label">详细地址</span>						
							<input class="action_field_txt" name="" type="text">
						</label>
						-->
						<div class="add_action_field  radio_btn"><span class="add_action_label">行动费用</span>
						<label><input name="apply" value="0" checked="checked" type="radio">免费</label>
						<label><input name="apply" value="1" type="radio">收费</label>
						<span>¥&nbsp;&nbsp;&nbsp;<input class="apply_num_limit action_field_sel" name="apply_num" type="text"></span>
						</div>
						<div class="add_action_field mark_loaciton">
							<span class="add_action_label" >所在位置
							</span>
							<div class="mark_map action_field_main">
								<input class="action_field_txt"  id="this_group_addr" name="" type="hidden">
								<div id="map_canvas" name="map_canvas" class="formbox" style="width:502px; height:400px;"></div>
								<div class="group_tip"><span>小提示:</span>点击地图：将小红气球定位到你需要的位置</div>
								<div class="mark_city">
									<span class="types" style="display:none;" id="showgname"></span>
									<span class="types" style="display:none;" id="showgroup_type"></span>
									<span class="types" style="display:none;" id="showprovince"></span>
									
									<input name="hid_lat" value="32.039665" id="g_lat" type="hidden">
									<input name="hid_lng" value="118.808604" id="g_lng" type="hidden">
									<input name="group_addr" value="江苏省南京报业大厦" id="group_addr" type="hidden">
								</div>
							</div>	
							
						</div>
					</div>
					
					<div class="add_action_step">
						
					    <div class="add_action_field"><span class="add_action_label">乘车路线</span>
					    	<input class="action_field_txt" id="bus" name="bus" />
					    </div>
					    <div class="add_action_field action_sign">
							<span class="add_action_label">{$name}标签:</span>
							<div class="action_field_main">
							<input value="不超过5个，请用逗号隔开。输入标签可以为你带来更多关注者" class="apply_num_limit action_field_txt" name="action_sign" id="add_thread_title" type="text">
							<div class="action_sign_list">
								<span id="group_tags">
									<ul>
										<li>青奥</li><li>公益</li><li>旅行</li><li>文化</li><li>创业</li><li>创意</li><li>科技</li>
										<li>音乐</li><li>艺术</li><li>居家</li><li>摄影</li><li>设计</li><li>电影</li><li>美食</li>
										<li>汽车</li><li>游戏</li><li>动漫</li><li>时尚</li><li>原创</li><li>搭配</li><li>生活</li>
									</ul>
								</span>
							</div>
							</div>
						</div>
					</div>				
					<div class="add_action_field radio_btn">
						<span class="add_action_label">附加信息:</span>
						<div class="action_field_main">
							<div class="action_limit"><span>参加权限</span>
							<label><input name="rights" value="0" checked="checked" type="radio">任何人可参加</label>
							<label><input name="rights" value="1" type="radio" >需要我审批</label>
							</div>
							<h4>确定发布，激活你的行动页面</h4>
						</div>
					</div>
					<div class="add_group_btn"><input type="submit" name="add_group_btn" value="创建行动" /></div>
				</form>
				</div>
				</div>			
			<div class="gmain_bottom"></div>
		</article>
		<!--右侧-->
		{template:./csetion}
	</section>
	<script>
	$(".action_field_txt, .action_field_text").focus(function(){
		//alert("wowo");
		if($(this).val() == $(this)[0].defaultValue){
			$(this).val("");
		}
	}).blur(function(){
		if($(this).val() ==""){
			$(this).val($(this)[0].defaultValue);
		}
	});
	</script>
{template:./footer}