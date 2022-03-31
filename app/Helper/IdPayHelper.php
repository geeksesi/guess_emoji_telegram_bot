<?php
namespace App\Helper;

use App\Enums\PaymentStatusEnum;

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
            throw new \Exception(json_encode($result, JSON_UNESCAPED_UNICODE & JSON_PRETTY_PRINT), 500);
        }

        return $result;
    }

    public static function idpay_payment_get_inquiry(string $id, int $order_id)
    {
        $header = [
            "Content-Type: application/json",
            "X-API-KEY:" . $_ENV["IDPAY_KEY"],
            "X-SANDBOX:" . boolval($_ENV["IDPAY_SANDBOX"]),
        ];

        $params = [
            "id" => $id,
            "order_id" => $order_id,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$URL_INQUIRY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        if (empty($result) || empty($result->status)) {
            throw new \Exception(json_encode($result, JSON_UNESCAPED_UNICODE & JSON_PRETTY_PRINT), 500);
        }

        return self::idpay_payment_get_message($result->status);

        return false;
    }

    public static function idpay_payment_verify(string $id, int $order_id)
    {
        $header = [
            "Content-Type: application/json",
            "X-API-KEY:" . $_ENV["IDPAY_KEY"],
            "X-SANDBOX:" . boolval($_ENV["IDPAY_SANDBOX"]),
        ];

        $params = [
            "id" => $id,
            "order_id" => $order_id,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$URL_VERIFY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);
        if (empty($result) || empty($result->status)) {
            throw new \Exception(json_encode($result, JSON_UNESCAPED_UNICODE & JSON_PRETTY_PRINT), 500);
        }

        return self::idpay_payment_get_message($result->status);
    }

    public static function idpay_payment_get_message($status): PaymentStatusEnum
    {
        switch ($status) {
            case 10:
                return PaymentStatusEnum::PENDING;

            case 100:
            case 101:
                return PaymentStatusEnum::SUCCESS;

            case 1:
            case 2:
            case 3:
            default:
                return PaymentStatusEnum::FAIL;
        }
    }
}
