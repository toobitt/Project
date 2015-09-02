{template:head}
{code}
$subway = $formdata['subway'];
$site_info = $formdata['site'];
//print_r( $site_info );
if ( is_array($subway) )
{  
    foreach ( $subway as $k => $v ) 
    {
        $$k = $v;
    }
}
$ac = $id ? 'update' : 'create';
$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
    
/*所有选择控件基础样式*/
$all_select_style = array(
    'state'     =>  0,  /*0--正常数据选择列表，1--日期选择*/
    'is_sub'    =>  1,
    'state'     =>  0, 
    'width'     => 109
);

{/code}

{css:2013/form}
{css:common/common}
{css:colorpicker}
{css:hg_sort_box}
{css:2013/button}
{css:subway}

{js:jqueryfn/colorpicker.min}
{js:ajax_upload}
{js:2013/ajaxload_new}
{js:2013/hg_colorpicker}
{js:hg_sort_box}
{js:common/common_form}
{js:live/my-ohms}
{js:subway/subway_tab}
{js:subway/subway}
{js:subway/subway_set}

<div class="main-wrap">
    <div id="ohms-instance" style="position:absolute;display:none;"></div>
    <header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}地铁线路</h1>
            <div class="m2o-m m2o-flex-one">
                 <input placeholder="填写线路名称" class="m2o-m-title" name="title" title="{$title}" value="{$title}" for="roadForm" />
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存" class="m2o-save" name="sub" id="sub" />
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
    </div>  
    </header>
    <div class="m2o-inner">
        <div class="m2o-main m2o-flex">
            <aside class="m2o-l">
                <form name="roadform" action="run.php?mid={$_INPUT['mid']}" method="post" id="roadForm" data-id="{$id}">
                    <div class="form-dioption-sort m2o-item"  id="sort-box">
                        <label style="color:#9f9f9f;">分类</label>
                        <p style="display:inline-block;" class="sort-label" _multi="subway_sort"> {$currentSort[$sort_id]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative; left:10px; bottom:2px;" /></p>
                        <div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                        <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
                    </div>
                    <div class="m2o-item">
                        <label class="title">标识名</label><input type="text" value="{$sign}" name='sign' />
                        <input class="select-input color-picker" data-color="{$color}" type="text" name="fontcolor" value="{$color}"/>
                    </div>
                    <div class="m2o-item">
                        <div>
                            <label class="title">起点</label><input type="text" disabled="disabled" value="" placeholder="起点" class="start"><em title="start" class="way-time {if !$start_time}time-icon{/if}">{$start_time}</em>
                            <input type="text" class="engname" name="start_egname" disabled="disabled" value="{$start_egname}" placeholder="英文名称">
                        </div>
                        <div>
                            <label class="title">终点</label><input type="text" disabled="disabled" value="" placeholder="终点" class="end"><em title="end" class="way-time {if !$end_time}time-icon{/if}">{$end_time}</em>
                            <input type="text" class="engname" name="end_egname" disabled="disabled" value="{$end_egname}" placeholder="英文名称">
                        </div>
                    </div>
                    <div class="m2o-item m2o-switch" >
                        <label class="title">状态</label>
                        <div class="common-switch {if $is_operate}common-switch-on{/if}">
                           <div class="switch-item switch-left" data-number="0"></div>
                           <div class="switch-slide"></div>
                           <div class="switch-item switch-right" data-number="100"></div>
                        </div>
                    </div>
                    <input type="hidden" name="is_operate" value="{$is_operate}">
                    <input type="hidden" name="a" value="{$ac}" />
                    <input type="hidden" name="title" value="{$title}" />
                    <input type="hidden" name="id" value="{$id}" />
                    <input type="hidden" name="start" value="" />
                    <input type="hidden" name="end" value="" />
                    <input type="hidden" name="site_count" value="" />
                    <input type="hidden" name="content_id" value="" />
                    <input type="hidden" name="order_id" value="" />
                    <input type="hidden" name="ajax" value="1" />
                    <input type="hidden" name="start_time" value="{$start_time}" />
                    <input type="hidden" name="end_time" value="{$end_time}" />
                    <input type="hidden" name="start_egname" value="{$start_egname}" />
                    <input type="hidden" name="end_egname" value="{$end_egname}" /
                    <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
                    <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
                    <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
                </form>
             </aside>
            <section class="m2o-m m2o-flex-one way-m">
                <div class="way-head">
                    <div class="way-title">线路站点<span class="site_num">共<em>13</em>个站点</span></div>
                    <a class="list_sort">开启拖动排序</a>
                </div>
                <div class="way-map">
                    <div class="way-list">
                    </div>
                    <div id="way-slider" class="slider"></div>
                    <em class="extend-bend right"></em>
                    <div id="1_way-slider" class="slider"></div>
                </div>
                <div class="way-content">
                    <ul class="way-tab">
                        <li class="selected">基本信息</li>
                        <li>出入口信息</li>
                        <li>服务设施</li>
                    </ul>
                    <input type="file" name="img" style="display:none;" class="images-file" multiple/>
                </div>
            </section>
        </div>
        {code}
            $hg_bmap = array(
                'height' => 480,
                'width'  => 600,
                'longitude' => $site_longitude ? $site_longitude : '0', 
                'latitude'  => $site_latitude ? $site_latitude : '0',
                'zoomsize'  => 13,
                'areaname'  => $city_name?$city_name:$_configs['city']['name'],
                'is_drag'   => 1,
            );
        {/code}
        {template:unit/map_unit,site_longitude,site_latitude,$hg_bmap}
    </div>
    
    <!-- 获取公交box -->
    <div class="pop-box bus-box pop-hide">
        <div class="pop-title bus-title">选取公交站点
         <input type="button" class="pop-save-button bus-save" value="保存"/> 
         <a class="pop-close-button2 bus-close"></a>
        </div>
        <div class="bus-area">
            <ul></ul>
        </div>
    </div>
    
    <!-- 站点排序box -->
    <div class="cover-layer cover-show"></div>
    <div class="pop-box site-box pop-hide">
        <div class="pop-title site-title">开启站点排序
         <input type="button" class="pop-save-button sort-save" value="保存"/>    
         <a class="pop-close-button2 sort-close"></a>
        </div>
        <div class="site-area">
            <ul>
                
            </ul>
        </div>
    </div>
</div>
{template:foot}

{code}
    $grade_source = $all_select_style;
    $grade_source['class'] = 'down_list way-list';
    $grade_source['show'] = 'way_show';
    $grade_default = $play_grade ? $play_grade : -1;
    $subs = $subways[0]['sub'];
    $subs[-1] = '请选择所属线路 ';
    
    $sites = $subways[0]['site'];
    
    $type_source = $all_select_style;
    $type_source['class'] = 'down_list way-list';
    $type_source['show'] = 'extend_show';
    $type_default = $play_type ? $play_type : -1;
    $_configs['play_type'][-1] = '请选择扩展信息 ';
    
    $service_source = $all_select_style;
    $service_source['class'] = 'down_list way-list';
    $service_source['show'] = 'service_show';
    $service_default = $service_type ? $service_type : -1;
    $_configs['service_type'][-1] = '请选择服务设施状态 ';

{/code}
<div id="way">
    {template:form/search_source,line,$grade_default,$subs,$grade_source}
</div>
<div id="extend">
    {template:form/search_source,typeid,$type_default,$_configs['play_type'],$type_source}
</div>
<div id="service">
    {template:form/search_source,serivce_id,$service_default,$_configs['service_type'],$service_source}
</div>

<script type="text/javascript">
$(function(){
    $.globalSite = {code} echo $site_info ?  json_encode($site_info) : '{}'; {/code};
    $.globalRoad = {code} echo $sites ?  json_encode($sites) : '{}'; {/code};
    var ohmsInstance = $('#ohms-instance').ohms();
    $('.way-content').subwayInfo({
        pointShow : function( event, keys, val ){
            $('.way-map').subwaySlider('pointShow', keys, val);
        },
        successCallback : function( event, options ){
            $('.way-map').subwaySlider('callbackData', options);
        },
        getBeginningSite : function( event ){
            $('.way-map').subwaySlider('getBeginningSite');
        },
        JudgeShow : function( event, dom, type ){
            $('.way-map').subwaySlider('JudgeShow', dom, type);
        },
        getTypeUrl : './run.php?mid=' + gMid + '&a=get_site_type',
        getImageUrl : './run.php?mid=' + gMid + '&a=upload',
        delImageUrl : './run.php?mid=' + gMid + '&a=delete_img',
        addDefinedUrl : './run.php?mid=' + gMid + '&a=operate_site_type',
        editSiteUrl : './run.php?mid=' + gMid + '&a=detail_site&ajax=1',
        getBusUrl : './run.php?mid=' + gMid + '&a=get_bus&ajax=1',
        addImagetpl : $('#add-pic-tpl').html(),
        addRoadtpl : $('#add-road-tpl').html(),
        addExtendtpl : $('#add-extend-tpl').html(),
        addGatetpl : $('#add-gate-tpl').html(),
        pullDowntpl : $('#add-pulldown-tpl').html(),
        addServicetpl : $('#add-service-tpl').html(),
        addSiteboxtpl : $('#add-sitebox-tpl').html(),
        addGateboxtpl : $('#add-gatebox-tpl').html(),
        addServiceboxtpl : $('#add-servicebox-tpl').html(),
        addBustpl : $('#add-bus-tpl').html(),
        ohms : ohmsInstance
    });
    $('.way-map').subwaySlider({
        addSitetpl : $('#add-site-tpl').html(),
        addSlidertpl : $('#add-slider-tpl').html(),
        addSorttpl : $('#add-sort-tpl').html(),
        tabShow : function( event, _keys ){
            $('.way-content').subwayInfo('slideTog', _keys);
        },
        saveRoadForm : function(){
            $.roadInfo.initSubmit();
        },
        tabTrigger : function(event, eq){
            $('.way-content').subwayInfo('chooseSelect', eq);
        },
        editSiteinfo : function(event, site_id, _keys){
            $('.way-content').subwayInfo('getSiteinfo', site_id, _keys);
        },
        removeItem : function( event, _keys ){
            $('.way-content').subwayInfo('removeItem', _keys);
        },
        sliderTip : function(event, dom, str){
            $('.way-content').subwayInfo('myTip', dom, str);
        },
        getBeginning : function( event, start, end ){
            $('.way-content').subwayInfo('getName', start, end);
        },
        removeSiblings : function( event, dom ){
            $('.way-content').subwayInfo('removeSiblings', dom);
        },
        delSiteUrl : './run.php?mid=' + gMid + '&a=delete_site&ajax=1',
        dragOrderUrl : './run.php?mid=' + gMid + '&a=drag_order&ajax=1&flag=1',
    });
});
</script>

<script type="text/x-jquery-tmpl" id="add-slider-tpl">
<em class="extend-bend ${point} top_${index}"></em>
<div id="${index}_way-slider" class="slider"></div>
</script>

<script type="text/x-jquery-tmpl" id="add-bus-tpl">
<li _id='${stationid}'><input type="checkbox" /><label>${stationname}</label></li>
</script>

<script type="text/x-jquery-tmpl" id="add-sort-tpl">
{{if id}}<li _slider="${slider}" site_id="${id}" order_id="${order_id}" _keys="${_keys}"><span class="program-con"></span><label>${title}</label></li>{{/if}}
</script>

<script type="text/x-jquery-tmpl" id="add-site-tpl">
<div class="program-li {{if tname}}program-huan{{/if}}" _slider="${slider}" site_id="${id}" _keys="${_keys}" style="z-index:999; left:${slider}px; top:${top}px">
    <span class="program-con"></span>
    <span class="theme-label" title="${title}">${title}</span>
    <span class="theme-edit">删除</span>
    {{if tname}}
        <div class="hover-line">
            经过此站点线路：${tname}
        </div>
    {{/if}}
</div>
</script>

<script type="text/x-jquery-tmpl" id="add-sitebox-tpl">
    <div class="way-item basic-info">
        <form name="basicform" class="basicform" action="run.php?mid={$_INPUT['mid']}" method="post" >
        <div class="m2o-item">
            <span class="site-name"><label class="title">站名：</label><input type="text" name="site_name" value="${title}"/></span>
            <span class="site-engname"><label class="title">英文名：</label><input type="text" name="site_egname" value="${egname}"/></span>
            <span class="site-sign"><label class="title">标识：</label><input type="text" name="site_sign" value="${sign}"/></span>
            <span class="site-titude"><label class="title">经度：</label><input type="text" name="site_longitude" value="${longitude}"/></span>
            <span class="site-titude"><label class="title">纬度：</label><input type="text" name="site_latitude" value="${latitude}"/></span>
            <em class="map-icon"></em>
            <span class="site-descrip"><textarea name="site_brief" cols="127" rows="2" placeholder="描述">${brief}</textarea></span>
            <span class="site-axis"><label class="title">x轴：</label><input type="text" name="site_x" value="${site_x}"/></span>
            <span class="site-axis"><label class="title">y轴：</label><input type="text" name="site_y" value="${site_y}"/></span>
        </div>
        <div class="m2o-item solidLine"></div>
        {{tmpl($data["roadtpl"]) "#add-road-tpl"}}
        <span class="add-way" data-type="basic">添加所属线路</span>
        <div class="m2o-item solidLine"></div>
        <div class="m2o-item">
            <label class="title">客流高峰：</label><span class="passenger-list">
                <input type="text" name="site_peaktime" value="${peaktime}"/>
            </span>
            <span class="interval-point"><input type="text" class="way-time start" name="site_peakstart" value="${peakstart}"/>—<input type="text" class="way-time end" name="site_peakend" value="${peakend}"/></span>
            <span class="site-descrip"><textarea name="site_peakbrief" cols="127" rows="2" placeholder="描述">${peakbrief}</textarea></span>
        </div>
        <div class="m2o-item way-img">
            <label class="title">图片信息：</label>
            <ul class="img-list clear">
                {{if imgtpl}}
                {{tmpl($data["imgtpl"]) "#add-pic-tpl"}}
                {{/if}}
                <li class="add-img">添加图片</li>
            </ul>
        </div>
        <div class="m2o-item">
            <label class="title">厕所：</label><input type="radio" name="has_toilet" {{if has_toilet == 1}}checked="checked"{{/if}} value="1"/>有<input type="radio" name="has_toilet" {{if has_toilet == 0}}checked="checked"{{/if}} value="0" />无
        </div>
        <div class="m2o-item solidLine"></div>
        <div class="m2o-item">
            <input type="submit" value="保存" class="save-button" /><span class="cancel-button">取消</span>
            <input type="hidden" name="a" value="create_site" />
            <input type="hidden" name="site_id" value="${id}" />
            <input type="hidden" name="sub_id" value="" />
            <input type="hidden" name="ajax" value="1" />
        </div>
        </form>
    </div>
</script>

<script type="text/x-jquery-tmpl" id="add-road-tpl">
<div class="m2o-item m2o-way">
    <div class="way-road">
        <label class="title">所属线路：</label>
        <div class="way-obj"></div>
        <em class="del-way" data-type="way"></em>
    </div>
    <div class="way-interval">
        <label class="title">首末时间：</label>
        <div class="interval-point">
            <span class="iterval-time"><label>开往<em class="start"></em></label><input type="text" class="way-time start" name="${sitek}_start[]" value="${sstart}"/>—<input type="text" class="way-time end" name="${sitek}_end[]" value="${send}"/></span>
            <span class="iterval-time"><label>开往<em class="end"></em></label><input type="text" class="way-time start" name="${sitek}_start[]" value="${estart}"/>—<input type="text" class="way-time end" name="${sitek}_end[]" value="${eend}"/></span>
        </div>
    </div>
    <div class="m2o-item dottedLine"></div>
</div>
</script>

<script type="text/x-jquery-tmpl" id="add-pic-tpl">
<li {{if type}}class="img-item"{{/if}} order_id="${id}" orderid="${order_id}" data-id="${id}" title="可拖动排序">
    <img src="${src}">
    <input type="hidden" name="{{if index}}${index}_{{/if}}new_site_indexpic[]" value="${id}" />
    <em class="del-image"></em>
</li>
</script>

<script type="text/x-jquery-tmpl" id="add-gatebox-tpl">
    <div class="way-item gate-info">
        <form name="operateform" class="operateform" action="run.php?mid={$_INPUT['mid']}" method="post" >
            {{if roadtpl}}
            {{each roadtpl}}
            <div class="let-list let-show">
                <div class="m2o-item">
                    <span class="site-sign"><label class="title">标示：</label><input type="text" name="new_gate_sign[]" value="${sign}"/></span>
                    <span class="site-name"><label class="title">出口：</label><input type="text" name="new_gate_title[]" value="${title}"/></span>
                    <span class="site-titude"><label class="title">经度：</label><input type="text" name="new_gate_longitude[]" value="${longitude}"/></span>
                    <span class="site-titude"><label class="title">纬度：</label><input type="text" name="new_gate_latitude[]" value="${latitude}"/></span>

                    <em class="map-icon"></em><em class="edit">缩小</em><em class="del">删除</em><span class="site-order" style="#ccccc"><label class="title" style="color:#6da1ea">排序ID:</label><input type="text" name="order_id[]" value="${order_id}"/></span>
                    <span class="site-descrip"><textarea name="new_gate_brief[]" cols="127" rows="2" placeholder="描述">${brief}</textarea></span>
                    <div class="m2o-item dottedLine"></div>
                </div>
                {{tmpl($value['expand']) "#add-extend-tpl"}}
                <span class="add-way" data-type="extend">添加扩展信息</span>
                <div class="m2o-item dottedLine"></div>
                <div class="m2o-item way-img">
                    <label class="title">平面图：</label>
                    <ul class="img-list flat-list clear">
                        {{if indexpic}}
                        {{tmpl($value["indexpic"]) "#add-pic-tpl"}}
                        {{/if}}
                        <li class="add-img"></li>
                    </ul>
                </div>
                <div class="m2o-item solidLine"></div>
            </div>
            {{/each}}
            {{else}}
            {{tmpl($data["roadtpl"]) "#add-gate-tpl"}}
            {{/if}}
            <span class="add-way" data-type="gate">添加出入口信息</span>
            <div class="m2o-item solidLine"></div>
            <div class="m2o-item">
                <input type="submit" value="保存" class="save-button" /><span class="cancel-button" >取消</span>
                <input type="hidden" name="a" value="operate_site_gate" />
                <input type="hidden" name="site_id" value="${site_id}" />
                <input type="hidden" name="sub_id" value="" />
            </div>
        </form>
    </div>
</script>

<script type="text/x-jquery-tmpl" id="add-extend-tpl">
<div class="m2o-item">
    <label class="title">扩展信息：</label>
    <div class="extend">
        <div class="add-img extend-img">
            <input type="hidden" title="_extend_img[]" name="${index}_extend_img[]" value="${id}">
            {{if src}}<img src="${src}"/>{{/if}}
        </div>
        <div class="extend-obj">
            <div class="userdefined">
                <input type="text" title="_type_title[]" name="${index}_type_title[]" placeholder="请输入新的类型名称" value=""/><input type="text" title="_sign[]" name="${index}_sign[]" placeholder="标识" value="" />
                <em class="sure-defined">确定</em><em class="cancel-defined">取消</em>
            </div>
        </div>
        <span class="extend-name" style="{{if sign == 'bus'}}display:inline-block; {{/if}}"><input type="text" title="_new_extend_station_name[]" name="${index}_new_extend_station_name[]" value="${station_name}" placeholder="站点名"/>
        <input type="button" class="search-btn"/></span> 
        <input type="text" class="station_id" placeholder="station_id" style="{{if sign == 'bus'}}display:inline-block; {{/if}}" title="_new_extend_station_id[]" name="${index}_new_extend_station_id[]" value="${station_id}"/>
        <em class="del-way" data-type="gate"></em>
        <span class="site-descrip"><textarea title="_new_extend_brief[]" name="${index}_new_extend_brief[]" cols="110" rows="2" placeholder="描述">${brief}</textarea></span>
    </div>
    <div class="m2o-item dottedLine"></div>
</div>
</script>

<script type="text/x-jquery-tmpl" id="add-gate-tpl">
<div class="let-list let-show">
    <div class="m2o-item">
        <span class="site-sign"><label class="title">标识：</label><input type="text" name="new_gate_sign[]" value="${sign}"/></span>
        <span class="site-name"><label class="title">出口：</label><input type="text" name="new_gate_title[]" value="${title}"/></span>
        <span class="site-titude"><label class="title">经度：</label><input type="text" name="new_gate_longitude[]" value="${longitude}"/></span>
        <span class="site-titude"><label class="title">纬度：</label><input type="text" name="new_gate_latitude[]" value="${latitude}"/></span>
        <em class="map-icon"></em><em class="edit">缩小</em><em class="del">删除</em><span class="site-order" style="#ccccc"><label class="title" style="color:#6da1ea">排序ID:</label><input type="text" name="order_id[]" value=""/></span>
        <span class="site-descrip"><textarea name="new_gate_brief[]" cols="127" rows="2" placeholder="描述">${brief}</textarea></span>
        <div class="m2o-item dottedLine"></div>
    </div>
    <span class="add-way" data-type="extend">添加扩展信息</span>
    <div class="m2o-item dottedLine"></div>
    <div class="m2o-item way-img">
        <label class="title">平面图：</label>
        <ul class="img-list flat-list clear">
            <li class="add-img"></li>
        </ul>
    </div>
    <div class="m2o-item solidLine"></div>
</div>
</script>

<script type="text/x-jquery-tmpl" id="add-servicebox-tpl">
    <div class="way-item service-info">
        <form name="serviceform" class="serviceform" action="run.php?mid={$_INPUT['mid']}" method="post" >
            {{tmpl($data["roadtpl"]) "#add-service-tpl"}}
            <span class="add-way" data-type="service">添加服务设施</span>
            <div class="m2o-item solidLine"></div>
            <div class="m2o-item">
                <input type="submit" value="保存" class="save-button" /><span class="cancel-button" >取消</span>
                <input type="hidden" name="a" value="operate_site_service" />
                <input type="hidden" name="site_id" value="${site_id}" />
                <input type="hidden" name="sub_id" value="" />
            </div>
        </form>
    </div>
</script>

<script type="text/x-jquery-tmpl" id="add-service-tpl">
<div class="m2o-item">
    <label class="title">服务设施：</label>
    <div class="extend">
        <div class="add-img extend-img">
            <input type="hidden" name="service_img[]" value="${id}">
            {{if src}}<img src="${src}"/>{{/if}}
        </div>
        <div class="service-obj">
            <div class="userdefined">
                <input type="text" name="type_title" placeholder="请输入新的服务名称" value=""/><input type="text" name="sign" placeholder="标识" value="" />
                <em class="sure-defined">确定</em><em class="cancel-defined">取消</em>
            </div>
        </div>
        <input class="select-input color-picker" data-color="${color}" type="text" name="color[]" value="${color}"/>
        <em class="del-way" data-type="service"></em>
        <span class="site-descrip"><textarea name="brief[]" cols="110" rows="2" placeholder="描述">${brief}</textarea></span>
    </div>
    <div class="m2o-item solidLine"></div>
</div>
</script>

<script type="text/x-jquery-tmpl" id="add-pulldown-tpl">
    <li style="cursor: pointer" _sign="${sign}">
        <a href="###" onclick="if(hg_select_value(this,0, '${show}', '${typeid}', 0)){};" attrid="${id}" class="overflow">${title}</a>
        {{if id}}<em class="del-pull"></em>{{/if}}
    </li>
</script>




