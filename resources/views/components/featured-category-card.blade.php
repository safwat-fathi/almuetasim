<a href="{{ url('/category', $slug) }}" class="block" aria-label="الانتقال إلى قسم {{ $title }}">
	<div class="card bg-base-200 shadow-xl cursor-pointer transition-all duration-300 hover:scale-105 hover:bg-base-300">
			<div class="card-body text-center h-60 max-h-60 flex flex-col items-center justify-center gap-2">
					<h3 class="card-title justify-center line-clamp-2">{{ $title }}</h3>
					<p class="line-clamp-2 !flex-grow-0">{{ $description }}</p>
			</div>
	</div>
</a>
