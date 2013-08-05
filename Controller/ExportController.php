<?php
/**
 * File containing the ExportController class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace EzSystems\SummerCamp2013PublicApiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use ezcFeed;

class ExportController extends Controller
{
    public function rssBlogPost()
    {
        $repository = $this->getRepository();

        // FIXME
        // put in $contents array the 10 last blog posts
        // ordered by publication date (field publication_date) DESC
        $contents = array();

        return $this->feedResponse( 'Blog RSS feed', $contents );
    }


    /**
     * Build a response for the RSS feed based on the title and the array of 
     * content
     *
     * @param string $title
     * @param eZ\Publish\API\Repository\Values\Content\Content[] $contents
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function feedResponse( $title, array $contents )
    {
        $request = $this->getRequest();

        $feed = new ezcFeed();
        $feed->title = $title;
        $feed->description = '';
        $feed->published = time();

        $link = $feed->add( 'link' );
        $link->href = $request->getUriForPath( $request->getRequestUri() );

        foreach ( $contents as $content )
        {
            /** @var $content eZ\Publish\API\Repository\Values\Content\Content */
            $item = $feed->add( 'item' );

            $item->title = htmlspecialchars( 'FIXME NAME OF THE CONTENT', ENT_NOQUOTES, 'UTF-8' );

            $guid = $feed->add( 'id' );
            $guid->isPermaLink = false;
            $guid->id = 'FIXME REMOTE ID OF THE CONTENT';

            $item->link = 'FIXME full link to the post';

            $item->pubDate = 1234; // FIXME publication date (timestamp in publication_date field)
            $item->published = 1234; // FIXME same as above

            $item->description = 'FIXME BLOG POST CONTENT'; // strip_tags( ezxml ) is ok for now

            $dublincore = $item->addModule( 'DublinCore' );
            $creator = $dublincore->add( 'creator' );

            $creator->name = 'FIXME NAME OF THE OWNER';
        }


        $xml = $feed->generate( 'rss2' );
        $response = new Response();
        $response->headers->set( 'content-type', $feed->getContentType() );
        $response->setContent( $xml );
        return $response;
    }
}
