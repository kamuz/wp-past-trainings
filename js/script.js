jQuery(document).ready(function($) {
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        navText: ['<span></span>','<span></span>'],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        },
        animateOut: 'fadeOut'
    });
    $('.vertical').bxSlider({
        mode: 'vertical',
        minSlides: 4,
        pager: false,
        prevText: "",
        nextText: "",
        slideMargin: 15
    });
    
    $('.slider-wrap').hide();
    $('.slider-wrap:first-child').show();
    $('.vertical div a.event-item').click(function(e){
        e.preventDefault();
        var eventId = $(this).data('event-id');
        $('.slider-wrap').hide();
        $('.slider-' + eventId).show();
    });
});