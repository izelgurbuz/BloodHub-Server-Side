<?php
/**
 * @author Oğuzhan ÇAKAR
 * @webSite http://www.ogzcakar.net
 * Class oneSignal
 * Date: 18.11.2016
 */

class oneSignal
{
    public $apiKey = 'a5df1457-e405-4ad8-87e5-911ea307efaf'; // Api Key

    private $restApiKey = 'ZmRjY2RjN2MtYzU4Mi00Zjc1LTg1ZWQtYTJmNjliZjE0YjYx'; // Rest Api Key

    function __construct()
    {
        return $this->apiKey;
    }

    public function sendMessage($messageEn , $url = null, $image = 'http://via.placeholder.com/512x256.png')
    {
        $content = array(
            'en' => $messageEn
        );

        $data = array(
            'app_id' => $this->apiKey,
            'included_segments' => array('All'),
            'contents' => $content,
            'url' => $url
            
        );
        $data['chrome_web_icon'] = 'http://cs491-2.mustafaculban.net/images/un.png';
        $data['chrome_web_image'] = $image;
        $data['chrome_web_badge'] = 'http://cs491-2.mustafaculban.net/push/output.png';
        $data = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onesignal.com/api/v1/notifications',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic '.$this->restApiKey
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

}