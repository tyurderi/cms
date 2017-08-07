<?php

namespace CMS\Controllers\Frontend;

use CMS\Components\Controller;
use CMS\Models\Domain\Domain;

class IndexController extends Controller
{

    public function indexAction()
    {
        $domain = $this->domain();

        if ($domain instanceof Domain)
        {
            return $domain->id;
        }
        else
        {
            return '404 Not Found';
        }
    }

}