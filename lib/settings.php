<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version {version} $Revision: {revision}
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

global $CONFIG;

/**
 * SOME VARS
 */

$PLUGIN_NAME = GLOBAL_IZAP_DISKQUOTA_PLUGIN;
$PLUGIN_PAGEHANDLER = GLOBAL_IZAP_DISKQUOTA_PAGEHANDLER;
$PLUGIN_ACTIONHANDLER = GLOBAL_IZAP_DISKQUOTA_ACTIONHANDLER;
$INITIAL_URL = 'pg/' . $PLUGIN_PAGEHANDLER;

return array(
        'plugin' => array(
                'name' => $PLUGIN_NAME,
                'url_title' => $PLUGIN_PAGEHANDLER,
                'actions' => array(
                        $PLUGIN_ACTIONHANDLER . '/set_user_diskspace' => array(
                                'file' => 'set_user_diskspace.php',
                                'admin_only' => TRUE,
                        )
                ),
                'events' => array(
                        'create' => array(
                                'object' => array(
                                        'func_izap_diskquota_increment',
                                ),

                                'group' => array(
                                        'func_izap_diskquota_increment',
                                ),
                        ),

                        'update' => array(
                                'object' => array(
                                        'func_izap_diskquota_increment',
                                ),

                                'group' => array(
                                        'func_izap_diskquota_increment',
                                ),
                        ),

                        'delete' => array(
                                'all' => array(
                                        'func_izap_diskquota_decrement',
                                ),
                        ),
                ),

                'extend' => array(
                        'profile/profilelinks' => array(
                                $PLUGIN_NAME . '/forms/user_settings' => array(
                                        'priority' => 1,
                                ),
                        ),
                ),

                'submenu' => array(
                        'admin' => array(
                                $INITIAL_URL . '/settings/' => array(
                                        'title' => elgg_echo('izap-diskquota:admin_settings'),
                                        'admin_only' => TRUE),
                        ),
                ),

        ),

        'includes'=>array(
                dirname(__FILE__) . '/classes' => array('IzapDiskquota.php'),
                dirname(__FILE__) . '/functions' => array('core.php'),
        ),

        'path' => array(
                'www' => array(
                        'page' => $CONFIG->wwwroot . $INITIAL_URL,
                        'images' => $CONFIG->wwwroot . 'mod/'.$PLUGIN_NAME.'/_graphics/',
                        'action' => $CONFIG->wwwroot . 'action/'.$PLUGIN_ACTIONHANDLER.'/',
                ),
                'dir' => array(
                        'plugin' => dirname(dirname(__FILE__)).'/',
                        'actions' => $CONFIG->pluginspath . $PLUGIN_NAME.'/actions/',
                        'class' => dirname(__FILE__).'/classes/',
                        'functions' => dirname(__FILE__).'/functions/',
                        'lib' => dirname(__FILE__) . '/',
                        'views' => array(
                                'home' => $PLUGIN_NAME . '/',
                                'forms' => $PLUGIN_NAME . '/forms/',
                        ),
                ),
        ),
);