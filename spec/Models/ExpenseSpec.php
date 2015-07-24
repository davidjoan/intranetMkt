<?php

namespace spec\IntranetMkt\Models;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExpenseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('IntranetMkt\Models\Expense');
    }
}
