<?
require_once('./lib/corelib.inc.php');
require_once('./lib/mathlib.inc.php');

//error_reporting(E_ALL);

$siteconfig = './config/site.ini';

$CORE = new Core(parse_ini_file($siteconfig, true));
$VIEW = $CORE->smarty;
set_error_handler(array($CORE, 'customErrorHandler'));

/* * * Session params block ** */
if (isset($_REQUEST['appid'])) {
    $_SESSION['appid'] = trim($_REQUEST['appid']);
}

$VIEW->assign("appid", $_SESSION['appid']);

if (isset($_REQUEST['zone'])) {
    $_SESSION['zone'] = $_REQUEST['zone'];
}

$old = $_SESSION[$_SESSION['zone'] . '_recent'];
if ($old[0] != $_SESSION['appid']) {
    $new = array();
    $new[0] = $_SESSION['appid'];
    $exist = false;
    for ($i = 1; $i < 7; $i++) {
        if ($old[$i - 1] == $new[0]) {
            $exist = true;
        }
        $new[$i] = $exist == true ? $old[$i] : $old[$i - 1];
    }
    $_SESSION[$_SESSION['zone'] . '_recent'] = $new;
}
$VIEW->assign("recent", $_SESSION[$_SESSION['zone'] . '_recent']);

if (isset($_REQUEST['dep'])) {
    $_SESSION['dep'] = $_REQUEST['dep'];
}
/* * * End of session params block ** */

$currentdep = $CORE->getcurrentdep($_SESSION['zone'], $_SESSION['dep']);
$_SESSION['dep'] = $currentdep['name'];

/* * * Temprary debug ** */
try {
    if (!file_exists($CORE->inc_admin_dir . $currentdep['incl'])) {
        $CORE->errorHandler(E_ERROR, 'File not found -' . $CORE->inc_admin_dir . $currentdep['incl'], 'admin.php', 57);
    } else {
        include_once($CORE->inc_admin_dir . $currentdep['incl']);
    }
    $VIEW->assign('page', $currentdep['tpl']);
    if (!file_exists($CORE->tpl_admin_dir . $currentdep['tpl'])) {
        $CORE->errorHandler(E_ERROR, 'File not found -' . $CORE->tpl_admin_dir . $currentdep['tpl'], 'admin.php', 60);
        $currentdep['tpl'] = $CORE->default_tpl;
    }
} catch (Exception $e) {
    $CORE->errorHandler(E_ERROR, $e->getMessage(), $e->getFile(), $e->getLine());
}
/* * * End of Temprary debug ** */

$template = $CORE->admin_tpl;
$CORE->applypolicy($_SESSION['login'], $_SESSION['zone']);
$VIEW->assign('dep', $_SESSION['dep']);
$VIEW->assign('zone', $_SESSION['zone']);
if ($_REQUEST['mode'] == 'async') {
    if ($_REQUEST['oper'] == 'xls') {
        header("Content-Type:  application/vnd.ms-excel; charset=".$CORE->configuration['global']['encodingHTML']);
        header("Content-Disposition: attachment; filename=" . $_SESSION['zone'] . "_" . $_SESSION['dep'] . "_" . time() . ".xls");
    } else if ($_REQUEST['oper'] == 'doc') {
		header("Content-Type:  application/vnd.ms-word; charset=".$CORE->configuration['global']['encodingHTML']);
        header("Content-Disposition: attachment; filename=" . $_SESSION['zone'] . "_" . $_SESSION['dep'] . "_" . time() . ".doc");
    } else {
		//json
		header("Content-Type:  application/json; charset=".$CORE->configuration['global']['encodingHTML']);
		$template = 'json.tpl';
    }
} else {
    //$CORE->log_access();
}

$VIEW->display($template);
//print_r($CORE->debugs);
?>

