<?php

namespace Sail\Data\Model;

use Sail\Utils\Logger;
use WP_REST_Request;

class FriendshipConnectProfile extends SailDataObject
{
  use Logger;

  // Mapping of field to database format
  private const FC_DB_FIELDS = array(
    'userId' => '%s',
    'authorized' => '%s',
    'profilePicture' => '%s',
    'gender' => '%s',
    'namePreference' => '%s',
    'nickname' => '%s',
    'primaryContactType' => '%s',
    'activities' => '%s',
    'hobbies' => '%s',
    'typicalDay' => '%s',
    'strengths' => '%s',
    'makesYouHappy' => '%s',
    'lifesVision' => '%s',
    'supportRequirements' => '%s',
    'referenceName' => '%s',
    'referencePhoneNumber' => '%s',
    'referenceEmail' => '%s',
    'primaryContact' => '%s'
  );

  public function __construct(array $data)
  {
    parent::__construct($data);
  }

  public static function fieldKeys(): array
  {
    return self::FC_DB_FIELDS;
  }

  public function updateProfilePic(WP_REST_Request $request, string $oldProfilePicture): FriendshipConnectProfile
  {
    $files = $request->get_file_params();

    // TODO: check if $this->profilePicture is a base64 string and if so create the $file object that wp_upload_bits is expecting
    // see here: https://stackoverflow.com/questions/63860300/getting-error-specified-file-failed-upload-test-when-adding-file-from-base64-d
    // and here: https://developer.wordpress.org/reference/functions/wp_upload_bits/
    if (
      isset($files['profilePicture']) && isset($files['profilePicture']['name']) && isset($files['profilePicture']['name'])
      && !empty($files['profilePicture']['name']) && !empty($files['profilePicture']['name'])
    ) {
      $nameFile = $files['profilePicture']['name'];
      $tmpName = $files['profilePicture']['tmp_name'];
      $upload = wp_upload_bits($nameFile, null, file_get_contents($tmpName));

      if (!$upload['error']) {
        return $this->merge(['profilePicture' => $upload['url']]);
      } else {
        $this->log('Error occurred saving profile picture: ' . $upload['error']);
      }
    }
    else {
      return $this->merge(['profilePicture' => $oldProfilePicture]);
    }
    return $this;
  }
}