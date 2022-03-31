<?php

use App\Enums\PaymentStatusEnum;
use App\Helper\ExceptionHepler;
use App\Helper\IdPayHelper;
use App\Model\Payment;
use App\Model\Plan;
use App\Model\User;

include __DIR__ . "/../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

function error(string $error = "")
{
    include __DIR__ . "/../Templates/PaymentErrorTemplate.php";
}

if (!isset($_GET["key"]) || !isset($_GET["plan"])) {
    error("درخواست نامعتبر");
    die();
}
if (empty($_GET["key"]) || empty($_GET["plan"])) {
    error("درخواست نامعتبر");
    die();
}

$chat_id = filter_var($_GET["key"], FILTER_DEFAULT);
$plan_key = filter_var($_GET["plan"], FILTER_DEFAULT);

$user = User::get_first("WHERE chat_id=:chat_id", [":chat_id" => $chat_id]);
if (!$user) {
    error("کاربر یافت نشد لطفا به ربات برگشته و دوباره تلاش کنید.<br> در صورت تکرار با ما تماس بگیرید ");
    die();
}

$plan = Plan::get($plan_key);
if (!$plan) {
    error("پلن یافت نشد لطفا به ربات برگشته و دوباره تلاش کنید.<br> در صورت تکرار با ما تماس بگیرید ");
    die();
}

$callback = $_ENV["HTTP_URL"] . "/payment_result.php";

try {
    $payment = Payment::create([
        "user_id" => $user->id,
        "plan_id" => $plan->id,
        "payment_key" => null,
        "credit" => $plan->credit,
        "cost" => $plan->cost,
        "status" => PaymentStatusEnum::CREATED->value,
    ]);
} catch (\Throwable $th) {
    error("در صورت تکرار با ما تماس بگیرید ");
    (new ExceptionHepler($th))(false);
    die();
}

$params = [
    "order_id" => $payment->id,
    "amount" => $payment->cost * 10,
    "phone" => "",
    "name" => $user->chat_id,
    "desc" => "خرید بسته : " . $plan->name,
    "callback" => $callback,
];

try {
    $idpay = IdPayHelper::idpay_payment_create($params);
} catch (\Throwable $th) {
    error("در صورت تکرار با ما تماس بگیرید ");
    (new ExceptionHepler($th))(false);
    die();
}

if (!isset($idpay["link"]) || !isset($idpay["id"])) {
    error("در صورت تکرار با ما تماس بگیرید ");
    die();
}

$link = $idpay["link"];
$idpay_key = $idpay["id"];

$payment->payment_key = $idpay_key;
$payment->status = PaymentStatusEnum::PENDING->value;

try {
    $payment->save();
} catch (\Throwable $th) {
    error("در صورت تکرار با ما تماس بگیرید ");
    (new ExceptionHepler($th))(false);
    die();
}

echo "<h1 style='text-align:center'>درحال انتقال به درگاه پرداخت</h1>";

$link = filter_var($idpay["link"], FILTER_SANITIZE_URL);

// header("Location:" . filter_var($idpay["link"], FILTER_SANITIZE_URL));
?>

<script>
window.location.replace("<?= $link ?>");
</script>

