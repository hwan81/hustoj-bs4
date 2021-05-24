<?php include("template/$OJ_TEMPLATE/js.php");?>

<div class="marq_msg">
    <a type="button" class="btn  btn-danger" data-toggle="popover" data-content="And here's some amazing content. It's very engaging. Right?"><i class="fas fa-exclamation-circle"></i></a>
</div>

</div>  <!--oj-header container-->
<div id="main-footer" class="p-5 bg-secondary text-light">
    <div class="container text-center">
        <?=$MSG_HELP_HUSTOJ?><br>
        GPLv2 licensed by <a class="text-light" href='https://github.com/zhblue/hustoj' >HUSTOJ</a> <script> document.write((new Date()).getFullYear())</script><br>
        <button type="button" class="btn btn-sm border-0 btn-secondary text-light  btn-outline-dark" data-toggle="modal" data-target="#exampleModal">
            Skin  & library info
        </button>
    </div>

</div>

<!--modal -->
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Skin info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul>

                    <li>이 스킨은 hustoj를 원본소스를 수정하여 제작되었습니다.</li>
                    <li>수정 : Leejunghwan(bigdipper81@nate.com)</li>
                    <li class="text-secondary">주원아 자연은 원래 느린거란다.</li>
                    <li>
                        변경된 기능
                        <ul>
                            <li>fontawsome사용</li>
                            <li>상단 헤더부분을 oj-header.php로 변경 통합</li>
                            <li>충격적이었던 problemset.php의 검색기능 대폭수정(코어변경 필요)</li>
                            <li>일부 라이브러리 CDN으로 대체</li>
                            <li>차트 디자인 변경</li>
                            <li>자주묻는 질문-컴파일러 정보를 서버에서 받아 출력합니다.</li>
                            <li>관리자-db_config.php을 웹에서 수정할 수 있게 했습니다.</li>
                            <li>관리자-lang.ko.php을 웹에서 수정할 수 있게 했습니다.</li>
                            <li>관리자페이지를 웹표준에 맞춰 제작하려 했습니다.</li>
                            <li>에디터를 변경하였습니다. - 이미지 복붙 가능(필드타입 변경필요)</li>
                            <li>문제 등록화면에서 scrollspy를 추가했습니다.</li>
                            <li>대회등록 UI를 개선했습니다.</li>
                            <li>시스템 정보를 출력하도록 하였습니다.</li>
                            <li>한 줄 공지사항(msg.txt)을 개선했습니다. html 사용이 가능합니다.</li>
                            <li>모바일 반응성을 개선했습니다.</li>
                            <li>랭킹순위를 표시합니다.</li>
                            <li>문제보기 페이지 디자인 수정</li>
                            <li>공지사항 페이지 제작(코어파일 필요)</li>
                        </ul>
                    </li>
                    <li>
                        사용된 라이브러리
                        <ul>
                            <li>chart.js</li>
                            <li>jQuery 3.6</li>
                            <li>fontawsome</li>
                            <li>bootstrap 4.6</li>
                            <li>tinyMCE</li>                            
                        </ul>
                    </li>
                    <li>
                        도움을 주신 분들
                        <ul>
                            <li>이주원(기차, 버스, 비행기, 고기를 좋아함)</li>
                            <li>newnnewer (주종족 저그)</li>
                            <li>hebelle (멋쟁이 레이서)</li>
                        </ul>
                    </li>

                </ul>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<!--go to top btn-->
<div class="top-btn text-center">
    <i class="far fa-3x fa-caret-square-up"></i>
</div>

<script>
    $(window).scroll(function (){
        if($(window).scrollTop() >= 100){
            $(".top-btn").fadeIn();
        }else{
            $(".top-btn").fadeOut();
        }
    });
    $(document).ready(function (){
       $(".top-btn").on("click",function (){
           location.href="#"
       });
       fixBottom();
        <?php
            if($view_marquee_msg){
                ?>
        $("#news_area").show();
        <?php
            }
        ?>
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
    });

    $(window).resize(function(){
        fixBottom();
    });
    function fixBottom(){
        var h = $(window).height() - $(".content-main").offset().top -  $("#main-footer").height() - parseInt($("#main-footer").css("padding")) * 2 - parseInt($("#main-footer").css("margin")) * 2 - parseInt($(".content-main").css("margin-bottom"))  ;
        $(".content-main").css("min-height", h+"px");
    }
</script>

</body>
</html>
