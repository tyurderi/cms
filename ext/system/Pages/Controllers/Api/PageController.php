<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\Page\Page;

class PageController extends Controller
{
    
    public function listAction()
    {
        return $this->json()->success([
            'data' => [
                [
                    'id'       => 1,
                    'domainID' => 1,
                    'parentID' => null,
                    'label'    => 'header',
                    'type'     => Page::TYPE_SECTION,
                    'children' => [
                        [
                            'id'       => 2,
                            'domainID' => 1,
                            'parentID' => 1,
                            'label'    => 'Home',
                            'type'     => Page::TYPE_ITEM
                        ],
                        [
                            'id'       => 3,
                            'domainID' => 1,
                            'parentID' => 1,
                            'label'    => 'Projects',
                            'type'     => Page::TYPE_ITEM,
                            'children' => [
                                [
                                    'id'       => 6,
                                    'domainID' => 1,
                                    'parentID' => 3,
                                    'label'    => 'CMS',
                                    'type'     => Page::TYPE_ITEM
                                ]
                            ]
                        ],
                        [
                            'id'       => 4,
                            'domainID' => 1,
                            'parentID' => 1,
                            'label'    => 'Contact',
                            'type'     => Page::TYPE_ITEM
                        ],
                        [
                            'id'       => 5,
                            'domainID' => 1,
                            'parentID' => 1,
                            'label'    => 'Imprint',
                            'type'     => Page::TYPE_ITEM
                        ]
                    ]
                ]
            ]
        ]);
    }
    
}