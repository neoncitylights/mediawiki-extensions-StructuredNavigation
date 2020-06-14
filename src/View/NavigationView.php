<?php

namespace StructuredNavigation\View;

use OOUI\HtmlSnippet;
use OOUI\Tag;
use StructuredNavigation\Libs\OOUI\Element\DescriptionList;
use StructuredNavigation\Libs\OOUI\Element\UnorderedList;
use StructuredNavigation\Navigation;

/**
 * Renders a structured navigation view. This by default does
 * not load any modules on the rendering event, it simply
 * provides a base HTML structure that's meant to be semantic
 * and flexible. Consumers are expected to provide their own
 * ResourceLoader modules (as of now)
 *
 * @license MIT
 */
final class NavigationView {
	private const CSS_CLASS = [
		'groups' => 'mw-structurednav-groups-container',
		'group' => 'mw-structurednav-group',
		'group-title' => 'mw-structurednav-group-title',
		'group-content' => 'mw-structurednav-group-content',
		'group-content-list' => 'mw-structurednav-group-content-list',
		'group-content-item' => 'mw-structurednav-group-content-item',
		'group-content-link' => 'mw-structurednav-group-content-link',
		'header' => 'mw-structurednav-header',
		'header-title' => 'mw-structurednav-header-title',
		'nav' => 'mw-structurednav-navigation-container'
	];

	private ContentLinkView $contentLinkView;

	public function __construct( ContentLinkView $contentLinkView ) {
		$this->contentLinkView = $contentLinkView;
	}

	public function getView( Navigation $navigation ) : Tag {
		return ( new Tag( 'nav' ) )
			->addClasses( [ self::CSS_CLASS['nav'] ] )
			->appendContent( new HtmlSnippet(
				$this->doRenderHeader( $navigation ) .
				$this->doRenderGroups( $navigation )
			) );
	}

	private function doRenderHeader( Navigation $navigation ) : Tag {
		return ( new Tag( 'header' ) )
			->addClasses( [ self::CSS_CLASS['header'] ] )
			->appendContent( new HtmlSnippet(
				( new Tag( 'h2' ) )
					->addClasses( [ self::CSS_CLASS['header-title'] ] )
					->appendContent( $navigation->getTitleLabel() )
					->toString()
			) );
	}

	private function doRenderGroups( Navigation $navigation ) : DescriptionList {
		$allGroups = [];
		$groups = $navigation->getGroups();

		foreach ( $groups as $group ) {
			$allGroups[] = [
				'term' => $group->getLabel(),
				'detail' => $this->doRenderContent( $group->getLinks() )
			];
		}

		return new DescriptionList( [
			'items' => $allGroups,
			'container-attributes' => [ 'class' => self::CSS_CLASS['group'] ],
			'term-attributes' => [ 'class' => self::CSS_CLASS['group-title'] ],
			'detail-attributes' => [ 'class' => self::CSS_CLASS['group-content'] ],
			'classes' => [ self::CSS_CLASS['groups'] ]
		] );
	}

	private function doRenderContent( array $navigationLinks ) : UnorderedList {
		$allContent = [];

		foreach ( $navigationLinks as $navigationLink ) {
			$allContent[] = $this->contentLinkView->getLink(
				$navigationLink,
				[ 'class' => self::CSS_CLASS['group-content-link'] ]
			);
		}

		return new UnorderedList( [
			'items' => $allContent,
			'item-attributes' => [ 'class' => self::CSS_CLASS['group-content-item'] ],
			'classes' => [ self::CSS_CLASS['group-content-list'] ]
		] );
	}
}
