<?php
/**
 * 返现
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/20
 * Time: 10:19
 */

class HgCashReturn extends HgCash
{
    //返现条件
    private $condition = '';

    //返现金额
    private $return = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }


    public function getReturn($price)
    {
        if ($price > $this->condition) {
            $price = $price - floor($price/$this->condition) * $this->return;
        }
        return $price;
    }


}