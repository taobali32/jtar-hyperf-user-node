<?php

// 命令行创建表
//  php bin/hyperf create-user-node table

// 发布配置文件
// user_node.php

//  配置配置文件模型

//  用户表引入UserNode Trait

//  导入数据
//  php bin/hyperf import-user-node

// 创建节点,移动节点,删除节点

//  看UserNode Trait

// 查询数据
/**
$id = $this->request->input('id', 1024);

$level = $this->request->input('level', 0);

$count = AppUserNode::query()->where('ancestor', $id)
    ->where('descendant', '<>', $id)
    ->where(function ($q) use ($level) {
        if ($level != 0) {
            $q->where('distance', $level);
        }
    })
    ->count();

return $this->success('团队人数', ['count' => $count]);
 */


