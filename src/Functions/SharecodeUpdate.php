<?php

namespace Actcmsvn\Sharecode\Functions;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class SharecodeUpdate {

    public static function companySetting()
    {
        $setting = config('actcmsvn_sharecode.setting');
        return (new $setting)::first();
    }

    public static function showReview()
    {
        $setting = config('actcmsvn_sharecode.setting');
        $sharecodeUpdateCompanySetting = (new $setting)::first();

        // ShowReview only when supported members and show_review_modal is enabled
        return (!is_null($sharecodeUpdateCompanySetting->supported_until) &&
            !\Carbon\Carbon::parse($sharecodeUpdateCompanySetting->supported_until)->isPast() &&
            ((\Carbon\Carbon::parse($sharecodeUpdateCompanySetting->supported_until)->diffInDays(\Carbon\Carbon::now()) <= 175) || (\Carbon\Carbon::parse($sharecodeUpdateCompanySetting->supported_until)->diffInDays(\Carbon\Carbon::now()) > 200 && \Carbon\Carbon::parse($sharecodeUpdateCompanySetting->supported_until)->diffInDays(\Carbon\Carbon::now()) <= 360)) &&
            $sharecodeUpdateCompanySetting->show_review_modal===1);

    }

    public static function reviewUrl()
    {
        $setting = config('actcmsvn_sharecode.setting');
        $sharecodeUpdateCompanySetting = (new $setting)::first();

        $url = str_replace('verify-purchase','review',config('actcmsvn_sharecode.verify_url'));
        return $url.'/'.$sharecodeUpdateCompanySetting->purchase_code;

    }
    
    public static function plugins(){
        $client = new Client();
        $res = $client->request('GET', config('actcmsvn_sharecode.plugins_url'), ['verify' => false]);
        $lastVersion = $res->getBody();
        return json_decode($lastVersion, true);
    }
    
     public static function updateVersionInfo()
    {
        $updateVersionInfo = [];
        try {
            $client = new Client();
            // Get Data from server for download files
            $res = $client->request('GET', config('actcmsvn_sharecode.updater_file_path'), ['verify' => false]);
            $lastVersion = $res->getBody();
            $lastVersion = json_decode($lastVersion, true);
            if ($lastVersion['version'] > File::get('version.txt')) {
                $updateVersionInfo['lastVersion'] = $lastVersion['version'];
                $updateVersionInfo['updateInfo'] = $lastVersion['description'];
            }
            $updateVersionInfo['updateInfo'] = $lastVersion['description'];

        } catch (\Exception $e) {
        }

        try{
            // Get data of Logs
            $resLog = $client->request('GET', config('actcmsvn_sharecode.versionLog') . '/' . File::get('version.txt'), ['verify' => false]);
            $lastVersionLog = json_decode($resLog->getBody(), true);
            foreach ($lastVersionLog as $item) {
                // Ignore duplicate of latest version
                $releaseDate = $item['release_date']?' (Released on:'. Carbon::parse($item['release_date'])->format('d M Y').')':'';
                if (version_compare($item['version'], $lastVersion['version']) == 0) {
                    $updateVersionInfo['updateInfo'] = '<strong>Version: ' . $item['version'] .$releaseDate. '</strong>' . $item['description'];
                    continue;
                };
                $updateVersionInfo['updateInfo'] .= '<strong>Version: ' . $item['version'] .$releaseDate. '</strong>' . $item['description'];
            }
        } catch (\Exception $e) {
        }

        $updateVersionInfo['appVersion'] = File::get('version.txt');
        $laravel = app();
        $updateVersionInfo['laravelVersion'] = $laravel::VERSION;
        return $updateVersionInfo;
    }


}
