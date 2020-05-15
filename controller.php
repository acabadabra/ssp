<?php
require_once 'models/Reports.class.php';
$cont = new Reports;
require 'vendor/autoload.php';
// Templates location
$loader = new Twig\Loader\FilesystemLoader('templates');
// On your mark Twig !
$twig = new Twig\Environment($loader);

require 'controller.inc.php';
// Checking actions and parameters
// default to listpublic
$action = $_GET['action'] ?? 'listpublic';
$message = "";
$now = date("Y-m-d H:i:s");
switch ($action) {
case "listpublic":
    listpublic_action($cont, $twig, $message);
    break;
case "listrss":
    listrss_action($cont, $twig, $message);
    break;
case "listlast":
    require_auth();
    listlast_action($cont, $twig, $message, $now);
    break;
case "listall":
    require_auth();
    listall_action($cont, $twig, $message, $now);
    break;
case "detail":
    require_auth();
    detail_action($cont, $twig, $now, $_GET['id']);
    break;
case "suppr":
    require_auth();
    if (suppr_action($cont, $_GET['id'])) {
        $message = "Report successfully removed ! !";
    } else { $message = "Something went wrong !";
    }
    listlast_action($cont, $twig, $message, $now);
    listrss_action($cont, $twig, $message);
    break;
case "suppr_description":
    require_auth();
    if (suppr_description_action($cont, $_GET['id'])) {
        $message = "Report detail successfully removed ! !";
    } else { $message = "Something went wrong !";
    }
    listlast_action($cont, $twig, $message, $now);
    listrss_action($cont, $twig, $message);
    break;
case "update":
    require_auth();
    if (!empty($_GET['id']) and !empty($_GET['date']) and !empty($_GET['title']) and !empty($_GET['maindescription'])) {
        $res = update_action($cont, $_GET['id'], $_GET['date'], $_GET['title'], $_GET['maindescription'], $_GET['state']);
    }
    if (!empty($res)) {
        $message = "Report successfully updated !";
    } else {
        $message = "Something went wrong !";
    }
    listlast_action($cont, $twig, $message, $now);
    listrss_action($cont, $twig, $message);
    break;
case "add":
    require_auth();
    if (add_action($cont, $_GET)) {
        $message = "Report successfully added !";
    } else { $message = "Something went wrong !";
    }
    listlast_action($cont, $twig, $message, $now);
    listrss_action($cont, $twig, $message);
    break;
case "add_description":
    require_auth();
    if (add_description_action($cont, $_GET)) {
        $message = "Report detail successfully added !";
    } else { $message = "Something went wrong !";
    }
    listlast_action($cont, $twig, $message, $now);
    listrss_action($cont, $twig, $message);
    break;
default:
    listpublic_action($cont, $twig, $message, $now);
}
