<?php

namespace spec\IntranetMkt;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('IntranetMkt\User');
    }
}
