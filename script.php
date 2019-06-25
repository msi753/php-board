<script type="text/javascript">
        
    $(document).ready(function() {
        $(window).scroll(function() {
            if($(this).scrollTop()>200) {
                $('.plus_btn').fadeIn();
                $('.notice_box').fadeIn();
                setTimeout(function(){
                    $('.notice_box').fadeOut();
                    $(window).unbind('scroll');
                }, 3000);
            }else {
                $( '.plus_btn' ).fadeOut();
                $('.notice_box').fadeOut();
              }            
        });
    });          
        
/*        $(document).ready(function() {
            $( window ).scroll( function() {
              if ( $( this ).scrollTop() > 200 ) {
                $( '.plus_btn' ).fadeIn();
                $( '.notice_box' ).fadeIn();
              } else {
                $( '.plus_btn' ).fadeOut();
				$( '.notice_box' ).fadeOut();
              }
            } );
        } ); */
    </script>
<!--    <script type="text/javascript">
    // html dom 이 다 로딩된 후 실행된다.
    $(document).ready(function(){
        // menu 클래스 바로 하위에 있는 a 태그를 클릭했을때
        $(".showvd>a").click(function(){
            var submenu = $(this).next("ul");
 
            // submenu 가 화면상에 보일때는 위로 보드랍게 접고 아니면 아래로 보드랍게 펼치기
            if( submenu.is(":visible") ){
                submenu.slideUp();
            }else{
                submenu.slideDown(200);
            }
        });
    });
    <script>        
        function hideDiv (){ 
          document.getElementById("report_pop").style.display="none"; 
        } 
        self.setTimeout("hideDiv()",2000); // 초 지정    
    </script> -->

    <script>
    // html dom 이 다 로딩된 후 실행된다.
    $(document).ready(function(){
        // memu 클래스 바로 하위에 있는 a 태그를 클릭했을때
        $(".showvd>a").click(function(){
            // 현재 클릭한 태그가 a 이기 때문에
            // a 옆의 태그중 ul 태그에 hide 클래스 태그를 넣던지 빼던지 한다.
            $(this).next("ul").toggleClass("hide");
        });
    });
    </script>