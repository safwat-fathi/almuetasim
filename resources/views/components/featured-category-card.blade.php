@php
	// If no explicit icon is given, guess an appropriate lucide icon from slug/title keywords (supports Arabic + English)
	$iconGuess = $icon ?? null;
	if (empty($iconGuess)) {
		$text = mb_strtolower($title . ' ' . ($slug ?? ''));
		// Arabic keyword mapping
		if (str_contains($text, 'فلتر') || str_contains($text, 'فلاتر') || str_contains($text, 'مرشح')) {
			$iconGuess = 'filter';
		} elseif (str_contains($text, 'ماء') || str_contains($text, 'محطات') || str_contains($text, 'فلترة') || str_contains($text, 'مياه')) {
			$iconGuess = 'droplet';
		} elseif (str_contains($text, 'قطع') || str_contains($text, 'قطع غيار') || str_contains($text, 'اسطمبة') || str_contains($text, 'قطع')) {
			$iconGuess = 'package';
		} elseif (str_contains($text, 'تركيب') || str_contains($text, 'خدمة') || str_contains($text, 'صيانة')) {
			$iconGuess = 'settings';
		} elseif (str_contains($text, 'مواسير') || str_contains($text, 'وصلات')) {
			$iconGuess = 'share-2';
		} elseif (str_contains($text, 'مضخة') || str_contains($text, 'pump')) {
			$iconGuess = 'cpu';
		} else {
			// English keyword fallback
			if (str_contains($text, 'filter')) {
				$iconGuess = 'filter';
			} elseif (str_contains($text, 'water')) {
				$iconGuess = 'droplet';
			} elseif (str_contains($text, 'parts') || str_contains($text, 'spare')) {
				$iconGuess = 'package';
			} elseif (str_contains($text, 'service') || str_contains($text, 'installation')) {
				$iconGuess = 'settings';
			} else {
				$iconGuess = 'box';
			}
		}
	}
@endphp

<a href="{{ url('/category', $slug) }}">
	<div class="card bg-base-200 shadow-xl cursor-pointer transition-all duration-300 hover:scale-105 hover:bg-base-300">
			<div class="card-body text-center h-60 max-h-60">
					<i data-lucide="{{ $iconGuess }}" class="w-12 h-12 mx-auto mb-4 text-primary"></i>
					<h3 class="card-title justify-center line-clamp-2">{{ $title }}</h3>
					<p class="line-clamp-2">{{ $description }}</p>
			</div>
	</div>
</a>