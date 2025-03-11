<?php

if (!function_exists('getDeviceId')) {
    function getDeviceId()
    {
        return hash('sha256', request()->userAgent());
    }
}
