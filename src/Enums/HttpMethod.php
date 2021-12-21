<?php

    declare(strict_types=1);

    namespace Stui\StatuspageIo\Enums;

    class HttpMethod extends Enum
    {
        public const GET = 'GET';
        public const POST = 'POST';
        public const PATCH = 'PATCH';
        public const PUT = 'PUT';
        public const DELETE = 'DELETE';
    }
