<?php

namespace DevUri\Config;

use DevUri\Config\App\Traits\KernelTrait;
use DevUri\Config\App\HttpKernel;

class Kernel extends HttpKernel
{
    use KernelTrait;
}
