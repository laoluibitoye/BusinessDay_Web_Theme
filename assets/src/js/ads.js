// console.log = function () {};
var REFRESH_KEY = "refresh";
var REFRESH_VALUE = "true";
var SECONDS_TO_WAIT_AFTER_VIEWABILITY = 60;

window.onload = function () {
  console.log("window loaded");
  // var amazonSlots = [];
  var lazyGamIds = [];
  var lazyGamTags = [].slice.call(
    document.querySelectorAll("div.bd-admanager")
  );
  for (lazyGamTag of lazyGamTags) {
    lazyGamIds.push(lazyGamTag.id);
  }
  var isIdExist = document.getElementById("no-ads");
  // var initializeGPT = document.getElementById("gpt-ad-tag");

  if (isIdExist) {
    console.log("no ads");
  } else {
    console.log("show ads");

    var bd_mobile_1,
      bd_mobile_2,
      bd_mobile_3,
      bd_mobile_4,
      bd_mobile_tenancy_1,
      bd_mobile_tenancy_2,
      bd_desktop_1,
      bd_desktop_2,
      bd_desktop_3,
      bd_desktop_4,
      bd_desktop_homepage_tenancy_1,
      bd_desktop_homepage_tenancy_2,
      bd_homepage_desktop_hero,
      bd_sidebar_1,
      interstitialSlot,
      anchorSlot,
      bd_inarticle_video;

    window.googletag = window.googletag || { cmd: [] };
    initGAM();

    var lazyGamTags = [].slice.call(
      document.querySelectorAll("div.bd-admanager")
    );
    // console.log(lazyGamTags.length);
    if ("IntersectionObserver" in window) {
      // console.log(lazyGamTags);
      // Create new observer object
      let lazyGamTagObserver = new IntersectionObserver(function (
        entries,
        observer
      ) {
        // Loop through IntersectionObserverEntry objects
        entries.forEach(function (entry) {
          // Do these if the target intersects with the root
          if (entry.isIntersecting) {
            let lazyGamTag = entry.target;
            switch (lazyGamTag.id) {
              case "div-gpt-ad-1690469943006-0":
                lazySlot = bd_desktop_1;
                break;
              case "div-gpt-ad-1690475556536-0":
                lazySlot = bd_desktop_2;
                break;
              case "div-gpt-ad-1693018087997-0":
                lazySlot = bd_desktop_3;
                break;
              case "div-gpt-ad-1693018488143-0":
                lazySlot = bd_desktop_4;
                break;

              case "div-gpt-ad-1693018770369-0":
                lazySlot = bd_desktop_homepage_tenancy_1;
                break;

              case "div-gpt-ad-1693018831507-0":
                lazySlot = bd_desktop_homepage_tenancy_2;
                break;

              case "div-gpt-ad-1693020204908-0":
                lazySlot = bd_homepage_desktop_hero;
                break;

              case "div-gpt-ad-1690475828217-0":
                lazySlot = bd_sidebar_1;
                break;

              case "div-gpt-ad-1693019230878-0":
                lazySlot = bd_sidebar_2;
                break;

              case "div-gpt-ad-1690469983344-0":
                lazySlot = bd_mobile_1;
                break;

              case "div-gpt-ad-1690475714652-0":
                lazySlot = bd_mobile_2;
                break;

              case "div-gpt-ad-1693019413544-0":
                lazySlot = bd_mobile_3;
                break;

              case "div-gpt-ad-1693019455783-0":
                lazySlot = bd_mobile_4;
                break;

              case "div-gpt-ad-1693019649387-0":
                lazySlot = bd_mobile_tenancy_1;
                break;

              case "div-gpt-ad-1693019696301-0":
                lazySlot = bd_mobile_tenancy_2;
                break;

              case "div-gpt-ad-1698135246783-0":
                lazySlot = bd_inarticle_video;
                break;

              default:
                lazySlot = "";
            }

            if (lazySlot != "") {
              // console.log('slot is '.lazySlot)
              googletag.pubads().refresh([lazySlot]);
            }

            googletag.cmd.push(function () {
              googletag.display(lazyGamTag.id);
            });
            console.log("intersecting gpt " + lazyGamTag.id);
            lazyGamTag.classList.remove("bd-admanager");
            lazyGamTagObserver.unobserve(lazyGamTag);
          }
        });
      });

      // Loop through and observe each image
      lazyGamTags.forEach(function (lazyGamTag) {
        lazyGamTagObserver.observe(lazyGamTag);
      });
    }

    // } else {
    //   console.log('AWAITING GPT INITIALIZATION......')
    // }
  }

  function initGAM() {
    if (window.googletag && googletag.apiReady) {
      console.log("GPT INITIALIZED......");
      // GPT ad slots

      googletag.cmd.push(function () {
        if (document.body.clientWidth <= 500) {
          anchorSlot = googletag.defineOutOfPageSlot(
            "/21781351181/db_anchor",
            googletag.enums.OutOfPageFormat.TOP_ANCHOR
          );
        } else {
          anchorSlot = googletag.defineOutOfPageSlot(
            "/21781351181/db_anchor",
            googletag.enums.OutOfPageFormat.TOP_ANCHOR
          );
        }

        if (anchorSlot) {
          anchorSlot
            .setTargeting(REFRESH_KEY, REFRESH_VALUE)
            .addService(googletag.pubads());
          console.log("Anchor ad is initialized. Scroll page to activate.");
        }

        console.log("Web Interstitial initialized");
        interstitialSlot = googletag.defineOutOfPageSlot(
          "/21781351181/bd_interstitial",
          googletag.enums.OutOfPageFormat.INTERSTITIAL
        );

        if (interstitialSlot) {
          interstitialSlot.addService(googletag.pubads());

          console.log("Web Interstitial is loading...");

          googletag.pubads().addEventListener("slotOnload", function (event) {
            if (interstitialSlot === event.slot) {
              console.log("Web Interstitial is loaded");
            }
          });
        } else {
          console.log("Web interstitial slot returned null");
        }

        if (isMobile()) {
          if (lazyGamIds.includes("div-gpt-ad-1690469983344-0")) {
            bd_mobile_1 = googletag
              .defineSlot(
                "/21781351181/bd_mobile_1",
                [
                  [300, 250],
                  [300, 50],
                ],
                "div-gpt-ad-1690469983344-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1690475714652-0")) {
            bd_mobile_2 = googletag
              .defineSlot(
                "/21781351181/bd_mobile_2",
                [
                  [300, 100],
                  [300, 250],
                ],
                "div-gpt-ad-1690475714652-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693019413544-0")) {
            bd_mobile_3 = googletag
              .defineSlot(
                "/21781351181/bd_mobile_3",
                [
                  [300, 50],
                  [336, 280],
                  [300, 100],
                  [300, 250],
                  [320, 100],
                ],
                "div-gpt-ad-1693019413544-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693019455783-0")) {
            bd_mobile_4 = googletag
              .defineSlot(
                "/21781351181/bd_mobile_4",
                [
                  [300, 50],
                  [336, 280],
                  [300, 100],
                  [300, 250],
                  [320, 100],
                ],
                "div-gpt-ad-1693019455783-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693019649387-0")) {
            bd_mobile_tenancy_1 = googletag
              .defineSlot(
                "/21781351181/bd_mobile_tenancy_1",
                [
                  [320, 50],
                  [320, 100],
                  [300, 100],
                  [300, 250],
                  [336, 280],
                  [300, 50],
                ],
                "div-gpt-ad-1693019649387-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693019696301-0")) {
            bd_mobile_tenancy_2 = googletag
              .defineSlot(
                "/21781351181/bd_mobile_tenancy_2",
                [
                  [300, 100],
                  [300, 250],
                  [320, 50],
                  [336, 280],
                  [300, 50],
                  [320, 100],
                ],
                "div-gpt-ad-1693019696301-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }
        } else {
          if (lazyGamIds.includes("div-gpt-ad-1690469943006-0")) {
            bd_desktop_1 = googletag
              .defineSlot(
                "/21781351181/bd_desktop_1",
                [[300, 250], [728, 90], "fluid"],
                "div-gpt-ad-1690469943006-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1690475556536-0")) {
            bd_desktop_2 = googletag
              .defineSlot(
                "/21781351181/bd_desktop_2",
                [[300, 250], "fluid", [728, 90]],
                "div-gpt-ad-1690475556536-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693018087997-0")) {
            bd_desktop_3 = googletag
              .defineSlot(
                "/21781351181/bd_desktop_3",
                [[728, 90], "fluid", [300, 50], [300, 100]],
                "div-gpt-ad-1693018087997-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693018488143-0")) {
            bd_desktop_4 = googletag
              .defineSlot(
                "/21781351181/bd_desktop_4",
                ["fluid", [300, 100], [300, 250], [728, 90]],
                "div-gpt-ad-1693018488143-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693018770369-0")) {
            bd_desktop_homepage_tenancy_1 = googletag
              .defineSlot(
                "/21781351181/bd_desktop_homepage_tenancy_1",
                [
                  [300, 250],
                  [728, 90],
                  [300, 100],
                  [970, 250],
                  [300, 50],
                  "fluid",
                ],
                "div-gpt-ad-1693018770369-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693018831507-0")) {
            bd_desktop_homepage_tenancy_2 = googletag
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
                  [970, 66],
                ],
                "div-gpt-ad-1693018831507-0"
              )
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1690475828217-0")) {
            bd_sidebar_1 = googletag
              .defineSlot(
                "/21781351181/bd_sidebar_1",
                [
                  [300, 600],
                  [300, 250],
                ],
                "div-gpt-ad-1690475828217-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693019230878-0")) {
            bd_sidebar_2 = googletag
              .defineSlot(
                "/21781351181/bd_sidebar_2",
                [
                  [300, 250],
                  [300, 600],
                ],
                "div-gpt-ad-1693019230878-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }

          if (lazyGamIds.includes("div-gpt-ad-1693020204908-0")) {
            bd_homepage_desktop_hero = googletag
              .defineSlot(
                "/21781351181/bd_homepage_desktop_hero",
                [1900, 200],
                "div-gpt-ad-1693020204908-0"
              )
              .setTargeting(REFRESH_KEY, REFRESH_VALUE)
              .addService(googletag.pubads());
          }
        }

        if (lazyGamIds.includes("div-gpt-ad-1698135246783-0")) {
          bd_inarticle_video = googletag
            .defineSlot(
              "/21781351181/bd_inarticle_video",
              [1, 1],
              "div-gpt-ad-1698135246783-0"
            )
            .addService(googletag.pubads());
        }

        googletag.pubads().enableLazyLoad({
          fetchMarginPercent: 300, // Fetch slots within 3 viewports.
          renderMarginPercent: 100, //render slots within 1 viewport
          mobileScaling: 2.0,
        });

        googletag
          .pubads()
          .addEventListener("impressionViewable", function (event) {
            var slot = event.slot;
            if (slot.getTargeting(REFRESH_KEY).indexOf(REFRESH_VALUE) > -1) {
              setTimeout(function () {
                googletag.pubads().refresh([slot]);
              }, SECONDS_TO_WAIT_AFTER_VIEWABILITY * 1000);
            }
          });

        // googletag.pubads().addEventListener('slotRequested', function(event) {
        //   console.log('fetched_'.event.slot.getSlotElementId());
        // });

        // googletag.pubads().addEventListener('slotOnload', function(event) {
        //   updateSlotStatus('rendered_'.event.slot.getSlotElementId());
        // });

        googletag.pubads().disableInitialLoad();
        googletag.pubads().enableSingleRequest();
        googletag.enableServices();
        googletag.pubads().refresh([anchorSlot, interstitialSlot]);
      });
    } else {
      console.log("AWAITING GPT INITIALIZATION......");
      setTimeout(initGAM, 500);
    }
  }

  //   function isMobile() {
  // 	if (
  // 		/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(
  // 			navigator.userAgent
  // 		) ||
  // 		/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| ||a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
  // 			navigator.userAgent.substr(0, 4)
  // 		)
  // 	) {
  // 		return true;
  // 	}
  // 	return false;
  // }
};
