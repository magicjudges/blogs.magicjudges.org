(function($) {
  "use strict";
  var $index = $("#sidebar-index");
  var $indexMenu = $("#sidebar-index ul");
  var $headers = $("article h2");
  var $window = $(window);
  var offset = $index.offset();

  $headers.each(function() {
    var title = $(this).text();
    var id = title.toLowerCase().replace(/ /g, "-").replace(/'|\?|!/g, "");
    $(this).attr("id", id);
    var $element = $(document.createElement("li"));
    $element.addClass("nav-item");
    var $link = $(document.createElement("a"));
    $link.addClass("nav-link");
    $link.attr("href", "#" + id);
    $link.text(title);
    $element.append($link);
    $indexMenu.append($element);
  });

  function adjustPosition() {
    var currentScroll = $window.scrollTop();
    if (currentScroll > offset.top) {
      $index.css({
        "position": "fixed",
        "top": "0"
      });
    } else {
      $index.css({
        "position": "static",
        "top": ""
      });
    }
  }

  $window.scroll(adjustPosition);
  adjustPosition();

  $("body").scrollspy({
    target: "#sidebar-index nav",
    offset: 200
  });
})(jQuery);
