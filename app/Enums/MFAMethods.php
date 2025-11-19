<?php

namespace App\Enums;

enum MFAMethods: int
{
    case NONE = 0;
    case EMAIL = 1;
    case SMS = 2;
    case GOOGLE_AUTHENTICATOR = 3;
}
