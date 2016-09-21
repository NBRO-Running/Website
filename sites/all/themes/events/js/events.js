(function($) {

  Drupal.Events = Drupal.Events || {};
  Drupal.Events.mobileThreadHold = 939.5;
  Drupal.Events.curWidth = false;

  Drupal.behaviors.facebookAttend = {
    attach: function(context, settings) {

      //If the user is logged in through Facebook
      if(Drupal.settings.events.facebook_auth){

	$.each($('div.fb-attend'), function(key, value){
		var well = $(this);
		$.get("https://graph.facebook.com/"+this.id+"/attending/"+Drupal.settings.events.facebook_userid, {access_token : Drupal.settings.events.facebook_auth}, function(data) {
			//If array is not empty, we have the user signed up for the event. 
			if(data.data.length != 0){
				var wellWidth = $("div.fb-attend").width();
				var slider = $(this).find('strong.fb-attend-slider');
		                var imageWidth = slider.width();
				
				slider.css('left', wellWidth - imageWidth);
	                        $(this).addClass('attending');
				well.find('span').html("ATTENDING");
			}else {
				//console.log("NOT ATTENDING");
			}
		}.bind(this));
	});
      }
    }
  }

  Drupal.behaviors.actionEvents = {
    attach: function(context) {
      $('.btn-btt').smoothScroll({
        speed: 600
      });

      Drupal.Events.moveSearch();
      Drupal.Events.countdownTimer();
      Drupal.Events.formatDateEvent();
      Drupal.Events.preventLink(".views-slideshow-controls-bottom a", false);
      Drupal.Events.coverFlip();
      Drupal.Events.fixedHeader();
      Drupal.Events.moveCreatedDate();
      Drupal.Events.hideBlockCart();
      //Placeholder
      Drupal.Events.setInputPlaceHolder('name', 'Your name...', '#comment-form');
      Drupal.Events.setInputPlaceHolder('field_email[und][0][value]', 'Email address...', '#comment-form');
      Drupal.Events.setInputPlaceHolder('subject', 'Subject...', '#comment-form');
      Drupal.Events.setInputPlaceHolder('comment_body[und][0][value]', 'Comments...', '#comment-form');
      Drupal.Events.setInputPlaceHolder('search_block_form', 'Search...', '#search-block-form');
      Drupal.Events.setInputPlaceHolder('mail', 'Enter your email to get newsletter...', '.simplenews-subscribe');
      Drupal.Events.setInputPlaceHolder('name', 'Your name...', '.contact-form');
      Drupal.Events.setInputPlaceHolder('mail', 'Email address...', '.contact-form');
      Drupal.Events.setInputPlaceHolder('subject', 'Subject...', '.contact-form');
      Drupal.Events.setInputPlaceHolder('message', 'Message...', '.contact-form');
      Drupal.Events.setInputPlaceHolder('title', 'Event\'s name', '.view-speakers');
      Drupal.Events.setInputPlaceHolder('mail', 'Enter your email', '.simplenews-subscribe');
      Drupal.Events.setInputPlaceHolder('keys', 'Search...', '#search-form');

      Drupal.Events.animations();
      Drupal.Events.closeSearch();

      $("div.event-views .view-content .event-link").first().trigger("click");
      $(".panel-group .panel:first .accordion-toggle").trigger("click");

      $(window).resize(function() {
        $(".date-countdown").TimeCircles().destroy();
        Drupal.Events.countdownTimer();
        var width = $(window).innerWidth();
        if ((width - Drupal.Events.mobileThreadHold) * (Drupal.Events.curWidth - Drupal.Events.mobileThreadHold) < 0) {
          if (width < Drupal.Events.mobileThreadHold) {
            //Action for mabile
          } else {
            //Action for desktop
          }
        }
        Drupal.Events.curWidth = width;
      });

      // Mobile menu
      $('#menu-toggle').mobileMenu({
        targetWrapper: '#main-menu-inner',
        targetMenu: '#block-tb-megamenu-main-menu, #block-system-main-menu'
      });

      if ($(window).width() <= 991) {
        $('.mobile-main-menu .region-main-menu').accordionMenu();
      }
      $(window).resize(function() {
        if ($(window).width() <= 991) {
          $('.mobile-main-menu .region-main-menu').accordionMenu();
        }
      });
    }
  };

  Drupal.Events.hideBlockCart = function() {
    if ($('#block-commerce-cart-cart div.cart-empty-block').length) {
      $('#block-commerce-cart-cart').hide();
    }
  }

  Drupal.Events.fixedHeader = function() {
    var isFrontPage = $('body').hasClass('front');
    if ($(window).width() < 992) return;
    if (isFrontPage) {
      $("header").affix({
        offset: {
          top: function() {
            return $(window).height() + 100;
          }
        }
      });
    } else {
      $("header").affix({
        offset: {
          top: function() {
            return 40;
          }
        }
      });
    }

  }

  Drupal.Events.moveCreatedDate = function() {
    $('.node .node-content .created-date').insertAfter(".node .node-content .node-title");
    $('.node .node-content .comment-links').insertAfter(".node .node-content .field-collection-container");
  }

  Drupal.Events.moveSearch = function() {
    $('#block-search-form .content').appendTo($('#events-search .container'));
    $('#block-search-form').click(function() {
      if ($('#events-search').hasClass('open')) {
        $('#events-search').removeClass('open');
      } else {
        if ($("#main-menu-inner").hasClass("in")) {
          $(".navbar-toggle").trigger("click");
        }
        $('#events-search').addClass('open');
      }
    });
  }

  Drupal.Events.coverFlip = function() {
    jQuery('.coverflip').jcoverflip({
      current: 0,
      beforeCss: function(el, container, offset) {
        var el_width = container.width() * 0.3;
        return [
          $.jcoverflip.animationElement(el.removeClass("active"), {
            width: '30%',
            left: (0 - (offset * el_width)) + 'px',
            bottom: '20px'
          }, {})
        ];
      },
      afterCss: function(el, container, offset) {
        var el_width = container.width() * 0.3;
        return [
          $.jcoverflip.animationElement(el.removeClass("active"), {
            width: '30%',
            left: ((container.width() - el_width) + (offset * el_width)) + 'px',
            bottom: '20px'
          }, {})
        ];
      },
      currentCss: function(el, container) {
        var el_width = container.width() * 0.4;
        return [
          $.jcoverflip.animationElement(el.addClass("active"), {
            width: '40%',
            left: ((container.width() / 2) - (el_width / 2)) + 'px',
            bottom: 0
          }, {})
        ];
      },
      change: function(event, ui) {
        jQuery('#scrollbar').slider('value', ui.to * 25);
      }
    });
    jQuery('#scrollbar').slider({
      value: 1,
      stop: function(event, ui) {
        if (event.originalEvent) {
          var newVal = Math.round(ui.value / 25);
          jQuery('.coverflip').jcoverflip('current', newVal);
          jQuery('#scrollbar').slider('value', newVal * 25);
        }
      }
    });
  }

  Drupal.Events.formatDateEvent = function() {
    var eventDateSelector = $(".event-date span.date-display-single");
    eventDateSelector.each(function() {
      var eventDate = $(this).text();
      var dateTemp = eventDate.split(" ");
      eventDate = "<span class='day'>" + dateTemp[0] + "</span><span class='year'>" + dateTemp[1] + ", " + dateTemp[2] + "</span><span class='hour'>" + dateTemp[3] + "</span>";
      $(this).html(eventDate);
    });
  }

  Drupal.Events.preventLink = function(selector, flag) {
    $(selector).on("click", function(event) {
      event.preventDefault();
      if (flag) {
        event.stopPropagation();
      }
    });
  }

  Drupal.Events.countdownTimer = function() {
    $(".date-countdown").TimeCircles({
      "animation": "smooth",
      "bg_width": 0.2,
      "fg_width": 0.01,
      "count_past_zero": false,
      "direction": "Counter-clockwise",
      "circle_bg_color": "#90989F",
      "time": {
        "Days": {
          "text": "Days",
          "color": "#FFDD00",
          "show": true
        },
        "Hours": {
          "text": "Hours",
          "color": "#FFDD00",
          "show": true
        },
        "Minutes": {
          "text": "Minutes",
          "color": "#FFDD00",
          "show": true
        },
        "Seconds": {
          "text": "Seconds",
          "color": "#FFDD00",
          "show": true
        }
      }
    });
    $(".time_circles .textDiv_Days").append($(".time_circles .textDiv_Days h4"));
    $(".time_circles .textDiv_Hours").append($(".time_circles .textDiv_Hours h4"));
    $(".time_circles .textDiv_Minutes").append($(".time_circles .textDiv_Minutes h4"));
    $(".time_circles .textDiv_Seconds").append($(".time_circles .textDiv_Seconds h4"));
  }

  Drupal.Events.setInputPlaceHolder = function(name, text, selector) {
    selector = selector == undefined ? '' : selector + ' ';

    if ($.support.placeholder) {
      $(selector + 'input[name="' + name + '"]').attr('placeholder', Drupal.t(text));
    } else {
      $(selector + 'input[name="' + name + '"]').val(Drupal.t(text));
      $(selector + 'input[name="' + name + '"]').focus(function() {
        if (this.value == Drupal.t(text)) {
          this.value = '';
        }
      }).blur(function() {
        if (this.value == '') {
          this.value = Drupal.t(text);
        }
      });
    }
  }

  Drupal.Events.animations = function() {
    //Event slide image in frontpage
    var top = $(window).height();
    var target = $('.event-views .views-slideshow-cycle-main-frame .views-slideshow-cycle-main-frame-row:first .views-field-field-event-image');
    target.velocity({
      opacity: 0
    });
    target.waypoint(function() {
      target.velocity("transition.slideDownBigIn", 2000);
      target.velocity("callout.shake", 200);
    }, {
      offset: top,
      triggerOnce: true
    });
    //Animate Number for About page
    $('.animate-number .field-item').each(function() {
      var data = $(this).text();
      var separator = (data.indexOf(',') > -1);
      var after = data.substring(data.length - 1);
      data = data.replace(new RegExp('[,\+]', 'gm'), '');
      var comma_separator = $.animateNumber.numberStepFactories.separator(',');
      console.log(comma_separator);
      var comma_append = $.animateNumber.numberStepFactories.append('+');
      console.log(comma_append);
      $(this).waypoint(function() {
        $(this).animateNumber({
          number: data,
          numberStep: function(e, k) {
            comma_separator(e, k);
            comma_append(e, k);
          }
        }, 10000);
      }, {
        offset: top,
        triggerOnce: true
      });
    });
  }

  Drupal.Events.closeSearch = function() {
    $(".navbar-toggle").on("click", function() {
      if ($('#events-search').hasClass("open")) {
        $('#events-search').removeClass("open");
      }
    })
  }

  jQuery.support.placeholder = (function() {
    var i = document.createElement('input');
    return 'placeholder' in i;
  })();

  //Set equal height for tickets
  Drupal.Events.animations = function() {
    var maxHeight = 0;
    $('body.node-type-event div.field-name-field-tickets > div.field-items > div.field-item article.node-ticket div.content div.field-name-field-product-description').each(function(){
  	maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
    });

    $('body.node-type-event div.field-name-field-tickets > div.field-items > div.field-item article.node-ticket div.content div.field-name-field-product-description').each(function(){
          $(this).height(maxHeight);
    });
  };

})(jQuery);
