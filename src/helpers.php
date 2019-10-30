<?php

use Coderello\FaviconGenerator\FaviconManipulator;

if (! function_exists('favicon')) {
    function favicon()
    {
        return app(FaviconManipulator::class);
    }
}
