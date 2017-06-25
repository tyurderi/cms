<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\User\Group;

class GroupController extends Controller
{

    public function listAction()
    {
        return self::json()->success([
            'data' =>  self::models()->newBuilder(Group::class)->fetchArrayResult()
        ]);
    }

    public function getAction()
    {
        $groupID = self::request()->getParam('id');
        $group   = Group::findByID($groupID);

        if ($group instanceof Group)
        {
            return $this->json()->success([
                'data' => $group->get()
            ]);
        }

        return $this->json()->failure();
    }

    public function saveAction()
    {
        $data  = self::request()->getParams();
        $group = Group::findByID(self::request()->getParam('id'));

        if (!($group instanceof Group))
        {
            $group = new Group();
        }

        $group->set([
            'label' => $data['label']
        ]);

        $result = $group->validate();

        if (isSuccess($result))
        {
            $group->save();

            return $this->json()->success($group->get());
        }

        return $this->json()->failure($result);
    }

    public function removeAction()
    {
        $group = Group::findByID((int) self::request()->getParam('id'));

        if ($group instanceof Group)
        {
            $group->delete();

            return $this->json()->success();
        }

        return $this->json()->failure();
    }
    
}