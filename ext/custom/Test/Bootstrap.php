<?php

namespace Test;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{

    public function install()
    {
        $this->createMenu([
            'label' => 'Test',
            'url'   => ''
        ]);
        
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    public function execute()
    {
        $this->registerController('Api', 'Test');
    }

}