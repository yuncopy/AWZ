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

    public  function  insert_auth_rule($post)
    {
        if($post){
            $this->load->helper('security');
            var_dump($post);exit;
            $data = array(
                'path' => encode_php_tags($post['path']),
                'title' => encode_php_tags($post['title']),
                'status' => encode_php_tags($post['states']),
                'description' => encode_php_tags($post['description'])
            );
            $results = $this->db->insert(config_item('AUTH_RULE'), $data);
        }
        return  !empty($results) ? $results : false;

    }

}