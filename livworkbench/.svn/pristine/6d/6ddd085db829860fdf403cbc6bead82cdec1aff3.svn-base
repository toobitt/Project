<!-- 提示框 start -->
<div id="global-btns">
    <?php
    $btns = array(
        'ym' => '<div data-type="ym" id="ppp-btn" title="页面属性 ctrl + y">属性</div>',
        'bj' => '<div data-type="bj" title="布局 ctrl + d">布局</div>',
        'bjm' => '<div data-type="bjm" title="布局标题">标题</div>',
        'qh' => '<div data-type="qh" id="qhc-btn" title="换页面 ctrl + e">换页</div>',
        /*'ys' => '<div data-type="ys" class="gt-ys">
                     样式<span>ctrl + f</span>
                     <div class="m2o-flex m2o-flex-center">
                         <span>上一页 <br/> ctrl + 1</span>
                         <span>下一页 <br/> ctrl + 2</span>
                     </div>
                 </div>',*/
        'dy' => '<div data-type="dy" id="xianyin-btn" title="显示/隐藏单元 ctrl + h" data-open="显示" data-close="隐藏">隐藏</div>',
        'dys' => '<div data-type="dys" id="shuaxin-btn" title="刷新单元 ctrl + s">刷新</div>',
        'yl' => '<div data-type="yl" id="yulan-btn" title="预览 ctrl + g"><a target="_blank" style="display:none;"></a>预览</div>',
        'fb' => '<div data-type="fb" id="make-btn" title="发布页面">发布</div>',
        'css' => '<div data-type="css" title="页面素材">素材</div>',
        'sde' => '<div data-type="sde" title="数据编辑">数据</div>'
    );

    $bses = array(
        'm' => array('dy', 'dys', 'css', 'yl', 'fb'),
        'p' => array('dy', 'dys', 'yl', 'fb'),
        'k' => array('bj', 'bjm', 'dy', 'dys', 'css', 'yl', 'fb'),
        'b' => array('ys', 'dy', 'dys'),
    );

    $currentBtns = $bses[$bs];
    ?>
    <ul id="global-btns">
        <?php
        foreach($currentBtns as $k => $v){
        ?>
            <li><?php echo $btns[$v];?></li>
        <?php
        }
        ?>
    </ul>
</div>
<!-- 提示框 end -->