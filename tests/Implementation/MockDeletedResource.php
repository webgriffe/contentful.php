<?php

/**
 * This file is part of the contentful/contentful package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Implementation;

use Contentful\Delivery\Resource\DeletedResource;

class MockDeletedResource extends DeletedResource
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
