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

function func_izap_diskquota_increment($event, $object_type, $object) {
    // subtypes to skip
    $array = array('plugin');
    if(in_array($object->getSubtype(), $array)) {
        return TRUE;
    }

    $izap_disk_quota = new IzapDiskQuota($object->getOwnerEntity());
    $return = $izap_disk_quota->validate();
    if(!$return) {
        register_error(elgg_echo('izap-diskquota:limt_up'));
    }

    // save file size if any with this object
    if($return) {
        $object->izap_diskspace_used = $izap_disk_quota->getCurrentUploadSize();
    }
    return $return;
}

function func_izap_diskquota_decrement($event, $object_type, $object) {
    $izap_disk_quota = new IzapDiskQuota($object->getOwnerEntity());
    $izap_disk_quota->releaseSpace($object);
}