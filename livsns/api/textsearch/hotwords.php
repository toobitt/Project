<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'hotwords');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");

class HotwordsApi extends adminBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include site.class.php
     */
    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/hotwords.class.php');
        $this->obj	= new Hotwords();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $condition     = $this->get_condition();
        $offset        = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count         = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit         = " limit {$offset}, {$count}";

        $ret = $this->obj->show($condition, $limit,$this->other_field);
        if (is_array($ret))
        {
            foreach ($ret AS $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    public function get_condition()
    {
        $condition = '';
        if($this->input['search_text'])
        {
                include(ROOT_PATH . 'lib/class/pinyin.class.php');
                $title_pinyin_result = hanzi_to_pinyin($this->input['search_text'], false, 0);

                if ($title_pinyin_result['word'])
                {
                    $pinyin = implode('', $title_pinyin_result['word']);
                    $condition .= ' AND pinyin like "%'.$pinyin.'%"';
                    //$title = $this->get_titleResult($title . ' ');
                    //$condition .= " AND MATCH (pinyin) AGAINST ('" . $pinyin . "' IN BOOLEAN MODE )";
                    //$this->other_field = ",MATCH (pinyin) AGAINST ('" . $pinyin . "' IN BOOLEAN MODE ) AS pinyin_score";
                }
            /**
            else
            {
                $condition .= ' name like "%'.$this->input['search_text'].'%"';
            }
            */
        }
        $condition .=" ORDER BY ".($this->other_field?' pinyin_score DESC,':'');
        
        if ($this->input['sort_type'] == 'ASC')
        {
            $condition .= " order_id  " . $this->input['sort_type'];
        }
        else
        {
            $condition .= " order_id DESC ";
        }
        return $condition;
    }

    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new HotwordsApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
