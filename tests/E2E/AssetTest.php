<?php

/**
 * This file is part of the contentful/contentful package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Delivery\E2E;

use Contentful\Core\File\File;
use Contentful\Core\File\ImageFile;
use Contentful\Core\Resource\ResourceArray;
use Contentful\Delivery\Query;
use Contentful\Delivery\Resource\Asset;
use Contentful\Delivery\Resource\Environment;
use Contentful\Delivery\Resource\Space;
use Contentful\Tests\Delivery\TestCase;

class AssetTest extends TestCase
{
    /**
     * @vcr e2e_asset_get_all_locale_all.json
     */
    public function testGetAll()
    {
        $client = $this->getClient('cfexampleapi');

        $query = (new Query())
            ->setLocale('*');
        $assets = $client->getAssets($query);

        $this->assertInstanceOf(ResourceArray::class, $assets);
    }

    /**
     * @vcr e2e_asset_get_all_locale_default.json
     */
    public function testGetAllSingleLocale()
    {
        $client = $this->getClient('cfexampleapi');

        $assets = $client->getAssets();

        $this->assertInstanceOf(ResourceArray::class, $assets);
    }

    /**
     * @vcr e2e_asset_get_one_locale_all.json
     */
    public function testGetOne()
    {
        $client = $this->getClient('cfexampleapi');

        $asset = $client->getAsset('nyancat', '*');

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertSame('nyancat', $asset->getId());
        $this->assertInstanceOf(ImageFile::class, $asset->getFile());
        $this->assertInstanceOf(Environment::class, $asset->getEnvironment());
        $this->assertInstanceOf(Space::class, $asset->getSpace());
    }

    /**
     * @vcr e2e_asset_get_one_locale_default.json
     */
    public function testGetOneSingleLocale()
    {
        $client = $this->getClient('cfexampleapi');

        $asset = $client->getAsset('nyancat');

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertSame('nyancat', $asset->getId());
    }

    /**
     * @vcr e2e_asset_included_locale.json
     */
    public function testIncludedAssetLocale()
    {
        $client = $this->getClient('88dyiqcr7go8');

        $query = (new Query())
            ->setInclude(1)
            ->where('sys.id', 'Kpwt1njxgAm04oQYyUScm')
            ->setLocale('es');

        $entry = $client->getEntries($query)[0];
        // This is to make sure that the retrieved asset has the locale code correctly initialized
        $this->assertSame('Ben Chang', $entry->getName());
        $this->assertSame('Señor', $entry->getJobTitle());

        $asset = $entry->getPicture();
        $this->assertSame('es', $asset->getLocale());
        $this->assertSame('Ben Chang', $asset->getTitle());
        $this->assertSame('I AM A SPANISH GENIUS!', $asset->getDescription());

        $this->assertInstanceOf(ImageFile::class, $asset->getFile());
        $this->assertInstanceOf(ImageFile::class, $asset->getFile('es'));
        $this->assertSame('//images.ctfassets.net/88dyiqcr7go8/SQOIQ1rZMQQUeyoyGiEUq/84b6aef287ed214b464114655f99bfa8/ben-chang.jpg', $asset->getFile('es')->getUrl());
    }

    /**
     * @vcr e2e_asset_regular_file.json
     */
    public function testRegularFile()
    {
        $client = $this->getClient('88dyiqcr7go8');

        $asset = $client->getAsset('47kTpd50rSgKQy2acO2u6Y');

        $file = $asset->getFile();
        $this->assertInstanceOf(File::class, $file);
        $this->assertSame('LICENSE.txt', $file->getFileName());
        $this->assertSame(1064, $file->getSize());
        $this->assertSame('text/plain', $file->getContentType());
        $this->assertSame('//assets.ctfassets.net/88dyiqcr7go8/47kTpd50rSgKQy2acO2u6Y/e3d92a018de99b451a323a0fcca0b7b7/LICENSE.txt', $file->getUrl());
    }
}
