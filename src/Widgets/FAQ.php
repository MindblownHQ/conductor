<?php

namespace ShopMaestro\Conductor\Widgets;

use ShopMaestro\Conductor\Contracts\Widget;

abstract class FAQ extends Widget {

	/**
	 * Undocumented function
	 */
	public function __construct() {
		parent::__construct();
		$this->set_id( $this->id )
			 ->set_title( $this->title )
			 ->set_name( $this->name )
			 ->set_content( $this->content() );
	}

	/**
	 * Helper method to set the content of the dashboard widget.
	 */
	public function content(): string {

		$html = '<div class="conductor--faq-widget">';
		$questions = $this->get_questions();
		if( !empty( $questions ) ){
			foreach( $questions as $question ){
				$html .= '<details class="conductor--faq-item">';
					$html .= '<summary>'.esc_html( $question['question'] ).'</summary>';
					$html .= '<p>'.esc_html( $question['answer'] ).'</p>';
				$html .= '</details>';
			}
		}
		$html .= '</div>';
		return $html;
	}

	/**
	 * Return the questions in an array
	 *
	 * @return array
	 */
	public function get_questions(): array {
		return [];	
	}
}
