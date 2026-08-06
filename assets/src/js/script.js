// You can specify which plugins you need
// import { Tooltip, Toast, Popover } from '../../../node_modules/bootstrap';

$(document).ready(function() {

    // loadScriptById("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js", "owl-library", "text/javascript");



    $('.main-menu .dropdown').hover(function() {
        $(this).children('.dropdown-menu').toggle();
    })

    var currentPath = window.location.pathname;
    $('a.nav-link[href="' + currentPath + '"]').addClass('active');
    var header = $('.main-menu');
    var headerOffset = header.offset().top;
    $(window).scroll(function() {
        var scrollPosition = $(window).scrollTop();
        if (scrollPosition > headerOffset) {
            header.addClass('fixed');
        } else {

            header.removeClass('fixed');
        }
    });


    $(".sub-menu").hide();

    // Handle hover event on the parent menu
    $(".offcanvas .offcanvas-body .menu li").hover(
        function() {
            // When hovering in, show the sub-menu
            $(this).children(".sub-menu").slideDown('fast');
        },
        function() {
            // When hovering out, hide the sub-menu
            $(this).children(".sub-menu").hide();
        }
    )



    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

    } else {
        // $(".owl-carousel").owlCarousel({
        //     margin: 10,
        //     loop: true,
        //     items: 1,
        //     autoplay: true,
        //     dots: true,
        //     nav: true,
        //     navText: ['<', '>'],
        //     autoplaySpeed: 2000,
        //     responsiveClass: true,
        //     responsive: {
        //         0: {
        //             items: 1,
        //             dots: true,
        //         },
        //         600: {
        //             items: 1,
        //             dots: true,
        //         },
        //         1000: {
        //             items: 1,

        //         }
        //     }
        // });
    }

    //SLIDER FOR TOP STORIES

    if (isMobile()) {
        console.log("is mobile");
    } else {
        console.log("is not mobile");
        loadScriptById("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4436182731786816", "adsense-ad-tag", "text/javascript");
        loadScriptById("https://securepubads.g.doubleclick.net/tag/js/gpt.js", "gpt-ad-tag", "text/javascript");
        
    }

    var maxScrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    window.addEventListener('scroll', function() { 

        var scrollVal = window.scrollY;
        var scrollPercentage = Math.round(scrollVal / maxScrollHeight * 100);
        // let articleLink = location.href; //ARTICLE OF PAGE LINK

        if (isMobile()) {
        //    if (scrollPercentage >= 1) {
                // console.log('scroll_gpt')
                loadScriptById("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4436182731786816", "adsense-ad-tag", "text/javascript");
                loadScriptById("https://securepubads.g.doubleclick.net/tag/js/gpt.js", "gpt-ad-tag", "text/javascript");
                // loadScriptById("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js", "owl-library", "text/javascript");
        //    }
        }


        if (scrollPercentage >= 5) {
            console.log('here');
            // conditionalInjection('bsrv-4468','AV65014f2a7b2714cc7508c1d8','https://tg1.bidsxplayer.com/api/adserver/spt?AV_TAGID=65014f2a7b2714cc7508c1d8&AV_PUBLISHERID=5eb7be27791eec2a0f7f2d49')
        }

        if (scrollPercentage >= 50) {
            appendDisqus();
            conditionalInjection('SC_TBlock_882015','adnow','https://st-n.ads1-adnow.com/js/a.js')
            // conditionalInjection("show-outbrain-ads", "outbrain", "https://widgets.outbrain.com/outbrain.js");
            // conditionalInjection("show-whizzco-ads", "whizzco", "https://cdn.whizzco.com/scripts/widget/widget_v3.js")

        }

        },
        false,
    );












})

document.addEventListener("DOMContentLoaded", function () {
    // Get our lazy-loaded images
    var lazyImages = [].slice.call(
      document.querySelectorAll("img.img-lazy-load")
    );
  
    if ("IntersectionObserver" in window) {
      // Create new observer object
      let lazyImageObserver = new IntersectionObserver(function (
        entries,
        observer
      ) {
        // Loop through IntersectionObserverEntry objects
        entries.forEach(function (entry) {
          // Do these if the target intersects with the root
          if (entry.isIntersecting) {
            // console.log('intersecting')
            let lazyImage = entry.target;
            lazyImage.src = lazyImage.dataset.src;
            lazyImage.classList.remove("img-lazy-load");
            lazyImageObserver.unobserve(lazyImage);
          }
        });
      });
  
      // Loop through and observe each image
      lazyImages.forEach(function (lazyImage) {
        lazyImageObserver.observe(lazyImage);
      });
    }
});


function loadScriptById(src, id, type, async = true) {
    var isIdExist = document.getElementById(id);
    if (isIdExist) {
        // console.log('element with id=' + id + ' already exist');
    } else {
        var thridPartyScript = document.createElement("script");
        thridPartyScript.type = type;
        thridPartyScript.setAttribute("async", async);
        thridPartyScript.setAttribute("src", src);
        thridPartyScript.id = id;
        document.body.appendChild(thridPartyScript);
        console.log("appended " + thridPartyScript.id + " script successfully");
    }
}

function loadheaderscripts(src, name, id) {
    var isIdExist = document.getElementById(id);
    if (isIdExist) {
        // console.log('element with id=' + id + ' already exist');
    } else {
        var script = document.createElement("script");
        script.src = src;
        script.type = "text/javascript";
        document.getElementsByTagName("head")[0].appendChild(script);
        console.log("added " + name + " script");
    }
}

function appendDisqus() {

    //fix this to load only on artilce pag
    var isArticle = document.getElementById('disqus_thread');
    if ( isArticle ) {
        var isIdExist = document.getElementById('disqus');
        if (isIdExist) {
            // console.log('disqus already exist');
        } else {
            (function() { // DON'T EDIT BELOW THIS LINE
                var d = document,
                    s = d.createElement('script');

                s.id = 'disqus';

                s.src = 'https://businessday-ng.disqus.com/embed.js';

                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        }
    }
    

}

function conditionalInjection(id, placementId, src, type = "text/javascript") {
    var isIdExist = document.getElementById(id);
    if (isIdExist) {
        loadScriptById(src, placementId, type, true)
    }
}

function isMobile() {
    if (
        /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(
            navigator.userAgent
        ) ||
        /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| ||a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
            navigator.userAgent.substr(0, 4)
        )
    ) {
        return true;
    }
    return false;
}

function appendAdnow() {
    var isIdExist = document.getElementById('adnow');
    if (isIdExist) {
        // console.log('disqus already exist');
    } else {
        (sc_adv_out = window.sc_adv_out || []).push({
            id : "882015",
            domain : "n.ads1-adnow.com",
            no_div: false
        });
    }
}

