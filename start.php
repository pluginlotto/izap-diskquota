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

define('GLOBAL_IZAP_DISKQUOTA_PLUGIN', 'izap-diskspace-quota');
define('GLOBAL_IZAP_DISKQUOTA_PAGEHANDLER', 'diskquota');
define('GLOBAL_IZAP_DISKQUOTA_ACTIONHANDLER', 'izap_diskquota');

function izap_diskquota_init() {
  if(is_plugin_enabled('izap-elgg-bridge')){
    func_init_plugin_byizap(array('plugin' => array('name' => GLOBAL_IZAP_DISKQUOTA_PLUGIN)));
  }else{
    register_error('This Plugin Needs Izap-Elgg-Bridge Plugin');
    disable_plugin(GLOBAL_IZAP_DISKQUOTA_PLUGIN);
  }
}register_elgg_event_handler('init', 'system', 'izap_diskquota_init');