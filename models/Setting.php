<?php namespace Definer\SmscRuSender\Models;

use Model;

class Setting extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = [
    	'System.Behaviors.SettingsModel'
    ];

    public $settingsCode = 'definer_smscrusender_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [
    	'api_id' => 'size:36|check_smscru_api_id'
    ];

    public $customMessages = [
    	'api_id.check_smscru_api_id' => 'definer.smscrusender::lang.backend.check_smscru_api_id'
    ];
}
