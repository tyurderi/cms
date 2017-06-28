<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\Site\Item;

class PageController extends Controller
{
    
    public function listAction()
    {
        return $this->json()->success([
            'data' => [
                [
                    'id'       => 1,
                    'siteID'   => 1,
                    'parentID' => null,
                    'label'    => 'header',
                    'type'     => Item::TYPE_SECTION,
                    'children' => [
                        [
                            'id'       => 2,
                            'siteID'   => 1,
                            'parentID' => 1,
                            'label'    => 'Home',
                            'type'     => Item::TYPE_ITEM
                        ],
                        [
                            'id'       => 3,
                            'siteID'   => 1,
                            'parentID' => 1,
                            'label'    => 'Projects',
                            'type'     => Item::TYPE_ITEM,
                            'children' => [
                                [
                                    'id'       => 6,
                                    'siteID'   => 1,
                                    'parentID' => 3,
                                    'label'    => 'CMS',
                                    'type'     => Item::TYPE_ITEM
                                ]
                            ]
                        ],
                        [
                            'id'       => 4,
                            'siteID'   => 1,
                            'parentID' => 1,
                            'label'    => 'Contact',
                            'type'     => Item::TYPE_ITEM
                        ],
                        [
                            'id'       => 5,
                            'siteID'   => 1,
                            'parentID' => 1,
                            'label'    => 'Imprint',
                            'type'     => Item::TYPE_ITEM
                        ]
                    ]
                ]
            ]
        ]);
    }
    
}