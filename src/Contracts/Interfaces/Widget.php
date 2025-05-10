<?php

namespace ShopMaestro\Conductor\Contracts\Interfaces;

interface Widget {

	public function set_title( string $title ): self;

	public function set_name( string $name ): self;

	public function set_content( string $content ): self;

	public function render(): void;
}