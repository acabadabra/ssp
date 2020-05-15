<?php
require_once 'models/Reports.class.php';

/**
* https://gist.github.com/rchrd2/c94eb4701da57ce9a0ad4d2b00794131
*/
function Require_auth()
{
    $AUTH_USER = 'admin';
    $AUTH_PASS = 'admin';
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (
    !$has_supplied_credentials ||
    $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
    $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
    );
    if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        exit;
    }
}

function Listrss_action($cont,$twig,$message)
{
    $reports = $cont->Get_rss();
    $template = $twig->load('feed.xml.twig');
    $page_title="acab.io::status feed";
    $page_url="https://status.acab.io";
    $content = $template->render(
        array(
        'page_title' => $page_title,
        'page_url' => $page_url,
        'reports' => $reports,
        'bla' => $reports,
        'message' => $message,
        )
    );
    $xmlfile = fopen('rss.xml', 'r+');
    fseek($xmlfile, 0);
    fputs($xmlfile, $content);
    fclose($xmlfile);
}

function Listpublic_action($cont,$twig,$message)
{
    $reports = $cont->Get_public();
    $template = $twig->load('public.html.twig');
    $page_title="Last 5 reports";
    echo $template->render(
        array(
        'page_title' => $page_title,
        'reports' => $reports,
        'message' => $message
        )
    );
}

function Listlast_action($cont,$twig,$message,$now)
{
    $reports = $cont->Get_last_reports();
    $template = $twig->load('reports.html.twig');
    $page_title="Last 5 reports";
    echo $template->render(
        array(
        'page_title' => $page_title,
        'reports' => $reports,
        'message' => $message,
        'now' => $now
        )
    );
}

function Listall_action($cont,$twig,$message,$now)
{
    $reports = $cont->Get_all_reports();
    $template = $twig->load('reports.html.twig');
    $page_title="All reports";
    echo $template->render(
        array(
        'page_title' => $page_title,
        'reports' => $reports,
        'message' => $message,
        'now' => $now
        )
    );
}

function Detail_action($cont,$twig,$now,$id,$message='')
{
    $report = $cont->Get_report_by_id($id);
    $template = $twig->load('report.html.twig');
    $page_title="Update report";
    echo $template->render(
        array(
        'page_title' => $page_title,
        'report' => $report,
        'now' => $now,
        'message' => $message
        )
    );
}

function Suppr_action($cont,$id)
{
    return ($cont->Delete_report_by_id($id));
}
function Suppr_Description_action($cont, $id)
{
    return ($cont->Delete_description_by_id($id));
}

function Update_action($cont,$id,$date,$page_title,$maindescription,$state)
{
    return ($cont->Update($id, $date, $page_title, $maindescription, $state));
}

function Add_action($cont,$report)
{
    return ($cont->Add_report($report));
}
function Add_Description_action($cont,$report)
{
    return ($cont->Add_description_report($report));
}
