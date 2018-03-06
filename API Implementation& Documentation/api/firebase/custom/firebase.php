<?php
/**
 * Created by PhpStorm.
 * User: Cristian Ramírez
 * Date: 4/10/2016
 * Time: 10:32 PM
 */
class Firebase {
    // enviar notificación para un solo usuario por el reg id de firebase
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this -> sendPushNotification($fields);
    }

    // Enviar notificación a un tema en especifico
    public function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this -> sendPushNotification($fields);
    }

    //Enviar notificaciones a multiples usuarios con ids registrados en firebase
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );
        return $this -> sendPushNotification($fields);
    }

    // Función que hace un CURL request a los servidores de firebase
    private function sendPushNotification($fields) {
        require_once __DIR__ . '/config.php';
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . FIREBASE_API_KEY,
            'Content-Type: application/json'
        );

        // Abrir conexión
        $request = curl_init();
        // setear la url, numero de variables POST, POST data
        curl_setopt($request, CURLOPT_URL, $url);

        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        //Deshabilitar temporalmente el Certficado de SSL
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($fields));

        // Ejecutar petición
        $result = curl_exec($request);
        if ($result == false) {
            die('Curl failed: ' . curl_error($request));
        }

        // Cerrar conexión
        curl_close($request);

        return $result;

    }

}