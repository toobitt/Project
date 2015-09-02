
<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=tuji_form&id=${id}&infrm=1" target="formwin">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=move_form&id=${id}&nodevar=tuji_node" data-node='tuji_node'>移动</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=${ state == globalData.auditValue ? 0 : 1 }&id=${id}" 
				onclick="return hg_ajax_post(this, '{{if state == globalData.auditValue}}打回{{else}}审核{{/if}}', 0, 'hg_change_status');">
				{{if state == globalData.auditValue}}打回{{else}}审核{{/if}}
			</a>
		</div>
		<div class="record-edit-btn-area clear">
			{if $_configs['App_publishcontent']}
			<a href="./run.php?mid={$_INPUT['mid']}&a=recommend&id=${id}" onclick="return hg_ajax_post(this, '推荐', 0);">签发</a>
			{/if}
			{if $_configs['App_share']}
			{{if !(expand_id == 0)}}
			<a href="./run.php?mid={$_INPUT['mid']}&a=share_form&id=${_.values(pub_url)[0]}" onclick="return hg_ajax_post(this, '分享', 0);">分享</a>
			{{/if}}
			{/if}
			{if $_configs['App_special']}
			<a href="run.php?mid={$_INPUT['mid']}&a=special&id=${id}&infrm=1">专题</a>
			{/if}
			{if $_configs['App_block']}
			<a>区块</a>
			{/if}
		</div>
		<div class="record-edit-btn-area clear">
		    <a  href="./run.php?mid=2890&a=create&id=${id}&pushType=tuji">推送</a>
		</div>
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-area clear">
			{{each img_src}}
				{{if _index == 0 || _index == 1 || _index == 2}}
					<span class="record-edit-img-wrap"><img _key="${_index}" src="${_value}"></span>
				{{/if}}
			{{/each}}
			<span class="record-edit-img-wrap">${img_count} P</span>
		</div>
		<div class="record-edit-line"></div>
		
		{{if catalog}}
		<div class="record-catalog-info">
			<span>编目信息</span>
			<ul>
			{{each catalog}}
				{{if _value }}
				<li><label>${_value.zh_name}：</label>
					{{if typeof( _value.value ) == 'string'}}
						<p>${_value.value}</p>
					{{else}}
						<p class="clear">
						{{each _value.value}}
							{{if _value.host}}
							<span class="record-edit-img-wrap"><img src="${_value.host}${_value.dir}${_value.filepath}${_value.filename}"></span>
							{{else}}
							<span>${_value}</span>
							{{/if}}
						{{/each}}
						</p>
					{{/if}}
				</li>
				{{/if}}
			{{/each}}
			</ul>
		</div>
		{{/if}}
		
		<div class="record-edit-line"></div>
		
		<div class="record-edit-info">
			{{if click_num}}<span>访问:${click_num}</span>{{/if}}
			{{if down_num}}<span>下载:${down_num}</span>{{/if}}
			{{if share_num}}<span>分享:${share_num}</span>{{/if}}
		</div>
		<span class="record-edit-close"></span>
	</div>
	<div class="record-edit-confirm">
		<p>确定要删除该内容吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a>确定</a>
			<a>取消</a>
		</div>
		<span class="record-edit-confirm-close"></span>
	</div>

	<div class="push-edit-confirm">
		<p>确定将该内容推送到CRE吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a class="push-btn">确定</a>
			<a>取消</a>
		</div>
		<span class="push-edit-confirm-close"></span>
    </div>


	<div class="record-edit-play">
	</div>
</div>
<style>
#tuji_pics_show{width:346px;height:304px;}
#tuji_pics_show img{left:52px important!;}
#picinfo{display:none;}
</style>
<script type="tpl" id="vedio-tpl">
<div id="tuji_pics_show" class="tuji_pics_show">
  	  <img src="{$image_resource}black.jpg" id="tuji_content_img" style="position:absolute;left:0px;top:0px;width:346px;" />
  	  <div id="over_tip" style="width:200px;height:100px;position:absolute;left:25%;top:30%;background:none repeat scroll 0 0 #000000;opacity:0.7;display:none;"></div>
  	  <div style="width:45px;height:20px;position:absolute;left:10px;top:280px;background:black;text-align:center;">
  	     	<div style="color:white;line-height:20px;">封面</div>
  	  </div>
  	  <input type="hidden" name="isover" id="isover" value="0" />
  	 
	  <div class="arrL" title="点击浏览上一张图片 "  onmouseover="hg_onPicMouseOver(this,1);" onmouseout="hg_onPicMouseOver(this,0);" onclick="hg_showOtherPic(${id},0);"></div>
	  <div class="arrR" title="点击浏览下一张图片 "  onmouseover="hg_onPicMouseOver(this,1);" onmouseout="hg_onPicMouseOver(this,0);" onclick="hg_showOtherPic(${id},0);"></div>
	  <div class="btnPrev" style="display:none;" id="left_btn"  onmouseover="hg_show_btn(this);" onclick="hg_showOtherPic(${id},0);"><a href="#"></a></div>
	  <div class="btnNext" style="display:none;" id="right_btn" onmouseover="hg_show_btn(this);" onclick="hg_showOtherPic(${id},0);"><a href="#"></a></div>
  </div>
<span class="record-edit-close"></span>
</script>
