/**
 * Main JS file for theme
 */

/* globals jQuery, document */
(function ($, undefined) {
    "use strict";

    $("#filter-category").multipleSelect({
        filter: true,
        width: '200px',
        placeholder: 'Category,Category2,Category3'
    });
    $("#filter-tag").multipleSelect({
        filter: true,
        width: '200px',
        placeholder: 'Tag1,Tag2,Tag3'
    });
    $("#filter-color").multipleSelect({
        filter: true,
        width: '200px',
        placeholder: 'Color1,Color2,Color3'
    });
    $('input[name="start"]').datepicker({
        weekStart: 1,
        format: 'dd-mm-yyyy'
    });
    $('input[name="end"]').datepicker({
        weekStart: 1,
        format: 'dd-mm-yyyy'
    });

    $(document).on('change', ".sidebar-toggle", function () {
        $(".sidebar-toggle").not(this).prop('checked', false);
    });

    var links = document.querySelectorAll('a[href]');
    var cbk = function (e) {
        if (e.currentTarget.href === window.location.href) {
            e.preventDefault();
            e.stopPropagation();
        }
    };

    for (var i = 0; i < links.length; i++) {
        links[i].addEventListener('click', cbk);
    }
})(jQuery);

/* global Pjax */
var pjax;
document.addEventListener("DOMContentLoaded", function () {
    pjax = new Pjax({
        elements: [".js-Pjax"],
        selectors: ["#content", "title"],
        cacheBust: false,
        scrollTo: false,
        switches: {
            "#content": Pjax.switches.sideBySide
        },
        switchesOptions: {
            "#content": {
                classNames: {
                    remove: "animated",
                    add: "animated",
                    backward: "fadeIn",
                    forward: "fadeOut"
                },
                callbacks: {
                    removeElement: function (el) {
                        el.style.marginLeft = "-" + (el.getBoundingClientRect().width / 2) + "px"
                    }
                }
            }
        }
    })
})