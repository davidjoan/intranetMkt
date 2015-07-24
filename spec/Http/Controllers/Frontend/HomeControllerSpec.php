<?php

namespace spec\IntranetMkt\Http\Controllers\Frontend;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HomeControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('IntranetMkt\Http\Controllers\Frontend\HomeController');
    }
}
