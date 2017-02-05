<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller {


    public function __construct($params=null)
    {
        parent::__construct($params);
        // Your own constructor code
    }

    /**
     *显示后台首页
     */
    public  function lists(){

        $this->load->view('admin/member_lists');
    }

}