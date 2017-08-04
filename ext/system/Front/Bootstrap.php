<?php

namespace Front;

use Favez\Mvc\App;
use Front\Components\DomainFinder;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{

    public function execute()
    {
        App::di()->registerShared('domain', function() {
            static $domain = null;

            if ($domain === null)
            {
                $finder = new DomainFinder();
                $domain = $finder->find();
            }

            return $domain;
        });

        $this->registerController('Frontend', 'Index');
    }

}