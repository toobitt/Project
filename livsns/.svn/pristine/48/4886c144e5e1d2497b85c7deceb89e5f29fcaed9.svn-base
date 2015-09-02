<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/25
 * Time: 下午4:01
 */
require_once ('./global.php');
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'RegionApi');
class RegionApi extends outerReadBase
{
    public function __construct()
    {
        parent::__construct();
        include_once (CUR_CONF_PATH . 'lib/region.class.php');
        $this->obj = new Region();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show(){}
    public function count(){}
    public function detail(){}


    public function get_province()
    {
        $prov_id = $this->input['province_id'];
        $key = trim($this->input['key']);
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 1000;
        $limit = $offset . ', ' . $count;

        $where = $order = '';
        if($prov_id)
        {
            $where .= " AND id = " . $prov_id;
        }
        if($key)
        {
            $where .= " AND name LIKE '%".$key."%'";
        }
        $prov = $this->obj->get_province($where, $order, $limit);
        foreach ((array)$prov as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    public function get_city()
    {
        $prov_id = $this->input['province_id'];
        $city_id = $this->input['city_id'];
        $key = trim($this->input['key']);

        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 1000;
        $limit = $offset . ', ' . $count;

        $where = $order = '';
        if($prov_id)
        {
            $where .= " AND province_id = " . $prov_id;
        }
        else
        {
            $where .= " AND province_id != 110000 AND province_id != 120000 AND province_id != 310000 AND province_id != 500000";
        }
        if($city_id)
        {
            $where  .= " AND id = ".$city_id;
        }
        if($key)
        {
            $where .= " AND city LIKE '%".$key."%'";
        }
        $city = $this->obj->get_city($where, $order, $limit);
        foreach ((array)$city as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    public function get_area()
    {
        $city_id = $this->input['city_id'];
        $area_id = $this->input['area_id'];
        $key = trim($this->input['key']);

        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 1000;
        $limit = $offset . ', ' . $count;

        $where = $order = '';
        if($city_id)
        {
            $where .= " AND city_id = " . $city_id;
        }
        if($area_id)
        {
            $where  .= " AND id = ".$area_id;
        }
        if($key)
        {
            $where .= " AND area LIKE '%".$key."%'";
        }

        $area = $this->obj->get_area($where, $order, $limit);

        foreach ((array)$area as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }
}
require_once (ROOT_PATH . 'excute.php');