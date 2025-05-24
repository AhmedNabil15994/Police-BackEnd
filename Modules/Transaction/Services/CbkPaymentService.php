<?php

namespace Modules\Transaction\Services;

use GuzzleHttp\Middleware;
use Illuminate\Support\Str;

class CbkPaymentService
{
    /*
     * Test CREDENTIALS
     */

    const USERNAME = "M156";
    const PASSWORD = "ANh3cfeO";
    const CLIENT_ID = "62040925";
    const CLIENT_SECRET = "4SGEP86zuHcLmtALkVilpLGP2wanzJdl35faWgNETG01";
    const ENCRP_KEY = "T-p2sjN8i67w3i4oat2HIp4aei0QbTOE3nZa3D70QuEB2WDPwM-eUbs7bzRguC0WhU_9xUzKwi3D3tL7hNKhMgglBSD3kVcQ0Dd-zcvMT7k1";
    const BASE_URL = "https://pgtest.cbk.com";


    /*
     * Live CREDENTIALS
     */

    /*const USERNAME = "M521";
    const PASSWORD = "kOdVD5US";
    const CLIENT_ID = "62040925";
    const CLIENT_SECRET = "5SjZHaBgiAiC-1N91lq2LrVTgqvJw9Z8erR0DYyrCD41";
    const ENCRP_KEY = "2dvYuN8uEvilNk9sXPofGuzRA4tT0m1OQf7KZgBO2tl3G3QnhDNKh1uHdiOPku7VVG5MoowAX45ivfU_cpPSK5QDP83J4F0uu7xIe7tFJ1c1";
    const BASE_URL = "https://pg.cbk.com";*/

    protected string $paymentMode = 'test_mode';
    protected string $paymentUrl = 'https://pgtest.cbk.com';
    protected $apiKey = 'T-p2sjN8i67w3i4oat2HIp4aei0QbTOE3nZa3D70QuEB2WDPwM-eUbs7bzRguC0WhU_9xUzKwi3D3tL7hNKhMgglBSD3kVcQ0Dd-zcvMT7k1';
    protected $clientId = '62040925';
    protected $clientSecret = '4SGEP86zuHcLmtALkVilpLGP2wanzJdl35faWgNETG01';

    public function __construct()
    {
        if (config('setting.payment_gateway.cbk_payment.payment_mode') == 'live_mode') {
            $this->paymentMode = 'live_mode';
            $this->paymentUrl = "https://pg.cbk.com";
            $this->apiKey = config('setting.payment_gateway.cbk_payment.' . $this->paymentMode . '.ENCRP_KEY') ?? self::ENCRP_KEY;
            $this->clientId = config('setting.payment_gateway.cbk_payment.' . $this->paymentMode . '.CLIENT_ID') ?? self::CLIENT_ID;
            $this->clientSecret = config('setting.payment_gateway.cbk_payment.' . $this->paymentMode . '.CLIENT_SECRET') ?? self::CLIENT_SECRET;
        }
    }

    public function send($order, $pay_id, $payment = 'online', $requestType = '')
    {
        $accessToken = $this->generateAccessToken();
        if ($accessToken == '')
            return 'failed_to_generate_access_token';

        $lang = locale() == 'ar' ? 'ar' : 'en';
        if (!empty($accessToken)) {

            $payment_url = null;

            try {
                $tij_MerchReturnUrl = $requestType == 'api-order' ? url(route('api.orders.callback')) : url(route('frontend.orders.callback'));

                $client = new \GuzzleHttp\Client([
                    'base_uri' => $this->paymentUrl,
                    'allow_redirects' => true,
                    'cookies' => true,
                ]);

                $res = $client->request('POST', '/ePay/pg/epay?_v=' . $accessToken, [
                    'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$payment_url) {
                        $payment_url = $stats->getEffectiveUri();
                    },
                    'form_params' => [
                        'tij_MerchantEncryptCode' => $this->apiKey,
                        'tij_MerchAuthKeyApi' => $accessToken,
                        'tij_MerchantPaymentLang' => $lang,
                        'tij_MerchantPaymentAmount' => $order['total'],
                        'tij_MerchantPaymentTrack' => $pay_id,
                        'tij_MerchantPaymentRef' => 'description',
                        'tij_MerchantPaymentCurrency' => 'KWD',
                        'tij_MerchantUdf1' => $order['id'],
                        'tij_MerchantUdf2' => auth()->check() ? auth()->id() : null,
                        'tij_MerchantUdf3' => '',
                        'tij_MerchantUdf4' => '',
                        'tij_MerchantUdf5' => '',
                        'tij_MerchPayType' => '',
//                        'tij_MerchReturnUrl' => 'https://stg.policesteakkw.com/api/orders/callback',
                        "tij_MerchReturnUrl" => $tij_MerchReturnUrl,
                    ]
                ]);
            } catch (\Exception $e) {
                // handle error response here...
                logger('Payment:msg::');
                logger($e->getMessage());
                /*logger('body: ' . $res->getBody());
                logger('statusCode: ' . $res->getStatusCode());*/
            }

            return (string)$payment_url;
        }

    }

    public function generateAccessToken()
    {
        $url = $this->paymentUrl . '/ePay/api/cbk/online/pg/merchant/Authenticate';
        $postRequest = [
            "ClientId" => $this->clientId,
            "ClientSecret" => $this->clientSecret,
            "ENCRP_KEY" => $this->apiKey
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postRequest));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        $err_in = curl_error($curl);
        $info = htmlspecialchars(curl_exec($curl));
        curl_close($curl);
        $json = json_decode($result, true);

        logger('AccessToken::');
        logger($json);

        if (!is_null($json) && $json['Status'] == '1')
            $accessToken = $json['AccessToken'] ?? '';
        else
            $accessToken = '';

        return $accessToken;
    }

    public function verifyPayment($encrp)
    {
        $accessToken = $this->generateAccessToken();
        $url = $this->paymentUrl . '/ePay/api/cbk/online/pg/GetTransactions/' . $encrp . '/' . $accessToken;
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        $err_in = curl_error($curl);
        $info = htmlspecialchars(curl_exec($curl));
        curl_close($curl);
        $json = json_decode($result, true);

        logger('verifyPayment::');
        logger($json);

        return $json;
    }

}

