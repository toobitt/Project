{template:head}
{code}

$optext="更新";

{/code}
{if is_array($formdata)}
{foreach $formdata as $key => $value}
{code}
$$key = $value;
{/code}
{/foreach}
{/if}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}
{js:wb_circle}
<script type="text/javascript">

</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
    <div class="ad_middle">
        <form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}"  method="post">
            <h2>{$optext}webview</h2>
            <ul class="form_ul">
                <li class="i">
                    <div class="form_ul_div clear">
                        <span class="title">webview：</span><input type="text" value='{$webview_url}' name='webview_url' class="title">
                    </div>
                </li>
            </ul>
            <input type="hidden" name="a" value="update_webviewurl" />
            <input type="hidden" name="{$primary_key}" value="{$id}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
            <br />
            <input type="submit" id="submit_ok" name="sub" value="{$optext}webview" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
        </form>
    </div>
    <div class="right_version">
        <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
    </div>
</div>

{template:foot}