<?php


Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

/*Route::get('/guzzle', function () {
    $payment_url = null;
    $access_token = 'DXEK5ljHcEXpYQlcdvu77XtB9_nzbzF5KYoehHmN0s81';
    $pay_id = \Str::random(mt_rand(20, 30));

    try {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://pg.cbk.com',
            'allow_redirects' => true,
            'cookies' => true,
        ]);

        $res = $client->request('POST', '/ePay/pg/epay?_v=' . $access_token, [
            'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$payment_url) {
                $payment_url = $stats->getEffectiveUri();
            },
            'form_params' => [
                'tij_MerchantEncryptCode' => '2dvYuN8uEvilNk9sXPofGuzRA4tT0m1OQf7KZgBO2tl3G3QnhDNKh1uHdiOPku7VVG5MoowAX45ivfU_cpPSK5QDP83J4F0uu7xIe7tFJ1c1',
                'tij_MerchAuthKeyApi' => $access_token,

                'tij_MerchantPaymentLang' => 'en',
                'tij_MerchantPaymentAmount' => '10',
                'tij_MerchantPaymentTrack' => $pay_id,
                'tij_MerchantPaymentRef' => 'desc',
                'tij_MerchantPaymentCurrency' => 'KWD',

                'tij_MerchantUdf1' => '2',
                'tij_MerchantUdf2' => '',
                'tij_MerchantUdf3' => '',
                'tij_MerchantUdf4' => '',
                'tij_MerchantUdf5' => '',
                'tij_MerchPayType' => '',

                'tij_MerchReturnUrl' => 'https://police.tocaanme.com/ar/orders/success',
            ]
        ]);
    } catch (\Exception $e) {
        // handle error response here...
        dd($e);
    }

    // echo '<br>';
    // echo $res->getBody();
    // echo '<br>';
    // echo $res->getStatusCode();
    // echo '<br>';
    // echo $payment_url;

    return [
        'status' => $res->getStatusCode(),
        'payment_url' => (string)$payment_url,
    ];
});*/
