<?php

namespace CMS\Controllers\Api;

use BackendMenu\Models\BackendMenu;
use CMS\Components\Controller;
use Favez\ORM\Entity\Collection;

class MenuController extends Controller
{

    public function listAction()
    {
        $this->json()->assign('data', $this->load());

        return $this->json()->success();
    }

    public function load($parentID = null)
    {
        $items      = [];
        $repository = BackendMenu::repository();
        $menus      = $repository->getQuery()->where('parentID', $parentID)->orderBy('position DESC')->fetchAll();
        $menus      = Collection::create(BackendMenu::class, $menus);

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