<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function sendNotification(Request $request) {
        $title = $request->title;
        $body = $request->body;
        $id = $request->id;
        $token_id = $request->token_id;

        $response = $this->sendPushNotification($title, $body, $id, $token_id);

        return response()->json($response);
    }


    function sendPushNotification($title, $body, $id, $token_id) {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $dataArr = [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'id' => $id,
            'status' => 'done'
        ];

        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => 'default',
            'badge' => '1'
        ];

        $arrayToSend = [
            'to' => $token_id,
            'notification' => $notification,
            'data' => $dataArr,
            'priority' => 'high'
        ];

        $headers = [
            'Authorization' => 'key=' . 'AAAAZOnbePQ:APA91bFLiXRJMUX7XhyQMH2-MalUsKF71PbK_GApPjYnWxtTYWWBkuGCF3mFE1cOGoPHDAwHfaUfPHBYOj2WtywtZRrPQVkicECWW-w35_wbWL93_bP8S4Rv8J8x7ZzPtPOG3_WP4I_x',
            'Content-Type' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->post($url, $arrayToSend);

        return $response->json();
    }

    function sendPushNotificationsAll(Request $request) {
        $title = $request->input('title');
        $body = $request->input('body');
        $id = $request->input('id');

        $url = 'https://fcm.googleapis.com/fcm/send';

        $dataArr = [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'id' => $id,
            'status' => 'done'
        ];

        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => 'default',
            'badge' => '1'
        ];

        $arrayToSend = [
            "to" => '/topics/all',
            'notification' => $notification,
            'data' => $dataArr,
            'priority' => 'high'
        ];

        $headers = [
            'Authorization' => 'key=' . 'AAAAZOnbePQ:APA91bFLiXRJMUX7XhyQMH2-MalUsKF71PbK_GApPjYnWxtTYWWBkuGCF3mFE1cOGoPHDAwHfaUfPHBYOj2WtywtZRrPQVkicECWW-w35_wbWL93_bP8S4Rv8J8x7ZzPtPOG3_WP4I_x',
            'Content-Type' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->post($url, $arrayToSend);

        return $response->json();
    }
}
