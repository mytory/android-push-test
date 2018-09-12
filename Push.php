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

        $message = self::messageCommon($body);

        foreach (Config::$target_tokens as $token) {
            $message->addRecipient(new Device($token));
        }

        return self::send($message);

    }

    public static function pushToChannel($body): array
    {
        $message = self::messageCommon($body);

        foreach (Config::$target_topics as $target_topic) {
            $message->addRecipient(new Topic($target_topic));
        }

        return self::send($message);

    }

    private static function messageCommon($body): Message
    {
        $message = new Message();
        $message->setPriority('high');

        // Use only data for same notification service even if app is in background.
        // Don't use notification object.
        $message
//            ->setNotification(new Notification('Android Push Test', $body))
            ->setData([
                'title' => 'Android Push Test',
                'body' => $body,
                'url' => 'https://google.com'
            ])
        ;
        return $message;
    }

    /**
     * @param $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    private static function send($message): array
    {
        $client = new Client();
        $client->setApiKey(Config::FCM_KEY);
        $response = $client->send($message);

        return [
            'statusCode' => $response->getStatusCode(),
            'contents' => \GuzzleHttp\json_decode($response->getBody()->getContents()),
        ];
    }

}