<?php
class Pages {
   
private $page;
public $page_size;
public	$page_num;
private $total_count;
public	$img_next	=	'다음';
public	$img_pre	=	'이전';
private $page_link;
private $next_page;

#쿼리 LIMIT 시작값 리턴
public function start_num() {
    $tmp_num	=	($this->page-1) * $this->page_size;
    return $tmp_num;
}

#총출력될 하단 페이지 수
private function page_count() {
    $page_count	=	(int)(($this->total_count-1)/$this->page_size)+1;
    return $page_count;
}


#총출력될 하단 페이지 처음 숫자[11,21,31 등등]
private function pre_page() {
    $pre_page	=	(int)(($this->page-1)/$this->page_num)*$this->page_num+1;
    return $pre_page;
}


#링크만들어 주는 함수[val=page값]
private function link_str($val1,$var2) {
    $tmp_link	=	$this->page_link.$val1;

    if($var2=="p_pre_page") {
        $tmp_var2	=	$this->img_pre;
    } elseif($var2=="p_next_page") {
        $tmp_var2	=	$this->img_next;
    } elseif($var2=="p_center_page") {
        $tmp_var2	=	$val1;
    }

    $link_str	=	" <li class='page-item'><a class='page-link' href='".$tmp_link."'>".$tmp_var2."</a></li>\n";
    return $link_str;
}

#이전페이지
private function p_pre_page() {
    if($this->page_count() > ($this->pre_page()+($this->page_num -1))) {
        $this->next_page	=	$this->pre_page() + $this->page_num - 1;
    } else {
        $this->next_page	=	$this->page_count();
    }

    if((int)(($this->page-1)/$this->page_num)>0) {
        $tmp_page	=	$this->pre_page() - 1;
        $p_pre_page	=	$this->link_str($tmp_page,"p_pre_page");
    } else {
        $p_pre_page	=	"<li class='page-item'><a class='page-link'>".$this->img_pre."</a></li>\n";
    }
    return $p_pre_page;
}


#페이지 숫자부분[가운데부분]
private function p_cen_page() {
    $p_cen_page	=	"";
    for($i=$this->pre_page();$i<=$this->next_page;$i++) {
        if($i==$this->page) {
            $p_cen_page	.=	"<li class='page-item active'><a class='page-link'>$i</a></li>\n";
        } else {
            $p_cen_page	.=	$this->link_str($i,"p_center_page");
        }
    }
    return $p_cen_page;
}


#다음페이지
private function p_next_page() {
    if($this->next_page<$this->page_count()) {
        $tmp_page	=	$this->next_page + 1;
        $p_next_page	=	$this->link_str($tmp_page,"p_next_page");
    } else {
        $p_next_page	=	"<li class='page-item'><a class='page-link'>".$this->img_next."</a></li>\n";
    }
    return $p_next_page;
}

#문자열 결합
public function Pages($page,$total,$page_size,$page_num,$page_link) {
    #$this->start_num();
    $this->total_count	=	$total;
    if(!is_numeric($page)) {
        $this->page	=	1;
    } else {
        $this->page	=	$page;
    }
    $this->page_size	=	$page_size;
    $this->page_num		=	$page_num;
    $this->page_link	=	$page_link;
    return $this->Print_Page();
}

#문자열 출력
public function Print_Page() {
    $show_paging	=	$this->p_pre_page().$this->p_cen_page().$this->p_next_page()."\n";
    return $show_paging;
}
}
?>
