<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

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

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
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
    public  function permission(){

        $this->load->view('admin/permission');
    }


    /**
     *添加权限节点 / 显示页面
     */
    public  function permission_add(){
       // $config = config_item('time_reference');
       // var_dump($config);
        $post = $this->input->post();
        if($post){
            $this->load->helper('security');
            $data = array(
                'path' => encode_php_tags($post['path']),
                'title' => encode_php_tags($post['title']),
                'status' => encode_php_tags($post['status']),
                'desc' => encode_php_tags($post['desc'])
            );
            $results = $this->db->insert(config_item('AUTH_RULE'), $data);
            var_dump($results);

        }else{
            $this->load->view('admin/permission_add');
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
