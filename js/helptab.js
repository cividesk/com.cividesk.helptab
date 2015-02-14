cj(document).ready(function() {

  cj('body').append('<div id="helptab-panel"><div id="helptab-map-legend"><div class="jScrollbar4"><div class="jScrollbar_mask"><div id="helptab-accordion" style="text-align: left;" class="helptab-container"></div></div><div class="jScrollbar_draggable"><a href="#" class="draggable"></a></div></div></div><div id="helptab-map-legend-control" title="Looking for help ?" class="left"><span class="helptab-pointer"><span id="helptab-count" style="display: none;"></span></span><div href="javascript:void()" id="helptab-toggle-slide-button"></div></div></div>');
  var state = false;
  cj("body").on('click', '#helptab-map-legend-control, span#helptab-count', function(event) {
    event.stopImmediatePropagation();
    if (!state) {
      state = true;
      if (! cj('.helptab-container').is(':empty')) {
        cj('#helptab-map-legend').animate({width: 468, padding: 12}, 1000);
      }
      //Get the content on open of panel
      //Restrict ajax request of data  already loaded
      if (cj('.helptab-container').is(':empty')) {
        cj("#helptab-count").text('Loading').show();
        getContent();
      }
    }
    else {
      cj('#helptab-map-legend').animate({width: 0, padding: 0}, 1000);
      state = false;
    }
  });
  if ( parseInt(cj.fn.jquery.replace(/\./gi,'').substring(0,3)) >= 183 ) {
    //Tooltip for showing the total counts
    cj( '#helptab-map-legend-control' ).tooltip();
    cj( "#helptab-map-legend-control" ).tooltip('option', 'tooltipClass', 'left');
    cj( "#helptab-map-legend-control" ).tooltip('option', 'position', {my: "right center", at: "left-10 center"});
  }
});

//Ajax request to get the data
function getContent() {
  var helpTabUrl = 'https://api.cividesk.com/helptab/index.php';
  var getcontent = helpTabUrl + '?action=getContent';
  cj.ajax(getcontent, {
    type: 'post',
    dataType: 'json',
    data: {cividesk_key : cividesk_key, civicrm_version : civicrm_version, context: civicrm_contex },
    error: function() {
      alert('Could not retrieve data from HelpTab');
    },
    success: function(response) {
      var container = cj('.helptab-container');
      cj("#helptab-count").hide();
      cj('#helptab-map-legend').animate({width: 468, padding: 12}, 1000);
      if(response.counts > 0){
        cj("#helptab-count").text(response.counts).css({'margin-top':'-12px'}).show('slow');
      }
      cj.each(response.result, function(i, obj) {
        //@todo - temporary url for tracking of logging info, which will something like - 'http://api.cividesk.com/redirect.php?itemId=XXX';
        var redirectUrl = helpTabUrl + '?action=redirect&itemId=' + obj.item_id;
        var viewData = '<h3><a target="_blank" class="helptab-title" style="font-weight: normal;font-size: 1em;" url=' + obj.url + ' href="' + redirectUrl + '">' + obj.title + '</a></h3><div class="helptab-context">' + obj.text + '</div>';
        container.append(viewData)

      });
      if (response.result.length == 0 ) {
        cj('#helptab-map-legend').empty().append('<div style="text-align:center;vertical-align: middle;padding-top:100px;">Help content not available.</div>');
      }
      //cj('#total_record').html(response.total);
      
      //Implemented listing show-hide using jquery UI
      cj("#helptab-accordion").accordion({
        event: "click hoverintent",
        heightStyle: "content"
      });

      //Js custom scroll bar
      cj('.jScrollbar4').jScrollbar();

      //Hide scrollbar for short records - set height as per set in css to 209
      var contentHeight = cj(".jScrollbar_mask").height();
      if (contentHeight < 209) {
        cj('.jScrollbar_draggable').hide();
      }

      //Handle click event for head tag in accordion
      cj(".helptab-title").on("click", function(e) {
        //keep track of logging            
        setLogs(cj(this).attr('url'), cj(this).attr('href'), civicrm_contex);
      });
    }
  });
}

/**
 * Functions to redirect to destination and set the logs
 * @param {string} url
 * @param {string} href
 * @param {string} civicrm_contex
 */
function setLogs(url, href, civicrm_contex) {
  cj.ajax(href, {
    type: 'get',
    data: {href: href, url: url, context: civicrm_contex},
    error: function() {
      alert('Unable to open help page');
    },
    success: function(response) {
      if (response == 'true') {
        window.open(url, '_blank');
      }
    }
  });
}

/*
* hoverIntent | Copyright 2011 Brian Cherne
* http://cherne.net/brian/resources/jquery.hoverIntent.html
* modified by the jQuery UI team
* This patch is needed to support for mousehover event for Accordion
*/

cj.event.special.hoverintent = {
  setup: function() {
    cj(this).bind("mouseover", cj.event.special.hoverintent.handler);
  },
  teardown: function() {
    cj(this).unbind("mouseover", cj.event.special.hoverintent.handler);
  },
  handler: function(event) {
    var currentX, currentY, timeout,
      args = arguments,
      target = cj(event.target),
      previousX = event.pageX,
      previousY = event.pageY;
    function track(event) {
      currentX = event.pageX;
      currentY = event.pageY;
    };

    function clear() {
      target.unbind("mousemove", track).unbind("mouseout", clear);
      clearTimeout(timeout);
    }
    function handler() {
      var prop, orig = event;
      if ((Math.abs(previousX - currentX) + Math.abs(previousY - currentY)) < 7) {
        clear();
        event = cj.Event("hoverintent");
        for (prop in orig) {
          if (!(prop in event)) {
            event[ prop ] = orig[ prop ];
          }
        }
        // Prevent accessing the original event since the new event
        // is fired asynchronously and the old event is no longer
        // usable (#6028)
        delete event.originalEvent;
        target.trigger(event);
      } else {
          previousX = currentX;
          previousY = currentY;
          timeout = setTimeout(handler, 100);
      }
    }
    timeout = setTimeout(handler, 100);
    target.bind({
        mousemove: track,
        mouseout: clear
    });
  }
};

