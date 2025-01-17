<?php

namespace App\Helpers;

class DesignCodeUtil
{
    public static function designUrlToDesignCode($designUrl) {
        $parseUrl = parse_url($designUrl);
        $path = substr($parseUrl['path'], 1);
        $folder = str_replace('/', '-', substr($path, 0, strripos($path, '/')));
        $fileName = str_replace('.png', '', substr($path, strlen($folder) ? strripos($path, '/') + 1 : strripos($path, '/')));
        if (strpos($designUrl, 'zazzle-crawler-storage')) {
            return 'zzlus1-' . $fileName;
        } else if (strpos($designUrl, '/printerval-mirror/images/723/')) {
            return 'zzl723-' . $fileName;
        } else if (strpos($designUrl, '/printerval-mirror/images/725/')) {
            return 'spr725-' . $fileName;
        } else if (strpos($designUrl, '/printerval-mirror/images/')) {
            return 'zzlus2-' . $fileName;
        } else if (strpos($designUrl, '66.94.124.83') !== false) {
            return 'zzlipa-' . $fileName;
        } else if (strpos($designUrl, '207.244.242.20') !== false) {
            return 'zzlipb-' . $fileName;
        } else if (strpos($designUrl, '217.76.56.107') !== false) {
            return 'zzlipc-' . $fileName;
        } else if (strpos($designUrl, '217.76.56.120') !== false) {
            return 'zzlipd-' . $fileName;
        } else if (strpos($designUrl, 'spreadshirt')) {
            return 'sprv1-' . str_replace(',height=1200', '', $fileName);
        }
        if (!$folder || !$fileName) {
            return false;
        }
        return 'print-' . $folder . '_' . $fileName;
    }
}