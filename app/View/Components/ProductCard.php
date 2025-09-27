<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
	/**
	 * Create a new component instance.
	 */
	public function __construct(
		public string $id,
		public string $image,
		public string $title,
		public string $slug,
		public float $price,
		public ?float $originalPrice = null,
		public ?string $category = null,
		public ?string $type = null,
		public bool $onSale = false
	) {
		//
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.product-card');
	}
}
