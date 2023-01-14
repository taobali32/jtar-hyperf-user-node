<?php

namespace Jtar\UserNode\Command;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\DbConnection\Db;
use Symfony\Component\Console\Input\InputArgument;

#[Command]
class CreateUserNodeTableCommand extends HyperfCommand
{
    protected ?string $name = 'create-user-node';

    public function configure()
    {
        parent::configure();
        $this->setHelp('创建用户节点表');

        $this->addArgument('table', InputArgument::OPTIONAL, '表名字', 'user_node');
    }

    public function handle()
    {
        $table_name = $this->input->getArgument('table');

        $sql = "CREATE TABLE `{$table_name}`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ancestor` int(11) NULL DEFAULT NULL COMMENT '祖先',
  `descendant` int(11) NULL DEFAULT NULL COMMENT '后代',
  `distance` int(11) NULL DEFAULT NULL COMMENT '距离',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户关系记录' ROW_FORMAT = DYNAMIC;
";

        Db::select($sql);  //  返回array
        //  判读表是否存在

        $this->info("{$table_name}表创建成功");
    }

}