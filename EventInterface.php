<?php

namespace Kiri\Events;

interface EventInterface
{

    /**
     * @return void
     */
    public function process(): void;

}