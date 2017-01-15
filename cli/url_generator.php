<?php
/**
 * This script can be used to generate a file containing random API urls for load-testing (e.g. using siege or ab)
 */
$count = 1000;
$baseUrl = 'http://localhost/api/';
$baseUrlSecure = 'https://localhost/api/';

$urls = [];
for ($i = 0; $i < $count; $i++) {
    $ip = long2ip(rand(0, pow(2, 32) -1));
    $full = (rand(1,5) === 5) ? '/full' : '';
    $callback = (rand(1, 4) === 4) ? '?callback=foo' : '';
    if (rand(1, 5) === 5) {
        $url = $baseUrlSecure . $ip . $full . $callback;
    } else {
        $url = $baseUrl . $ip . $full . $callback;
    }
    array_push($urls, $url);
}
file_put_contents('aerys.txt', implode("\n", $urls));
echo 'done' . PHP_EOL;