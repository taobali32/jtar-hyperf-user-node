<?php

namespace Jtar\UserNode;

use Hyperf\Database\Model\Model;

trait UserNode
{
    public function createNode(Model $user): void
    {
        $userNodeModel = new (config('user_node.user_node_model'));

        $userNodeModel::query()->create([
            'ancestor' => $user->id,
            'descendant' => $user->id,
            'distance' => 0,
        ]);

        if ($user->parent_id != 0){
            $gets = $userNodeModel::query()->where('descendant', $user->parent_id)->get();

            foreach ($gets as $k => $v) {
                $ins['ancestor'] = $v->ancestor;
                $ins['descendant'] = $user->id;
                $ins['distance'] = $v->distance + 1;
                $userNodeModel::query()->create($ins);
            }
        }
    }

    /**
     *
     * @param int $move_node_id 需要移动的节点
     * @param int $to_node_id   要移动到那个节点下面
     * @return void
     */
    public function moveNode($move_node_id,$to_node_id): void
    {
        $userNodeModel = new (config('user_node.user_node_model'));

        $userModel = new (config('user_node.user_model'));

        //  先处理自己节点
        $userNodeModel::query()->where('descendant', $move_node_id)->delete();
        $userNodeModel::query()->create([
            'ancestor' => $move_node_id,
            'descendant' => $move_node_id,
            'distance' => 0,
        ]);

        // 找到要移动的节点的所有后代.
        $gets = $userNodeModel::query()->where('descendant', $to_node_id)->get();

        // 循环遍历要移动的所有后代. 把他的后代给要移动到那个节点下面
        foreach ($gets as $k => $v) {

            $ins['ancestor'] = $v->ancestor;
            $ins['descendant'] = $move_node_id;
            $ins['distance'] = $v->distance + 1;

            $userNodeModel::query()->create($ins);
        }

        //  找到要移动的节点的 后台 1层的
        $data = $userNodeModel::query()->where('ancestor', $move_node_id)
            ->where('distance', 1)
            ->get();
        //
        if ($data->count()) {
            //  循环所有下级节点 => 重构关系
            foreach ($data as $k => $v) {
                // 删除所有关系重新录入关系
                $next_node_id = $v->descendant;
                $next_node_parent = $v->ancestor;


                $userNodeModel::query()->where('descendant', $next_node_id)->delete();

                //  创建0-节点  ->
                $userNodeModel::query()->create([
                    'ancestor' => $next_node_id,
                    'descendant' => $next_node_id,
                    'distance' => 0,
                ]);

                //  找到老的上级节点
                $parentComment = $userModel::query()->where('id', $next_node_parent)->first();

                //  上例里面的所有下级
                $gets = $userNodeModel::query()->where('descendant', $parentComment->id)->get();

                foreach ($gets as $kk => $vv) {
                    $ins['ancestor'] = $vv->ancestor;
                    $ins['descendant'] = $v->descendant;
                    $ins['distance'] = $vv->distance + 1;
                    $userNodeModel::query()->create($ins);
                }
            }
        }

        $userModel::query()->find($move_node_id)->update(['parent_id' => $to_node_id]);
    }

    public function deleteNode($id): void
    {
        $userNodeModel = new (config('user_node.user_node_model'));

        /**
         * @var Model $userModel
         */
        $userModel = new (config('user_node.user_model'));

        $res = $userNodeModel::query()->where('ancestor', $id)
            ->distinct('descendant')
            ->pluck('descendant');

        $ids = $res->toArray();

        // 删除树
        $userNodeModel::query()->whereIn('descendant', $ids)->delete();

        $userModel::query()->whereIn('id',$ids)->update(['parent_id' => 0]);
    }
}