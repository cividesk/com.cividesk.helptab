cj(document).ready(function() {

  //getContent Function called
    setTimeout(function() {
        getContent();
    }, 2000)

  var state = false;
  cj("#toggle-slide-button").click(function() {

    if (!state) {
      cj('#map-legend').animate({width: "toggle"}, 1000);
      state = true;
    }
    else {
      cj('#map-legend').animate({width: "toggle"}, 1000);
      state = false;
    }
  });

  //@todo - Implementation of tooltip
  cj('#txtName').tooltip();        

  });

//Ajax request to get the data
function getContent() {
  var helpTabUrl = CRM.url('civicrm/ajax/rest', 'className=CRM_HelpTab_Page_AJAX&fnName=getItems&json=1');
  cj.ajax(helpTabUrl, {
    type: 'post',
    dataType: 'json',
    data: {cividesk_key : cividesk_key, civicrm_version : civicrm_version},
    error: function() {
      alert('Could not fetch the data');
    },
    success: function(response) {
      var container = cj('.container');
      cj.each(response, function(i, obj) {
        var viewData = '<h3><a target="_blank" class="title" href="' + obj.url + '">' + obj.title + '</a></h3><div class="context">' + obj.text + '</div>';
        container.append(viewData);

      });

      //Implemented listing show-hide using jquery UI
      cj("#accordion").accordion({
        event: "click hoverintent",
        heightStyle: "content"
      });
        
      //Js custom scroll bar
      cj('.jScrollbar4').jScrollbar();
      
      //Hide scrollbar for short records - set height as per set in css to 209
      var contentHeight = cj(".jScrollbar_mask").height();
      if( contentHeight < 209 ){
        cj('.jScrollbar_draggable').hide();
      }
      
      //Handle click event for head tag in accordion
      cj(".title").on("click", function() {
        var url = cj(this).attr('href');
        window.open(url, '_blank');

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
    cj(this).bind("mouseover", jQuery.event.special.hoverintent.handler);
  },
  teardown: function() {
    cj(this).unbind("mouseover", jQuery.event.special.hoverintent.handler);
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

