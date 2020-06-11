<?php

function createToken()
{
    return bin2hex(random_bytes(16));
}

function jsonDecode($string, $assoc = FALSE)
{
    if (!empty($string)) {
        $result = json_decode($string, $assoc);

        if (json_last_error() === JSON_ERROR_NONE || json_last_error() === 0) {
            return $result;
        }
    }

    return false;
}
