<?php
/**
 * Created by PhpStorm.
 * User: Cristian Ramírez
 * Date: 4/10/2016
 * Time: 10:08 PM
 */
class Notification {
    private $title;
    private $message;
    private $image;
    // notification message payload
    private $data;
    private $is_background;

    function __construct() {
    }

    public function setTitle($title) {
        $this -> title = $title;
    }

    public function setMessage($message) {
        $this -> message = $message;
    }

    public function setImage($image) {
        $this -> image = $image;
    }

    public function setPayload($data) {
        $this -> data = $data;
    }

    public function setIsBackground($is_background) {
        $this -> is_background = $is_background;
    }

    public function getNotification() {
        $res = array();
        $res['data']['title'] = $this -> title;
        $res['data']['is_background'] = $this -> is_background;
        $res['data']['message'] = $this -> message;
        $res['data']['image'] = $this -> image;
        $res['data']['payload'] = $this -> data;
        $res['data']['timestamp'] = date('Y-m-d G:i:s');
        return $res;
    }


}