<?php
namespace content\handler;
require_once 'class/user.php';
use class_server\user\user;
use pattern\registry\registry;

require_once 'header.php';
$user_handler=new user();
    if ($_COOKIE['id'] and $_COOKIE['name']) {
        if (!$_COOKIE['status']) {
            $status = $user_handler->handler_user($_COOKIE['id'], $_COOKIE['name']);
            $status = $_COOKIE['status'];
            if ($status === 'client.php') {
                echo '1';
                require_once 'body_client.php';
            } elseif ($status === "realtor") {
                echo '2';
                require_once 'body_realtor.php';
            } else {
                echo '3';
                require_once 'body_false.php';
            }
        }
        } else {
            $status = $_COOKIE['status'];
            if ($status === 'client.php') {
                echo '1';
                require_once 'body_client.php';
            } elseif ($status === "realtor") {
                echo '2';
                require_once 'body_realtor.php';
            } else {
                echo '3';
                require_once 'body_false.php';
            }
        }
require_once 'footer.php';
?>