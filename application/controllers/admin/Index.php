<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    //http://hostname/admin/index/show

    static public  $permission = null;

    public function __construct($params=null)
    {
        parent::__construct($params);
        // Your own constructor code

        //加载所需要的模块
        if( !self::$permission){
            $this->load->model('permission_model');
            self::$permission =  $this->permission_model;
        }

        //使用加密类
        $this->load->library('encryption');

    }

    /**
     *显示后台首页
     */
    public  function index(){
        $this->load->view('admin/index');
    }

    /**
     *显示欢迎统计页面
     */
    public  function welcome(){

        $this->load->view('admin/welcome');
    }



    /*=========================管理员管理==========================*/
    /**
     *角色管理列表
     */
    public  function admin_role(){

        $this->load->view('admin/admin_role');
    }


    /**
     *添加角色
     */
    public  function admin_role_add(){

        $this->load->view('admin/admin_role_add');
    }

    /**
     *权限管理列表
     */
    public  function permission($result= false){

        //select *,concat(path,id) as path_id from awz_auth_rule order by path_id

        //加载权限模块
        $auth_rule = self::$permission -> query_auth_rule();

        if($auth_rule){
            $option = null;
            $auth_result = array();
            foreach($auth_rule as $key => $row){
                $m  = substr_count($row['path'],",") - 1;
                $strpad = str_pad("",$m*12*4,"&nbsp;&nbsp;");
                $dbd =  $row['pid'] == 0 ? "disabled" : "";
                $ids = $this->encryption->encrypt($row['id']);
                $option .= "<option {$dbd} value='{$ids}'>{$strpad}{$row['title']}</option>";
                foreach($row as $kk => $col){
                    if($kk == 'title') {
                        $title = $row['pid'] == 0 ? "<b>{$strpad}{$col}</b>" : $strpad . $col;
                        $auth_result[$key][$kk] = $title;
                    }else if($kk == 'states'){
                        if($col == 1){
                            $auth_result[$key][$kk]  = '<span class="label label-success radius">已启用</span>';
                        }else if($col == 2){
                            $auth_result[$key][$kk]  = '<span class="label radius">已停用</span>';
                        }
                    }else{
                        $auth_result[$key][$kk]  = $col;
                    }
                }
            }
            if($result){
                return $option;
            }else{
                $permission['auth_rule'] = $auth_result;
                $this->load->view('admin/permission',$permission);
            }
        }

        /*
        $this->db->select('path, title, status,id');
        $query = $this->db->get(config_item('AUTH_RULE'));  //result_array()
        if ($query->num_rows() > 0)
        {
            $result_auth_rule = array();
            foreach ($query->result_array() as $row)
            {
                $result_auth_rule[] = $row;
            }
        }
        $data['result_list']=$result_auth_rule;
        $this->load->view('admin/permission',$data);
        */

    }



    /**
     *权限管理列表
     */
    public  function permissionlist(){

        $is_ajax = $this->input->is_ajax_request();
        if($is_ajax){
            $aColumns = array('id','url', 'title','states','description','pid');
            $sTable = config_item('AUTH_RULE');
            $output  = $this->getTable($aColumns, $sTable);
            //顺序和内容取决于页面显示内容
            foreach ($output['rResult'] as $key => $col){
                $row = array();
                foreach($col as $k => $v){
                    switch($k){
                        case 'id':
                            $row[] = '<input value="'.$v.'" name="" type="checkbox">';
                            $row[] =  $v;
                            break;
                        case 'states':
                            if($v == 1){
                                $iconfont = '<i class="Hui-iconfont">&#xe631;</i>';
                                $row[] = '<span class="label label-success radius">已启用</span>';
                            }else if($v == 2){
                                $iconfont = '<i class="Hui-iconfont">&#xe615;</i>';
                                $row[] = '<span class="label radius">已停用</span>';
                            }
                            break;
                        default:
                            $row[] = $v;
                            break;
                    }
                }
                $row[] ='<a style="text-decoration:none;" onClick="admin_start(this,'.$col['id'].')" href="javascript:;" title="启用">'.$iconfont.'</i>
                        </a>
                        <a style="text-decoration:none;" title="编辑" href="javascript:;" onclick="admin_edit(管理员编辑,admin-add.html,'.$col['id'].',800,500)" class="ml-5" >
                            <i class="Hui-iconfont">&#xe6df;</i>
                        </a>';
                $output['output']['aaData'][] = $row;
            }
            echo json_encode($output['output']);
        }else{
            $this->load->view('admin/permissionlist');
        }
        return false;
        /*
        $this->db->select('path, title, status,id');
        $query = $this->db->get(config_item('AUTH_RULE'));  //result_array()
        if ($query->num_rows() > 0)
        {
            $result_auth_rule = array();
            foreach ($query->result_array() as $row)
            {
                $result_auth_rule[] = $row;
            }
        }
        $data['result_list']=$result_auth_rule;
        $this->load->view('admin/permission',$data);
        */

    }


    /**
     *添加权限节点 / 显示页面
     */
    public  function permission_add(){

        $post = $this->input->post();
        if($post){
            self::$permission -> insert_auth_rule($post);
        }else{
            $data_option = $this->permission(true);
            $data_results['data_option'] = $data_option;
            $this->load->view('admin/permission_add',$data_results);
        }
    }

    /**
     *删除权限节点
     */
    public  function permission_del(){

       echo 'permission_del';
    }


    /**
     *批量删除权限节点
     */
    public  function permission_bth(){

        echo 'permission_del';
    }














}
