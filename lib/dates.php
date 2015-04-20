<?php
function month2Name($m)
{
    $mnames[1] = 'Jan';
    $mnames[2] = 'Feb';
    $mnames[3] = 'Mar';
    $mnames[4] = 'Apr';
    $mnames[5] = 'May';
    $mnames[6] = 'Jun';
    $mnames[7] = 'Jul';
    $mnames[8] = 'Aug';
    $mnames[9] = 'Sep';
    $mnames[10] = 'Oct';
    $mnames[11] = 'Nov';
    $mnames[12] = 'Dec';

    return $mnames[$m];
}

function name2Month($m)
{
    $mnames['Jan'] = 1;
    $mnames['Feb'] = 2;
    $mnames['Mar'] = 3;
    $mnames['Apr'] = 4;
    $mnames['May'] = 5;
    $mnames['Jun'] = 6;
    $mnames['Jul'] = 7;
    $mnames['Aug'] = 8;
    $mnames['Sep'] = 9;
    $mnames['Oct'] = 10;
    $mnames['Nov'] = 11;
    $mnames['Dec'] = 12;

    return $mnames[$m];
}

define('T_INT_SECOND', 1);
define('T_INT_MINUTE', 60);
define('T_INT_HOUR', 3600);
define('T_INT_DAY', 86400);
define('T_INT_WEEK', 604800);

function diff_in_days($startts, $endts)
{
  return (int)(($endts - $startts)/T_INT_DAY)+1;
}
?>