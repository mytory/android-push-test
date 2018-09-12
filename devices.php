<?php
include 'vendor/autoload.php';
require 'Config.php';
require 'Push.php';

use Mytory\AndroidPushTest\Config;
use \Mytory\AndroidPushTest\Push;

//========= validation ==========

if (!Config::FCM_KEY) {
    fwrite(STDERR, CLI\Style::red("You must set FCM_KEY in Config.php."));
    die(1);
}

if (count(Config::$target_tokens) === 0) {
    fwrite(STDERR, CLI\Style::red("Specify target tokens in Config.php."));
    die(1);
}

if ($argc === 1) {
    fwrite(STDERR, CLI\Style::yellow("ex) {$argv[0]} text"));
    die(1);
}

// ========= push ============
try {

    $response = Push::push($argv[1]);

} catch (Exception $e) {

    fwrite(STDERR, CLI\Style::yellow("ex) {$argv[0]} text"));
    CLI\Output::line($e->getTraceAsString());
    die(1);

}

print_r($response);
CLI\Output::line(CLI\Style::green('Done.'));


