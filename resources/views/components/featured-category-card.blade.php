@php
	// If no explicit icon is given, guess an appropriate lucide icon from slug/title keywords (supports Arabic + English)
	$iconGuess = $icon ?? null;
	if (empty($iconGuess)) {
		$text = mb_strtolower($title . ' ' . ($slug ?? ''));

		// Keyword => icon mapping (ordered). Add Arabic and English keywords.
		$mapping = [
			['keywords' => ['فلتر','فلاتر','filter','filters','مرشح'], 'icon' => 'filter'],
			['keywords' => ['ماء','مياه','محطات','water','pump','pumps'], 'icon' => 'droplet'],
			['keywords' => ['قطع غيار','قطع','parts','spare','spares'], 'icon' => 'package'],
			['keywords' => ['خدمة','صيانة','service','installation','install'], 'icon' => 'settings'],
			['keywords' => ['مواسير','وصلات','pipes','pipe','hose'], 'icon' => 'share-2'],
			['keywords' => ['مضخة','pump','pumpstation','مضخات'], 'icon' => 'cpu'],
			['keywords' => ['ترشيح','فلترة','purify'], 'icon' => 'wind'],
			['keywords' => ['محطة','station','stations'], 'icon' => 'map-pin'],
			['keywords' => ['توصيل','شحن','delivery','truck'], 'icon' => 'truck'],
			['keywords' => ['تخفيض','عرض','sale','discount'], 'icon' => 'tag'],
		];

		$found = false;
		foreach ($mapping as $map) {
			foreach ($map['keywords'] as $kw) {
				if (mb_stripos($text, $kw) !== false) {
					$iconGuess = $map['icon'];
					$found = true;
					break 2;
				}
			}
		}

		if (!$found) {
			// Try a simple fallback heuristics by slug segments
			if (!empty($slug)) {
				$parts = preg_split('/[-_\/]+/', $slug);
				foreach ($parts as $p) {
					foreach ($mapping as $map) {
						foreach ($map['keywords'] as $kw) {
							if (mb_stripos($p, $kw) !== false) {
								$iconGuess = $map['icon'];
								$found = true;
								break 3;
							}
						}
					}
				}
			}
		}

		if (!$found) {
			$iconGuess = 'tag';
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