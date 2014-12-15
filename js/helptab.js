$(document).ready(function() {
   
    //getContent Function called
    getContent();

    var state = false;
    $("#toggle-slide-button").click(function() {

        if (!state) {
            $('#map-legend').animate({width: "toggle"}, 1000);
            state = true;
        }
        else {
            $('#map-legend').animate({width: "toggle"}, 1000);
            state = false;
        }
    });
    
    //@todo - Implementation of tooltip
    $('#txtName').tooltip();        

});

//Ajax request to get the data
function getContent() {

    $.ajax('/SAMPLE/getContent.php', {
        type: 'post',
        dataType: 'json',
        error: function() {
            alert('Could not fetch the data');
        },
        success: function(response) {
            var container = $('.container');
            $.each(response, function(i, obj) {
                var viewData = '<h3><a target="_blank" class="title" href="' + obj.url + '">' + obj.title + '</a></h3><div class="context">' + obj.text + '</div>';
                container.append(viewData);

            });

            //Implemented listing show-hide using jquery UI
            $("#accordion").accordion({
                event: "click hoverintent",
                heightStyle: "content"
            });
            
            //Js custom scroll bar
            $('.jScrollbar4').jScrollbar();
            
            //Hide scrollbar for short records - set height as per set in css to 209
            var contentHeight = $(".jScrollbar_mask").height();
            if( contentHeight < 209 ){
              $('.jScrollbar_draggable').hide();
            }
            
            //Handle click event for head tag in accordion
            $(".title").on("click", function() {
                var url = $(this).attr('href');
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

$.event.special.hoverintent = {
    setup: function() {
        $(this).bind("mouseover", jQuery.event.special.hoverintent.handler);
    },
    teardown: function() {
        $(this).unbind("mouseover", jQuery.event.special.hoverintent.handler);
    },
    handler: function(event) {
        var currentX, currentY, timeout,
                args = arguments,
                target = $(event.target),
                previousX = event.pageX,
                previousY = event.pageY;
        function track(event) {
            currentX = event.pageX;
            currentY = event.pageY;
        }
        ;
        function clear() {
            target
                    .unbind("mousemove", track)
                    .unbind("mouseout", clear);
            clearTimeout(timeout);
        }
        function handler() {
            var prop,
                    orig = event;
            if ((Math.abs(previousX - currentX) +
                    Math.abs(previousY - currentY)) < 7) {
                clear();
                event = $.Event("hoverintent");
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
        