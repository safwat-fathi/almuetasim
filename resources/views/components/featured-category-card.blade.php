<a href="{{ url('/category', $slug) }}">
	<div class="card bg-base-200 shadow-xl cursor-pointer transition-all duration-300 hover:scale-105 hover:bg-base-300">
			<div class="card-body text-center h-60 max-h-60">
					<i data-lucide="{{ $icon }}" class="w-12 h-12 mx-auto mb-4 text-primary"></i>
					<h3 class="card-title justify-center line-clamp-2">{{ $title }}</h3>
					<p class="line-clamp-2">{{ $description }}</p>
			</div>
	</div>
</a>