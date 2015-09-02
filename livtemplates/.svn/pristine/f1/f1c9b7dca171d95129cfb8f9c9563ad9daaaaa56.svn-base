{template:head}
{css:upload_vod}
{css:column_node}
{js:column_node}
{template:form/common_form}
{css:hg_sort_box}
{js:hg_sort_box}
{css:catalog}
{js:catalog}
{code}
if($formdata['edit'])
{
	$is_update = 1;
}
else
{
	$is_update = 0;
}

$tuji_info = $formdata['tuji'];
foreach ($tuji_info as $k => $v) {
	$$k = $v;
}
$pics_info = $formdata['pics'];
$pics_info = urlencode(json_encode($pics_info));
$attr_water = array(
	'class' => 'transcoding down_list',
	'show' => 'watert_show',
	'width' => 150,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
);

$default_water = 0;
$water_arr[$default_water] = '无';
foreach($water_config[0] AS $k => $v)
{
	$water_arr[$v['id']] = $v['config_name'];
}

if(!$tuji_info['water_id'])
{
	$tuji_info['water_id'] = $default_water;
}

//print_r($water_config_list);
{/code}


{css:common/common_form}
{css:tuji_form}

{css:2013/iframe_form}

{js:common/ajax_upload}
{js:2013/ajaxload_new}
{js:common/preloadimg}
{js:common/auto_textarea}
{js:common/common_form}
{js:tuji/tuji_form}
<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="content_form" title="">
 <div class="common-form-head">
     <div class="common-form-title">
          <h2>{code}echo !$id ? '添加' : '更新';{/code}图集</h2>
          <div class="form-dioption-title form-dioption-item">
                <!-- <textarea name="title" type="text" _value="{if $title}{$title}{else}添加图集标题{/if}" id="title" class="title {if $title}input-hide{/if}" placeholder="添加图集标题">{$title}</textarea> -->
                <input name="title" type="text" _value="{if $title}{$title}{else}添加图集标题{/if}" id="title" class="title {if $title}input-hide{/if} need-word-count" placeholder="添加图集标题" value="{$title}"/>
                <div class="color-selector clearfix">
                    <span class="form-title-color"></span>
                    <span class="form-title-weight"></span>
                    <span class="form-title-italic"></span>
                </div>
                       <input name="tcolor" type="hidden" value="{$tcolor}" id="tcolor" />
                       <input name="isbold" type="hidden" value="{if $isbold}1{else}0{/if}" id="isbold" />
                       <input name="isitalic" type="hidden" value="{if $isitalic}1{else}0{/if}" id="isitalic" />
                      <input name="weight" value="{$weight}" id="weight" type="hidden" />
          </div>
          <input type="hidden" name="submit_type" id="submit_type"/>
		  <div class="form-dioption-submit">
		      <!--  <input type="submit" id="submit_ok" name="sub" value="确定并继续添加" class="button_6_14" _submit_type="2"/>
		      <input type="submit" id="submit" value="确定" class="button_2_14" style="margin-left:5px;" _submit_type="1"/>-->
		      <input type="submit" id="submit_ok" name="sub" value="保存图集" class="common-form-save" _submit_type="2" />
		      <span class="option-iframe-back">关闭</span>
		  </div>
		  <div id="weightPicker">
	                    {template:list/list_weight,agd,$weight}
          </div>   
    </div>
</div>
{code}
    $title = $tuji_info['title'];
    $comment = $tuji_info['comment'];
{/code}	   
<div class="common-form-main tuji-area">
   {code}$formdata = &$tuji_info;{/code}
   {template:unit/publish_for_form, 1, $formdata['column_id']}
    <div class="form-left">
        <div class="form-dioption">
            <div class="form-dioption-inner">
                <div class="form-dioption-brief form-dioption-item">
                    <div style="overflow:hidden;display:none;">
                        <textarea name="comment" id="brief" class="comment {if $comment}input-hide{/if}" style="height:22px;line-height:22px;" placeholder="添加图集摘要">{$comment}</textarea>
                    </div>
                    <div contenteditable="true" class="need-word-count" data-left="-50px" data-top="-19px"  id="brief-clone" target="brief" placeholder="添加图集摘要">{$comment}</div>
                </div>

                <div class="form-dioption-source form-dioption-item">
                    <input name="source" id="source" type="text" value="{$source}" placeholder="来源" style="width:90%;"/>
                </div>
                
                  <div class="form-dioption-author form-dioption-item">
                    <input name="author" id="author" type="text" value="{$author}" placeholder="作者" style="width:90%;"/>
                </div>

                <div class="form-dioption-sort form-dioption-item"  id="sort-box">
                    <label style="color:#9f9f9f;{if !$tuji_info['tuji_sort_id']}display:none;{/if}">分类： </label><p class="sort-label" _multi="tuji_node">{code}echo $tuji_info['tuji_sort_name'] ? $tuji_info['tuji_sort_name'] : '请选择分类';{/code}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
                    <div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                    <input name="tuji_sort_id" type="hidden" value="{$tuji_info['tuji_sort_id']}" id="sort_id" />
                </div>

                <div class="form-dioption-keyword form-dioption-item clearfix">
                    <span class="keywords-del"></span>
                    <span class="form-item" _value="添加关键字" id="keywords-box">
                        <span class="keywords-start">添加关键字</span>
                        <span class="keywords-add">+</span>
                    </span>
                    <input name="keywords" value="{$keywords}" id="keywords" style="display:none;"/>
                </div>
				 <div class="form-dioption-fabu form-dioption-item">
                    <a class="common-publish-button overflow" href="javascript:;" _default="发布至" _type="publish" _prev="发布至：">发布至</a>
                </div>

                <!-- 
                <div class="form-dioption-fabu form-dioption-item">
                    <a>水印:</a>
                    <span class="add-water-pic">点击设置水印</span>
                </div>
                 -->
  				<div id="lumin"></div>
            </div>
        </div>
    </div>
    <!-- 水印设置 -->
    <div class="set-watermark-box">
		{if is_array($water_config_list)}
    	<ul class="watermark-option clear">
		{foreach $water_config_list[0] as $k => $v}
			<li data-value="{$v['config_name']}" _id="{$v['id']}" class="{if $v['id'] == $tuji_info['water_id'] }selected{/if}">
				<div class="watermark-content">
					{if $v['img_url']}
					<a><img src="{$v['img_url']}"></a>
					{/if}
					<p title="{$v['config_name']}" class="name {if $v['img_url']}hasimg{/if}">{$v['config_name']}</p>
				</div>
			</li>
		{/foreach}
		</ul>
		{else}
		暂无水印
		{/if}
		<div class="watermark-btns">
			<div class="handle-btn submit-watermark">确 定</div>
			<div class="handle-btn cancel-watermark">取 消</div>
			<div class="handle-btn del-watermark">去除水印</div>
		</div>
		<a class="arrow"></a>
		<input type="hidden" name="water_id" value="{$tuji_info['water_id']}"/>
		<input type="hidden" name="default_water_id" value="{$tuji_info['water_id']}"/>
	</div>
    
    <div class="form-middle">
        <div class="form-option" style="position:relative;">
            <div class="clear">

                <span class="form-select-all">
                    <span class="form-button-box">
                        <span class="form-button-left"></span>
                        <span class="form-button-middle"><label><input type="checkbox" value="1"/>选择全部<em>(0)</em></label></span>
                        <span class="form-button-right"></span>
                    </span>
                </span>
                <span class="form-change-des">
                    <span class="form-button-box form-button-cannot">
                        <span class="form-button-left"></span>
                        <span class="form-button-middle">更改描述</span>
                        <span class="form-button-right"></span>
                    </span>
                </span>
                <span class="form-option-del">
                    <span class="form-button-box">
                        <span class="form-button-left"></span>
                        <span class="form-button-middle">删除</span>
                        <span class="form-button-right"></span>
                    </span>
                </span>
                <span class="form-batch-watermark">
                    <span class="form-button-box form-button-cannot">
                        <span class="form-button-left"></span>
                        <span class="form-button-middle">水印</span>
                        <span class="form-button-right"></span>
                    </span>
                </span>
                <span class="form-option-cancel">取消选择</span>
            </div>
            <div style="position:absolute;right:0;top:0;">
						<span class="form-button-box">
						    <span class="form-button-left"></span>
						    <span class="form-button-middle form-zip">打包上传(只支持zip格式)</span>
						    <span class="form-button-right"></span>
						</span>
                        <input type="file" id="form-zip" style="display:none;"/>
            </div>
            <div style="position:absolute;right:170px;top:0;">
						<span class="form-button-box">
						    <span class="form-button-left"></span>
						    <span class="form-button-middle link-btn">链接上传</span>
						    <span class="form-button-right"></span>
						</span>
            </div>
        </div>
        <div class="form-upload">
            <div class="form-des-box">
                <div class="form-des-number">更改<span></span>图片的描述</div>
                <textarea style="width:97%;margin:6px 10px 10px 12px;height:44px;line-height:22px;"></textarea>
            </div>
            <div class="form-link-box">
                <div class="form-des-number">输入图片的链接地址 每行作为一张图片</div>
                <textarea style="width:97%;height:100px;margin:6px 10px 10px 12px;" name="pic_links"></textarea>
            </div>
            <div class="form-imgs clear" style="position:relative;">
                <div class="form-add" picid="0" style="display:none;">
                	添加图片
                	<a class="watermark-btn the-add">添加水印</a>
                	<input type="hidden" name="water_id" value="{$tuji_info['water_id']}">
                </div>
            </div>
        </div>
    </div>
    <input type="file" multiple class="form-file" />
    <input type="hidden" value="{if $is_update}update_tuji{else}create_tuji{/if}" name="a" />
    <input type="hidden" value="{$_INPUT['mid']}" name="module_id" />
    <input type="hidden" value="{$tuji_info['id']}" name="id" />
    <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
    <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />

    <input type="hidden" name="imgs" id="imgs" value="{if $pics_info}{$pics_info}{/if}"/>


 </form>
</div>
<textarea id="imgs-data" style="display:none">{if $pics_info}{$pics_info}{/if}</textarea>
<textarea id="img-tpl" style="display:none;">
    <div class="form-img-each {isfm} form-img-each-transition" index="{index}" sort="{sort}" material_id="{materialid}" picid="{picid}" {ext}>
        <div class="form-img-fm"></div>
        <div class="form-img-option">
            <div class="form-img-option-mask"></div>
            <div class="form-img-option-box">
                <span class="form-img-obig"></span>
                <span class="form-img-oleft"></span>
                <span class="form-img-oright"></span>
                <span class="form-img-odel"></span>
        	    <a class="watermark-btn" _waterid={waterid}>添加水印</a>
            </div>
        </div>
        <div class="form-img-box"><img class="suo" _src="{src}"/></div>
        <div class="form-img-title" title="{title}"><div class="form-img-title-content">{title}</div></div>
        <div class="form-img-center"></div>
        <div class="form-img-reddel">x</div>
    </div>
</textarea>

<div></div>

{template:foot}