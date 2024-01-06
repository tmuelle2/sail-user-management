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
    'primaryContact' => '%s',
    'dob' => '%s',
    'city' => '%s',
    'state' => '%s',
    'firstName' => '%s',
    'lastName' => '%s',
    'referenceRelation' => '%s'
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
    $params = $request->get_json_params();

    if ( isset($params['profilePicture']) && !empty($params['profilePicture']) && strlen($params['profilePicture']) > 21)
    {
      $getType1 = explode(',', $params['profilePicture']);
      $getType2 = explode(';', $getType1[0]);
      $getType3 = explode('/', $getType2[0]);
      $fileExt = "";
      if (count($getType3) > 1) {
        $fileExt = "." . $getType3[1];
      }
      else {
        $fileExt = ".jpeg";
      }

      $filename = uniqid() . $fileExt;
      $filepath = wp_upload_dir()['basedir'] . "/profilePictures" .  "/";
      $outputPath = $filepath . $filename ;

      $result = file_put_contents($outputPath, file_get_contents($params['profilePicture']));

      if ($result > 0) {
        return $this->merge(['profilePicture' => wp_upload_dir()['baseurl'] . "/profilePictures/" . $filename ]);
      } else {
        $this->log('Error occurred saving profile picture: ' . $filename);
        return $this->merge(['profilePicture' => $oldProfilePicture]);
      }
    }
    else {
      //$this->log("No pfp provided, using old value.");
      return $this->merge(['profilePicture' => $oldProfilePicture]);
    }
    return $this;
  }
}
