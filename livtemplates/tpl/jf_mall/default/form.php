<?php ?>
{template:head}
{css:2013/form}
{css:2013/button}
{css:hg_sort_box}
{css:form}
{js:hg_preview}
{js:hg_sort_box}
{js:ajax_upload}
{js:common/common_form}
{js:live/my-ohms}
{js:jf_mall/jf_mall_form}
{code}
if ( is_array($formdata ) )
{
foreach ( $formdata as $k => $v )
{
$$k = $v;
}
}
if($id)
{
$optext="更新";
$ac="update";
}
else
{
$optext="添加";
$ac="create";
}
$currentSort[$node_id] = ($node_id ? $name : '选择分类');
$markswf_url = RESOURCE_URL.'swf/';
{/code}
<style>
.w100{width:100px;}
.w60{width:60px!important;}
.pick-self{display:none;}
input[type="text"] , textarea{border-radius:2px;}
</style>
<body>
<form class="m2o-form" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="tv_interact_form" data-id="{$id}">
    <div id="ohms-instance" style="position:absolute;display:none;"></div>
    {template:unit/bg_picture}
    <header class="m2o-header">
        <div class="m2o-inner">
            <div class="m2o-title m2o-flex m2o-flex-center">
                <h1 class="m2o-l">{$optext}商品</h1>
                <div class="m2o-m m2o-flex-one">
                    <input placeholder="输入商品名称" name="title" class="m2o-m-title need-word-count" title="{$title}" required value="{$title}" />
                    <input type="hidden" name="old_name" value="{$name}" />
                </div>
                <div class="m2o-btn m2o-r">
                    <input type="submit" value="保存信息" class="m2o-save" name="sub" id="sub" data-target="run.php?mid={$_INPUT['mid']}&a={$ac}" data-method="{$ac}"/>
                    <span class="m2o-close option-iframe-back"></span>
                    <input type="hidden" name="a" value="{$ac}" />
                    <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
                    <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
                    <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
                </div>
            </div>
        </div>
    </header>
    <div class="m2o-inner">
        <div class="m2o-main m2o-flex">
            <aside class="m2o-l m2o-aside">
                <div class="m2o-item img-info" style="position:relative">
                    <div class="indexpic">
                    	{code}
                    		$indexpic_url = $indexpic_url['host'] . $indexpic_url['dir'] . $indexpic_url['filepath'] . $indexpic_url['filename'];
                    	{/code}
                        <img src="{$indexpic_url}" />          
                        <span class="indexpic-suoyin {if $formdata['indexpic']}indexpic-suoyin-current{/if}"></span>
                        <input type="hidden" name="indexpic" value="{$indexpic}" />
                    </div>
                    <input type="file" name="upload-file" style="display:none;" class="upload-file" />
                </div>
                <div class="form-dioption-sort m2o-item"  id="sort-box">
                    <label style="color:#9f9f9f;">分类： </label>
                    <p style="display:inline-block;" class="sort-label" _multi="node"> {$currentSort[$node_id]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
                    <div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                    <input name="node_id" type="hidden" value="{$node_id}" id="sort_id" />
                    <input name="fieldcontentdel" type="hidden" value="{$node_id}" />
                </div> 
                <div class="form-dioption-sort m2o-item"  id="sort-box">
                    <label style="color:#9f9f9f;">链接标题： </label>
                    <input name="outlink_title" type="text" style="width:177px" value="{$outlink_title}" />
                </div>
                <div class="form-dioption-sort m2o-item"  id="sort-box">
                    <label style="color:#9f9f9f;">链接地址： </label>
                    <input name="outlink_url" type="text" style="width:177px" value="{$outlink_url}" />
                </div>            
            </aside>
            <section class="m2o-m m2o-flex-one">
                <div class="basic-info">
                    <div class="m2o-item tv-info">
                        <a class="tv-title active" data-type="basic">基本信息</a>
                    </div>
                    <div class="m2o-item cut-off">
                        <label class="title">描述简介: </label>
                        <textarea class="brief" name="brief" cols="120" rows="5" placeholder="描述简介">{$brief}</textarea>
                    </div>
                    <div class="m2o-item cut-off">
                        <label class="title">关键字: </label>
                        <div class="info">
                            <input type="text" name="keywords" value="{$keywords}" class="w200" placeholder=""/>
                            <span>多个用,号隔开</span>
                        </div>
                    </div>                    
                    <div class="m2o-item cut-off">
                        <label class="title">积分价格: </label>
                        <div class="info">
                            <input type="text" name="score" value="{$score}" class="w200" placeholder=""/>
                        </div>
                    </div>
                    <!--
                    <div class="m2o-item cut-off">
                        <label class="title">所属金钱: </label>
                        <div class="info">
                            <input type="text" name="price" value="{$price}" class="w200" placeholder=""/>
                            <span>元</span>
                        </div>
                    </div>
                    -->
                    <div class="m2o-item cut-off">
                        <label class="title">市场价格: </label>
                        <div class="info">
                            <input type="text" name="market_price" value="{$market_price}" class="w200" placeholder=""/>
                            <span>元</span>
                        </div>
                    </div>
                    <div class="m2o-item cut-off">
                        <label class="title">日期条件: </label>
                        <div class="info">
                            <input type="text" name="start_date" class="date-picker" value="{if $start_date_show && $start_date_show !==0 } {$start_date_show} {/if}"/>
                            <span>至</span>
                            <input type="text" name="end_date" class="date-picker"  value="{if $end_date_show && $end_date_show!==0 } {$end_date_show} {/if}"/>
                        </div>
                        <label class="title">时间条件: </label>
                        <div class="info">
                        	<span>
                        		<input type="text" class="way-time start w100" name="start_time" value="{if $start_time_show && $start_time_show !==0 }{$start_time_show}{/if}"/>
                            	<span>至</span>
                            	<input type="text" class="way-time end w100" name="end_time" value="{if $end_time_show && $end_time_show !==0 }{$end_time_show}{/if}"/>
                            </span>
                        </div>
                    </div>
                    <div class="m2o-item cut-off">
                        <div id="week_date" class="clear">
                        {code}
                        $week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
                        {/code}
                        <span class="title">星期条件：</span>
                        <label>
                            <input class="n-h" type="checkbox"  id="every_day" class="every_day" name="every_day" {if count($week_day)==7}checked{/if}/><span>每天</span>
                        </label>
                        {foreach $week_day_arr as $key => $value}
                        <label>
                            <input class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {foreach $week_day as $k => $v}{if $v == $key}checked{/if}{/foreach} value="{$key}" /><span>{$value}</span>
                        </label>
                        {/foreach}
                    </div>
                    </div>
                    <div class="m2o-item cut-off">
                        <label class="title">总量限制: </label>
                        <div class="info">
                            <input type="text" name="total_limit" value="{$total_limit}" class="w200" placeholder="可兑换数量 留空不限制"/>
                        </div>
                    </div>
                    <div class="m2o-item cut-off">
                        <label class="title">周期数量限制: </label>
                        <div class="info">
                            <input type="text" name="period_limit" value="{$period_limit}" class="w200" placeholder="单次/每个活动周期可兑换数量"/>
                        </div>
                        <label class="title">周期类型: </label>
                        <div class="info">
                            <div class="info-switch">
                                <div class="switch">
                                    <input type="radio" name="period_type" value=1 {if $period_type == 1}checked="checked"{/if}/>
                                    <p>每天</p>
                                </div>
                                <div class="switch">
                                    <input type="radio" name="period_type" value=2 {if $period_type == 2}checked="checked"{/if}/>
                                    <p>每周</p>
                                </div>                                
                                <div class="switch">
                                    <input type="radio" name="period_type" value=3 {if $period_type == 3}checked="checked"{/if}/>
                                    <p>每月</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m2o-item cut-off">
                        <label class="title">订单数量限制: </label>
                        <div class="info">
                            <input type="text" name="order_limit" value="{$order_limit}" class="w200" placeholder="每个订单可兑换数量"/>
                        </div>
                    </div>
                    <div class="m2o-item cut-off">
                        <label class="title">账号限制: </label>
                        <div class="info">
                            <input type="text" name="amount_limit" value="{$amount_limit}" class="w200" placeholder="每个账号可兑换数量"/>
                        </div>
                    </div>

                    <!--
                    <div class="m2o-item cut-off">
                        <label class="title">设备限制: </label>
                        <div class="info">
                            <div class="info-switch">
                                <div class="switch">
                                    <input type="radio" name="is_equipment_limit" />
                                    <p>开启</p>
                                </div>
                                <div class="switch">
                                    <input type="number"  min="0" name="limit_time" class="w50"/>
                                    <p>次</p>
                                </div>
                                <div class="switch">
                                    <input type="radio" name="is_equipment_limit" />
                                    <p>关闭</p>
                                </div>
                            </div>
                        </div>
                    </div>
                     -->

                    <div class="m2o-item cut-off">
                        <label class="title">活动规则: </label>
                        <div class="info">
                            <textarea name="exchange_rule" placeholder="请输入提示描述">{$exchange_rule}</textarea>
                        </div>
                    </div>
                    <div class="m2o-item cut-off">
                        <label class="title">活动说明: </label>
                        <div class="info">
                            <textarea name="exchange_state" placeholder="请输入提示描述">{$exchange_state}</textarea>
                        </div>
                    </div>

                    <div class="m2o-item cut-off">
                        <label class="title">兑换附加信息: </label>
                        <div class="info">
                            <div class="info-switch">
                                <div class="switch">
                                    <input type="radio" name="need_extras_info" value=1 {if $need_extras_info}checked="checked"{/if}/>
                                    <p>开启</p>
                                </div>
                                <div class="switch">
                                    <input type="radio" name="need_extras_info" value=0 {if !$need_extras_info}checked="checked"{/if}/>
                                    <p>关闭</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="m2o-item cut-off">
                        <label class="title">商品图片: </label>
                        <div class="info" id="img-list">
                        	{if is_array($material) && count($material) > 0}
                        	{foreach $material as $k => $v}
                        	{code}
                        		$bigsrc = $v['pic']['host'] . $v['pic']['dir'] . $v['pic']['filepath'] . $v['pic']['filename'];
                        		$src = $v['pic']['host'] . $v['pic']['dir']  . '80x/' . $v['pic']['filepath'] . $v['pic']['filename']
                        	{/code}
		                     <div class="item-box">
		                            <span class="del"></span>
		                            <div class="item-inner-box">
		                                <a class="suoyin set-suoyin {if $v['id'] == $indexpic}suoyin-current{/if}"></a>
		                                <img class="image" imageid="{$v['id']}" bigsrc="{$bigsrc}" src="{$src}">
		                            </div>
		                            <div class="nooption-mask"></div>
		                            <div class="image-option-box">
                            		<span class="image-option-del image-option-item"></span>
                            		</div>             
		                            <input type="hidden" value="{$v['id']}" name="material_id[]" />
		                        </div>                       	
                        	{/foreach}
                        	{/if}
		        			<div class="img-info">
		        				<div class="icon"><img src="{$un_start_icon}" /></div>
		        				<input type="file" name="un_start_file" class="upload-file" style="display:none" accept="image/*"/>
		        			</div>                        
                        </div>
                    </div>    
                    <div class="m2o-item cut-off">
                        <label class="title">领取方式: </label>
                        <div class="info">
                            <div class="info-switch">
                                <div class="switch">
                                    <input type="radio" class="pick-way" name="pick_up_way" value="1" {if $pick_up_way}checked="checked"{/if}/>
                                    <p>邮寄</p>
                                </div>
                                <div class="switch">
                                    <input type="radio" class="pick-way" name="pick_up_way" value="0" {if !$pick_up_way}checked="checked"{/if}/>
                                    <p>自取</p>
                                </div>
                            </div>
                            <div class="pick-self" style="{if !$pick_up_way}display:block;{/if}">
                            	<div class="m2o-item">
			                        <label class="title w60">联系方式: </label>
			                        <div class="info">
			                            <input type="text" name="contact_way" value="{$contact_way}" class="w200" placeholder="请输入联系方式"/>
			                        </div>
			                    </div>
			                    <div class="m2o-item">
			                        <label class="w60 title">换取地址: </label>
			                        <div class="info">
			                            <input type="text" name="pick_address" value="{$pick_address}" class="w200" placeholder="请输入换取地址"/>
			                        </div>
			                    </div>
			                    <div class="m2o-item">
			                        <label class="w60 title">备注说明: </label>
			                        <div class="info">
			                            <textarea name="comments" placeholder="备注说明">{$comments}</textarea>
			                        </div>
			                    </div>
                            
                            </div>
                        </div>
                    </div>                
                </div>
            </section>
        </div>
</form>
</body>
