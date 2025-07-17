<?php

use App\Models\Setting;


function setting($key, $default = null) {
    try {
        $data = \App\Models\Setting::first();
        return $data?->{$key} ?? $default;
    } catch (\Throwable $e) {
        return $default;
    }
}

