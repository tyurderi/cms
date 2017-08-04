<?php

namespace CMS\Controllers\Frontend;

use CMS\Components\Controller;
use CMS\Models\Domain\Domain;
use Slim\Http\Response;

class IndexController extends Controller
{

    public function indexAction()
    {
        $domain = $this->domain();

        if ($domain instanceof Domain)
        {
            return $domain->id;
        }
        else if($domain instanceof Response)
        {
            return $domain;
        }
        else
        {
            return '404 Not Found';
        }
    }

}