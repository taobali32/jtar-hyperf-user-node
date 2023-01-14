<?php

namespace Jtar\UserNode\Command;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Symfony\Component\Console\Input\InputArgument;

#[Command]
class ImportUserNodeCommand extends HyperfCommand
{
    protected ?string $name = 'import-user-node';

    public function configure()
    {
        parent::configure();
        $this->setHelp('导入节点');
    }

    public function handle()
    {
        /**
         * @var Model $userModel
         */
        $userModel = new (config('user_node.user_model'));

        $userModel::query()->chunkById(1000, function ($items) use ($userModel){
            foreach ($items as $item) {
                $userModel->createNode($item);
                var_dump("导入ID:" . $item->id);
            }
        });

        $this->info("节点导入成功");

    }

}