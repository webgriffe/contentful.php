<?php

/**
 * This file is part of the contentful/contentful package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Implementation;

use Contentful\Delivery\Resource\Environment;
use Contentful\Delivery\SystemProperties;

class MockEnvironment extends Environment
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public static function withSys($id, $data = [])
    {
        return new static(\array_merge($data, [
            'sys' => new SystemProperties([
                'id' => $id,
                'type' => 'Environment',
            ]),
        ]));
    }
}
