$(document).ready(function() {
    
    
    //hide
    $('#hideAd').click(function() {
        $('.ad-content').hide(500); 
    });
    
    // show
    $('#showAd').click(function() {
        $('.ad-content').show(500); 
    });
    
    // fadeOut
    $('#fadeOutAd').click(function() {
        $('.ad-container').fadeOut(1000);
    });
    
    // fadeIn
    $('#fadeInAd').click(function() {
        $('.ad-container').fadeIn(1000);
    });
    
    // fadeTo
    $('#fadeToAd').click(function() {
        $('.ad-container').fadeTo(500, 0.5); 
    });
    
    //slideUp
    $('#slideUpAd').click(function() {
        $('.ad-container').slideUp(800);
    });
    
    //slideDown
    $('#slideDownAd').click(function() {
        $('.ad-container').slideDown(800);
    });
    
    // animate
    $('#animateAd').click(function() {
        $('.ad-container').animate({
            opacity: 0.4,
            marginLeft: '50px',
            fontSize: '20px'
        }, 1000).animate({
            opacity: 1,
            marginLeft: '0px',
            fontSize: '16px'
        }, 1000);
    });
    
    //stop
    $('#stopAd').click(function() {
        $('.ad-container').stop();
    });
    
    //крестик
    $('.ad-close-btn').click(function() {
        $('.ad-container').slideUp(500);
    });
    
    //анимация карточек 
    $('.event-card').hover(
        function() {
            // mouse enter
            $(this).find('img').stop().animate({
                opacity: 0.8
            }, 200);
        },
        function() {
            // mouse leave
            $(this).find('img').stop().animate({
                opacity: 1
            }, 200);
        }
    );
    
    // Анимация для кнопок управления
    $('.ad-controls button').click(function() {
        $(this).animate({
            backgroundColor: '#3498db',
            color: '#fff'
        }, 200).animate({
            backgroundColor: '#fff',
            color: '#2c3e50'
        }, 200);
    });
    
});