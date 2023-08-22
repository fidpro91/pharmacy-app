<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
class Pusher
{
    public function call_antrian($respon = null) {
        $pusher = new Pusher\Pusher("2c006c80922871a2eef0", "ad8a5d13674480444839", "1648183", array('cluster' => 'ap1'));

        $pusher->trigger('my-channel', 'my-event', array(
            'message'   => 'hello world',
            "response"  => $respon
        ));
    }
}