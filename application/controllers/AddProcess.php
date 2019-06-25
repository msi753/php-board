<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//클래스명은 대문자로 시작해야 한다
class AddProcess extends CI_Controller {
    protected $POST;
    public function __construct()
    {
        parent::__construct();
            
            // 오토로드 후 모델 호출

            $this->load->model('MaddBoard');
            $this->POST['NO'] = $this->input->post("no"); 
            $this->POST['TITLE'] = $this->input->post("title"); 
            $this->POST['CONTENTS'] = $this->input->post("contents"); 
            $this->POST['PW'] = $this->input->post("pw");
            $this->POST['WRITER'] = $this->input->post("writer");
            $this->POST['IP'] = $this->input->ip_address();
    }

    public function index() {
        if($this->POST['NO']) {
            $this->dbUpdate();
        } else {
            $this->dbInsert();
        }
    }

    //db저장
    public function dbInsert() {
        //print_r($this->POST);
        $this->MaddBoard->dbInsert($this->POST);
    }

    public function dbUpdate() {
    
        $this->MaddBoard->dbUpdate($this->POST);
    }



}