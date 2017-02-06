<?php
class Permission_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public  function  query_auth_rule(){


        $table_name = $this->db->dbprefix(config_item('AUTH_RULE'));
        $sql = "select id , pid , url ,path , title , states , concat(path,id) as path_id from ".$table_name." order by path_id";
        $query = $this->db->query($sql);
        $list_result = array();
        if($query){
            foreach ($query->result_array() as $row)
            {
                $list_result[] = $row;
            }
        }
        return !empty($list_result) ? $list_result : false;
    }


}