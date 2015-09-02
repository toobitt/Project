{template:head}
{if is_array($formdata)}
{foreach $formdata as $key => $value}
{code}
$$key = $value;
{/code}
{/foreach}
{/if}
{css:ad_style}
{js:ad}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
    <div class="ad_middle">
        <form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
            <h2>配置</h2>
            <ul class="form_ul">
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">支付方式:</span><span>{$title}<span>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">商户号:</span><input type="text" value="{$pay_config['mer_id']}" name='pay_config[mer_id]' class="title">
                    </div>
                </li>
                
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">商户秘钥:</span><input type="text" value="{$pay_config['security_key']}" name='pay_config[security_key]' class="title">
                    </div>
                </li>
                
				<li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">异步通知URL:</span><input type="text" value="{$pay_config['notify_url']}" name='pay_config[notify_url]' class="title">
                    	<font class="important">例:http://218.2.102.114:233/livsns/api/hogeorder/notify/notify_url_alipay.php</font>
                    </div>
                </li>
				
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">商户私钥：</span><textarea name='pay_config[private_key_path]'>{$pay_config['private_key_path']}</textarea>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">支付宝公钥：</span><textarea name="pay_config[public_key_path]">{$pay_config['public_key_path']}</textarea>
                    </div>
                </li>
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">是否启用：</span>
                        <label>
                            <input type="radio" value="1" name="status" class="n-h" {if $status == 1 }checked{/if}><span>启用</span>
                        </label>
                        <label>
                            <input type="radio" value="0" name="status" class="n-h" {if $status == 0 }checked{/if}><span>不启用</span>
                        </label>
                    </div>
                </li>
                <!--                <li class="i">-->
                <!--                    <div class="form_ul_div clear">-->
                <!--                        <span class="title">支付方式类型:</span><span>{$v['title']}-->
                <!--                    </div>-->
                <!--                </li>-->
                <!--                <li class="i">-->
                <!--                    <div class="form_ul_div clear">-->
                <!--                        <span class="title">圈子描述：</span><textarea name="description">{$brief}</textarea>-->
                <!--                    </div>-->
                <!--                </li>-->
            </ul>
            <input type="hidden" name="a" value="setting" />
            <input type="hidden" name="pay_type" value="{$pay_type}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
            <br />
            <input type="submit" id="submit_ok" name="sub" value="保存" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
        </form>
    </div>
    <div class="right_version">
        <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
    </div>
</div>

{template:foot}