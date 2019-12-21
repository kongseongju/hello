// 토글 메뉴
function toggle(){
    $('.navBtn, .navCnt').click(function(){
        $('.navBtn').toggleClass('on');
        $('.navCnt').fadeToggle();
        $('.navCnt').removeClass('navHide');
    });
}

// 함수 실행
$(document).on('ready', function(){
    toggle();
});









