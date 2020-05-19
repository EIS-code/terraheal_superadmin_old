<?php

function createToken()
{
    return bin2hex(random_bytes(16));
}
