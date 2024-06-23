<?php
    // echo "Today is " . date("Y-m-d") . "<br>";
    // echo sprintf("%02s", 1);

    // $date1=date_create("2014-05-9");
    // $date2=date_create("2014-05-10");
    // $diff=date_diff($date1,$date2);
    // echo $diff->format("%a");

    $startTime = strtotime( '2010-05-01' );
    $endTime = strtotime( '2010-05-3' );
    // Loop between timestamps, 24 hours at a time
    for ( $i = $startTime; $i < $endTime; $i = $i + 86400 ) {
        $thisDate = date( 'Y-m-d', $i );
        echo $thisDate . ' ';
    }
