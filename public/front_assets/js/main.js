

!function(t){"use strict";var e,a,i=t(window);t("#preloader").fadeOut("normall",function(){t(this).remove()}),i.on("scroll",function(){var e=i.scrollTop(),a=t(".navbar-brand img"),o=t(".navbar-brand.logodefault img");e<=50?(t("header").removeClass("scrollHeader").addClass("scrollHeader"),a.attr("src","/front_assets/img/logos/logo.png")):(t("header").removeClass("scrollHeader").addClass("scrollHeader"),a.attr("src","/front_assets/img/logos/logo.png")),o.attr("src","/front_assets/img/logos/logo.png")}),i.on("scroll",function(){500<t(this).scrollTop()?t(".scroll-to-top").fadeIn(400):t(".scroll-to-top").fadeOut(400)}),t(".scroll-to-top").on("click",function(e){e.preventDefault(),t("html, body").animate({scrollTop:0},600)}),new WOW({boxClass:"wow",animateClass:"animated",offset:0,mobile:!1,live:!0}).init(),t(".parallax,.bg-img").each(function(e){t(this).attr("data-background")&&t(this).css("background-image","url("+t(this).data("background")+")")}),t(".story-video").magnificPopup({delegate:".video",type:"iframe"}),t(".source-modal").magnificPopup({type:"inline",mainClass:"mfp-fade",removalDelay:160}),t(".tab1").click(function(){t(".second, .third").fadeOut(),t(".first").fadeIn(1e3)}),t(".tab2").click(function(){t(".first, .third").fadeOut(),t(".second").fadeIn(1e3)}),t(".tab3").click(function(){t(".second, .first").fadeOut(),t(".third").fadeIn(1e3)}),t(".vision-wrapper").on("mouseenter",function(e){var a=t(this).data("background");t(".vision-changebg").animate({opacity:.9},50,function(){t(".vision-changebg").css("background-image","url("+a+")")}),t(".vision-changebg").delay(50).animate({opacity:.9},50)}),0!==t(".copy-clipboard").length&&(new ClipboardJS(".copy-clipboard"),t(".copy-clipboard").on("click",function(){var e=t(this);e.text();e.text("Copied"),setTimeout(function(){e.text("Copy")},2e3)})),e=t(".full-screen"),a=i.height(),e.css("min-height",a),e=t("header").height(),a=t(".screen-height"),e=i.height()-e,a.css("height",e),t(document).ready(function(){t(".testimonial-carousel").owlCarousel({loop:!0,responsiveClass:!0,autoplay:!1,smartSpeed:1500,nav:!1,dots:!0,center:!1,margin:25,responsive:{0:{items:1},768:{items:1},992:{items:1}}}),t(".pricing-carousel").owlCarousel({loop:!0,responsiveClass:!0,autoplay:!1,smartSpeed:1500,nav:!1,dots:!0,center:!1,margin:20,responsive:{0:{items:1},768:{items:1},992:{items:2}}}),t(".vision-changebg").owlCarousel({loop:!0,responsiveClass:!0,center:!1,nav:!1,dots:!1,autoplay:!1,autoplayTimeout:5e3,margin:0,smartSpeed:900,responsive:{0:{items:1},576:{items:2},992:{items:4,loop:!1}}}),t(".portfolio-single").owlCarousel({loop:!0,responsiveClass:!0,autoplay:!0,smartSpeed:800,nav:!1,dots:!0,center:!1,margin:20,responsive:{0:{items:1},767:{items:2},768:{items:2},992:{items:3}}}),t(".portfolio-block").owlCarousel({loop:!0,responsiveClass:!0,center:!0,nav:!1,dots:!1,autoplay:!0,autoplayTimeout:5e3,margin:30,smartSpeed:900,responsive:{0:{items:1},768:{items:2},1200:{items:2}}}),t(".client-carousel").owlCarousel({loop:!0,responsiveClass:!0,autoplay:!1,smartSpeed:1500,nav:!1,dots:!1,center:!1,margin:0,responsive:{0:{items:1},768:{items:1},992:{items:1}}}),t(".slider-fade .owl-carousel").owlCarousel({items:1,loop:!0,dots:!1,margin:0,nav:!0,navText:["<i class='ti-angle-left'></i>","<i class='ti-angle-right'></i>"],autoplay:!0,smartSpeed:500,mouseDrag:!1,animateIn:"fadeIn",animateOut:"fadeOut",responsive:{0:{nav:!1},768:{nav:!0}}}),t(".owl-carousel").owlCarousel({items:1,loop:!0,dots:!1,margin:0,autoplay:!0,smartSpeed:500}),t(".slider-fade").on("changed.owl.carousel",function(e){e=e.item.index-2;t("h3").removeClass("animated fadeInUp"),t("h1").removeClass("animated fadeInUp"),t("p").removeClass("animated fadeInUp"),t(".butn").removeClass("animated fadeInUp"),t(".owl-item").not(".cloned").eq(e).find("h3").addClass("animated fadeInUp"),t(".owl-item").not(".cloned").eq(e).find("h1").addClass("animated fadeInUp"),t(".owl-item").not(".cloned").eq(e).find("p").addClass("animated fadeInUp"),t(".owl-item").not(".cloned").eq(e).find(".butn").addClass("animated fadeInUp")}),0!==t(".horizontaltab").length&&t(".horizontaltab").easyResponsiveTabs({type:"default",width:"auto",fit:!0,tabidentify:"hor_1",activate:function(e){var a=t(this),o=t("#nested-tabInfo");t("span",o).text(a.text()),o.show()}}),t(".countup").counterUp({delay:25,time:2e3}),t(".countdown").countdown({date:"01 Oct 2024 00:01:00",format:"on"}),t(".current-year").text((new Date).getFullYear())}),i.on("load",function(){var a=t(".portfolio-gallery-isotope").isotope({});t(".filtering").on("click","span",function(){var e=t(this).attr("data-filter");a.isotope({filter:e})}),t(".filtering").on("click","span",function(){t(this).addClass("active").siblings().removeClass("active")}),t(".portfolio-gallery,.portfolio-gallery-isotope").lightGallery(),t(".portfolio-link").on("click",e=>{e.stopPropagation()}),i.stellar()})}(jQuery);