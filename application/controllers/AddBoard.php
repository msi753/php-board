<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//클래스명은 대문자로 시작해야 한다
class AddBoard extends CI_Controller {

	public function index()
	{
		//이거 안씀 echo 'Hello World!<br>';

		//config>autoload.php 파일에서 database와 session을 자동으로 로드한다

		//뷰에 값 보내기 가능
		$data['no'] = 112;
		
		//view폴더에 있는 add_board.php를 로드한다
		$this->load->view('add_board', $data);

	}



	
}
