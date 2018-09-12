<?php

namespace Mytory\AndroidPushTest;

use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use CLI;
use Exception;


class Push
{

    public static function push($body): array
    {
        $client = new Client();
        $client->setApiKey(Config::FCM_KEY);

        $message = new Message();
        $message->setPriority('high');

        foreach (Config::$target_tokens as $token) {
            $message->addRecipient(new Device($token));
        }

        $message
            ->setNotification(new Notification('Android Push Test', $body))
            ->setData(['url' => 'https://google.com'])
        ;

        $response = $client->send($message);

        return [
            'statusCode' => $response->getStatusCode(),
            'contents' => \GuzzleHttp\json_decode($response->getBody()->getContents()),
        ];
    }

    public static function pushToChannel($body): array
    {
        $client = new Client();
        $client->setApiKey(Config::FCM_KEY);

        $message = new Message();
        $message->setPriority('high');

        foreach (Config::$target_topics as $target_topic) {
            $message->addRecipient(new Topic($target_topic));
        }

        $message
            ->setNotification(new Notification('Android Push Test', $body))
            ->setData(['url' => 'https://google.com'])
        ;

        $response = $client->send($message);

        return [
            'statusCode' => $response->getStatusCode(),
            'contents' => \GuzzleHttp\json_decode($response->getBody()->getContents()),
        ];

    }


}