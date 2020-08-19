<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud\bootstrap;

/**
 * Interface for CRUD module
 * @package execut\crud
 */
interface Module
{
    /**
     * Returns admin role string
     * @return string
     */
    public function getAdminRole();
}
