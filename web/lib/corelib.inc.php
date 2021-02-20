<?

class Core
{

    public $admin_php = 'index.php';
    public $admin_tpl = 'index.tpl';
    public $default_tpl = 'home.tpl';
    public $login_tpl = 'login.tpl';
    public $smarty_class_path = './smarty/Smarty.class.php';
    public $smarty_compile_dir = './smarty/templates_c/';
    public $smarty_config_dir = './smarty/config/';
    public $smarty_cache_dir = './smarty/cache/';
    public $smarty_pages_dir = '../pages/';
    public $files_dir = './files/';
    public $fotos_dir = './fotos/';
    public $img_admin_dir = './img/';
    public $inc_admin_dir = './include/';
    public $js_dir = './js/';
    public $stylesheet_dir = './css/';
    public $thumbs_dir = './fotos/thumbs/';
    public $tpl_admin_dir = './templates/';
    public $configuration = null;
    public $zones = null;
    public $smarty = null;
    public $roles = null;
    private $root_role = 'developer';
    public $debugs = array();
    private $error = 0;

    public function __construct($conf)
    {
        if ($conf != null) {
            $this->configuration = $conf;
            $this->init();
            $this->initsmarty();
            $this->initdeps();
        }
    }

    private function init()
    {
        setlocale(LC_ALL, '');
        ini_set('memory_limit', $this->configuration['global']['memory_limit']);
        date_default_timezone_set($this->configuration['global']['timezone']);
        $this->configuration['admin']['major'] = (date("y") - 9);
        $this->configuration['admin']['minor'] = '.' . date("m");
        $this->configuration['global']['copyright'] = $this->configuration['global']['copyright'] . '-' . date("Y");
        $this->configuration['admin']['copyright'] = $this->configuration['admin']['copyright'] . '-' . date("Y");
    }

    public function getConnection($props)
    {
        $timeout = microtime();
        $conn = new SQLite3('db/schema.sqlite');
        $this->debugTimeout('SQLITE CONNECT', $timeout, 5);
        return $conn;
    }

    public function executeQuery($conn, $query, $debug = 0)
    {
        //print_r($query);
        $timeout = microtime();
        $result = $conn->query($query);
        $data = array();
        while ($row = $result->fetchArray()) {
            $data[] = $row;
        }
        $this->debugTimeout('EXECUTE', $timeout, 5);
        $this->debugQuery($query, $data, $debug);
        return $data;
    }

    public function closeConnection($conn)
    {
        $conn->close();
    }

    private function debugTimeout($descr, $timeout, $limit)
    {
        $tmp = explode(" ", microtime());
        $end = $tmp[0] + $tmp[1];
        $tmp = explode(" ", $timeout);
        $start = $tmp[0] + $tmp[1];
        $timeout = round($end - $start, 2);
        if ($timeout < $limit) {
            $this->debugs[] = $descr . ' TIMEOUT: ' . $timeout . 's';
        } else {
            $this->debugs[] = '<span style="color:red;">' . $descr . ' TIMEOUT: ' . $timeout . 's</span>';
        }
    }

    private function debugQuery($query, $data, $debug)
    {
        if (count($data) == 0) {
            $data[0] = 'No rows found';
        }
        $trace = 'QUERY:<b>' . $query . '</b><br/>';
        $trace .= 'FIRST ROW:';
        $trace .= '<pre>';
        $trace .= print_r($debug > 1 ? $data : $data[0], true);
        $trace .= '</pre>';
        $this->debugs[] = $trace;
    }

    private function initsmarty()
    {
        require_once($this->smarty_class_path);
        $this->smarty = new Smarty;
        $this->smarty->compile_dir = $this->smarty_compile_dir;
        $this->smarty->config_dir = $this->smarty_config_dir;
        $this->smarty->cache_dir = $this->smarty_cache_dir;
        $this->smarty->template_dir = $this->tpl_admin_dir;
        $this->smarty->assign('const', $this->configuration);
        $this->smarty->assign('factory', $this);
    }

    private function initdeps()
    {
        $root = parse_ini_file($this->configuration['admin']['adminconf'], true);
        for ($i = 0; $i < count($root['root']['zone']); $i++) {
            $this->zones[$i]['name'] = $root['root']['zone'][$i];
            $zone = $root[$root['root']['zone'][$i]];
            $this->zones[$i]['descr'] = $zone['descr'];
            $props = $root[$this->zones[$i]['name']];
            unset($props['name']);
            unset($props['descr']);
            unset($props['dep']);
            for ($j = 0; $j < count($zone['dep']); $j++) {
                $this->zones[$i]['dep'][$j]['name'] = $zone['dep'][$j];
                $this->zones[$i]['dep'][$j]['descr'] = $root[$zone['dep'][$j]]['descr'];
                $this->zones[$i]['dep'][$j]['access'] = $root[$zone['dep'][$j]]['access'];
                $this->zones[$i]['dep'][$j]['incl'] = $this->zones[$i]['dep'][$j]['name'] . '.php';
                $this->zones[$i]['dep'][$j]['icon'] = 'admin_' . $this->zones[$i]['dep'][$j]['name'] . '.gif';
                $this->zones[$i]['dep'][$j]['tpl'] = $this->zones[$i]['dep'][$j]['name'] . '.tpl';
                $this->zones[$i]['dep'][$j]['props'] = $props;
            }
        }
        $this->smarty->assign('zones', $this->zones);
        $this->root_role = $root['roles']['root'];
        $this->guest_role = $root['roles']['guest'];
        $roles = $root['roles']['role'];
        for ($i = 0; $i < count($roles); $i++) {
            $this->roles[$roles[$i]]['name'] = $roles[$i];
            $this->roles[$roles[$i]]['login'] = $root[$roles[$i]]['login'];
            $this->roles[$roles[$i]]['descr'] = $root[$roles[$i]]['descr'];
            $this->roles[$roles[$i]]['email'] = 'XXXXXXX';
        }
    }

    public function applypolicy($login, $zone)
    {
        $deps = $this->getdeps($zone);
        for ($i = 0; $i < count($deps); $i++) {
            if (($deps[$i]['access'] != '') && (strpos($deps[$i]['access'], $login) === false)) {
                $deps[$i]['access'] = 1;
            } else {
                $deps[$i]['access'] = 0;
            }
        }
        $this->smarty->assign('deps', $deps);
        $this->smarty->assign('currentrole', $this->roles[$login]);
    }

    public function customErrorHandler($errno, $errstr, $errfile, $errline)
    {
        global $CORE;
        $CORE->errorHandler($errno, $errstr, $errfile, $errline);
        return true;
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if ($errno < $this->configuration['admin']['debug']) {
            echo '#' . $errno . ':' . $errstr . '<br/>[' . $errfile . '@' . $errline . '] E' . $this->error . '<br/>';
        }
        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_WARNING:
                $this->error++;
                $this->smarty->assign('mess', $errstr . '<br/>[' . $errfile . '@' . $errline . '] E' . $this->error);
                $this->debugs[] = '#' . $errno . ':' . $errstr . '<br/>[' . $errfile . '@' . $errline . '] E' . $this->error . '<br/>';
                break;

            case E_NOTICE:
                break;

            default:
                if ($errno < 128) {
                    echo "Unknown error type: [$errno] $errstr<br />\n";
                }
                break;
        }
    }

    public function debugRequestAndSession()
    {
        $trace = 'REQUEST:';
        $trace .= '<pre>';
        $trace .= print_r($_REQUEST, true);
        $trace .= '</pre>';
        $this->debugs[] = $trace;

        $trace = 'SESSION:';
        $trace .= '<pre>';
        $trace .= print_r($_SESSION, true);
        $trace .= '</pre>';
        $this->debugs[] = $trace;
    }

    public function timestamp2date($time)
    {
        return date($this->configuration['global']['dateformat'], $time);
    }

    function sendmail($sender, $email, $subject, $body)
    {
        //TODO move to site.ini
        $headers = "From: " . $sender . "\n" .
            "Content-Type: text/html; charset=windows-1251\n" .
            "X-Mailer: PHP/" . phpversion();
        mail($email, $subject, $body, $headers);
    }

    function fromutf($str)
    {
        return iconv('UTF-8', $this->configuration['global']['encoding'], $str);
    }

    function toutf($str)
    {
        return iconv($this->configuration['global']['encoding'], 'UTF-8', $str);
    }


    function getzones()
    {
        return $this->zones;
    }

    function getdeps($zone)
    {
        for ($i = 0; $i < count($this->zones); $i++) {
            if ($this->zones[$i]['name'] == $zone || $zone == '') {
                $this->smarty->assign('deps', $this->zones[$i]['dep']);
                $_SESSION['zone'] = $this->zones[$i]['name'];
                return $this->zones[$i]['dep'];
            }
        }
        return $this->getdeps('');
    }

    function getcurrentdep($zone, $dep)
    {
        $deps = $this->getdeps($zone);
        for ($i = 0; $i < count($deps); $i++)
            if ($deps[$i]['name'] == $dep || $dep == '') {
                $this->smarty->assign('currentdep', $deps[$i]);
                return $deps[$i];
            }
        return $this->getcurrentdep($zone, '');
    }

}

?>
