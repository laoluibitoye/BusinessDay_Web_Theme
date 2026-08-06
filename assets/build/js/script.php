var REFRESH_KEY = "refresh";
var REFRESH_VALUE = "true";
var SECONDS_TO_WAIT_AFTER_VIEWABILITY = 60;

function loadScriptById(src, id, type, async = true) {
  var el;
  if (!document.getElementById(id)) {
    el = document.createElement("script");
    el.type = type;
    el.setAttribute("async", async);
    el.setAttribute("src", src);
    el.id = id;
    document.body.appendChild(el);
    console.log("appended " + el.id + " script successfully");
  }
}

function loadheaderscripts(src, name, id) {
  if (!document.getElementById(id)) {
    id = document.createElement("script");
    id.src = src;
    id.type = "text/javascript";
    document.getElementsByTagName("head")[0].appendChild(id);
    console.log("added " + name + " script");
  }
}

function appendDisqus() {
  var d, s;
  if (document.getElementById("disqus_thread") && !document.getElementById("disqus")) {
    d = document;
    s = d.createElement("script");
    s.id = "disqus";
    s.src = "https://businessday-ng.disqus.com/embed.js";
    s.setAttribute("data-timestamp", +new Date());
    (d.head || d.body).appendChild(s);
  }
}

function conditionalInjection(elemId, id, src, type = "text/javascript") {
  if (document.getElementById(elemId)) {
    loadScriptById(src, id, type, true);
  }
}

function isMobile() {
  return !!(
    /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(
      navigator.userAgent
    ) &&
    !/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| ||a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
      navigator.userAgent.substr(0, 4)
    )
  );
}

/** NOTE: Removed appendAdnow() entirely **/

window.onload = function () {
  console.log("window loaded");

  var ids = [];
  for (lazyGamTag of [].slice.call(document.querySelectorAll("div.bd-admanager"))) {
    ids.push(lazyGamTag.id);
  }

  if (document.getElementById("no-ads")) {
    console.log("no ads");
  } else {
    console.log("show ads");
    window.googletag = window.googletag || { cmd: [] };

    (function waitForGPT() {
      if (window.googletag && googletag.apiReady) {
        console.log("GPT INITIALIZED......");

        googletag.cmd.push(function () {
          document.body.clientWidth;

          // Out-of-page: Top anchor
          var v = googletag.defineOutOfPageSlot(
            "/21781351181/db_anchor",
            googletag.enums.OutOfPageFormat.TOP_ANCHOR
          );
          if (v) {
            v.setTargeting(REFRESH_KEY, REFRESH_VALUE).addService(googletag.pubads());
            console.log("Anchor ad is initialized. Scroll page to activate.");
          }

          // (Removed GPT interstitial block here)

          // Define slots conditionally per device
          var a, o, n, d, s, l, r, g, c, h, p, b, u, m, f;

          if (isMobile()) {
            if (ids.includes("div-gpt-ad-1690469983344-0")) {
              a = googletag
                .defineSlot("/21781351181/bd_mobile_1", [[300, 250], [300, 50]], "div-gpt-ad-1690469983344-0")
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1690475714652-0")) {
              o = googletag
                .defineSlot("/21781351181/bd_mobile_2", [[300, 100], [300, 250]], "div-gpt-ad-1690475714652-0")
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693019413544-0")) {
              n = googletag
                .defineSlot(
                  "/21781351181/bd_mobile_3",
                  [[300, 50], [336, 280], [300, 100], [300, 250], [320, 100]],
                  "div-gpt-ad-1693019413544-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693019455783-0")) {
              d = googletag
                .defineSlot(
                  "/21781351181/bd_mobile_4",
                  [[300, 50], [336, 280], [300, 100], [300, 250], [320, 100]],
                  "div-gpt-ad-1693019455783-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693019649387-0")) {
              s = googletag
                .defineSlot(
                  "/21781351181/bd_mobile_tenancy_1",
                  [[320, 50], [320, 100], [300, 100], [300, 250], [336, 280], [300, 50]],
                  "div-gpt-ad-1693019649387-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693019696301-0")) {
              l = googletag
                .defineSlot(
                  "/21781351181/bd_mobile_tenancy_2",
                  [[300, 100], [300, 250], [320, 50], [336, 280], [300, 50], [320, 100]],
                  "div-gpt-ad-1693019696301-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
          } else {
            if (ids.includes("div-gpt-ad-1690469943006-0")) {
              r = googletag
                .defineSlot(
                  "/21781351181/bd_desktop_1",
                  [[300, 250], [728, 90], "fluid"],
                  "div-gpt-ad-1690469943006-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1690475556536-0")) {
              g = googletag
                .defineSlot(
                  "/21781351181/bd_desktop_2",
                  [[300, 250], "fluid", [728, 90]],
                  "div-gpt-ad-1690475556536-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693018087997-0")) {
              c = googletag
                .defineSlot(
                  "/21781351181/bd_desktop_3",
                  [[728, 90], "fluid", [300, 50], [300, 100]],
                  "div-gpt-ad-1693018087997-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693018488143-0")) {
              h = googletag
                .defineSlot(
                  "/21781351181/bd_desktop_4",
                  ["fluid", [300, 100], [300, 250], [728, 90]],
                  "div-gpt-ad-1693018488143-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693018770369-0")) {
              p = googletag
                .defineSlot(
                  "/21781351181/bd_desktop_homepage_tenancy_1",
                  [[300, 250], [728, 90], [300, 100], [970, 250], [300, 50], "fluid"],
                  "div-gpt-ad-1693018770369-0"
                )
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693018831507-0")) {
              b = googletag
                .defineSlot(
                  "/21781351181/bd_desktop_homepage_tenancy_2",
                  [
                    "fluid",
                    [970, 250],
                    [728, 90],
                    [300, 250],
                    [970, 90],
                    [300, 50],
                    [300, 100],
                    [970, 66]
                  ],
                  "div-gpt-ad-1693018831507-0"
                )
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1690475828217-0")) {
              m = googletag
                .defineSlot("/21781351181/bd_sidebar_1", [[300, 600], [300, 250]], "div-gpt-ad-1690475828217-0")
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693019230878-0")) {
              bd_sidebar_2 = googletag
                .defineSlot("/21781351181/bd_sidebar_2", [[300, 250], [300, 600]], "div-gpt-ad-1693019230878-0")
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
            if (ids.includes("div-gpt-ad-1693020204908-0")) {
              u = googletag
                .defineSlot("/21781351181/bd_homepage_desktop_hero", [1900, 200], "div-gpt-ad-1693020204908-0")
                .setTargeting(REFRESH_KEY, REFRESH_VALUE)
                .addService(googletag.pubads());
            }
          }

          if (ids.includes("div-gpt-ad-1698135246783-0")) {
            f = googletag
              .defineSlot("/21781351181/bd_inarticle_video", [1, 1], "div-gpt-ad-1698135246783-0")
              .addService(googletag.pubads());
          }

          // Lazy load and refresh-on-viewability
          googletag.pubads().enableLazyLoad({
            fetchMarginPercent: 300,
            renderMarginPercent: 100,
            mobileScaling: 2
          });

          googletag.pubads().addEventListener("impressionViewable", function (event) {
            var slot = event.slot;
            if (slot.getTargeting(REFRESH_KEY).indexOf(REFRESH_VALUE) > -1) {
              setTimeout(function () {
                googletag.pubads().refresh([slot]);
              }, 1000 * SECONDS_TO_WAIT_AFTER_VIEWABILITY);
            }
          });

          googletag.pubads().disableInitialLoad();
          googletag.pubads().enableSingleRequest();
          googletag.enableServices();

          // Only refresh the anchor (v); interstitial (i) was removed
          googletag.pubads().refresh([v]);
        });
      } else {
        console.log("AWAITING GPT INITIALIZATION......");
        setTimeout(waitForGPT, 500);
      }
    })();

    // IntersectionObserver for GPT display
    var a, o, n, d, s, l, r, g, c, h, p, b, u, m, i, v, f;
    var targets = [].slice.call(document.querySelectorAll("div.bd-admanager"));
    if ("IntersectionObserver" in window) {
      let io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            let el = entry.target;
            switch (el.id) {
              case "div-gpt-ad-1690469943006-0":
                lazySlot = r;
                break;
              case "div-gpt-ad-1690475556536-0":
                lazySlot = g;
                break;
              case "div-gpt-ad-1693018087997-0":
                lazySlot = c;
                break;
              case "div-gpt-ad-1693018488143-0":
                lazySlot = h;
                break;
              case "div-gpt-ad-1693018770369-0":
                lazySlot = p;
                break;
              case "div-gpt-ad-1693018831507-0":
                lazySlot = b;
                break;
              case "div-gpt-ad-1693020204908-0":
                lazySlot = u;
                break;
              case "div-gpt-ad-1690475828217-0":
                lazySlot = m;
                break;
              case "div-gpt-ad-1693019230878-0":
                lazySlot = bd_sidebar_2;
                break;
              case "div-gpt-ad-1690469983344-0":
                lazySlot = a;
                break;
              case "div-gpt-ad-1690475714652-0":
                lazySlot = o;
                break;
              case "div-gpt-ad-1693019413544-0":
                lazySlot = n;
                break;
              case "div-gpt-ad-1693019455783-0":
                lazySlot = d;
                break;
              case "div-gpt-ad-1693019649387-0":
                lazySlot = s;
                break;
              case "div-gpt-ad-1693019696301-0":
                lazySlot = l;
                break;
              case "div-gpt-ad-1698135246783-0":
                lazySlot = f;
                break;
              default:
                lazySlot = "";
            }

            if (lazySlot !== "") {
              googletag.pubads().refresh([lazySlot]);
            }
            googletag.cmd.push(function () {
              googletag.display(el.id);
            });

            console.log("intersecting gpt " + el.id);
            el.classList.remove("bd-admanager");
            io.unobserve(el);
          }
        });
      });

      targets.forEach(function (el) {
        io.observe(el);
      });
    }
  }
};

/* Lightbox plugin */
(function (root, factory) {
  if (typeof define === "function" && define.amd) {
    define(["jquery"], factory);
  } else if (typeof exports === "object") {
    module.exports = factory(require("jquery"));
  } else {
    root.lightbox = factory(root.jQuery);
  }
})(this, function ($) {
  function Lightbox(options) {
    this.album = [];
    this.currentImageIndex = void 0;
    this.init();
    this.options = $.extend({}, this.constructor.defaults);
    this.option(options);
  }

  Lightbox.defaults = {
    albumLabel: "Image %1 of %2",
    alwaysShowNavOnTouchDevices: false,
    fadeDuration: 600,
    fitImagesInViewport: true,
    imageFadeDuration: 600,
    positionFromTop: 50,
    resizeDuration: 700,
    showImageNumberLabel: true,
    wrapAround: false,
    disableScrolling: false,
    sanitizeTitle: false
  };

  Lightbox.prototype.option = function (options) {
    $.extend(this.options, options);
  };

  Lightbox.prototype.imageCountLabel = function (current, total) {
    return this.options.albumLabel.replace(/%1/g, current).replace(/%2/g, total);
  };

  Lightbox.prototype.init = function () {
    var self = this;
    $(document).ready(function () {
      self.enable();
      self.build();
    });
  };

  Lightbox.prototype.enable = function () {
    var self = this;
    $("body").on(
      "click",
      "a[rel^=lightbox], area[rel^=lightbox], a[data-lightbox], area[data-lightbox]",
      function (event) {
        self.start($(event.currentTarget));
        return false;
      }
    );
  };

  Lightbox.prototype.build = function () {
    var self;
    if ($("#lightbox").length > 0) return;

    self = this;
    $(
      '<div id="lightboxOverlay" tabindex="-1" class="lightboxOverlay"></div>' +
        '<div id="lightbox" tabindex="-1" class="lightbox">' +
        '<div class="lb-outerContainer">' +
        '<div class="lb-container">' +
        '<img class="lb-image" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" alt=""/>' +
        '<div class="lb-nav">' +
        '<a class="lb-prev" role="button" tabindex="0" aria-label="Previous image" href="" ></a>' +
        '<a class="lb-next" role="button" tabindex="0" aria-label="Next image" href="" ></a>' +
        "</div>" +
        '<div class="lb-loader"><a class="lb-cancel" role="button" tabindex="0"></a></div>' +
        "</div></div>" +
        '<div class="lb-dataContainer"><div class="lb-data">' +
        '<div class="lb-details"><span class="lb-caption"></span><span class="lb-number"></span></div>' +
        '<div class="lb-closeContainer"><a class="lb-close" role="button" tabindex="0"></a></div>' +
        "</div></div></div>"
    ).appendTo($("body"));

    this.$lightbox = $("#lightbox");
    this.$overlay = $("#lightboxOverlay");
    this.$outerContainer = this.$lightbox.find(".lb-outerContainer");
    this.$container = this.$lightbox.find(".lb-container");
    this.$image = this.$lightbox.find(".lb-image");
    this.$nav = this.$lightbox.find(".lb-nav");

    this.containerPadding = {
      top: parseInt(this.$container.css("padding-top"), 10),
      right: parseInt(this.$container.css("padding-right"), 10),
      bottom: parseInt(this.$container.css("padding-bottom"), 10),
      left: parseInt(this.$container.css("padding-left"), 10)
    };

    this.imageBorderWidth = {
      top: parseInt(this.$image.css("border-top-width"), 10),
      right: parseInt(this.$image.css("border-right-width"), 10),
      bottom: parseInt(this.$image.css("border-bottom-width"), 10),
      left: parseInt(this.$image.css("border-left-width"), 10)
    };

    this.$overlay.hide().on("click", function () {
      self.end();
      return false;
    });

    this.$lightbox.hide().on("click", function (event) {
      if ($(event.target).attr("id") === "lightbox") self.end();
    });

    this.$outerContainer.on("click", function (event) {
      if ($(event.target).attr("id") === "lightbox") self.end();
      return false;
    });

    this.$lightbox.find(".lb-prev").on("click", function () {
      if (self.currentImageIndex === 0) {
        self.changeImage(self.album.length - 1);
      } else {
        self.changeImage(self.currentImageIndex - 1);
      }
      return false;
    });

    this.$lightbox.find(".lb-next").on("click", function () {
      if (self.currentImageIndex === self.album.length - 1) {
        self.changeImage(0);
      } else {
        self.changeImage(self.currentImageIndex + 1);
      }
      return false;
    });

    this.$nav.on("mousedown", function (event) {
      if (event.which === 3) {
        self.$nav.css("pointer-events", "none");
        self.$lightbox.one("contextmenu", function () {
          setTimeout(function () {
            this.$nav.css("pointer-events", "auto");
          }.bind(self), 0);
        });
      }
    });

    this.$lightbox.find(".lb-loader, .lb-close").on("click keyup", function (event) {
      if (event.type === "click" || (event.type === "keyup" && (event.which === 13 || event.which === 32))) {
        self.end();
        return false;
      }
    });
  };

  Lightbox.prototype.start = function ($link) {
    var self = this;
    var $window = $(window);

    function addToAlbum($elem) {
      self.album.push({
        alt: $elem.attr("data-alt"),
        link: $elem.attr("href"),
        title: $elem.attr("data-title") || $elem.attr("title")
      });
    }

    $window.on("resize", $.proxy(this.sizeOverlay, this));
    this.sizeOverlay();

    this.album = [];
    var index = 0;
    var dataLightbox = $link.attr("data-lightbox");

    if (dataLightbox) {
      var $group = $($link.prop("tagName") + '[data-lightbox="' + dataLightbox + '"]');
      for (var i = 0; i < $group.length; i++) {
        addToAlbum($($group[i]));
        if ($group[i] === $link[0]) index = i;
      }
    } else if ($link.attr("rel") === "lightbox") {
      addToAlbum($link);
    } else {
      var $relGroup = $($link.prop("tagName") + '[rel="' + $link.attr("rel") + '"]');
      for (var j = 0; j < $relGroup.length; j++) {
        addToAlbum($($relGroup[j]));
        if ($relGroup[j] === $link[0]) index = j;
      }
    }

    var top = $window.scrollTop() + this.options.positionFromTop;
    var left = $window.scrollLeft();

    this.$lightbox.css({ top: top + "px", left: left + "px" }).fadeIn(this.options.fadeDuration);
    if (this.options.disableScrolling) $("body").addClass("lb-disable-scrolling");
    this.changeImage(index);
  };

  Lightbox.prototype.changeImage = function (index) {
    var self = this;
    var src = this.album[index].link;
    var ext = src.split(".").slice(-1)[0];
    var $img = this.$lightbox.find(".lb-image");
    var preloader = new Image();

    this.disableKeyboardNav();
    this.$overlay.fadeIn(this.options.fadeDuration);
    $(".lb-loader").fadeIn("slow");
    this.$lightbox.find(".lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption").hide();
    this.$outerContainer.addClass("animating");

    preloader.onload = function () {
      $img.attr({ alt: self.album[index].alt, src: src });
      $img.width(preloader.width);
      $img.height(preloader.height);

      var aspect = preloader.width / preloader.height;
      var winW = $(window).width();
      var winH = $(window).height();

      var maxW =
        winW -
        self.containerPadding.left -
        self.containerPadding.right -
        self.imageBorderWidth.left -
        self.imageBorderWidth.right -
        20;

      var maxH =
        winH -
        self.containerPadding.top -
        self.containerPadding.bottom -
        self.imageBorderWidth.top -
        self.imageBorderWidth.bottom -
        self.options.positionFromTop -
        70;

      var w, h;

      if (ext === "svg") {
        if (aspect >= 1) {
          h = parseInt(maxW / aspect, 10);
          w = maxW;
        } else {
          w = parseInt(maxH * aspect, 10);
          h = maxH;
        }
        $img.width(w);
        $img.height(h);
      } else {
        if (self.options.fitImagesInViewport) {
          if (self.options.maxWidth && self.options.maxWidth < maxW) maxW = self.options.maxWidth;
          if (self.options.maxHeight && self.options.maxHeight < maxH) maxH = self.options.maxHeight;
        } else {
          maxW = self.options.maxWidth || preloader.width || maxW;
          maxH = self.options.maxHeight || preloader.height || maxH;
        }

        if (preloader.width > maxW || preloader.height > maxH) {
          if (preloader.width / maxW > preloader.height / maxH) {
            w = maxW;
            h = parseInt(preloader.height / (preloader.width / w), 10);
          } else {
            h = maxH;
            w = parseInt(preloader.width / (preloader.height / h), 10);
          }
          $img.width(w);
          $img.height(h);
        }
      }

      self.sizeContainer($img.width(), $img.height());
    };

    preloader.src = src;
    this.currentImageIndex = index;
  };

  Lightbox.prototype.sizeOverlay = function () {
    var self = this;
    setTimeout(function () {
      self.$overlay.width($(document).width()).height($(document).height());
    }, 0);
  };

  Lightbox.prototype.sizeContainer = function (imageWidth, imageHeight) {
    var self = this;
    var oldWidth = this.$outerContainer.outerWidth();
    var oldHeight = this.$outerContainer.outerHeight();

    var newWidth =
      imageWidth +
      this.containerPadding.left +
      this.containerPadding.right +
      this.imageBorderWidth.left +
      this.imageBorderWidth.right;

    var newHeight =
      imageHeight +
      this.containerPadding.top +
      this.containerPadding.bottom +
      this.imageBorderWidth.top +
      this.imageBorderWidth.bottom;

    function postResize() {
      self.$lightbox.find(".lb-dataContainer").width(newWidth);
      self.$lightbox.find(".lb-prevLink").height(newHeight);
      self.$lightbox.find(".lb-nextLink").height(newHeight);
      self.$overlay.trigger("focus");
      self.showImage();
    }

    if (oldWidth !== newWidth || oldHeight !== newHeight) {
      this.$outerContainer.animate(
        { width: newWidth, height: newHeight },
        this.options.resizeDuration,
        "swing",
        function () {
          postResize();
        }
      );
    } else {
      postResize();
    }
  };

  Lightbox.prototype.showImage = function () {
    this.$lightbox.find(".lb-loader").stop(true).hide();
    this.$lightbox.find(".lb-image").fadeIn(this.options.imageFadeDuration);
    this.updateNav();
    this.updateDetails();
    this.preloadNeighboringImages();
    this.enableKeyboardNav();
  };

  Lightbox.prototype.updateNav = function () {
    var alwaysShow = false;
    try {
      document.createEvent("TouchEvent");
      alwaysShow = !!this.options.alwaysShowNavOnTouchDevices;
    } catch (e) {}

    this.$lightbox.find(".lb-nav").show();

    if (this.album.length > 1) {
      if (this.options.wrapAround) {
        if (alwaysShow) this.$lightbox.find(".lb-prev, .lb-next").css("opacity", "1");
        this.$lightbox.find(".lb-prev, .lb-next").show();
      } else {
        if (this.currentImageIndex > 0) {
          this.$lightbox.find(".lb-prev").show();
          if (alwaysShow) this.$lightbox.find(".lb-prev").css("opacity", "1");
        }
        if (this.currentImageIndex < this.album.length - 1) {
          this.$lightbox.find(".lb-next").show();
          if (alwaysShow) this.$lightbox.find(".lb-next").css("opacity", "1");
        }
      }
    }
  };

  Lightbox.prototype.updateDetails = function () {
    var self = this;

    if (this.album[this.currentImageIndex].title !== undefined && this.album[this.currentImageIndex].title !== "") {
      var $caption = this.$lightbox.find(".lb-caption");
      if (this.options.sanitizeTitle) {
        $caption.text(this.album[this.currentImageIndex].title);
      } else {
        $caption.html(this.album[this.currentImageIndex].title);
      }
      $caption.fadeIn("fast");
    }

    if (this.album.length > 1 && this.options.showImageNumberLabel) {
      var label = this.imageCountLabel(this.currentImageIndex + 1, this.album.length);
      this.$lightbox.find(".lb-number").text(label).fadeIn("fast");
    } else {
      this.$lightbox.find(".lb-number").hide();
    }

    this.$outerContainer.removeClass("animating");
    this.$lightbox.find(".lb-dataContainer").fadeIn(this.options.resizeDuration, function () {
      return self.sizeOverlay();
    });
  };

  Lightbox.prototype.preloadNeighboringImages = function () {
    if (this.album.length > this.currentImageIndex + 1) {
      new Image().src = this.album[this.currentImageIndex + 1].link;
    }
    if (this.currentImageIndex > 0) {
      new Image().src = this.album[this.currentImageIndex - 1].link;
    }
  };

  Lightbox.prototype.enableKeyboardNav = function () {
    this.$lightbox.on("keyup.keyboard", $.proxy(this.keyboardAction, this));
    this.$overlay.on("keyup.keyboard", $.proxy(this.keyboardAction, this));
  };

  Lightbox.prototype.disableKeyboardNav = function () {
    this.$lightbox.off(".keyboard");
    this.$overlay.off(".keyboard");
  };

  Lightbox.prototype.keyboardAction = function (event) {
    var KEY_ESC = 27;
    var KEY_LEFT = 37;
    var KEY_RIGHT = 39;
    var key = event.keyCode;

    if (key === KEY_ESC) {
      event.stopPropagation();
      this.end();
    } else if (key === KEY_LEFT) {
      if (this.currentImageIndex !== 0) {
        this.changeImage(this.currentImageIndex - 1);
      } else if (this.options.wrapAround && this.album.length > 1) {
        this.changeImage(this.album.length - 1);
      }
    } else if (key === KEY_RIGHT) {
      if (this.currentImageIndex !== this.album.length - 1) {
        this.changeImage(this.currentImageIndex + 1);
      } else if (this.options.wrapAround && this.album.length > 1) {
        this.changeImage(0);
      }
    }
  };

  Lightbox.prototype.end = function () {
    this.disableKeyboardNav();
    $(window).off("resize", this.sizeOverlay);
    this.$lightbox.fadeOut(this.options.fadeDuration);
    this.$overlay.fadeOut(this.options.fadeDuration);
    if (this.options.disableScrolling) $("body").removeClass("lb-disable-scrolling");
  };

  return new Lightbox();
});

/* jQuery ready handlers and utilities */
$(document).ready(function () {
  // Top nav hover
  $(".main-menu .dropdown").hover(function () {
    $(this).children(".dropdown-menu").toggle();
  });

  // Active nav link
  var path = window.location.pathname;
  $('a.nav-link[href="' + path + '"]').addClass("active");

  // Sticky main menu
  var $menu = $(".main-menu");
  var menuTop = $menu.offset().top;

  $(window).scroll(function () {
    var scrollTop = $(window).scrollTop();
    if (menuTop < scrollTop) $menu.addClass("fixed");
    else $menu.removeClass("fixed");
  });

  // Offcanvas submenu hover
  $(".sub-menu").hide();
  $(".offcanvas .offcanvas-body .menu li").hover(
    function () {
      $(this).children(".sub-menu").slideDown("fast");
    },
    function () {
      $(this).children(".sub-menu").hide();
    }
  );

  // Ad/SDK bootstraps based on device
  if (isMobile()) {
    console.log("is mobile");
  } else {
    console.log("is not mobile");
    loadScriptById(
      "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4436182731786816",
      "adsense-ad-tag",
      "text/javascript"
    );
    loadScriptById("https://securepubads.g.doubleclick.net/tag/js/gpt.js", "gpt-ad-tag", "text/javascript");
  }

  // Scroll progress
  var docScrollable = document.documentElement.scrollHeight - document.documentElement.clientHeight;

  window.addEventListener(
    "scroll",
    function () {
      var y = window.scrollY;
      var pct = Math.round((y / docScrollable) * 100);

      // Ensure GPT/Adsense loaded on mobile as user scrolls
      if (isMobile()) {
        loadScriptById(
          "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4436182731786816",
          "adsense-ad-tag",
          "text/javascript"
        );
        loadScriptById("https://securepubads.g.doubleclick.net/tag/js/gpt.js", "gpt-ad-tag", "text/javascript");
      }

      if (pct >= 5) {
        console.log("here");
      }

      if (pct >= 50) {
        appendDisqus();
        // NOTE: Removed Adnow conditional injection here.
        // Previously: conditionalInjection("SC_TBlock_882015","adnow","https://st-n.ads1-adnow.com/js/a.js")
      }
    },
    false
  );
});

// Native lazy-load for images with IntersectionObserver
document.addEventListener("DOMContentLoaded", function () {
  var lazyImgs = [].slice.call(document.querySelectorAll("img.img-lazy-load"));
  if ("IntersectionObserver" in window) {
    let io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          let img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove("img-lazy-load");
          io.unobserve(img);
        }
      });
    });
    lazyImgs.forEach(function (img) {
      io.observe(img);
    });
  }
});
