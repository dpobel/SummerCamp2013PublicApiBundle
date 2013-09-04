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
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

class ExportController extends Controller
{
    public function rssBlogPost()
    {
        $repository = $this->getRepository();

        $contents = array();

        $searchService = $repository->getSearchService();
        $query = new Query();
        $query->limit = 10;
        $query->offset = 0;

        $query->criterion = new Criterion\LogicalAnd(
            array(
                new Criterion\Visibility( Criterion\Visibility::VISIBLE ),
                new Criterion\Subtree( '/1/2' ),
                new Criterion\ContentTypeIdentifier( array( 'blog_post' ) )
            )
        );
        $query->sortClauses = array( new SortClause\Field( 'blog_post', 'publication_date', Query::SORT_DESC ) );

        $results = $searchService->findContent( $query );
        foreach ( $results->searchHits as $hit )
        {
            $contents[] = $hit->valueObject;
        }
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
        $locationService = $this->getRepository()->getLocationService();
        $urlAliasService = $this->getRepository()->getURLAliasService();
        $userService = $this->getRepository()->getUserService();

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

            $item->title = htmlspecialchars( $content->contentInfo->name, ENT_NOQUOTES, 'UTF-8' );

            $guid = $feed->add( 'id' );
            $guid->isPermaLink = false;
            $guid->id = $content->contentInfo->remoteId;

            $location = $locationService->loadLocation( $content->contentInfo->mainLocationId );
            $aliases = $urlAliasService->listLocationAliases(
                $location, false
            );
            $item->link = $request->getUriForPath( $aliases[0]->path );

            $item->pubDate = $content->getFieldValue( 'publication_date' )->value->format( 'U' );
            $item->published = $content->getFieldValue( 'publication_date' )->value->format( 'U' );

            $item->description = strip_tags( $content->getFieldValue( 'body' )->xml->saveXML() );

            $dublincore = $item->addModule( 'DublinCore' );
            $creator = $dublincore->add( 'creator' );

            $owner = $userService->loadUser( $content->contentInfo->ownerId );
            $creator->name = $owner->login;
        }


        $xml = $feed->generate( 'rss2' );
        $response = new Response();
        $response->headers->set( 'content-type', $feed->getContentType() );
        $response->setContent( $xml );
        return $response;
    }
}
