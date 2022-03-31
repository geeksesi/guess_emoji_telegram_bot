<?php
namespace App\Helper;

class IdPayHelper
{
    private static $URL_PAYMENT = "https://api.idpay.ir/v1.1/payment";
    private static $URL_INQUIRY = "https://api.idpay.ir/v1.1/payment/inquiry";
    private static $URL_VERIFY = "https://api.idpay.ir/v1.1/payment/verify";
    public static function idpay_payment_create($params)
    {
        $header = [
            "Content-Type: application/json",
            "X-API-KEY:" . $_ENV["IDPAY_KEY"],
            "X-SANDBOX:" . boolval($_ENV["IDPAY_SANDBOX"]),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$URL_PAYMENT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        if (empty($result) || empty($result["link"])) {
            print "Exception message:";
            print "<pre>";
            print_r($result);
            print "</pre>";

            return false;
        }

        return $result;
    }
}
