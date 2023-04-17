/*const projectCarousel = new Carousel(document.querySelector("#projectCarousel"), {
    Dots: false,
    Navigation: false,
});*/

const navCarousel = new Carousel(document.querySelector("#navCarousel"), {
    Dots: false,
    Navigation: true,

    //infinite: false,
    center: true,
    slidesPerPage: 1,
});

Fancybox.bind('[data-fancybox="gallery"]', {
    Carousel: {
        on: {
            change: (that) => {
                navCarousel.slideTo(navCarousel.findPageForSlide(that.page), {
                    friction: 0,
                });
            },
        },
    },
});