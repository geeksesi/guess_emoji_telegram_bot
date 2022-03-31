<?php

use App\Enums\OutputMessageEnum;
use App\Helper\IdPayHelper;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Helper\ExceptionHepler;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;
use App\Model\Payment;
use App\Model\Transaction;

include __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

function html(array $data = [])
{
    include __DIR__ . "/../Templates/PaymentResultTemplate.php";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = $_POST;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $response = $_GET;
}

if (
    empty($response["status"]) ||
    empty($response["id"]) ||
    empty($response["track_id"]) ||
    empty($response["order_id"])
) {
    html(["title" => "پرداخت نامعتبر", "error" => "لطفا به ربات برگشته و دوباره تلاش کنید"]);
    die();
}

$response = filter_var_array($response);

$payment = Payment::get_first("WHERE id=:id AND payment_key=:payment_key", [
    ":id" => $response["order_id"],
    ":payment_key" => $response["id"],
]);

if (!$payment) {
    html(["title" => "پرداخت نامعتبر", "error" => "لطفا به ربات برگشته و دوباره تلاش کنید"]);
    die();
}

if (PaymentStatusEnum::from($payment->status) === PaymentStatusEnum::SUCCESS) {
    html(["title" => "پرداخت قبلا انجام شده است", "error" => "برای خرید دوباره به ربات مراجعه فرمایید"]);
    die();
}

$status = $response["status"];
$status = IdPayHelper::idpay_payment_get_message($status);

if ($status != PaymentStatusEnum::PENDING) {
    html([
        "title" => "پرداخت لغو شد",
        "error" => "در صورت کسر، موجودی تا 72 ساعت آینده از طریق آی‌دی‌پی به شما بازگردانده خواهد شد.",
    ]);
    die();
}
try {
    $dual_check_status = IdPayHelper::idpay_payment_get_inquiry($payment->payment_key, $payment->id);
} catch (\Throwable $th) {
    html([
        "title" => "پرداخت با خطا مواجه شد",
        "error" => "در صورت تکرار با ما تماس بگیرید .",
    ]);
    (new ExceptionHepler($th))(false);
    die();
}
if ($dual_check_status != PaymentStatusEnum::PENDING) {
    html([
        "title" => "پرداخت لغو شد",
        "error" => "در صورت کسر، موجودی تا 72 ساعت آینده از طریق آی‌دی‌پی به شما بازگردانده خواهد شد.",
    ]);
    die();
}

try {
    $verify = IdPayHelper::idpay_payment_verify($payment->payment_key, $payment->id);
} catch (\Throwable $th) {
    html([
        "title" => "پرداخت با خطا مواجه شد",
        "error" => "در صورت تکرار با ما تماس بگیرید .",
    ]);
    (new ExceptionHepler($th))(false);
    die();
}

// change payment status
try {
    $payment->status = PaymentStatusEnum::SUCCESS;
    $payment->save();
} catch (\Throwable $th) {
    (new ExceptionHepler($th))(false);
    TelegramHelper::send_message("can't store payment for P_ID : " . $payment->id, $_ENV["ADMIN"]);
}

$user = $payment->user();
// add transaction and calcuate credit and send message
try {
    $transaction = Transaction::create([
        "balance" => $payment->credit,
        "type" => TransactionTypeEnum::BUY_CREDIT->value,
        "user_id" => $user->id,
        "payment_id" => $payment->id,
    ]);
    // calculate credit
    $user->credit = $transaction->credit_calculate();
    $user->save();
    OutputHelper::by_type($user->chat_id, OutputMessageEnum::SUCCESS_BUY_CREDIT, true, [
        "+-CREDIT-+" => $payment->credit,
    ]);
} catch (\Throwable $th) {
    (new ExceptionHepler($th))(false);
    TelegramHelper::send_message("Error on proccess credit of user PD_ID : " . $payment->id, $_ENV["ADMIN"]);
}

// var_dump($verify);
html(["title" => "پرداخت با موفقیت انجام شد"]);
