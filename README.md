## SmscRu Sender plugin for OctoberCMS

### Install
Use Definer.SmscRuSender to search/install this plugin.

* In the backend panel, go to System -> Updates
* Click Install Plugin
* Go to https://smsc.ru and create your account or sign in
* Get your API credentials
* Add your Smsc.Ru datas on the System -> SmscRuSender section
* Add at the top level of your controllers `use Definer\SmscRuSender\Classes\SmscRuSender;`
* Send Sms with `SmscRuSender::sendMessage('phone_number', 'text');`

### Localisation
English
Russian

### License
The Import plugin for OctoberCMS is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).