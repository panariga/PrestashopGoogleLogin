<?php

use PrestaShop\PrestaShop\Adapter\ServiceLocator;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Symfony\Component\HttpClient\HttpClient;


include_once dirname(__FILE__) . '/../../vendor/autoload.php';

class GoogleloginEndpointModuleFrontController extends ModuleFrontController

{
    public string $jwksUri = 'https://www.googleapis.com/oauth2/v3/certs';
    public stdClass $idToken;

    public function postProcess()
    {

        $this->decodeIdToken();

        if ($this->checkAUD() && $this->checkcsrfToken() && $this->checkISS()) {
            $this->login();
        } else {
            $this->errors[] = $this->l('There is an error in processing data. Please, try again later.');
            $this->redirectWithError();
        }

        $this->redirectWithNotifications(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->context->link->getBaseLink());
    }

    public function login()
    {
        try {
            $customer = $this->getCustomer();
            $this->context->updateCustomer($customer);
            CartRule::autoRemoveFromCart($this->context);
            CartRule::autoAddToCart($this->context);
            PrestaShopLogger::addLog('GoogleLogin Module logged in Customer from Google User email:[' . $this->idToken->email . '] ID:[' . $this->idToken->sub . ']', 1, 0, 'Customer', $this->context->customer->id);
        } catch (Exception $e) {
            PrestaShopLogger::addLog('GoogleLogin error executing login() function: ' . $e->getMessage(), 3, 10);
            $this->redirectWithError();
        }
    }



    public function getGoogleKeys()
    {
        try {

            $client  = HttpClient::create();
            $response = $client->request('GET', $this->jwksUri);
            if ($response->getStatusCode() == 200) {
                return  $response->toArray();
            } else {
                sleep(2);
                $response = $client->request('GET', $this->jwksUri);
                if ($response->getStatusCode() == 200) {
                    return  $response->toArray();
                }
            }
        } catch (Exception $e) {
            PrestaShopLogger::addLog('GoogleLogin error executing getGoogleKeys() function: ' . $e->getMessage(), 3, 20);
            error_log($e->getMessage());
            $this->redirectWithError();
        }
    }


    public function decodeIdToken()
    {

        try {
            $this->idToken =  JWT::decode(
                Tools::getvalue('credential', false),
                JWK::parseKeySet($this->getGoogleKeys())
            );
        } catch (Exception $e) {
            PrestaShopLogger::addLog('GoogleLogin error executing decodeIdToken() function: ' . $e->getMessage(), 3, 30);
            error_log($e->getMessage());
            $this->redirectWithError();
        }
    }


    public function checkISS()
    {
        $iss = [
            "https://accounts.google.com",
            "accounts.google.com"
        ];

        if (
            isset($this->idToken->iss) &&
            in_array($this->idToken->iss, $iss)
        ) {
            return true;
        }
        return false;
    }


    public function checkcsrfToken()
    {
        if (
            isset($_COOKIE["g_csrf_token"]) &&
            (Tools::getvalue('g_csrf_token', false) == $_COOKIE["g_csrf_token"])
        ) {
            return true;
        }

        return false;
    }


    public function checkAUD()
    {

        if (
            isset($this->idToken->aud) &&
            ($this->idToken->aud == Configuration::get('GOOGLE_CLIENT_ID', null, null, null, 'default'))
        ) {
            return true;
        }
        return false;
    }


    public function redirectWithError()
    {

        $this->errors[] = $this->l('There is an error in processing data. Please, try again later.');
        $this->redirectWithNotifications(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->context->link->getBaseLink());
    }


    public function createNewCustomer(): Customer
    {
        try {

            $crypto = ServiceLocator::get('\\PrestaShop\\PrestaShop\\Core\\Crypto\\Hashing');
            $customer = new Customer();
            $customer->firstname = $this->idToken->given_name ?? 'Firstname';
            $customer->lastname = $this->idToken->family_name ?? 'Lastname';
            $customer->email = $this->idToken->email;
            $customer->passwd =  $crypto->hash(Tools::passwdGen(16, 'RANDOM'));
            $customer->add();
            $this->success[] = $this->l('Name set to: ') . ' ' . $customer->firstname . ' ' . $customer->lastname;
            $this->success[] = $this->l('You can change info on Account Page');
            PrestaShopLogger::addLog('GoogleLogin Module created new Customer from Google User email:[' . $this->idToken->email . '] ID:[' . $this->idToken->sub . ']', 1, 0, 'Customer', $this->context->customer->id);
            return  $customer;
        } catch (Exception $e) {
            PrestaShopLogger::addLog('GoogleLogin error executing createNewCustomer() function: ' . $e->getMessage(), 3, 10);
            $this->redirectWithError();
        }
    }

    public function getCustomer(): Customer
    {
        try {
            $customerID = Customer::customerExists($this->idToken->email, true);
            if ($customerID) {
                $customer =  new Customer($customerID);
                return $customer;
            } else {
                return $this->createNewCustomer();
            }
        } catch (Exception $e) {
            PrestaShopLogger::addLog('GoogleLogin error executing getCustomer() function: ' . $e->getMessage(), 3, 10);
            $this->redirectWithError();
        }
    }
}
