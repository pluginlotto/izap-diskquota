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
if(!isadminloggedin()) {
  return '';
}
$user = $vars['entity'];
$izap_diskspace = new IzapDiskQuota($user);
ob_start();
?>
<label>
  <?php echo elgg_echo('izap-diskquota:add_space_limit')?>
  <br />
  <?php echo elgg_view('input/text', array(
  'internalname' => 'space',
  'value' => $izap_diskspace->getUserDiskquotaInMB(),
  ));?>
</label>
<?php
echo elgg_view('input/hidden', array(
'value' => $user->guid,
'internalname' => 'user_guid',
));
$form = ob_get_clean();
$form = elgg_view('input/form', array(
        'body' => $form,
        'action' => func_get_actions_path_byizap(array(
                'plugin' => GLOBAL_IZAP_DISKQUOTA_PLUGIN,
                )) . 'set_user_diskspace',
));
?>
<div>
  <?php echo $form;?>
</div>