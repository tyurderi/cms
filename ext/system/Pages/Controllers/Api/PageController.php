<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\Page\Page;

class PageController extends Controller
{
    
    public function listAction()
    {
        return $this->json()->success([
            'data' => Page::repository()->getQuery()->fetchAll()
        ]);
    }

    public function saveAction()
    {
        $id   = $this->request()->getParam('id');
        $data = $this->request()->getParams();
        $page = Page::findByID((int) $id);

        unset($data['id']);

        if (!($page instanceof Page))
        {
            $page = new Page();
            $page->created = date('Y-m-d H:i:s');
        }

        $page->changed = date('Y-m-d H:i:s');
        $page->set($data);

        $page->save();

        return $this->json()->success([
            'data' => $page->get()
        ]);
    }

    public function removeAction()
    {
        $id   = $this->request()->getParam('id');
        $page = Page::findByID($id);

        if ($page instanceof Page)
        {
            $this->removeRecursive($page);

            return $this->json()->success();
        }

        return $this->json()->failure();
    }

    /**
     * Deletes a page and its children recursive.
     *
     * @param Page $page
     */
    private function removeRecursive(Page $page)
    {
        if ($page->getRelated('children')->count())
        {
            foreach ($page->getRelated('children') as $children)
            {
                $this->removeRecursive($children);
            }
        }

        $page->delete();
    }

}