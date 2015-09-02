<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/20
 * Time: 10:26
 */

class HgCashIncrease extends HgCash
{
    //增加的金额
    private $increase = '';

    //使用的数量
    private $amount = '';

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
        return $price + $this->increase * $this->amount;
    }

}