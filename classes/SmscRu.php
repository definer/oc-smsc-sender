<?php namespace Definer\SmscRuSender\Classes;

use Exception;

class SmscRu
{
    private $ApiLogin;
    private $ApiPassword;
    private $protocol = 'https';
    private $domain = 'smsc.ru';
    private $count_repeat = 5;

    function __construct($ApiLogin, $ApiPassword)
    {
        $this->ApiLogin = $ApiLogin;
        $this->ApiPassword = $ApiPassword;
    }

    /**
     * Совершает отправку СМС сообщения одному или нескольким получателям.
     * @param $post
     *   $post->to = string - Номер телефона получателя (либо несколько номеров, через запятую — до 100 штук за один запрос). Если вы указываете несколько номеров и один из них указан неверно, то на остальные номера сообщения также не отправляются, и возвращается код ошибки.
     *   $post->msg = string - Текст сообщения в кодировке UTF-8
     *   $post->from = string - Имя отправителя (должно быть согласовано с администрацией). Если не заполнено, в качестве отправителя будет указан ваш номер.
     *   $post->test = 1 - Имитирует отправку сообщения для тестирования ваших программ на правильность обработки ответов сервера. При этом само сообщение не отправляется и баланс не расходуется. (по умолчанию 0)
     * @return array|mixed|\stdClass
     */
    public function send_one($post)
    {
        $url = $this->protocol . '://' . $this->domain . '/sys/send.php';

        $request = $this->Request($url, $post);
        echo "<pre>"; print_r($request); exit();
        $resp = $this->CheckReplyError($request, 'send');

        if ($resp->status == "OK") {
            $temp = (array)$resp->sms;
            unset($resp->sms);

            $temp = array_pop($temp);
            $temp = (object)array_merge((array)$temp, (array)$resp);

            return $temp;
        } else {
            return $resp;
        }
    }

    public function send($post)
    {
        $url = $this->protocol . '://' . $this->domain . '/sms/send';
        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'send');
    }

    private function Request($url, $post = FALSE)
    {
        if ($post) {
            $r_post = $post;
        }

        $ch = curl_init($url . "?json=1");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        if (!$post) {
            $post = new \stdClass();
        }

        if (!empty($post->api_login) && $post->api_login == 'none') {
        } else {
            $post->login = $this->ApiLogin;
            $post->psw = $this->ApiPassword;
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query((array)$post));

        $body = curl_exec($ch);
        if ($body === FALSE) {
            $error = curl_error($ch);
        } else {
            $error = FALSE;
        }
        curl_close($ch);

        if ($error && $this->count_repeat > 0) {
            $this->count_repeat--;
            return $this->Request($url, $r_post);
        }
        return $body;
    }

    private function CheckReplyError($res, $action)
    {
        if (!$res) {
            $temp = new \stdClass();
            $temp->status = "ERROR";
            $temp->status_code = "000";
            $temp->status_text = "Невозможно установить связь с сервером.";
            return $temp;
        }

        $result = json_decode($res);

        if (!$result || !$result->status) {
            $temp = new \stdClass();
            $temp->status = "ERROR";
            $temp->status_code = "000";
            $temp->status_text = "Невозможно установить связь с сервером.";
            return $temp;
        }

        return $result;
    }
}
