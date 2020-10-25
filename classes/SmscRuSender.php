<?php namespace Definer\SmscRuSender\Classes;

use Exception;
use Definer\SmscRuSender\Classes\SmscRu;
use Definer\SmscRuSender\Models\Setting;

class SmscRuSender
{

    /*
     * Send SMS Smsc.Ru provider
     * @param integer the phone number to send the message
     * @param string the test message to send
     * return bool
     * SmsRuSender::sendMessage('phone_number', 'text');
     */
    public static function sendMessage($to, $text)
    {
        $api_login = Setting::get('api_login');
        $api_password = Setting::get('api_password');

        $from = Setting::get('from');
        $test_mode = Setting::get('test_mode');

        $smscru = new SmscRu($api_login, $api_password);

        $data = new \stdClass();
        $data->phones = $to; // Номер доставки
        $data->mes = $text; // Текст сообщения
        $data->from = $from; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
        $data->test = $test_mode; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
        $smsc = $smscru->send_one($data); // Отправка сообщения и возврат данных в переменную

        return ($smsc->status == "OK") ? true : false;
    }
}
