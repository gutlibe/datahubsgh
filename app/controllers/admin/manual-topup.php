<?php

use App\Classes\User;
use App\Classes\Transaction;

$user_found = false;
$user = null;
$user_class = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_user'])) {
    $email = trim($_POST['email']);
    $user = $user_class->findByEmail($email);
    if ($user) {
        $user_found = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topup_amount'])) {
    $userId = $_POST['user_id'];
    $amount = (float)$_POST['topup_amount'];
    $old_balance = (float)$_POST['old_balance'];
    $user_email = $_POST['user_email'];

    $user_class->updateBalance($userId, $old_balance + $amount);

    $transaction_class = new Transaction();
    $reference = 'manual_topup_' . time();
    $transaction_class->create($userId, $reference, $amount, 'manual_topup');

    header('Location: ' . BASE_URL . '/admin/manual-topup?success=true&email=' . urlencode($user_email) . '&old_balance=' . $old_balance . '&new_balance=' . ($old_balance + $amount));
    exit;
}

view('admin/manual-topup', [
    'layout' => 'admin',
    'user_found' => $user_found,
    'user' => $user
]);
