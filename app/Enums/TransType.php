<?php

namespace App\Enums;

 enum TransType: int
{
    case INSERT = 1;
    case UPDATE = 2;
    case DELETE = 3;
    case POST = 4;
    case UNPOST = 5;
    case REVERSE = 6;
    case UPLOAD = 7;
}
