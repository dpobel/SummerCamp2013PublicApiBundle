<?php
/**
 * File containing the ExportController class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace EzSystems\SummerCamp2013PublicApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExportControllerTest extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testRssBlogPostContentType()
    {
        $crawler = $this->client->request('GET', '/summercamp/rssblogposts');
        $response = $this->client->getResponse();

        $this->assertEquals(
            $response->headers->get( 'content-type' ),
            "application/rss+xml",
            "The content type of the response should be application/rss+xml"
        );
    }

    public function testRssBlogPostItemCount()
    {
        $crawler = $this->client->request('GET', '/summercamp/rssblogposts');
        $response = $this->client->getResponse();

        $repository = static::$kernel->getContainer()->get( 'ezpublish.api.repository' );
        // FIXME
        // get the total number of blog_post
        // and adapt the test to check for the exact number of blog_post or 10

        $items = $crawler->filterXpath( '//item' );
        $this->assertTrue(
            $items->count() > 0,
            'The RSS feed should return some elements'
        );
        $this->assertTrue(
            $items->count() <= 10,
            'The RSS feed should return less than 10 elements'
        );

    }

    public function testRssBlogPostItemsContent()
    {
        $crawler = $this->client->request('GET', '/summercamp/rssblogposts');
        $response = $this->client->getResponse();

        $items = $crawler->filterXpath( '//item' );
        foreach ( $items as $item )
        {
            foreach ( $item->childNodes as $child )
            {
                if ( $child->nodeType === XML_ELEMENT_NODE )
                {
                    $this->assertTrue(
                        stripos( 'FIXME', $child->nodeValue ) === false,
                        $child->nodeName . ' not fixed (' . $child->nodeValue . ')'
                    );
                }
            }
        }
    }
}
