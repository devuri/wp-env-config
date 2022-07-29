<?php

namespace DevUri\Config;

use DevUri\Config\App\HttpKernel;
use DevUri\Config\App\Traits\KernelTrait;

class Kernel extends HttpKernel
{
    use KernelTrait;
}
