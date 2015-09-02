<?php

define('ROOT_PATH', './../../');
define('CUR_CONF_PATH', './');
require_once(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'textsearch'); //模块标识

class textsearchApi extends outerReadBase
{

    private $bundle_id;
    private $module_id;
    private $filename;

    public function __construct()
    {
        parent::__construct();
        if (!$this->settings['is_open_xs'])
        {
            $this->errorOutput('NOT_OPEN_XS');
        }
        if (!$this->input['bundle_id'] || !$this->input['module_id'])
        {
            $this->errorOutput('NO_APP_MODULE');
        }
        $this->filename = $this->input['bundle_id'] . '_' . $this->input['module_id'];
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function xs_index()
    {
        $data = $this->input['data'];
        $type = $this->input['type'] ? $this->input['type'] : 'add';
        $conf = realpath(CUR_CONF_PATH . 'data/' . $this->filename . '.ini');
        if ($conf)
        {
            try
            {
                include_once (CUR_CONF_PATH . 'lib/xunsearch/XS.php');
                $xs    = new XS($conf); // 建立 XS 对象，项目名称为：demo
                $index = $xs->index; // 获取 索引对象
            }
            catch (XSException $e)
            {
                $this->errorOutput('ERROR');
            }
            if ($type == 'clean')
            {
                $index->clean();
                $this->addItem('true');
                $this->output();
            }
            if (empty($data))
            {
                $this->errorOutput('NO_DATA');
            }
            switch ($type)
            {
                case 'add':
                    $doc = new XSDocument;
                    $doc->setFields($data);
                    $index->add($doc);
                    break;

                case 'update':
                    $doc = new XSDocument;
                    $doc->setFields($data);
                    $index->update($doc);
                    break;

                case 'del':
                    $index->del($data);
                    break;

                case 'rebuild':
                    $doc = new XSDocument;
                    $doc->setFields($data);
                    $index->beginRebuild();
                    $index->add($doc);
                    $index->endRebuild();
                    break;
            }
            $this->addItem('true');
            $this->output();
        }
        else
        {
            $this->errorOutput('NO_***.INI');
        }
    }

    /**
     *  $array_field      数组的字段
     *  $highlight_field  需要高亮的字段
     *  搜索语句最大支持长度为 80 字节（每一个汉字占 3 字节）
     * 	addDB($name) - 用于多库搜索，添加数据库名称
      addRange($field, $from, $to) - 添加搜索过滤区间或范围
      addWeight($field, $term) - 添加权重索引词
      setCharset($charset) - 设置字符集
      setCollapse($field, $num = 1) - 设置搜索结果按字段值折叠
      setDb($name) - 设置搜索库名称，默认则为 db
      setFuzzy() - 设置开启模糊搜索, 传入参数 false 可关闭模糊搜索
      setLimit($limit, $offset = 0) - 设置搜索结果返回的数量和偏移
      setQuery() - 设置搜索语句
      setSort($field, $asc = false) - 设置搜索结果按字段值排序
      setFacets 第一参数为要分面的字段名称（多个字段请用数组作参数）， 第二参数是可选的布尔类型，true 表示需要准确统计，默认 false 则为估算
      getFacets 返回数组，以 fid 为键，匹配数量为值

      $searchdata = array(
      'charset'      =>      'utf-8',  //设置返回的字符编码
      'query'        =>      'bundle_id:(tuji) AND client_type:(2) OR column_id:(4) NOT site_id:(1) XOR 西湖', //查询语句
      'fuzzy'        =>       1为true 0为false,//模糊查询为true否则false
      'limit'        =>       'count,offset',
      'range'		   =>       array('publish_time'=>'1343432345,1463784752','create_time'=>'1343432345,1463784752'),//值的范围
      'sort'         =>        array('id'=>true,'weight'=>false),  //k为字段，false表示降序，true表示升序
      'weight'	   =>       array('title'=>'你好','brief'=>'好'),// 增加附加条件：提升标题中包含 'xunsearch' 的记录的权重
      'autosynonyms' =>		1为true 0为false,  //设为 true 表示开启同义词功能, 设为 false 关闭同义词功能
      'setfacets_fields' =>   array('field'=>array('fid','year'),'count_type'=>0或1), 该方法接受两个参数，第一参数为要分面的字段名称（多个字段请用数组作参数）， 第二参数是可选的布尔类型，1 表示需要准确统计，默认 0 则为估算
      'hotquery'     =>       array('limit'=>10,'type'=>'total'or'lastnum'or'currnum'),$limit 整数值，设置要返回的词数量上限，默认为 6，最大值为 50;$type 指定排序类型，默认为 total(总量)，可选值还有：lastnum(上周) 和 currnum(本周)
      );
      $array_field 表示输出的是数组 需要反串行化
      $highlight_field 需要高亮的字段
     * */
    public function xs_search()
    {
        $result          = $docs            = array();
        $searchdata      = $this->input['searchdata'] ? $this->input['searchdata'] : array();
        $array_field     = $this->input['array_field'] ? $this->input['array_field'] : array();
        $highlight_field = $this->input['highlight_field'] ? $this->input['highlight_field'] : array();
        $conf            = realpath(CUR_CONF_PATH . 'data/' . $this->filename . '.ini');
        if ($conf)
        {
            try
            {
                include_once (CUR_CONF_PATH . 'lib/xunsearch/XS.php');
                $xs     = new XS($conf); // 建立 XS 对象
                $search = $xs->search; // 获取 搜索对象
            }
            catch (XSException $e)
            {
                $this->errorOutput('ERROR');
            }
            if (isset($searchdata['charset']))
            {
                $search->setCharset(empty($searchdata['charset']) ? 'utf-8' : $searchdata['charset']);
            }
            if (isset($searchdata['query']))
            {
                $search->setQuery($searchdata['query']);
            }

            if (isset($searchdata['fuzzy']))
            {
                $search->setFuzzy($searchdata['fuzzy'] ? true : false);
            }
            if (isset($searchdata['autosynonyms']))
            {
                $search->setAutoSynonyms($searchdata['autosynonyms'] ? true : false);
            }
            if (isset($searchdata['limit']))
            {
                $limit_arr = explode(',', $searchdata['limit']);
                $search->setLimit(intval($limit_arr[0]), intval($limit_arr[1]));
            }
            if (!empty($searchdata['range']) && is_array($searchdata['range']))
            {
                foreach ($searchdata['range'] as $k => $v)
                {
                    if ($v)
                    {
                        $rangearg = explode(',', $v);
                        $search->addRange($k, $rangearg[0], $rangearg[1]);
                    }
                }
            }
            if (!empty($searchdata['sort']))
            {
                foreach ($searchdata['sort'] as $k => $v)
                {
                    $searchdata['sort'][$k] = $v ? true : false;
                }
                $search->setMultiSort($searchdata['sort']);
            }
            if (!empty($searchdata['weight']) && is_array($searchdata['weight']))
            {
                foreach ($searchdata['weight'] as $k => $v)
                {
                    $search->addWeight($k, $v);
                }
            }
            if (!empty($searchdata['setfacets_fields']) && is_array($searchdata['setfacets_fields']['field']))
            {
                foreach ($searchdata['setfacets_fields']['field'] as $sfk => $sf)
                {
                    $search->setFacets($searchdata['setfacets_fields']['field'], $searchdata['setfacets_fields']['count_type'] ? true : false);
                }
            }

            $docs  = $search->search(); // 执行搜索，将搜索结果文档保存在 $docs 数组中
            $count = $search->getLastCount(); // 获取搜索结果总数
            if (!is_array($docs))
            {
                $this->addItem(array());
                $this->output();
            }
            //取回分面搜索结果
            if (!empty($searchdata['setfacets_fields']) && is_array($searchdata['setfacets_fields']['field']))
            {
                foreach ($searchdata['setfacets_fields']['field'] as $sfk => $sf)
                {
                    $result['facet_' . $sf] = $search->getFacets($sf);
                }
            }

            //取热词
            if (!empty($searchdata['hotquery']))
            {
                $result['hotquery'] = $search->getHotQuery($searchdata['hotquery']['limit'], $searchdata['hotquery']['type'] ? $searchdata['hotquery']['type'] : 'total');
            }

            $result['count'] = $count;
            if ($array_field)
            {
                if ($highlight_field)
                {
                    foreach ($docs as $k => $v)
                    {
                        foreach ($v as $kk => $vv)
                        {
                            $vv                      = in_array($kk, $highlight_field) ? $search->highlight($vv) : $vv;
                            $result['data'][$k][$kk] = in_array($kk, $array_field) ? unserialize($vv) : $vv;
                        }
                    }
                }
                else
                {
                    foreach ($docs as $k => $v)
                    {
                        foreach ($v as $kk => $vv)
                        {
                            $result['data'][$k][$kk] = in_array($kk, $array_field) ? unserialize($vv) : $vv;
                        }
                    }
                }
            }
            else
            {
                if ($highlight_field)
                {
                    foreach ($docs as $k => $v)
                    {
                        foreach ($v as $kk => $vv)
                        {
                            $vv                      = in_array($kk, $highlight_field) ? $search->highlight($vv) : $vv;
                            $result['data'][$k][$kk] = $vv;
                        }
                    }
                }
                else
                {
                    foreach ($docs as $k => $v)
                    {
                        foreach ($v as $kk => $vv)
                        {
                            $result['data'][$k][$kk] = $vv;
                        }
                    }
                }
            }
            $this->addItem($result);
        }
        else
        {
            $this->errorOutput('NO_***.INI');
        }
        $this->output();
    }

    public function xs_get_hotquery()
    {
        $count  = $this->input['count']?($this->input['count']):10;
        $conf            = realpath(CUR_CONF_PATH . 'data/' . $this->filename . '.ini');
        $type   = '';
        try
        {
            include_once (CUR_CONF_PATH . 'lib/xunsearch/XS.php');
            $xs     = new XS($conf); // 建立 XS 对象
            $search = $xs->search; // 获取 搜索对象
        }
        catch (XSException $e)
        {
            $this->errorOutput('ERROR');
        }
        $result = $search->getHotQuery(50, $type ? $type : 'currnum');
        if ($result && is_array($result))
        {
            arsort($result);
        }
        $i = 1;
        $r = array();
        if ($count < 50)
        {
            foreach ($result as $k => $v)
            {
                if($i>$count)
                {
                    break;
                }
                $r[$k] = $v;
                $i++;
            }
        }
        else
        {
            $r = $result;
        }
        
        $this->addItem($r);
        $this->output();
    }

    /**
     * 取文本的关键词
     * limit 默认取10个
     * xattr   条件：在返回结果的词性过滤, 多个词性之间用逗号分隔, 以~开头取反 
     * 			如: 设为 n,v 表示只返回名词和动词; 设为 ~n,v 则表示返回名词和动词以外的其它词
     * return 返回词汇数组, 每个词汇是包含 [times:次数,attr:词性,word:词]
     * */
    public function xs_get_keyword()
    {
        $text  = $this->input['text'];
        $limit = $this->input['limit'] ? $this->input['limit'] : 10;
        $xattr = $this->input['xattr'];
        $conf  = realpath(CUR_CONF_PATH . 'data/' . 'textsearch_textsearch' . '.ini');
        if (!$conf)
        {
            $this->errorOutput('NO_DEFAULT.INI');
        }
        try
        {
            include_once (CUR_CONF_PATH . 'lib/xunsearch/XS.php');
            $xs     = new XS($conf);
            $xsts   = new XSTokenizerScws();
            $result = $xsts->getTops($text, $limit, $xattr); //,100,'~n,v'
        }
        catch (XSException $e)
        {
            $this->errorOutput('error');
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * 取文本的分词
     * $text 待分词的文本
     * return 返回词汇数组, 每个词汇是包含 [off:词在文本中的位置,attr:词性,word:词]
     * */
    public function xs_getResult()
    {
        $text = $this->input['text'];
        $conf = realpath(CUR_CONF_PATH . 'data/' . 'textsearch_textsearch' . '.ini');
        if (!$conf)
        {
            $this->errorOutput('NO_DEFAULT.INI');
        }
        try
        {
            include_once (CUR_CONF_PATH . 'lib/xunsearch/XS.php');
            $xs     = new XS($conf);
            $xsts   = new XSTokenizerScws();
            $result = $xsts->getResult($text);
            restore_error_handler();
        }
        catch (XSException $e)
        {
            $this->errorOutput('error');
        }
        $this->addItem($result);
        $this->output();
    }

    public function show()
    {
        
    }

    public function detail()
    {
        
    }

    public function index()
    {
        
    }

    public function count()
    {
        
    }

}

$out    = new textsearchApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>


