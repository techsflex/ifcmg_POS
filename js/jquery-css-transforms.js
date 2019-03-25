$counter = 0;

    $curr = $("#start");
    $curr.css("-webkit-transform", "translate3d(0,0,0)");
    $curr.css("-moz-transform", "translate(0,0)");
    $curr.append("<div><img src='" + image[$counter] + "'></div>");
    
$counter = 1;

    $currnext = $curr.next();
    $currnext.css("-webkit-transform", "translate3d(100%,0,0)");
    $currnext.css("-moz-transform", "translate(100%,0)");
    $currnext.append("<div><img src='" + image[$counter] + "'></div>");