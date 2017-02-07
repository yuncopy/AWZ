<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {


    protected $input;
    protected $tableName; //被操作的表名
    protected $fields=array(); //当前表的字段信息

    public function __construct($params)
    {

        parent::__construct();
        $this->load->database();
    }

    public function show()
    {
        echo 1111;
    }

    final public function getTable($aColumns,$sTable)
    {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        // $aColumns = array('id', 'first_name', 'last_name');


        // DB table to use
        // $sTable = 'data_table';

        if(!is_array($aColumns) || !$sTable){
            return false;
        }

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }

        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));

                }
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);

                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }

        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);
       // echo $this->db->last_query();
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;

        // Total data set length
        $iTotal = $this->db->count_all($sTable);

        /*
       // Output
       $output = array(
           'sEcho' => intval($sEcho),
           'iTotalRecords' => $iTotal,
           'iTotalDisplayRecords' => $iFilteredTotal,
           'aaData' => array()
       );
       foreach($rResult->result_array() as $aRow)
       {
           $row = array();

           foreach($aColumns as $col)
           {
               $row[] = $aRow[$col];
           }

           $output['aaData'][] = $row;
       }

       if( $output['aaData'] ){
           return json_encode($output);
       }
       */

        foreach($rResult->result_array() as $aRow)
        {
            $data[] = $aRow;
        }
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        $result_output['output'] = $output;
        $result_output['rResult'] = $data;
        return $result_output;

    }



    /**
     * 返回多层栏目
     * @param $data 操作的数组
     * @param int $pid 一级PID的值
     * @param string $html 栏目名称前缀
     * @param string $fieldPri 唯一键名，如果是表则是表的主键
     * @param string $fieldPid 父ID键名
     * @param int $level 不需要传参数（执行时调用）
     * @return array
     */
    static public function channelLevel($data, $pid = 0, $html = "&nbsp;", $fieldPri = 'cid', $fieldPid = 'pid', $level = 1)
    {
        if (empty($data)) {
            return array();
        }
        $arr = array();
        foreach ($data as $v) {
            if ($v[$fieldPid] == $pid) {
                $arr[$v[$fieldPri]] = $v;
                $arr[$v[$fieldPri]]['_level'] = $level;
                $arr[$v[$fieldPri]]['_html'] = str_repeat($html, $level - 1);
                $arr[$v[$fieldPri]]["_data"] = self::channelLevel($data, $v[$fieldPri], $html, $fieldPri, $fieldPid, $level + 1);
            }
        }

        return $arr;
    }

    /**
     * 获得所有子栏目
     * @param $data 栏目数据
     * @param int $pid 操作的栏目
     * @param string $html 栏目名前字符
     * @param string $fieldPri 表主键
     * @param string $fieldPid 父id
     * @param int $level 等级
     * @return array
     */
    static public function channelList($data, $pid = 0, $html = "&nbsp;", $fieldPri = 'id', $fieldPid = 'pid', $level = 1)
    {
        $data = self::_channelList($data, $pid, $html, $fieldPri, $fieldPid, $level);
        if (empty($data))
            return $data;
        foreach ($data as $n => $m) {
            if ($m['_level'] == 1)
                continue;
            $data[$n]['_first'] = false;
            $data[$n]['_end'] = false;
            if (!isset($data[$n - 1]) || $data[$n - 1]['_level'] != $m['_level']) {
                $data[$n]['_first'] = true;
            }
            if (isset($data[$n + 1]) && $data[$n]['_level'] > $data[$n + 1]['_level']) {
                $data[$n]['_end'] = true;
            }
        }
        //更新key为栏目主键
        $category=array();
        foreach($data as $d){
            $category[$d[$fieldPri]]=$d;
        }
        return $category;
    }

    //只供channelList方法使用
    static private function _channelList($data, $pid = 0, $html = "&nbsp;", $fieldPri = 'id', $fieldPid = 'pid', $level = 1)
    {
        if (empty($data))
            return array();
        $arr = array();
        foreach ($data as $v) {
            $id = $v[$fieldPri];
            if ($v[$fieldPid] == $pid) {
                $v['_level'] = $level;
                $v['_html'] = str_repeat($html, $level - 1);
                array_push($arr, $v);
                $tmp = self::_channelList($data, $id, $html, $fieldPri, $fieldPid, $level + 1);
                $arr = array_merge($arr, $tmp);
            }
        }
        return $arr;
    }

    /**
     * 获得树状数据
     * @param $data 数据
     * @param $title 字段名
     * @param string $fieldPri 主键id
     * @param string $fieldPid 父id
     * @return array
     */
    static public function tree($data, $title, $fieldPri = 'id', $fieldPid = 'pid')
    {
        if (!is_array($data) || empty($data))
            return array();
        $arr = self::channelList($data, 0, '', $fieldPri, $fieldPid);
        foreach ($arr as $k => $v) {
            $str = "";
            if ($v['_level'] > 2) {
                for ($i = 1; $i < $v['_level'] - 1; $i++) {
                    $str .= "&emsp;│";
                }
            }
            if ($v['_level'] != 1) {
                $t = $title ? $v[$title] : "";
                if (isset($arr[$k + 1]) && $arr[$k + 1]['_level'] >= $arr[$k]['_level']) {
                    $arr[$k]['_name'] = $str . "&emsp;├─ " . $v['_html'] . $t;
                } else {
                    $arr[$k]['_name'] = $str . "&emsp;└─ " . $v['_html'] . $t;
                }
            } else {
                $arr[$k]['_name'] = $v[$title];
            }
        }
        //设置主键为$fieldPri
        $data = array();
        foreach ($arr as $d) {
            $data[$d[$fieldPri]] = $d;
        }

        return $data;
    }

    /**
     * 获得所有父级栏目
     * @param $data 栏目数据
     * @param $sid 子栏目
     * @param string $fieldPri 唯一键名，如果是表则是表的主键
     * @param string $fieldPid 父ID键名
     * @return array
     */
    static public function parentChannel($data, $sid, $fieldPri = 'id', $fieldPid = 'pid')
    {
        if (empty($data)) {
            return $data;
        } else {
            $arr = array();
            foreach ($data as $v) {
                if ($v[$fieldPri] == $sid) {
                    $arr[] = $v;
                    $_n = self::parentChannel($data, $v[$fieldPid], $fieldPri, $fieldPid);
                    if (!empty($_n)) {
                        $arr = array_merge($arr, $_n);
                    }
                }
            }
            return $arr;
        }
    }

    /**
     * 判断$s_cid是否是$d_cid的子栏目
     * @param $data 栏目数据
     * @param $sid 子栏目id
     * @param $pid 父栏目id
     * @param string $fieldPri 主键
     * @param string $fieldPid 父id字段
     * @return bool
     */
    static function isChild($data, $sid, $pid, $fieldPri = 'id', $fieldPid = 'pid')
    {
        $_data = self::channelList($data, $pid, '', $fieldPri, $fieldPid);
        foreach ($_data as $c) {
            //目标栏目为源栏目的子栏目
            if ($c[$fieldPri] == $sid)
                return true;
        }
        return false;
    }

    /**
     * 检测是不否有子栏目
     * @param $data 栏目数据
     * @param $cid 要判断的栏目cid
     * @param string $fieldPid 父id表字段名
     * @return bool
     */
    static function hasChild($data, $cid, $fieldPid = 'pid')
    {
        foreach ($data as $d) {
            if ($d[$fieldPid] == $cid) return true;
        }
        return false;
    }

    /**
     * 递归实现迪卡尔乘积
     * @param $arr 操作的数组
     * @param array $tmp
     * @return array
     */
    static function descarte($arr, $tmp = array())
    {
        static $n_arr = array();
        foreach (array_shift($arr) as $v) {
            $tmp[] = $v;
            if ($arr) {
                self::descarte($arr, $tmp);
            } else {
                $n_arr[] = $tmp;
            }
            array_pop($tmp);
        }
        return $n_arr;
    }





}