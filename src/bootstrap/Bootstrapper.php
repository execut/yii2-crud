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
    /**
     * Bootstrap navigation for CRUD admin
     * @param Component $navigation
     * @return null
     */
    public function bootstrapForAdmin(Component $navigation);
}
