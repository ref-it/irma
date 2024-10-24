<?php

namespace App\Export\Mailman3;

class RequestsSessionResponse {
    public $status_code;
    public $data;

    public function __construct($status_code, $data) {
        $this->status_code = $status_code;
        $this->data = $data;
    }
}