<?php
require_once 'models/Reports.class.php';

// https://gist.github.com/rchrd2/c94eb4701da57ce9a0ad4d2b00794131
function require_auth() 
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

function listrss_action($cont,$twig,$message)
{
    $reports = $cont->get_rss();
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
    fseek($xmlfile, 0); // On remet le curseur au début du fichier
    fputs($xmlfile, $content); // On écrit le nouveau nombre de pages vues
    fclose($xmlfile);
}

function listpublic_action($cont,$twig,$message)
{
    $reports = $cont->get_public();
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

function listlast_action($cont,$twig,$message,$now)
{
    $reports = $cont->get_last_reports();
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

function listall_action($cont,$twig,$message,$now)
{
    $reports = $cont->get_all_reports();
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

function detail_action($cont,$twig,$now,$id,$message='')
{
    $report = $cont->get_report_by_id($id);
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

function suppr_action($cont,$id)
{
    return ($cont->delete_report_by_id($id));
}
function suppr_description_action($cont, $id)
{
    return ($cont->delete_description_by_id($id));
}

function update_action($cont,$id,$date,$page_title,$maindescription,$state)
{
    return ($cont->update($id, $date, $page_title, $maindescription, $state));
}

function add_action($cont,$report)
{
    return ($cont->add_report($report));
}
function add_description_action($cont,$report)
{
    return ($cont->add_description_report($report));
}
