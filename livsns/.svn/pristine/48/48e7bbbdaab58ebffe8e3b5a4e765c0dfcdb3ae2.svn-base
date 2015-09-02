<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-7-31
 * Time: 上午11:06
 */
class Mode extends InitFrm {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function select($where = '', $order = '', $limit = '', $group = '', $key = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT * FROM '.DB_PREFIX.'topics' .  $where . $order . $group . $limit;
        $q = $this->db->query($sql);

        $ret = array();
        while( ($row = $this->db->fetch_array($q)) != false ) {
            if ($key) {
                $ret[$row[$key]][] = $row;
            } else {
                $ret[] = $row;
            }
        }
        return $ret;
    }

    public function select_material($where = '', $order = '', $limit = '', $group = '', $key = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT * FROM '.DB_PREFIX.'materials' .  $where . $order . $group . $limit;
        $q = $this->db->query($sql);

        $ret = array();
        while( ($row = $this->db->fetch_array($q)) != false ) {
            if ($key) {
                $ret[$row[$key]][] = $row;
            } else {
                $ret[] = $row;
            }
        }
        return $ret;
    }

    public function select_tags($where = '', $order = '', $limit = '', $group = '', $key = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT * FROM '.DB_PREFIX.'tags' .  $where . $order . $group . $limit;
        $q = $this->db->query($sql);

        $ret = array();
        while( ($row = $this->db->fetch_array($q)) != false ) {
            if ($key) {
                $ret[$row[$key]][] = $row;
            } else {
                $ret[] = $row;
            }
        }
        return $ret;
    }

    public function select_guests($where = '', $order = '', $limit = '', $group = '', $key = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT * FROM '.DB_PREFIX.'guests' .  $where . $order . $group . $limit;
        $q = $this->db->query($sql);

        $ret = array();
        while( ($row = $this->db->fetch_array($q)) != false ) {
            if ($key) {
                $ret[$row[$key]][] = $row;
            } else {
                $ret[] = $row;
            }
        }
        return $ret;
    }


    public function getOne($where = '', $order = '', $group = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = ' LIMIT 1 ';

        $sql = 'SELECT *
                FROM '.DB_PREFIX.'topics' .  $where . $order . $group . $limit;
        $ret = $this->db->query_first($sql);

        //索引图
        if($ret['indexpic'])
        {
            $sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE id = ' . intval($ret['indexpic']) ;
            $indexpic = $this->db->query_first($sql);
            $ret['indexpic_url'] = $indexpic['pic'];
        }
        //参与嘉宾
        if ($ret['guest_ids'])
        {
            $sql = 'SELECT * FROM '.DB_PREFIX.'guests WHERE id IN(' . $ret['guest_ids'] . ')';
            $q = $this->db->query($sql);
            $ret['guests_info'] = array();
            while (false != ($row = $this->db->fetch_array($q)))
            {
                if ($row['indexpic'])
                {
                    $row['indexpic'] = json_decode($row['indexpic'], 1);
                    $row['indexpic'] = $row['indexpic'][0];
                }
                else
                {
                   $row['indexpic'] = array();
                }
                $ret['guests_info'][] = $row;
            }
        }
        //引用内容
        if ($ret['id'])
        {
            $sql = 'SELECT * FROM '.DB_PREFIX.'refer_content WHERE topic_id = ' . intval($ret['id']);
            $q = $this->db->query($sql);
            $ret['refer'] = array();
            while (($row = $this->db->fetch_array($q))!=false)
            {
                $row['indexpic_json'] = htmlspecialchars($row['indexpic']);
                $row['indexpic'] = $row['indexpic'] ? json_decode($row['indexpic'], 1) : array();
                $ret['refer'][] = $row;
            }
        }
        return $ret;
    }

    public function getOneTag($where = '', $order = '', $group = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = ' LIMIT 1 ';

        $sql = 'SELECT *
                FROM '.DB_PREFIX.'tags' .  $where . $order . $group . $limit;
        $ret = $this->db->query_first($sql);

        return $ret;
    }

    public function getOneGuest($where = '', $order = '', $group = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = ' LIMIT 1 ';

        $sql = 'SELECT *
                FROM '.DB_PREFIX.'guests' .  $where . $order . $group . $limit;
        $ret = $this->db->query_first($sql);

        return $ret;
    }

    public function count($where = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;

        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'topics' . $where;

        $total = $this->db->query_first($sql);

        return $total;
    }

    public function countTags($where = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;

        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'tags' . $where;

        $total = $this->db->query_first($sql);

        return $total;
    }

    public function countGuests($where = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;

        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'guests' . $where;

        $total = $this->db->query_first($sql);

        return $total;
    }


    public function insert($data, $table_name = 'topics', $replace = false) {
        if (!is_array($data) || count($data) < 0 ) {
            return false;
        }
        $insert_id = $this->db->insert_data($data, $table_name, $replace);

        if (!$insert_id) {
            return false;
        }

        //更改排序id
        $this->db->update_data(array('order_id' => $insert_id), $table_name, ' id = ' . $insert_id);

        return $insert_id;
    }

    /**
     * @param $data
     * @param string $where ' id= 1 AND good_id = 2'
     * @return bool
     */
    public function update($data, $where = '', $table_name = 'topics') {
        if ($data == '' || count($data) < 0 || $where == '') {
            return false;
        }
        return $this->db->update_data($data,$table_name, $where);
    }



    public function delete ($where) {
        if ($where == '') {
            return false;
        }

        $where = ' WHERE 1 ' . $where;

        $sql = 'DELETE  FROM '.DB_PREFIX.'topics' . $where;
        $this->db->query($sql);
        return true;
    }

    public function delete_material ($where) {
        if ($where == '') {
            return false;
        }

        $where = ' WHERE 1 ' . $where;

        $sql = 'DELETE  FROM '.DB_PREFIX.'materials' . $where;
        $this->db->query($sql);
        return true;
    }

    public function deleteTags ($where) {
        if ($where == '') {
            return false;
        }

        $where = ' WHERE 1 ' . $where;

        $sql = 'DELETE  FROM '.DB_PREFIX.'tags' . $where;
        $this->db->query($sql);
        return true;
    }

    public function deleteGuests ($where) {
        if ($where == '') {
            return false;
        }
        $where = ' WHERE 1 ' . $where;

        $sql = 'DELETE  FROM '.DB_PREFIX.'guests' . $where;
        $this->db->query($sql);
        return true;
    }

    public function deleteRefer ($where) {
        if ($where == '') {
            return false;
        }
        $where = ' WHERE 1 ' . $where;

        $sql = 'DELETE  FROM '.DB_PREFIX.'refer_content' . $where;
        $this->db->query($sql);
        return true;
    }


    public function create_chatroom($topic_id, $topic_info = '')
    {
        if (empty($topic_info))
        {
            $topic_info = $this->getOne(' AND id = ' . $topic_id);
        }
        if (!$topic_info['chatroom_id'])
        {
            $topic_info['indexpic_url'] = $topic_info['indexpic_url'] != '' ? json_decode($topic_info['indexpic_url'], 1) : array();
            include_once ROOT_PATH . 'lib/class/im.class.php';
            $this->im = new im();
            $chatroom_data = array(
                'title' => $topic_info['title'],
                'brief' => $topic_info['brief'],
                'indexpic' => $topic_info['indexpic_url'],
                'settings' => array(
                ),
                'app_uniqueid' => APP_UNIQUEID,
            );
            $chatroom = $this->im->create_session($chatroom_data);
            $this->update(array('chatroom_id' => $chatroom['id']), ' id='.$topic_id);
        }
        return $chatroom['id'];
    }

}
/* End of file topic.class.php */
