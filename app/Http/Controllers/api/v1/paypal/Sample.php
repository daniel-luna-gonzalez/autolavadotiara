<?php
namespace App\Http\Controllers\api\v1\paypal;

/**
 * Description of Sample
 *
 * @author danielunag
 */
class Sample {
    public function run(){
        return exec("php -f ".__DIR__.'/../../../../../../vendor/paypal/rest-api-sdk-php/sample/index.php');
    }
}
