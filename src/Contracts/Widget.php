<?php

namespace ShopMaestro\Conductor\Contracts;

/**
 *
 */
abstract class Widget implements Interfaces\Widget {
	/**
	 * Unique identifier for the Widget.
	 */
	protected string $id;

	/**
	 * Widget title as displayed in the dashboard
	 */
	protected string $title = '';

	/**
	 * The HTML content for the actual widget.
	 */
	protected string $content = '';


	/**
	 * Number of cols the widget spans in the dashboard
	 */
	protected int $cols = 1;

	/**
	 * Number of rows the widget spans in the dashboard
	 * */
	protected int $rows = 1;

	/**
	 *
	 */
	public function __construct() {

	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function set_id( string $id ): self {
		$this->id = $id;

		return $this;
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function set_title( string $title ): self {
		$this->title = $title;

		return $this;
	}

	/**
	 * @param string $content
	 *
	 * @return $this
	 */
	public function set_content( string $content ): self {
		$this->content = $content;

		return $this;
	}

	public function set_cols( int $cols ): self {
		$this->cols = $cols;

		return $this;
	}

	public function set_rows( int $rows ): self {
		$this->rows = $rows;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_id(): string {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function get_title(): string {
		return apply_filters(
			sprintf( 'shop-maestro/conductor/widget/%s/title', $this->get_id() ),
			$this->title
		);
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	public function get_content(): string {
		return $this->content;
	}

	public function get_cols(): int {
		return $this->cols;
	}

	public function get_rows(): int {
		return $this->rows;
	}

	/**
	 * @return void
	 */
	public function render(): void {
		conductor_template( 'widget', [
			'title' => $this->get_title(),
			'content' => $this->get_content(),
			'cols' => $this->get_cols(),
			'rows' => $this->get_rows(),
		] );
	}
}