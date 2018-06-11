<?php

Class SomeApiController {

    const LOG_FILE = 'debug.log';

    public function doGet()
    {
        if (count($_GET) <= 0) {
            exit ;
        }

        return $_GET;
    }

    public function sendResponse(array $sendData)
    {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($sendData);
    }

    public function getJsonData(array $rcvData)
    {
        $type = $rcvData['type'] ?? '';
        $id = $rcvData['id'] ?? '';

        $fileName = "{$type}/{$id}.json";
        if (!file_exists($fileName))
        {
            $fileName = "error/404.json";
        }


        $this->debug($fileName);

        $json = file_get_contents($fileName);

        return json_decode($json, true);
    }

    public function debug($msg)
    {
        date_default_timezone_set('Asia/Tokyo');
        $str = date("Y-m-d H:i:s") . " ";

        $logfp = fopen(self::LOG_FILE, "a");
        if (is_array($msg))
        {
            $str .= implode(',', $msg) . PHP_EOL;
        } else {
            $str .= $msg . PHP_EOL;
        }
        fputs($logfp, $str);
        fclose($logfp);
    }

}

// main
$api = new SomeApiController();
$rcvData = $api->doGet();
$sendData = $api->getJsonData($rcvData);

$api->sendResponse($sendData);
