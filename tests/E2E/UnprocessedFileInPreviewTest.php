<?php

/**
 * This file is part of the contentful.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Core\Api\Link;
use Contentful\Core\File\LocalUploadFile;
use Contentful\Core\File\RemoteUploadFile;
use Contentful\Tests\DeliveryEnd2EndTestCase;

class UnprocessedFileInPreviewTest extends DeliveryEnd2EndTestCase
{
    /**
     * @requires API no-coverage-proxy
     * @vcr e2e_file_get_unprocessed_file.json
     */
    public function testGetUnprocessedFile()
    {
        $client = $this->getClient('88dyiqcr7go8_preview');

        $asset = $client->getAsset('147y8r7Fx4YSEWYAQyggui');

        $file = $asset->getFile();

        $this->assertInstanceOf(RemoteUploadFile::class, $file);
        $this->assertSame('fitzgerald', $file->getFileName());
        $this->assertSame(
            'https://upload.wikimedia.org/wikipedia/commons/5/5c/F_Scott_Fitzgerald_1921.jpg',
            $file->getUpload()
        );
    }

    /**
     * Files uploaded to `https://upload.contentful.com` have an expiration date.
     * This means that the file in this test will expire shortly after the creation of this fixture.
     * Although the response from the Preview API won't change, it will be impossible to process the Asset
     * using the Management API. This is irrelevant for the Delivery API, but it's good to rememember
     * when dealing with the CMA.
     *
     * @requires API no-coverage-proxy
     * @vcr e2e_file_uploaded_from_unprocessed_file.json
     */
    public function testUploadedFromFileUnprocessed()
    {
        $client = $this->getClient('88dyiqcr7go8_preview');

        $asset = $client->getAsset('lp8z7n381EmisqwMgmqW2');

        $file = $asset->getFile();

        $this->assertInstanceOf(LocalUploadFile::class, $file);
        $this->assertSame('Contentful', $file->getFileName());
        $this->assertSame('image/svg+xml', $file->getContentType());
        $this->assertInstanceOf(Link::class, $file->getUploadFrom());
        $this->assertSame('Upload', $file->getUploadFrom()->getLinkType());
    }
}
