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
ob_start();
?>
<p>
  <label>
    <?php echo elgg_echo('izap-diskquota:max_allowed_space_per_user');?>
    <br />
    <?php echo elgg_view('input/text', array(
    'internalname' => 'params[izap_allowed_diskspace]',
    'value' => get_plugin_setting('izap_allowed_diskspace', GLOBAL_IZAP_DISKQUOTA_PLUGIN),
    ));?>
  </label>
</p>
<?php
echo elgg_view('input/hidden', array(
'internalname' => 'params[plugin_name]',
'value' => GLOBAL_IZAP_DISKQUOTA_PLUGIN,
));
echo elgg_view('input/submit', array(
'value' => elgg_echo('izap-diskquota:save_settings'),
));
$form = ob_get_clean();
$form = elgg_view('input/form', array(
        'body' => $form,
        'action' => func_get_actions_path_byizap(array('plugin' => GLOBAL_BRIDGE_PLUGIN)) . 'plugin_settings',
));
?>
<div class="contentWrapper">
  <?php echo $form;?>
</div>
