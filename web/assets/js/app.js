/**
 * (c) StaticAge Magazine
 *
 * @author Florian Weber <fweber@weber-elektronik.de>
 */

$(document).ready(function() {

    $('.vnd-gallery').justifiedGallery().on('jg.complete', function () {
        $('.vnd-gallery a').swipebox();
    });

    $('.slick').slick({
        infinite: true,
        autoplay: true,
        arrows: false
    });

    //blog post
    //$('.vnd-post img').addClass('img-responsive');
    $('.vnd-post img').replaceWith(function() {
        var src = $(this).attr('src');

        var newContent = '<a href="' + src + '" class="swipebox">';
        newContent += '<img class="img-responsive" src="' + src + '" alt="image">';
        newContent += '</a>';

        return newContent;
    });

    $('[style*="font-family"]').css('font-family', '');
    $('[style*="font-size"]').css('font-size', '');

    $('.vnd-post a.swipebox').swipebox();

    //header
    var headerImages = JSON.parse($('#header-images-source').html());
    var i = 0;

    //initial set
    $('.vnd-header').css('background-image', 'url(\'' + headerImages[i] + '\')');

    i++;

    setInterval(function() {
        $('.vnd-header').css('background-image', function() {
            if(i > headerImages.length - 1) {
                i = 0;
            }

            var url = 'url(\'' + headerImages[i] + '\')';
            i++;
            return url;
        });
    }, 10000);

    //scroll header
    var sc = $('.vnd-header').innerHeight();
    var sh = $('.vnd-header-small').innerHeight();

    $(window).scroll(function() {
        if($(window).scrollTop() > sc - sh) {
            $('.vnd-header-small').show();
        } else {
            $('.vnd-header-small').hide();
        }
    });

    $('.search-toggle').click(function(e) {
        e.preventDefault();

        if($('.search-form').css('display') == 'none') {
            $('.search-form').css('display', 'block');
        } else {
            $('.search-form').css('display', 'none');
        }
    });

    $('.vnd-search-form').submit(function(e) {
        var q = $('.vnd-search-form .vnd-search-input').val();

        if(q === '') {
            e.preventDefault();

            swal('', 'Please provide a search term!', 'error');
        }
    });

});
