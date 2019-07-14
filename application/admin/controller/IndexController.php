<?php
namespace app\admin\controller;

use think\Db;
use app\admin\model\AdminMenu;

class IndexController extends AdminBaseController
{

    public function index()
    {
    	$menuModel = new AdminMenu();
    	$menus = $menuModel->menuTree(); // 获取菜单
        $this->assign('menus', $menus);
        $this->assign('menu_label', fetch_setting_by_key('menu_label'));
        return $this->fetch();
    }

    public function welcome()
    {
        $server_info = array(
            lang('SYSTEM_TYPE_VERSION')  =>  php_uname(), // 例：Windows NT COMPUTER 5.1 build 2600
            lang('OPERATING_SYSTEM')     => PHP_OS, // php_uname('s')
            lang('SYSTEM_VERSION')       => php_uname('r'),
            lang('RUNTIME_ENVIRONMENT')  => $_SERVER["SERVER_SOFTWARE"],
            lang('HOST_NAME')            => $_SERVER['SERVER_NAME'],
            lang('WEB_SERVER_PORT')      => $_SERVER['SERVER_PORT'],
            lang('WEB_DOC_CATALOG')      => $_SERVER["DOCUMENT_ROOT"],
            lang('BROWSER_IMFORMATION')  => substr($_SERVER['HTTP_USER_AGENT'], 0, 40),
            lang('PHP_OPERATION_MODE')   => php_sapi_name(), // PHP run mode：apache2handler
            lang('PROTOCOL')             => $_SERVER['SERVER_PROTOCOL'],
            lang('ZEND_VERSION')         => Zend_Version(),
            lang('PHP_VERSION')          => PHP_VERSION,
            lang('PHP_INSTALLATION_PATH') => str_replace(array('.', ':'), '', DEFAULT_INCLUDE_PATH),
            lang('THINKPHP_VERSION')     => THINK_VERSION,
            lang('UPLOAD_LIMIT')         => ini_get('upload_max_filesize'),
            lang('EXECUTION_TIME_LIMIT') => ini_get('max_execution_time').'秒',
            lang('SERVER_TIME')          => date("Y年n月j日 H:i:s"),
            lang('BEIJING_TIME')         => gmdate("Y年n月j日 H:i:s",time()+8*3600),
            lang('DOMAIN_NAME')          => $_SERVER['SERVER_NAME'].' [ '.GetHostByName($_SERVER['SERVER_NAME']).' ]',
            lang('CLIENT_IP_ADDRESS')    => $_SERVER['REMOTE_ADDR'],
            lang('HTTP_REQUEST_HOST')    => $_SERVER['HTTP_HOST'], // 域名或IP
            lang('SERVER_SYSTEM_DIRECTORY')  => $_SERVER['DOCUMENT_ROOT'],
            lang('CURRENT_PROCESS')      => Get_Current_User(),
            lang('SERVER_LANGUAGE')      => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            lang('FREE_SPACE')           => round((disk_free_space(".")/(1024*1024)),2).'M',
        );
        $this->assign('server', $server_info);
        $this->assign('develop_log', $this->getDevLog());
        $this->assign('web_version', $this->web_version);
    	return $this->fetch();
    }

    /**
     * 更新数据表的自增
     */
    public function renew_auto_increment()
    {
        $tablesList = db()->query('show tables');
        $tables = array_column($tablesList, 'Tables_in_community');
        foreach ($tables as $tableName) {
            db()->execute('alter  table ' . $tableName . ' auto_increment = 0');
        }
    }
}