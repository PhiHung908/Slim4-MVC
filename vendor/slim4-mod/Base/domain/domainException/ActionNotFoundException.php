<?php
declare(strict_types=1);

namespace hSlim\base\domain\domainException;

class ActionNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The requested action does not exist.';
}
