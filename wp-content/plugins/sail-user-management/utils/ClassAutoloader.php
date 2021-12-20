<?php

namespace Sail\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Sail\Constants;

class ClassAutoloader {
    private static $classPathMap = array();

    private static function init() {
        if (!empty(self::$classPathMap)) {
            return;
        }

        $start = hrtime(true);
        $dirIter = new RecursiveDirectoryIterator(Constants::HOME_DIR);
        $iter = new RecursiveIteratorIterator($dirIter);
        $libPaths = new RegexIterator($iter, '/^.*\.php$/m', RegexIterator::MATCH); 

        $namespaceRegex =  '/^namespace (.*);$/m';
        $functionRegex =  '/^function (.*)\(.*$/m';
        foreach ($libPaths as $file) {
            $path = $file->getPathName();
            $split = explode('/', $path);
            $fileNameWithExt = end($split);
            $justFileName = basename($fileNameWithExt, '.php');
            $fileContents = file_get_contents($path);
            // This loads full namespaced classes assuming file name and class name matches 
            if (preg_match($namespaceRegex, $fileContents, $namespaceMatches)) {
                self::$classPathMap[$namespaceMatches[1] . '\\' . $justFileName] = $path;
            // This hack loads some GuzzleHttp functions 
            } else if (self::endsWith($path, 'functions.php')) {
                preg_match($functionRegex, $fileContents, $functionMatches);
                // Every other match will be the group with the function name
                for ($i = 1; $i < count($functionMatches); $i += 2) {
                    self::$classPathMap[$functionMatches[i]] = $path;
                }
            // This loads naked classes without namespaces assuming file name and class name matches 
            } else {
                self::$classPathMap[$justFileName] = $path;
            }
        }
        $end = hrtime(true);
        error_log('ClassAutoloader: Class path map initialized from source directory in: ' . (($end - $start)/1e+6) . 'ms');
        error_log(serialize(self::$classPathMap));
    }

    private static function init_from_string() {
        if (!empty(self::$classPathMap)) {
            return;
        }
        $start = hrtime(true);
        $str = 'a:204:{s:17:"add-family-member";s:75:"/var/www/html/wp-content/plugins/sail-user-management/add-family-member.php";s:14:"Sail\Constants";s:67:"/var/www/html/wp-content/plugins/sail-user-management/Constants.php";s:17:"fc-profile-update";s:75:"/var/www/html/wp-content/plugins/sail-user-management/fc-profile-update.php";s:15:"fc-registration";s:73:"/var/www/html/wp-content/plugins/sail-user-management/fc-registration.php";s:9:"join-port";s:67:"/var/www/html/wp-content/plugins/sail-user-management/join-port.php";s:34:"PayPalCheckoutSdk\Core\AccessToken";s:129:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/AccessToken.php";s:41:"PayPalCheckoutSdk\Core\AccessTokenRequest";s:136:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/AccessTokenRequest.php";s:44:"PayPalCheckoutSdk\Core\AuthorizationInjector";s:139:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/AuthorizationInjector.php";s:50:"PayPalCheckoutSdk\Core\FPTIInstrumentationInjector";s:145:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/FPTIInstrumentationInjector.php";s:35:"PayPalCheckoutSdk\Core\GzipInjector";s:130:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/GzipInjector.php";s:40:"PayPalCheckoutSdk\Core\PayPalEnvironment";s:135:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/PayPalEnvironment.php";s:39:"PayPalCheckoutSdk\Core\PayPalHttpClient";s:134:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/PayPalHttpClient.php";s:44:"PayPalCheckoutSdk\Core\ProductionEnvironment";s:139:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/ProductionEnvironment.php";s:42:"PayPalCheckoutSdk\Core\RefreshTokenRequest";s:137:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/RefreshTokenRequest.php";s:41:"PayPalCheckoutSdk\Core\SandboxEnvironment";s:136:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/SandboxEnvironment.php";s:32:"PayPalCheckoutSdk\Core\UserAgent";s:127:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/UserAgent.php";s:30:"PayPalCheckoutSdk\Core\Version";s:125:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Core/Version.php";s:47:"PayPalCheckoutSdk\Orders\OrdersAuthorizeRequest";s:142:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Orders/OrdersAuthorizeRequest.php";s:45:"PayPalCheckoutSdk\Orders\OrdersCaptureRequest";s:140:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Orders/OrdersCaptureRequest.php";s:44:"PayPalCheckoutSdk\Orders\OrdersCreateRequest";s:139:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Orders/OrdersCreateRequest.php";s:41:"PayPalCheckoutSdk\Orders\OrdersGetRequest";s:136:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Orders/OrdersGetRequest.php";s:43:"PayPalCheckoutSdk\Orders\OrdersPatchRequest";s:138:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Orders/OrdersPatchRequest.php";s:46:"PayPalCheckoutSdk\Orders\OrdersValidateRequest";s:141:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Orders/OrdersValidateRequest.php";s:55:"PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest";s:150:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Payments/AuthorizationsCaptureRequest.php";s:51:"PayPalCheckoutSdk\Payments\AuthorizationsGetRequest";s:146:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Payments/AuthorizationsGetRequest.php";s:59:"PayPalCheckoutSdk\Payments\AuthorizationsReauthorizeRequest";s:154:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Payments/AuthorizationsReauthorizeRequest.php";s:52:"PayPalCheckoutSdk\Payments\AuthorizationsVoidRequest";s:147:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Payments/AuthorizationsVoidRequest.php";s:45:"PayPalCheckoutSdk\Payments\CapturesGetRequest";s:140:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Payments/CapturesGetRequest.php";s:48:"PayPalCheckoutSdk\Payments\CapturesRefundRequest";s:143:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Payments/CapturesRefundRequest.php";s:44:"PayPalCheckoutSdk\Payments\RefundsGetRequest";s:139:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/Payments/RefundsGetRequest.php";s:45:"Sample\AuthorizeIntentExamples\AuthorizeOrder";s:137:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/AuthorizeIntentExamples/AuthorizeOrder.php";s:43:"Sample\AuthorizeIntentExamples\CaptureOrder";s:135:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/AuthorizeIntentExamples/CaptureOrder.php";s:42:"Sample\AuthorizeIntentExamples\CreateOrder";s:134:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/AuthorizeIntentExamples/CreateOrder.php";s:6:"RunAll";s:127:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/CaptureIntentExamples/RunAll.php";s:41:"Sample\CaptureIntentExamples\CaptureOrder";s:133:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/CaptureIntentExamples/CaptureOrder.php";s:40:"Sample\CaptureIntentExamples\CreateOrder";s:132:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/CaptureIntentExamples/CreateOrder.php";s:11:"ErrorSample";s:110:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/ErrorSample.php";s:15:"Sample\GetOrder";s:107:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/GetOrder.php";s:17:"Sample\PatchOrder";s:109:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/PatchOrder.php";s:19:"Sample\PayPalClient";s:111:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/PayPalClient.php";s:18:"Sample\RefundOrder";s:110:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/samples/RefundOrder.php";s:31:"Test\Orders\OrdersAuthorizeTest";s:123:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/tests/Orders/OrdersAuthorizeTest.php";s:29:"Test\Orders\OrdersCaptureTest";s:121:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/tests/Orders/OrdersCaptureTest.php";s:28:"Test\Orders\OrdersCreateTest";s:120:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/tests/Orders/OrdersCreateTest.php";s:25:"Test\Orders\OrdersGetTest";s:117:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/tests/Orders/OrdersGetTest.php";s:27:"Test\Orders\OrdersPatchTest";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/tests/Orders/OrdersPatchTest.php";s:16:"Test\TestHarness";s:108:"/var/www/html/wp-content/plugins/sail-user-management/libraries/Checkout-PHP-SDK-1.0.1/tests/TestHarness.php";s:25:"GuzzleHttp\BodySummarizer";s:99:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/BodySummarizer.php";s:34:"GuzzleHttp\BodySummarizerInterface";s:108:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/BodySummarizerInterface.php";s:17:"GuzzleHttp\Client";s:91:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Client.php";s:26:"GuzzleHttp\ClientInterface";s:100:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/ClientInterface.php";s:22:"GuzzleHttp\ClientTrait";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/ClientTrait.php";s:27:"GuzzleHttp\Cookie\CookieJar";s:101:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Cookie/CookieJar.php";s:36:"GuzzleHttp\Cookie\CookieJarInterface";s:110:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Cookie/CookieJarInterface.php";s:31:"GuzzleHttp\Cookie\FileCookieJar";s:105:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Cookie/FileCookieJar.php";s:34:"GuzzleHttp\Cookie\SessionCookieJar";s:108:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Cookie/SessionCookieJar.php";s:27:"GuzzleHttp\Cookie\SetCookie";s:101:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Cookie/SetCookie.php";s:41:"GuzzleHttp\Exception\BadResponseException";s:115:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/BadResponseException.php";';
        $str .= 's:36:"GuzzleHttp\Exception\ClientException";s:110:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/ClientException.php";s:37:"GuzzleHttp\Exception\ConnectException";s:111:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/ConnectException.php";s:36:"GuzzleHttp\Exception\GuzzleException";s:110:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/GuzzleException.php";s:45:"GuzzleHttp\Exception\InvalidArgumentException";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/InvalidArgumentException.php";s:37:"GuzzleHttp\Exception\RequestException";s:111:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/RequestException.php";s:36:"GuzzleHttp\Exception\ServerException";s:110:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/ServerException.php";s:46:"GuzzleHttp\Exception\TooManyRedirectsException";s:120:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/TooManyRedirectsException.php";s:38:"GuzzleHttp\Exception\TransferException";s:112:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Exception/TransferException.php";s:20:"GuzzleHttp\functions";s:94:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/functions.php";s:17:"functions_include";s:104:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/functions_include.php";s:30:"GuzzleHttp\Handler\CurlFactory";s:104:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Handler/CurlFactory.php";s:39:"GuzzleHttp\Handler\CurlFactoryInterface";s:113:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Handler/CurlFactoryInterface.php";s:30:"GuzzleHttp\Handler\CurlHandler";s:104:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Handler/CurlHandler.php";s:35:"GuzzleHttp\Handler\CurlMultiHandler";s:109:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Handler/CurlMultiHandler.php";s:29:"GuzzleHttp\Handler\EasyHandle";s:103:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Handler/EasyHandle.php";s:30:"GuzzleHttp\Handler\MockHandler";s:104:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Handler/MockHandler.php";s:24:"GuzzleHttp\Handler\Proxy";s:98:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Handler/Proxy.php";s:32:"GuzzleHttp\Handler\StreamHandler";s:106:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Handler/StreamHandler.php";s:23:"GuzzleHttp\HandlerStack";s:97:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/HandlerStack.php";s:27:"GuzzleHttp\MessageFormatter";s:101:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/MessageFormatter.php";s:36:"GuzzleHttp\MessageFormatterInterface";s:110:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/MessageFormatterInterface.php";s:21:"GuzzleHttp\Middleware";s:95:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Middleware.php";s:15:"GuzzleHttp\Pool";s:89:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Pool.php";s:32:"GuzzleHttp\PrepareBodyMiddleware";s:106:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/PrepareBodyMiddleware.php";s:29:"GuzzleHttp\RedirectMiddleware";s:103:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/RedirectMiddleware.php";s:25:"GuzzleHttp\RequestOptions";s:99:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/RequestOptions.php";s:26:"GuzzleHttp\RetryMiddleware";s:100:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/RetryMiddleware.php";s:24:"GuzzleHttp\TransferStats";s:98:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/TransferStats.php";s:16:"GuzzleHttp\Utils";s:90:"/var/www/html/wp-content/plugins/sail-user-management/libraries/guzzle-7.2.0/src/Utils.php";s:40:"Psr\Http\Client\ClientExceptionInterface";s:114:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-client-1.0.1/src/ClientExceptionInterface.php";s:31:"Psr\Http\Client\ClientInterface";s:105:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-client-1.0.1/src/ClientInterface.php";s:41:"Psr\Http\Client\NetworkExceptionInterface";s:115:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-client-1.0.1/src/NetworkExceptionInterface.php";s:41:"Psr\Http\Client\RequestExceptionInterface";s:115:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-client-1.0.1/src/RequestExceptionInterface.php";s:33:"Psr\Http\Message\MessageInterface";s:105:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-message-1.0/src/MessageInterface.php";s:33:"Psr\Http\Message\RequestInterface";s:105:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-message-1.0/src/RequestInterface.php";s:34:"Psr\Http\Message\ResponseInterface";s:106:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-message-1.0/src/ResponseInterface.php";s:39:"Psr\Http\Message\ServerRequestInterface";s:111:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-message-1.0/src/ServerRequestInterface.php";s:32:"Psr\Http\Message\StreamInterface";s:104:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-message-1.0/src/StreamInterface.php";s:38:"Psr\Http\Message\UploadedFileInterface";s:110:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-message-1.0/src/UploadedFileInterface.php";s:29:"Psr\Http\Message\UriInterface";s:101:"/var/www/html/wp-content/plugins/sail-user-management/libraries/http-message-1.0/src/UriInterface.php";s:39:"MailchimpMarketing\Api\AccountExportApi";s:123:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/AccountExportApi.php";s:40:"MailchimpMarketing\Api\AccountExportsApi";s:124:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/AccountExportsApi.php";s:38:"MailchimpMarketing\Api\ActivityFeedApi";s:122:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/ActivityFeedApi.php";s:40:"MailchimpMarketing\Api\AuthorizedAppsApi";s:124:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/AuthorizedAppsApi.php";s:37:"MailchimpMarketing\Api\AutomationsApi";s:121:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/AutomationsApi.php";s:33:"MailchimpMarketing\Api\BatchesApi";s:117:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/BatchesApi.php";s:39:"MailchimpMarketing\Api\BatchWebhooksApi";s:123:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/BatchWebhooksApi.php";s:41:"MailchimpMarketing\Api\CampaignFoldersApi";s:125:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/CampaignFoldersApi.php";s:35:"MailchimpMarketing\Api\CampaignsApi";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/CampaignsApi.php";s:40:"MailchimpMarketing\Api\ConnectedSitesApi";s:124:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/ConnectedSitesApi.php";s:39:"MailchimpMarketing\Api\ConversationsApi";s:123:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/ConversationsApi.php";s:42:"MailchimpMarketing\Api\CustomerJourneysApi";s:126:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/CustomerJourneysApi.php";s:35:"MailchimpMarketing\Api\EcommerceApi";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/EcommerceApi.php";s:37:"MailchimpMarketing\Api\FacebookAdsApi";s:121:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/FacebookAdsApi.php";s:37:"MailchimpMarketing\Api\FileManagerApi";s:121:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/FileManagerApi.php";s:38:"MailchimpMarketing\Api\LandingPagesApi";s:122:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/LandingPagesApi.php";s:31:"MailchimpMarketing\Api\ListsApi";s:115:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/ListsApi.php";s:30:"MailchimpMarketing\Api\PingApi";s:114:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/PingApi.php";s:35:"MailchimpMarketing\Api\ReportingApi";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/ReportingApi.php";s:33:"MailchimpMarketing\Api\ReportsApi";s:117:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/ReportsApi.php";s:30:"MailchimpMarketing\Api\RootApi";s:114:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/RootApi.php";s:41:"MailchimpMarketing\Api\SearchCampaignsApi";';
        $str .= 's:125:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/SearchCampaignsApi.php";s:39:"MailchimpMarketing\Api\SearchMembersApi";s:123:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/SearchMembersApi.php";s:41:"MailchimpMarketing\Api\TemplateFoldersApi";s:125:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/TemplateFoldersApi.php";s:35:"MailchimpMarketing\Api\TemplatesApi";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/TemplatesApi.php";s:41:"MailchimpMarketing\Api\VerifiedDomainsApi";s:125:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Api/VerifiedDomainsApi.php";s:28:"MailchimpMarketing\ApiClient";s:112:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/ApiClient.php";s:31:"MailchimpMarketing\ApiException";s:115:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/ApiException.php";s:32:"MailchimpMarketing\Configuration";s:116:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/Configuration.php";s:33:"MailchimpMarketing\HeaderSelector";s:117:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/HeaderSelector.php";s:35:"MailchimpMarketing\ObjectSerializer";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/mailchimp-marketing-php-3.0.69/lib/ObjectSerializer.php";s:15:"PayPalHttp\Curl";s:108:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Curl.php";s:18:"PayPalHttp\Encoder";s:111:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Encoder.php";s:22:"PayPalHttp\Environment";s:115:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Environment.php";s:21:"PayPalHttp\HttpClient";s:114:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/HttpClient.php";s:24:"PayPalHttp\HttpException";s:117:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/HttpException.php";s:22:"PayPalHttp\HttpRequest";s:115:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/HttpRequest.php";s:23:"PayPalHttp\HttpResponse";s:116:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/HttpResponse.php";s:19:"PayPalHttp\Injector";s:112:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Injector.php";s:22:"PayPalHttp\IOException";s:115:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/IOException.php";s:26:"PayPalHttp\Serializer\Form";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/Form.php";s:30:"PayPalHttp\Serializer\FormPart";s:123:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/FormPart.php";s:26:"PayPalHttp\Serializer\Json";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/Json.php";s:31:"PayPalHttp\Serializer\Multipart";s:124:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/Multipart.php";s:26:"PayPalHttp\Serializer\Text";s:119:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/Text.php";s:21:"PayPalHttp\Serializer";s:114:"/var/www/html/wp-content/plugins/sail-user-management/libraries/paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer.php";s:37:"GuzzleHttp\Promise\AggregateException";s:105:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/AggregateException.php";s:40:"GuzzleHttp\Promise\CancellationException";s:108:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/CancellationException.php";s:28:"GuzzleHttp\Promise\Coroutine";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/Coroutine.php";s:25:"GuzzleHttp\Promise\Create";s:93:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/Create.php";s:23:"GuzzleHttp\Promise\Each";s:91:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/Each.php";s:30:"GuzzleHttp\Promise\EachPromise";s:98:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/EachPromise.php";s:35:"GuzzleHttp\Promise\FulfilledPromise";s:103:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/FulfilledPromise.php";s:28:"GuzzleHttp\Promise\functions";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/functions.php";s:21:"GuzzleHttp\Promise\Is";s:89:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/Is.php";s:26:"GuzzleHttp\Promise\Promise";s:94:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/Promise.php";s:35:"GuzzleHttp\Promise\PromiseInterface";s:103:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/PromiseInterface.php";s:36:"GuzzleHttp\Promise\PromisorInterface";s:104:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/PromisorInterface.php";s:34:"GuzzleHttp\Promise\RejectedPromise";s:102:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/RejectedPromise.php";s:37:"GuzzleHttp\Promise\RejectionException";s:105:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/RejectionException.php";s:28:"GuzzleHttp\Promise\TaskQueue";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/TaskQueue.php";s:37:"GuzzleHttp\Promise\TaskQueueInterface";s:105:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/TaskQueueInterface.php";s:24:"GuzzleHttp\Promise\Utils";s:92:"/var/www/html/wp-content/plugins/sail-user-management/libraries/promises-1.5.0/src/Utils.php";s:28:"GuzzleHttp\Psr7\AppendStream";s:95:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/AppendStream.php";s:28:"GuzzleHttp\Psr7\BufferStream";s:95:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/BufferStream.php";s:29:"GuzzleHttp\Psr7\CachingStream";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/CachingStream.php";s:30:"GuzzleHttp\Psr7\DroppingStream";s:97:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/DroppingStream.php";s:24:"GuzzleHttp\Psr7\FnStream";s:91:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/FnStream.php";s:22:"GuzzleHttp\Psr7\Header";s:89:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Header.php";s:27:"GuzzleHttp\Psr7\HttpFactory";s:94:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/HttpFactory.php";s:29:"GuzzleHttp\Psr7\InflateStream";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/InflateStream.php";s:30:"GuzzleHttp\Psr7\LazyOpenStream";s:97:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/LazyOpenStream.php";s:27:"GuzzleHttp\Psr7\LimitStream";s:94:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/LimitStream.php";s:23:"GuzzleHttp\Psr7\Message";s:90:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Message.php";s:28:"GuzzleHttp\Psr7\MessageTrait";s:95:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/MessageTrait.php";s:24:"GuzzleHttp\Psr7\MimeType";s:91:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/MimeType.php";s:31:"GuzzleHttp\Psr7\MultipartStream";s:98:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/MultipartStream.php";s:28:"GuzzleHttp\Psr7\NoSeekStream";s:95:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/NoSeekStream.php";s:26:"GuzzleHttp\Psr7\PumpStream";s:93:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/PumpStream.php";s:21:"GuzzleHttp\Psr7\Query";s:88:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Query.php";s:23:"GuzzleHttp\Psr7\Request";s:90:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Request.php";s:24:"GuzzleHttp\Psr7\Response";s:91:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Response.php";s:23:"GuzzleHttp\Psr7\Rfc7230";s:90:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Rfc7230.php";s:29:"GuzzleHttp\Psr7\ServerRequest";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/ServerRequest.php";s:22:"GuzzleHttp\Psr7\Stream";s:89:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Stream.php";s:36:"GuzzleHttp\Psr7\StreamDecoratorTrait";s:103:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/StreamDecoratorTrait.php";s:29:"GuzzleHttp\Psr7\StreamWrapper";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/StreamWrapper.php";s:28:"GuzzleHttp\Psr7\UploadedFile";s:95:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/UploadedFile.php";'; 
        $str .= 's:19:"GuzzleHttp\Psr7\Uri";s:86:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Uri.php";s:29:"GuzzleHttp\Psr7\UriNormalizer";s:96:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/UriNormalizer.php";s:27:"GuzzleHttp\Psr7\UriResolver";s:94:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/UriResolver.php";s:21:"GuzzleHttp\Psr7\Utils";s:88:"/var/www/html/wp-content/plugins/sail-user-management/libraries/psr7-2.0.0/src/Utils.php";s:18:"link-family-member";s:76:"/var/www/html/wp-content/plugins/sail-user-management/link-family-member.php";s:10:"mail-chimp";s:68:"/var/www/html/wp-content/plugins/sail-user-management/mail-chimp.php";s:20:"sail-user-management";s:78:"/var/www/html/wp-content/plugins/sail-user-management/sail-user-management.php";s:11:"update-port";s:69:"/var/www/html/wp-content/plugins/sail-user-management/update-port.php";s:20:"user-change-password";s:78:"/var/www/html/wp-content/plugins/sail-user-management/user-change-password.php";s:20:"user-forgot-password";s:78:"/var/www/html/wp-content/plugins/sail-user-management/user-forgot-password.php";s:11:"user-logout";s:69:"/var/www/html/wp-content/plugins/sail-user-management/user-logout.php";s:12:"user-payment";s:70:"/var/www/html/wp-content/plugins/sail-user-management/user-payment.php";s:17:"user-registration";s:75:"/var/www/html/wp-content/plugins/sail-user-management/user-registration.php";s:11:"user-signon";s:69:"/var/www/html/wp-content/plugins/sail-user-management/user-signon.php";s:11:"user-update";s:69:"/var/www/html/wp-content/plugins/sail-user-management/user-update.php";s:17:"user-verify-email";s:75:"/var/www/html/wp-content/plugins/sail-user-management/user-verify-email.php";s:26:"Sail\Utils\ClassAutoloader";s:79:"/var/www/html/wp-content/plugins/sail-user-management/utils/ClassAutoloader.php";s:19:"Sail\Utils\WebUtils";s:72:"/var/www/html/wp-content/plugins/sail-user-management/utils/WebUtils.php";}';
        self::$classPathMap = unserialize($str);
        $end = hrtime(true);
        error_log('ClassAutoloader: Class path map initialized from string in: ' . (($end - $start)/1e+6) . 'ms');
    }

    public static function autoload($className) {
        self::init(); 
        #self::init_from_string(); 
        #error_log('ClassAutoloader loading: ' . $className);
        if (!class_exists($className, false) && !function_exists($className) && isset(self::$classPathMap[$className])) {
            include_once(self::$classPathMap[$className]);
        } else {
            $split = explode('\\', $className);
            $justClassName = end($split);
            if (!empty($justClassName) && !class_exists($justClassName, false) && !function_exists($justClassName) && isset(self::$classPathMap[$justClassName])) {
                include_once(self::$classPathMap[$justClassName]);
            }
        }
    }

    // This can be deleted if Bluhost migrates to PHP 8
    // https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
    private static function endsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }
}