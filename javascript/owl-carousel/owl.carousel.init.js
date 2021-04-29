$(function(){
    
    // Initialise Owl Carousel:
    
    var owl = $('#$CarouselID').owlCarousel({
        nav: $Nav,
        dots: $Dots,
        loop: $Loop,
        items: $NumberOfSlides,
        center: $Center,
        autoplay: $AutoPlay,
        autoHeight: $AutoHeight,
        animateIn: $AnimateIn,
        animateOut: $AnimateOut,
        navContainerClass: $ContainerClass,
        navText: [
            '<i class="fa $IconPrev"></i>',
            '<i class="fa $IconNext"></i>'
        ]
    });
    
    // Update Height on Resize: (courtesy pe6o, see: https://github.com/OwlCarousel2/OwlCarousel2/issues/197)
    
    owl.on('resized.owl.carousel', function() {
        var $this = $(this);
        $this.find('.owl-height').css('height', $this.find('.owl-item.active').height());
    });
    
});
