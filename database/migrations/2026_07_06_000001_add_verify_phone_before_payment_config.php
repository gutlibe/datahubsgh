<?php

use App\Classes\Migration;

class AddVerifyPhoneBeforePaymentConfig extends Migration
{
    public function up()
    {
        $this->db->exec("INSERT IGNORE INTO `configurations` (`name`, `value`) VALUES
            ('verify_phone_before_payment', '0')");
    }

    public function down()
    {
        $this->db->exec("DELETE FROM `configurations` WHERE `name` = 'verify_phone_before_payment'");
    }
}
