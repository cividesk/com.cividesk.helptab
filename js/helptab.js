cj(document).ready(function() {

  cj('body').append('<div id="panel"><div id="map-legend"><div class="jScrollbar4"><div class="jScrollbar_mask"><div id="accordion" class="container"></div></div><div class="jScrollbar_draggable"><a href="#" class="draggable"></a></div></div></div><div id="map-legend-control" title="Looking for help ?" class="left"><span class="pointer"></span><div href="javascript:void()" id="toggle-slide-button"></div></div></div>');
    var state = false;
    cj("#toggle-slide-button, #map-legend-control").live('click', function(event) {
      event.stopImmediatePropagation();
      if (!state) {
        state = true; 
        cj('#map-legend').animate({width: 468, padding: 12}, 1000);
        //Get the content on open of panel
        //Restrict ajax request of data  already loaded
        if (cj('.container').is(':empty')) {
          getContent();
        }
      }
      else {
        cj('#map-legend').animate({width: 0, padding: 0}, 1000);
        state = false;
      }
    })

  //Tooltip for showing the total counts  
  cj( '#map-legend-control' ).tooltip();    
  cj( "#map-legend-control" ).tooltip('option', 'tooltipClass', 'left');
  cj( "#map-legend-control" ).tooltip('option', 'position', {my: "right center", at: "left-10 center"});
});

//Ajax request to get the data
function getContent() {
  var helpTabUrl = CRM.url('civicrm/ajax/rest', 'className=CRM_HelpTab_Page_AJAX&fnName=getItems&json=1');
  cj.ajax(helpTabUrl, {
    type: 'post',
    dataType: 'json',
    data: {cividesk_key : cividesk_key, civicrm_version : civicrm_version, civicrm_contex: civicrm_contex },
    error: function() {
      alert('Could not fetch the data');
    },
    success: function(response) {
      var container = cj('.container');
      cj.each(response.result, function(i, obj) {
        //@todo - temporary url for tracking of logging info, which will something like - 'http://api.cividesk.com/redirect.php?itemId=XXX';
        var virtualUrl = 'redirect.php?itemId=' + obj.item_id;
        var viewData = '<h3><a target="_blank" class="title" url=' + obj.url + ' href="' + virtualUrl + '">' + obj.title + '</a></h3><div class="context">' + obj.text + '</div>';
        container.append(viewData)

      });
      //cj('#total_record').html(response.total);
      
      //Implemented listing show-hide using jquery UI
      cj("#accordion").accordion({
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
      cj(".title").on("click", function(e) {
        //keep track of logging            
        //setLogs(cj(this).attr('url'), cj(this).attr('href'));
      });
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

