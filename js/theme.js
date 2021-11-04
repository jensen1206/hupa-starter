/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.  Header
2.  Theme
--------------------------------------------------------------*/


/*--------------------------------------------------------------
1. Header
--------------------------------------------------------------*/


jQuery(document).ready(function ($) {

    // Data attribute to hide offcanvas and enable body scroll on resize through the breakpoints
    $(window).on('resize', function () {
        $('[data-bs-hideresize="true"]').offcanvas('hide');
    });

    // Close offcanvas on click a, keep .dropdown-menu open
    $('.offcanvas a:not(.dropdown-toggle):not(a.remove_from_cart_button), a.dropdown-item').on('click', function () {
       // $('.offcanvas').offcanvas('hide');
    });

    // Dropdown menu animation
    // Add slideDown animation to Bootstrap dropdown when expanding.
    $('.dropdown').on('show.bs.dropdown', function () {
       //$(this).find('.dropdown-menu').first().stop(true, true).slideDown();
        $(this).find('.dropdown-menu').first().addClass('dropdown-menu-slide');
    });
    // Add slideUp animation to Bootstrap dropdown when collapsing.
    $('.dropdown').on('hide.bs.dropdown', function () {
        // $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });

    // Mobile search button hide if empty
    if ($('.searchform').length != 1) {
        $('.top-nav-search-md, .top-nav-search-lg').addClass('hide');
    }
    if ($('.searchform').length != 0) {
        $('.top-nav-search-md, .top-nav-search-lg').removeClass('hide');
    }

    // Set parent nav-link active if blog post or shop item is open
    $('.current-post-ancestor .nav-link').addClass('active');
    $('.current_page_parent .nav-link').addClass('active');

}); // jQuery End



/*--------------------------------------------------------------
2. Theme
--------------------------------------------------------------*/

jQuery(document).ready(function ($) {

    // TODO JOB WARNING Ankerlink DropDown Click Function
    let path = location.hash;
    const regex = /#.*?/gm;
    let m;
    if ((m = regex.exec(path)) !== null) {
        m.forEach((match, groupIndex) => {
            if(match){
                let dropDown = $('.nav-link.active.dropdown-toggle').next();
                $('li', dropDown).removeClass('current-menu-item');
                $('li a', dropDown).removeClass('active');
            }
        });
    }
    // TODO JOB WARNING Ankerlink DropDown Click Function

    // Smooth Scroll
    $(function () {
        $('a[href*="#"]:not([href="#"]):not(a.comment-reply-link):not([href="#tab-reviews"]):not([href="#tab-additional_information"]):not([href="#tab-description"]):not([href="#reviews"]):not([href="#carouselExampleIndicators"]):not([data-smoothscroll="false"])').click(function (e) {

            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {

                    // TODO JOB WARNING Ankerlink DropDown Click Function
                    let x = $(this).parents('.menu-item-has-children.dropdown').children();
                    x.toggleClass('show');
                    let dropUl = $(this).parents('ul.dropdown-menu');
                    $('li', dropUl).removeClass('current-menu-item');
                    $('li a', dropUl).removeClass('active');
                    $(this).addClass('active');
                    // TODO JOB WARNING Ankerlink DropDown Click Function

                    $('html, body').animate({
                        // Change your offset according to your navbar height
                        scrollTop: target.offset().top - 55
                    }, 1000);
                    return !1
                }
            }
        })
    });

    // Scroll to ID from external url
    if (window.location.hash) scroll(0, 0);
    setTimeout(function () {
        scroll(0, 0)
    }, 1);
    $(function () {
        $('.scroll').on('click', function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(this).attr('href').offset().top - 55
            }, 1000, 'swing')
        });
        if (window.location.hash) {
            $('html, body').animate({
                scrollTop: $(window.location.hash).offset().top - 55
            }, 1000, 'swing')
        }
    });

    // Scroll to top Button
    $(window).scroll(function () {
        let scroll = $(window).scrollTop();
        if (scroll >= 400) {
            $(".top-button").addClass("visible");
        } else {
            $(".top-button").removeClass("visible");
        }
    });


    // div height, add class to your content
    $(".height-50").css("height", 0.5 * $(window).height());
    $(".height-75").css("height", 0.75 * $(window).height());
    $(".height-85").css("height", 0.85 * $(window).height());
    $(".height-100").css("height", 1.0 * $(window).height());

    // Forms
    $('select, #billing_state').addClass('form-select');
    // Alert links
    $('.alert a').addClass('alert-link');

    let canvasHeader = $('#logoPlaceholder');
    let image =  $("img.logo.md").attr('src');
    canvasHeader.html(`<a href="${get_hupa_option.site_url}"><img alt="" src="${image}" width="200"></a>`);
}); // jQuery End