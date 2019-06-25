<?php
class MaddBoard extends CI_Model {

    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }


    public function dbInsert($data) {
        $this->db->set('TITLE', $data['TITLE']);
        $this->db->set('CONTENTS', $data['CONTENTS']);
        $this->db->set('PW', $data['PW']);
        $this->db->set('WRITER', $data['WRITER']);
        $this->db->set('IP', $data['IP']);
        $this->db->set('DATE', "now()", false); //false라서 문자열이 아니고 함수
        $this->db->insert('board');
    }

    public function dbUpdate($data) {
        $this->db->set('TITLE', $data['TITLE']);
        $this->db->set('CONTENTS', $data['CONTENTS']);
        $this->db->set('PW', $data['PW']);
        $this->db->set('WRITER', $data['WRITER']);
        $this->db->set('IP', $data['IP']);
        $this->db->where('NO',$data['NO']);
        $this->db->update('board');
    }
}
?>