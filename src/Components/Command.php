<?php

namespace CMS\Components;

use Favez\Mvc\DI\Injectable;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    use Injectable;
}