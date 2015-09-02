<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/12/2
 * Time: 下午3:07
 */
require_once('global.php');
define(SCRIPT_NAME, 'ReceiveAddressUpdateApi');
define('MOD_UNIQUEID','hogepay_order');
class ReceiveAddressUpdateApi extends adminUpdateBase
{
    public function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/receive_address.class.php');
        $this->obj = new ReceiveAddress();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create(){}

    public function update()
    {
        $id = intval(trim($this->input['id']));
        $contact_name = trim($this->input['contact_name']);
        $mobile = trim($this->input['mobile']);
        if (!$id)
        {
            $this->errorOutput('NO ID');
        }
        if (!$contact_name)
        {
            $this->errorOutput('NO CONTACT_NAME');
        }
        if(!$mobile)
        {
            $this->errorOutput('NO MOBILE');
        }

        $address = $this->obj->detail($id);

        $data = array(
            'contact_name' => $contact_name,
            'mobile'       => $mobile,
            'prov'         => trim($this->input['prov']),
            'city'         => trim($this->input['city']),
            'area'         => trim($this->input['area']),
            'address_detail' => trim($this->input['address_detail']),
            'postcode'       => trim($this->input['postcode']),
            'email'          => trim($this->input['email']),
            'isdefault'      => intval(trim($this->input['isdefault'])),
        );

        if ($this->obj->update($data, ' id='.$id) )
        {
            if ($data['isdefault'])
            {
                $this->obj->update(array('isdefault'=>0), ' id != ' . $id .' AND user_id = ' . $address['user_id']);
            }

            $this->addItem('success');
            $this->output();
        }
        else
        {
            $this->errorOutput('FAIL');
        }
    }

    public function delete()
    {
        $ids = trim($this->input['id']);
        $ids = is_array($ids) ? implode(',', $ids) : $ids;
        if(!$ids)
        {
            $this->errorOutput('NO ID');
        }
        if ($this->obj->delete($ids))
        {
            $this->addItem($ids);
            $this->output();
        }
        else
        {
            $this->errorOutput('删除失败');
        }
    }

    public function sort(){}

    public function publish(){}

    public function audit() {}
}
require_once ROOT_PATH . 'excute.php';