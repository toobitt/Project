<li class="clear"  id="r_{$formdata['row_id']}"   name="{$v['row_id']}"  orderid="{$v['video_order_id']}"   onclick="hg_row_interactive(this, 'click', 'cur');" onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left"><a class="lb"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a><a class="slt"><img src="{$formdata['img']}" width="40" height="30"  onclick="hg_get_img({$formdata['id']},{$formdata['row_id']});" /></a><a class="bf"><em class="current"  onclick="hg_play_video({$formdata['vodid']});"></em></a></span>
                        <span class="right"><a class="fb"><em class="b2"></em></a><a class="ml"><em>{$formdata['bitrate']}</em></a><a class="fl"><em class="color_green">{$formdata['vod_sort_id']}</em></a><a class="zt"><em><sup id="text_{$v['row_id']}">{$v['status']}</sup><sub id="tool_{$v['row_id']}" ><span id="status_{$v['row_id']}" style="width:0px;"></span></sub></em></a><a class="tjr"><em>{$formdata['addperson']}</em><span>{$formdata['create_time']}</span></a></span><span class="title">{if $v['collects']}<em></em>{/if}<a href="javascript:void(0);" onclick="check_menu({$v['row_id']});" id="t_{$v['id']}">{$formdata['title']}<strong>{$formdata['duration']}</strong></a></span>
                        <div class="content_more clear" id="content_{$formdata['row_id']}" style="display:none">
                            	<ul class="content_more_left">
                                	<li>来&nbsp;&nbsp;&nbsp;&nbsp;源：<span>{$formdata['source']}</span></li>
                                    <li>分&nbsp;&nbsp;&nbsp;&nbsp;类：<span>{$formdata['vod_leixing']} > {$formdata['vod_sort_id']}</span></li>
                                    <li>关键字：<span>{$formdata['keywords']}</span></li>
                                    <li>发布至：<span>新闻综合频道</span></li>
                                    <li class="more">描&nbsp;&nbsp;&nbsp;&nbsp;述：<span>{$formdata['comment']}</span></li>
                                </ul>
                            <div class="content_more_right">
                            <ul>
                            	<li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a  href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['row_id']}">编辑</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a onclick="return hg_ajax_post(this, '删除', 1);" title="" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['row_id']}">删除</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$v['row_id']}&audit=1" onclick="return hg_ajax_post(this, '审核', 1);">审核</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$v['row_id']}&audit=0" onclick="return hg_ajax_post(this, '打回', 1);">打回</a></span>
                                </li>
                                
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"> <a href="./run.php?mid={$_INPUT['mid']}&a=move&id={$v['row_id']}"  onclick="return hg_ajax_post(this, '移动', 1);">移动</a></span>
                                </li>
                            </ul>
                                <p><a href="./run.php?mid={$_INPUT['mid']}&a=add_to_collect&id={$v['row_id']}" onclick="return hg_ajax_post(this, '添加至集合', 1);">添加至集合</a></p>
                                <p><a href="#">发布至网站</a></p>
                            </div>
                        </div>
                    </li>