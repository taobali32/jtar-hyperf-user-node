<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Jtar\UserNode;

use EasyTree\Adapter\Handler\ArrayAdapter;


class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'class_map' => [
                    
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'user-node',
                    'description' => 'user-node-config',
                    'source' => __DIR__ . '/../publish/user_node.php',
                    'destination' => BASE_PATH . '/config/autoload/user_node.php',
                ],
            ],
        ];
    }
}
