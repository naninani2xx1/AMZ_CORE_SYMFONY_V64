<?php

namespace App\Core\Trait;

trait FileTrait
{
    public function getExtension(string $url): string
    {
        $arr = explode('.', $url);
        return end($arr);
    }

    public function getSizeFile(string $url): string
    {
        if(str_starts_with($url, 'http')){
            $arr = explode('/', $url);
            unset($arr[0]);
            unset($arr[1]);
            unset($arr[2]);
            $url = implode('/', $arr);
        }
        $url = $this->getProjectDir().'/public/' . $url;
        return $this->formatFileSize(filesize($url));
    }

    public function formatFileSize($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . $units[$pow];
    }
}