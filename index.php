<?php
/**
 * This is a home page.
 * 
 */

//Set the base url
$base_url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"].'?').'/'; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Applications</title>
        <meta http-equiv="description" content="" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <base href="<?php echo $base_url; ?>">                   
        <script type="text/javascript" src="js/jquery-1.8.3.js"></script>   
        <script type="text/javascript">
            var cj=jQuery.noConflict();
        </script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> 
        <script type="text/javascript" src="js/jquery-mousewheel.js"></script>
        <script type="text/javascript" src="js/jScrollbar.jquery.js"></script>       
        <script type="text/javascript" src="js/helptab.js"></script>
        
        <link rel="stylesheet" href="css/jquery-ui.css">             
        <link rel="stylesheet" href="css/jScrollbar.jquery.css">                    
        <link rel="stylesheet" href="css/helptab.css">         
                   
    </head>
    
    <body>
        <div id="panel">            
            <div id="map-legend">	                            
                <div class="jScrollbar4">
                    <div class="jScrollbar_mask">

                        <div id="accordion" class="container"></div>

                    </div>                    
                    <div class="jScrollbar_draggable">
                        <a href="#" class="draggable"></a>
                    </div>
                </div>
            </div>                        
            
            <div id="map-legend-control" title="Looking for help ?" class="left">
                <span class="pointer"></span>
                <div href="javascript:void()" id="toggle-slide-button"></div>
            </div>
        </div>               
    </body>
</html>

