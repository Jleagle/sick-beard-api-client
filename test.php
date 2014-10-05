<?php
include('vendor/autoload.php');

$sb = new \Jleagle\SickBeard\SickBeard(
  'http://jimeagle.no-ip.org:8081',
  'a4676f416d63cbf7bf44d3ec53a5e710'
);

//print_r($sb->show_seasonlist(268592));
//print_r($sb->shows('id'));
echo $sb->show_getbanner(268592);
