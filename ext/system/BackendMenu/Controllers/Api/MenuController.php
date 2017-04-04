<?php

namespace CMS\Controllers\Api;

use BackendMenu\Models\BackendMenu;
use CMS\Components\Controller;

class MenuController extends Controller
{

    public function listAction()
    {
        $this->json()->assign('data', $this->load());

        return $this->json()->success();
    }

    public function load($parentID = null)
    {
        $items = [];
        $menus = BackendMenu::repository()->findBy(['parentID' => $parentID]);

        /** @var BackendMenu $menu */
        foreach ($menus as $menu)
        {
            $item             = $menu->get();
            $item['children'] = $this->load($menu->id);

            $items[] = $item;
        }

        return $items;
    }

}