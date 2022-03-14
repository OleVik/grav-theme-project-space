/**
 * Main JS file for theme
 */
var pjax;
document.addEventListener("DOMContentLoaded", function () {
  pjax = new Pjax({
    elements: [".js-Pjax"],
    selectors: ["#content", "title"],
    cacheBust: false,
    scrollTo: false,
    switches: {
      "#content": Pjax.switches.sideBySide,
    },
    switchesOptions: {
      "#content": {
        classNames: {
          remove: "animated",
          add: "animated",
          backward: "fadeIn",
          forward: "fadeOut",
        },
        callbacks: {
          removeElement: function (el) {
            el.style.marginLeft =
              "-" + el.getBoundingClientRect().width / 2 + "px";
          },
        },
      },
    },
  });
  (function ($, undefined) {
    "use strict";
    $("#filter-category").multipleSelect({
      filter: true,
      width: "200px",
      placeholder: THEME_PROJECTSPACE_TOOLS_PLACEHOLDERS_CATEGORY,
    });
    $("#filter-tag").multipleSelect({
      filter: true,
      width: "200px",
      placeholder: THEME_PROJECTSPACE_TOOLS_PLACEHOLDERS_TAG,
    });
    $("#filter-color").multipleSelect({
      filter: true,
      width: "200px",
      placeholder: THEME_PROJECTSPACE_TOOLS_PLACEHOLDERS_COLOR,
    });
    $('input[name="start"]').datepicker({
      weekStart: 1,
      format: "dd-mm-yyyy",
    });
    $('input[name="end"]').datepicker({
      weekStart: 1,
      format: "dd-mm-yyyy",
    });
    $(document).on("change", ".sidebar-toggle", function () {
      $(".sidebar-toggle").not(this).prop("checked", false);
    });
    var links = document.querySelectorAll("a[href]");
    var cbk = function (e) {
      if (e.currentTarget.href === window.location.href) {
        e.preventDefault();
        e.stopPropagation();
      }
    };
    for (var i = 0; i < links.length; i++) {
      links[i].addEventListener("click", cbk);
    }
  })(jQuery);
});
