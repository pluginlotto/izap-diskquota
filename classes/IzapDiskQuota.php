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

class IzapDiskQuota {
  private $max_allowed_space = 1073741824; // Default: 1 GB //
  private $current_user = FALSE;
  private $current_upload_size = 0;
  private $total_size_used = 0;

  public function __construct($user = NULL) {

    // set the user
    if($user instanceof ElggUser) {
      $this->current_user = $user;
    }else if(isloggedin()) {
      $this->current_user = elgg_get_logged_in_user_entity();
    }

    // determine how much a user has used
    $this->total_size_used = (int)$this->current_user->izap_disk_used;

    // check the maximun space for the user
    $user_diskquota = IzapBase::mb2byte($this->current_user->izap_disk_quota);
    if($user_diskquota) {
      $this->max_allowed_space = $user_diskquota;
    }else { // else allow the global_spage
      $global_max_allowed_space =IzapBase::pluginSetting(array(
              'plugin' => GLOBAL_IZAP_DISKQUOTA_PLUGIN,
              'name' => 'izap_allowed_diskspace',
              'value' => 1024,
      ));

      if((int) $global_max_allowed_space) {
        $this->max_allowed_space = IzapBase::mb2byte($global_max_allowed_space);
      }
    }

    // calculate the current upload size
    $this->calculateCurrentUpload();
  }

  public function calculateCurrentUpload() {
    if(sizeof($_FILES)) {    
      $total = 0;
      
      foreach($_FILES as $name => $values) {
        foreach($values as $key => $value) {
          if(!is_array($value)) {

            if($key == 'error') {
              $error = $value;
            }

            if($error == 0 && $key == 'size') {
              $total += $value;
            }

          }else {

            if($key == 'error') {
              foreach($value as $ke => $val) {
                if($val == 0)
                  $good_keys[] = $ke;
              }
            }

            if($key == 'size') {
              foreach($good_keys as $keee)
                $total += $value[$keee];
            }

          }
        }
      }
    }

    if($total > 0) {
      $this->current_upload_size = $total;
    }
  }

  public function getCurrentUploadSize() {
    return (int) $this->current_upload_size;
  }

  public function validate() {
    if(!$this->getCurrentUploadSize()) {
      return TRUE;
    }

    if(!$this->current_user) {
      return FALSE;
    }

    if(($this->total_size_used + $this->current_upload_size) > $this->max_allowed_space) {
      return FALSE;
    }

    $this->current_user->izap_disk_used = $this->total_size_used + $this->current_upload_size;
    return TRUE;
  }

  public function getUserDiskquotaInMB() {
    $space = (float) $this->current_user->izap_disk_quota;
    if(!$space) {
      $space = (float) IzapBase::byteToMb($this->max_allowed_space);
    }

    return $space;
  }

  public function getUserDiskquotaInB() {
    $space =  (float) IzapBase::mb2byte($this->current_user->izap_disk_quota);
    if(!$space) {
      $space = (float) $this->max_allowed_space;
    }

    return $space;
  }

  public function getUserUsedSpaceInMB() {
    return (float) round(IzapBase::byteToMb($this->current_user->izap_disk_used), 2);
  }

  public function getUserUsedSpaceInB() {
    return (float) $this->current_user->izap_disk_used;
  }

  public function getUserUsedSpaceInPercent() {
    $total_used = $this->getUserUsedSpaceInB();
    $allowed_space = $this->getUserDiskquotaInB();

    return (float) round(($total_used / $allowed_space) * 100, 2);
  }

  public function releaseSpace(ElggEntity $entity) {
    $space_used = (int)$entity->izap_diskspace_used;
    if($space_used) {
      $this->current_user->izap_disk_used = $this->current_user->izap_disk_used - $space_used;
    }
  }
}
