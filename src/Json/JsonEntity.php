<?php

namespace StructuredNavigation\Json;

/**
 * This provides basic read access to a navigation's JSON
 * which is assumed to have been decoded into an associative
 * PHP array.
 *
 * @license MIT
 */
final class JsonEntity {
	private array $content;

	public function __construct( array $content ) {
		$this->content = $content;
	}

	/**
	 * Returns the entire JSON blob. This should only be used
	 * if you need access to the **entire** blob at once. If
	 * you need more specific details, you should be calling
	 * the other methods instead.
	 */
	public function getContent() : array {
		return $this->content;
	}

	public function getConfig() : array {
		return $this->content['config'];
	}

	public function getTitleLabel() : string {
		return $this->findTitleLabel( $this->getConfig() );
	}

	public function getGroups() : array {
		return $this->content['groups'];
	}

	public function getGroupTitleLabel( array $group ) : string {
		return $this->findTitleLabel( $group );
	}

	public function getGroupContent( array $group ) : array {
		return $group['content'];
	}

	private function findTitleLabel( array $item ) : string {
		return $item['title']['label'];
	}
}
