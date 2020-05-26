<?php
require_once 'models/Reports.class.php';
$cont = new Reports;
require 'vendor/autoload.php';
// Templates location
$loader = new Twig\Loader\FilesystemLoader('templates');
// On your mark Twig !
$twig = new Twig\Environment($loader);

require 'controllers/controller.inc.php';
// Checking actions and parameters
// default to Listpublic
$action = $_GET['action'] ?? 'listpublic';
$message = "";
$now = date("Y-m-d H:i:s");
switch ($action) {
case "listpublic":
    Listpublic_action($cont, $twig, $message);
    break;
case "listrss":
    Listrss_action($cont, $twig, $message);
    break;
case "listlast":
    Require_auth();
    Listlast_action($cont, $twig, $message, $now);
    break;
case "listall":
    Require_auth();
    Listall_action($cont, $twig, $message, $now);
    break;
case "detail":
    Require_auth();
    Detail_action($cont, $twig, $now, $_GET['id']);
    break;
case "suppr":
    Require_auth();
    if (Suppr_action($cont, $_GET['id'])) {
        $message = "Report successfully removed ! !";
    } else { $message = "Something went wrong !";
    }
    Listlast_action($cont, $twig, $message, $now);
    Listrss_action($cont, $twig, $message);
    break;
case "suppr_description":
    Require_auth();
    if (Suppr_Description_action($cont, $_GET['id'])) {
        $message = "Report detail successfully removed ! !";
    } else { $message = "Something went wrong !";
    }
    Listlast_action($cont, $twig, $message, $now);
    Listrss_action($cont, $twig, $message);
    break;
case "update":
    Require_auth();
    if (!empty($_GET['id']) and !empty($_GET['date']) and !empty($_GET['title']) and !empty($_GET['maindescription'])) {
        $res = Update_action($cont, $_GET['id'], $_GET['date'], $_GET['title'], $_GET['maindescription'], $_GET['state']);
    }
    if (!empty($res)) {
        $message = "Report successfully updated !";
    } else {
        $message = "Something went wrong !";
    }
    Listlast_action($cont, $twig, $message, $now);
    Listrss_action($cont, $twig, $message);
    break;
case "add":
    Require_auth();
    if (Add_action($cont, $_GET)) {
        $message = "Report successfully added !";
    } else { $message = "Something went wrong !";
    }
    Listlast_action($cont, $twig, $message, $now);
    Listrss_action($cont, $twig, $message);
    break;
case "add_description":
    Require_auth();
    if (Add_Description_action($cont, $_GET)) {
        $message = "Report detail successfully added !";
    } else { $message = "Something went wrong !";
    }
    Listlast_action($cont, $twig, $message, $now);
    Listrss_action($cont, $twig, $message);
    break;
default:
    Listpublic_action($cont, $twig, $message, $now);
}
