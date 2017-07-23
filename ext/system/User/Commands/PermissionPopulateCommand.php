<?php

namespace User\Commands;

use CMS\Components\Command;
use CMS\Models\Permission\Permission;
use CMS\Models\Permission\Value;
use CMS\Models\User\Group;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PermissionPopulateCommand extends Command
{

    public function configure()
    {
        $this->setName('permission:populate')
            ->setDescription('Populates all permission to a desired group.');

        $this->addOption('groupID', 'g', InputOption::VALUE_REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $groupID = (int) $input->getOption('groupID');
        $group   = Group::findByID($groupID);

        if (!($group instanceof Group))
        {
            throw new \Exception('Group by id not found');
        }

        $permissions = Permission::findAll();

        /** @var Permission $permission */
        foreach ($permissions as $permission)
        {
            $value = Value::findOneBy([
                'groupID'      => $groupID,
                'permissionID' => $permission->id
            ]);

            if (!($value instanceof Value))
            {
                $value = new Value();
                $value->groupID      = $groupID;
                $value->permissionID = $permission->id;
            }

            $value->value = 1;
            $value->save();
        }
    }

}