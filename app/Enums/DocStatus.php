<?php
namespace App\Enums;

enum DocStatus: int {

    case DRAFT            = 0;
    case PENDING          = 1;
    case OPEN             = 2;
    case CLOSED           = 3;
    case APPROVED         = 4;
    case DISAPPROVED      = 5;
    case CANCELED         = 6;
    case VOID             = 7;
    case CHECKED          = 8;
    case VERIFIED         = 9;
    case DELIVERED        = 10;
    case PAID             = 11;
    case RECEIVED_IN_FULL = 12;
    case UNPAID           = 13;
    case CLEARED          = 14;
    case POSTED           = 15;
    case UNPOSTED         = 16;
    case REVERSE          = 17;
}
