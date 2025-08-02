<?php

namespace WPDuplicate\Services;

use WPDuplicate\Models\Settings;

class DuplicationService
{

    private $settings;

    public function __construct()
    {
        $this->settings = new Settings();
    }
} 
