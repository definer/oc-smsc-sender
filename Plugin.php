<?php namespace Definer\SmscRuSender;

use App;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'SmscRu Sender',
            'description' => 'definer.smscrusender::lang.backend.settings_desc',
            'author'      => 'Definer',
            'icon'        => 'icon-envelope-square'
        ];
    }

    public function registerSettings()
    {
        return [
            'smssender' => [
                'label' => 'SmscRu Sender',
                'icon' => 'icon-envelope-square',
                'description' => 'definer.smscrusender::lang.backend.settings_desc',
                'class' => 'Definer\SmscRuSender\Models\Setting',
                'order' => 100
            ]
        ];
    }
}
