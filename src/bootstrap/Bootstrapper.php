<?php

/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud\bootstrap;

use execut\navigation\Component;

/**
 * Interface Bootstrapper
 * @package execut\crud
 */
interface Bootstrapper
{
    public function bootstrapForAdmin(Component $navigation);
}
