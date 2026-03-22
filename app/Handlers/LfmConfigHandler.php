<?php

namespace App\Handlers;

use UniSharp\LaravelFilemanager\Handlers\ConfigHandler as BaseConfigHandler;

class LfmConfigHandler extends BaseConfigHandler
{
    /**
     * Override user folder
     */
    public function userField()
    {
        return 'uploads';
    }
}
