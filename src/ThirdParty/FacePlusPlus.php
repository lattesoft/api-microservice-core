<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/9/2018
 * Time: 3:21 PM
 */

namespace Lattesoft\ApiMocroserviceCore\ThirdParty;

class FacePlusPlus
{
    private static $API_KEY = "SsfXzOk5Px5GSeET_wFTLymhoO3-02oG";

    private static $API_SECRET = 'PPVijsp6J9CPIraxuXy45zR0phdj3Ax4';

    private static $API_URL = '';

    const TYPE_BASE64 = 'BASE64';

    const TYPE_URL = 'URL';

    public static function compareFaceApp($img_source_1, $img_type_1, $img_source_2, $img_type_2)
    {
        $api_data = array();
        $api_data["api_key"] = self::API_KEY;
        $api_data['api_secret'] = self::API_SECRET;
        if (FinizFacePlusPlus::TYPE_BASE64 == $img_type_1) {
            $api_data['image_base64_1'] = $img_source_1;
        } else if (FinizFacePlusPlus::TYPE_URL == $img_type_1) {
            $api_data['image_url1'] = $img_source_1;
        }

        if (FinizFacePlusPlus::TYPE_BASE64 == $img_type_2) {
            $api_data['image_base64_2'] = $img_source_2;
        } else if (FinizFacePlusPlus::TYPE_URL == $img_type_2) {
            $api_data['image_url2'] = $img_source_2;
        }
        $data_string = json_encode($api_data);

        $ch = curl_init('https://api-us.faceplusplus.com/facepp/v3/compare');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
        $curl_result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $curl_result;
    }

    public static function createFaceSet($facesetId, $facesetName, $tagName = 'default')
    {

        $api_data = array();
        $api_data["api_key"] = self::API_KEY;
        $api_data['api_secret'] = self::API_SECRET;
        if (FinizFacePlusPlus::TYPE_BASE64 == $img_type_1) {
            $api_data['image_base64_1'] = $img_source_1;
        } else if (FinizFacePlusPlus::TYPE_URL == $img_type_1) {
            $api_data['image_url1'] = $img_source_1;
        }

        if (FinizFacePlusPlus::TYPE_BASE64 == $img_type_2) {
            $api_data['image_base64_2'] = $img_source_2;
        } else if (FinizFacePlusPlus::TYPE_URL == $img_type_2) {
            $api_data['image_url2'] = $img_source_2;
        }
        $data_string = json_encode($api_data);

        $ch = curl_init('https://api-us.faceplusplus.com/facepp/v3/faceset/create');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
        $curl_result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $curl_result;
    }

}