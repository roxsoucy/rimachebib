/*!
 * Start Bootstrap - Agency Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
// $(function() {
//     $('a.page-scroll').bind('click', function(event) {
//         var $anchor = $(this);
//         $('html, body').stop().animate({
//             scrollTop: $($anchor.attr('href')).offset().top
//         }, 1500, 'easeInOutExpo');
//         event.preventDefault();
//     });
// });

$(document).ready(function(){
  $('a[href*=#]').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
    && location.hostname == this.hostname) {
      var $target = $(this.hash);
      $target = $target.length && $target
      || $('[name=' + this.hash.slice(1) +']');
      if ($target.length) {
        var targetOffset = $target.offset().top;
        $('html,body')
        .animate({scrollTop: targetOffset}, 1000);
       return false;
      }
    }
  });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top'
})

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});

$(window).bind('scroll', function() {
     if ($(window).scrollTop() > 100) {
         $('#fleche').hide();
     }
     else {
         $('#fleche').show();
     }
});
