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
  private $max_allowed_space = 10737418240; // Default: 10 GB //
  private $current_user = FALSE;
  private $current_upload_size = 0;
  private $total_size_used = 0;

  public function __construct($user = NULL) {

    // set the user
    if($user instanceof ElggUser) {
      $this->current_user = $user;
    }else if(isloggedin()) {
      $this->current_user = get_loggedin_user();
    }

    // determine how much a user has used
    $this->total_size_used = (int)$this->current_user->izap_disk_used;

    // check the maximun space for the user
    $user_diskquota = $this->getUserDiskquotaInB();
    if($user_diskquota) {
      $this->max_allowed_space = $user_diskquota;
    }else { // else allow the global_spage
      $global_max_allowed_space = izap_plugin_settings(array(
              'plugin_name' => GLOBAL_IZAP_DISKQUOTA_PLUGIN,
              'setting_name' => 'izap_allowed_diskspace',
      ));
      
      if((int) $global_max_allowed_space) {
        $this->max_allowed_space = mb2b($global_max_allowed_space);
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

    if($this->total_size_used >= $this->max_allowed_space) {
      return FALSE;
    }

    $this->current_user->izap_disk_used = $this->total_size_used + $this->current_upload_size;
    return TRUE;
  }

  public function getUserDiskquotaInMB() {
    return (float) $this->current_user->izap_disk_quota;
  }
  
  public function getUserDiskquotaInB() {
    return (float) mb2b($this->current_user->izap_disk_quota);
  }

  public function getUserUsedSpaceInMB() {
    return (float) round(b2mb($this->current_user->izap_disk_used), 2);
  }

  public function getUserUsedSpaceInB() {
    return (float) $this->current_user->izap_disk_used;
  }

  public function getUserUsedSpaceInPercent() {
    $total_used = $this->getUserUsedSpaceInB();
    $allowed_space = $this->getUserDiskquotaInB();

    return (float) round(($total_used / $allowed_space) * 100, 2);
  }
  
//
//  public static function getUserUsedSpaceInPercent($user) {
//    $space = self::getUserUsedSpace($user);
//    if($space) {
//      return b2mb($user->total_size_used);
//    }
//
//    return 0;
//  }
}

function mb2b($mb) {
  return 1024*1024*$mb;
}

function b2mb($b) {
  return $b/(1024*1024);
}