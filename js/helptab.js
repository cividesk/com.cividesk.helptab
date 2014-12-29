cj(document).ready(function() {

    cj('body').append('<span class="question"></span><div id="panel"><div id="map-legend"><div class="jScrollbar4"><div class="jScrollbar_mask"><div id="accordion" class="container"></div></div><div class="jScrollbar_draggable"><a href="#" class="draggable"></a></div></div></div><div id="map-legend-control" title="Looking for help ?" class="left"><span class="pointer"></span><div href="javascript:void()" id="toggle-slide-button"></div></div></div>');
    var state = false;
    cj('.question').hide();
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
            
            setTimeout(function(){
                cj('.question').toggle('bounce',2000)
            },1000);            
            
        }
        else {
            cj('#map-legend').animate({width: 0, padding: 0}, 1000);
            state = false;
            cj('.question').hide();
        }
    });
    
    //Tooltip for showing the total counts  
    cj('#map-legend-control').tooltip();
    cj("#map-legend-control").tooltip('option', 'tooltipClass', 'left');
    cj("#map-legend-control").tooltip('option', 'position', {my: "right center", at: "left-10 center"});
    
    //Tooltip for display of counts
   
   
   
});

//Ajax request to get the data
function getContent() {
    
    //@todo - Change/removed the following static parameter to valid one 
    var cividesk_key = 'XXXX';
    var civicrm_version = '4.4';    
    //@todo- This will fetch from civicrm code
    var civicrm_contex = 'civicrm/admin/job';
    
    //var helpTabUrl = 'http://local.cividesk/helptab/index.php';
    var helpTabUrl = 'https://api.cividesk.com/helptab/index.php';       
    
    /*Actual url for get the content*/       
    var getcontent = helpTabUrl + '?action=getContent';
   
    cj.ajax(getcontent, {
        type: 'get',
        dataType: 'json',
        data: {cividesk_key: cividesk_key, civicrm_version: civicrm_version, civicrm_contex: civicrm_contex},
        error: function() {
            alert('Could not fetch the data');
        },
        success: function(response) {
            var container = cj('.container');
            cj.each(response.result, function(i, obj) {
                
                if(response.counts > 0){
                    cj(".question").text("Help Count-" + response.counts);
                }
                //Redirect action to log the information and actual redirection
                var redirectUrl = helpTabUrl + '?action=redirect&itemId=' + obj.item_id;
                var viewData = '<h3><a target="_blank" class="title" url=' + obj.url + ' href="' + redirectUrl + '">' + obj.title + '</a></h3><div class="context">' + obj.text + '</div>';
                container.append(viewData)

            });

             //Show help count buncing 
            if (response.counts > 0) {                
                cj(".question").text("Help Count-" + response.counts);
            } else {                
                cj(".question").text("Help Count- 0");
            }
            
            //Show empty message
            if (response.result.length == 0 ) {
                cj('#map-legend').empty().append('<div style="text-align:center;vertical-align: middle;padding-top:100px;">Help content not available.</div>');
            }
      
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
            alert('Could not fetch the data');
        },
        success: function(response) {
            if (response == 'true') {
                window.open(url, '_blank');
            }
        }
    });
}
        
/**
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

