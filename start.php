<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


define('GLOBAL_IZAP_DISKQUOTA_PLUGIN', 'izap-disk-quota');
define('GLOBAL_IZAP_DISKQUOTA_PAGEHANDLER', 'diskquota');

elgg_register_event_handler('init', 'system', 'func_disk_quota_init');

function func_disk_quota_init() {
    global $CONFIG;
    if (elgg_is_active_plugin(GLOBAL_IZAP_ELGG_BRIDGE)) {
    izap_plugin_init(GLOBAL_IZAP_DISKQUOTA_PLUGIN);
  } else {
    register_error('This plugin needs izap-elgg-bridge');
    disable_plugin(GLOBAL_IZAP_CONTEST_PLUGIN);
  }
    
    elgg_register_page_handler(GLOBAL_IZAP_ANTISPAM_PAGEHANDLER, GLOBAL_IZAP_PAGEHANDLER);

    elgg_register_event_handler('create', 'object', 'func_izap_diskquota_increment');
    elgg_register_event_handler('create', 'group', 'func_izap_diskquota_increment');
    elgg_register_event_handler('update', 'object', 'func_izap_diskquota_decrement');
    elgg_register_event_handler('update', 'object', 'func_izap_diskquota_increment');
    elgg_register_event_handler('update', 'group', 'func_izap_diskquota_increment');
    elgg_register_event_handler('delete', 'object', 'func_izap_diskquota_decrement');
    elgg_register_event_handler('delete', 'group', 'func_izap_diskquota_decrement');
   
   elgg_extend_view('icon/user/default', GLOBAL_IZAP_DISKQUOTA_PLUGIN.'/forms/user_settings');
   elgg_extend_view('icon/user/default', GLOBAL_IZAP_DISKQUOTA_PLUGIN.'/user_status_profile');
   elgg_extend_view('page/elements/sidebar',GLOBAL_IZAP_DISKQUOTA_PLUGIN.'/user_status_sidebar');
}

//function func_diskquota_on_acivation() {
//    return true;
//    global $CONFIG;
//    forward($CONFIG->wwwroot . '/pg/admin/plugin_settings/' . GLOBAL_IZAP_DISKQUOTA_PLUGIN);
//}


function func_izap_diskquota_increment($event, $object_type, $object) {
    // Final check for the right object
    if (!method_exists($object, 'getOwnerEntity')) {
        return TRUE;
    }

    // subtypes to skip
    $array = array('plugin');
    if (in_array($object->getSubtype(), $array)) {
        return TRUE;
    }

    $izap_disk_quota = new IzapDiskQuota($object->getOwnerEntity());
    $return = $izap_disk_quota->validate();
    if (!$return) {
        register_error(elgg_echo('izap-diskquota:limt_up'));
    }

    // save file size if any with this object
    if ($return) {
        $object->izap_diskspace_used = $izap_disk_quota->getCurrentUploadSize();
    }
    return $return;
}

function func_izap_diskquota_decrement($event, $object_type, $object) {
    // Final check for the right object
    if (!method_exists($object, 'getOwnerEntity')) {
        return TRUE;
    }

    $izap_disk_quota = new IzapDiskQuota($object->getOwnerEntity());
    $izap_disk_quota->releaseSpace($object);
}