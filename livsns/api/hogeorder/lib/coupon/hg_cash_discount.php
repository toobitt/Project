<?php
/**
 * 折扣类
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/20
 * Time: 10:07
 */

class HgCashDiscount extends HgCash
{

    private $discount = 1;  //折扣  可选值 0-1

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
        return $price * $this->discount;
    }

}