<?php

namespace App\Export\Mailman3;

use App\Export\Mailman3\RequestsSession;

class Mailman3 {
    private $rs;
    private $api_rs;
    private $user;

    private $baseURL;
    private $apiBaseURL;

    public function __construct() {
        if (getenv('MAILMAN3_BASE_URL')) {
            $this->baseURL = env('MAILMAN3_BASE_URL');
            $this->rs = new RequestsSession(env('MAILMAN3_BASE_URL') . "/", false);
        } else {
            // TODO Error
        }

        if (getenv('MAILMAN3_API_BASE_URL')) {
            $this->apiBaseURL = env('MAILMAN3_API_BASE_URL');
            $this->api_rs = new RequestsSession("", true);
        } else {
            // TODO Error
        }
    }

    private function getCSRFToken($response) {
        preg_match('/(<input type="hidden" name="csrfmiddlewaretoken" value=")(\w*)/', $response, $matches);
        if (count($matches) > 2) {
            return $matches[2];
        } else {
            return "";
        }
    }

    private function checkIsLoggedIn($response) {
        preg_match('/<img.+\/>\n +(\w+)\n +<span.+><\/span>/', $response, $checkLoginMatches);

        if (count($checkLoginMatches) > 1) {
            if ($checkLoginMatches[1] == $this->user) return true;
        }

        return false;
    }

    private function checkHasAccessToList($response, $list) {
        preg_match('/<div class="page-header">\n.+<small>(.+)<\/small><\/h1>/', $response, $hasAccessMatches);

        if (count($hasAccessMatches) > 1) {
            if (strtolower($hasAccessMatches[1]) == $list) return true;
        }

        return false;
    }

    public function login() {
        if (getenv('MAILMAN3_USERNAME')) {
            $this->user = env('MAILMAN3_USERNAME');
        } else {
            // TODO Error
        }
        $loginForm = $this->rs->get($this->baseURL . '/accounts/login/?next=/postorius/lists/')->data;
        $csrftoken = $this->getCSRFToken($loginForm);

        $postfields = [];

        if (getenv('MAILMAN3_USERNAME') && getenv('MAILMAN3_PASSWORD')) {
            $postfields = [
                "csrfmiddlewaretoken" => $csrftoken,
                "login" => env('MAILMAN3_USERNAME'),
                "password" => env('MAILMAN3_PASSWORD'),
                "next" => "/postorius/lists/"
            ];
        }

        $this->rs->post($this->baseURL . '/accounts/login/', $postfields);
        $loginResponse = $this->rs->get($this->baseURL . '/postorius/lists/')->data;
        return $this->checkIsLoggedIn($loginResponse);
    }

    public function logout() {
        if(empty($this->user)) {
            return true;
        }

        $url = $this->baseURL . '/accounts/logout/';

        $logoutForm = $this->rs->get($url)->data;
        $csrftoken = $this->getCSRFToken($logoutForm);

        $postfields = array(
            "csrfmiddlewaretoken" => $csrftoken,
            "next" => "/postorius/lists/"
        );

        $this->rs->post($url, $postfields);

        return true;
    }

    public function apiLogin() {
        $httpBasicAuthCredString = "";
        if (getenv("MAILMAN3_API_USERNAME") && getenv("MAILMAN3_API_PASSWORD")) {
            $httpBasicAuthCredString =  env('MAILMAN3_API_USERNAME') . ":" . env('MAILMAN3_API_PASSWORD');
        }
        $this->api_rs->httpBasicAuth = true;
        $this->api_rs->httpBasicAuthCredString = $httpBasicAuthCredString;
        return true;
    }

    public function addListOwner($list, $ownerMail) {
        $list = strtolower($list);
        $ownerMail = strtolower($ownerMail);
        $url = $this->baseURL . '/postorius/lists/' . $list . '/members/owner/';
        $addOwnerForm = $this->rs->get($url)->data;

        if (!$this->checkHasAccessToList($addOwnerForm, $list)) {
            return false;
        }

        $csrftoken = $this->getCSRFToken($addOwnerForm);

        $postfields = array(
            "csrfmiddlewaretoken" => $csrftoken,
            "email" => $ownerMail,
            "display_name" => ""
        );

        $this->rs->post($url, $postfields);

        return true;
    }

    public function removeListOwner($list, $ownerMail) {
        $list = strtolower($list);
        $ownerMail = strtolower($ownerMail);
        $listOwnersUrl = $this->baseURL . '/postorius/lists/' . $list . '/members/owner/';
        $removeOwnerUrl = $this->baseURL . '/postorius/lists/' . $list . '/remove/owner/' . $ownerMail;

        if (!$this->checkHasAccessToList($this->rs->get($listOwnersUrl)->data, $list)) {
            return false;
        }

        $removeOwnerForm = $this->rs->get($removeOwnerUrl)->data;

        $csrftoken = $this->getCSRFToken($removeOwnerForm);

        $postfields = array(
            "csrfmiddlewaretoken" => $csrftoken
        );

        $this->rs->post($removeOwnerUrl, $postfields);

        return true;
    }

    public function getListRoster($list, $role) {
        $list = strtolower($list);
        $url = $this->apiBaseURL . '/3.0/lists/' . $list . '/roster/'. $role;

        $result = $this->api_rs->get($url);

        if ($result->status_code != 200) {
            return array();
        }

        $rosterList = array();

        $resultArray = json_decode($result->data, true);

        if (!array_key_exists('entries', $resultArray)) {
            return array();
        }

        foreach ($resultArray['entries'] as $entry) {
            array_push($rosterList, strtolower($entry['email']));
        }

        return $rosterList;
    }

    public function addListMembers($list, $mails) {
        $list = strtolower($list);

        if(!is_array($mails)) {
            return false;
        }

        if(empty($mails)) {
            return true;
        }

        $url = $this->baseURL . '/postorius/lists/' . $list . '/mass_subscribe/';
        $massSubscribeForm = $this->rs->get($url)->data;

        if (!$this->checkHasAccessToList($massSubscribeForm, $list)) {
            return false;
        }

        $csrftoken = $this->getCSRFToken($massSubscribeForm);

        $mailAddresses = "";
        foreach($mails as $mail) {
            $mailAddresses .= strtolower($mail) . "\n";
        }

        $postfields = array(
            "csrfmiddlewaretoken" => $csrftoken,
            "emails" => $mailAddresses,
            "pre_confirmed" => true,
            "pre_approved" => true,
            "pre_verified" => true
        );

        $this->rs->post($url, $postfields);

        return true;
    }

    public function removeListMembers($list, $mails) {
        $list = strtolower($list);

        if(!is_array($mails)) {
            return false;
        }

        if(empty($mails)) {
            return true;
        }

        $url = $this->baseURL . '/postorius/lists/' . $list . '/mass_removal/';
        $massRemovalForm = $this->rs->get($url)->data;

        if (!$this->checkHasAccessToList($massRemovalForm, $list)) {
            return false;
        }

        $csrftoken = $this->getCSRFToken($massRemovalForm);

        $mailAddresses = "";
        foreach($mails as $mail) {
            $mailAddresses .= strtolower($mail) . "\n";
        }

        $postfields = array(
            "csrfmiddlewaretoken" => $csrftoken,
            "emails" => $mailAddresses
        );

        $this->rs->post($url, $postfields);

        return true;
    }
}
