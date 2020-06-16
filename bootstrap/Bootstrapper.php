<?php


namespace execut\crud\bootstrap;


use execut\navigation\Component;

interface Bootstrapper
{
    public function bootstrapForAdmin(Component $navigation);
}