<?php
/**
 * File containing the ExportController class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace EzSystems\SummerCamp2013PublicApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateBlogPostCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName( 'summercamp:create_blog_posts' )
            ->setDescription( 'Create a random blog post' );
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $repository = $this->getContainer()->get( 'ezpublish.api.repository' );
        $blogPostData = array(
            'title' => 'Blog post ' . time(),
            'body' => '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><paragraph xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/">Hello eZ Summer Camp 2013</paragraph></section>',
            'publication_date' => time(),
            'tags' => array( 'summer camp', 'public api', 'import' ),
        );

        $output->writeLn(
            "Creating a blog post with " . var_export( $blogPostData, true )
        );
        $output->writeLn( "FIXME ... :p" );
        // FIXME
        // create the blog_post content
        // Steps:
        // * make to sure to be "identified" as a user that is allowed to create 
        //   content, admin is a good choice here ;-) Hardcoding the user id of
        //   the admin (14) is OK
        // * get the Blog post content type
        // * create a "Blog post" content under the location which id is 90
        // * publish this content
    }

}
