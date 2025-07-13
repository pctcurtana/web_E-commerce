<div class="flex items-center">
    @if($reviewCount > 0 || $rating > 0)
        <!-- Stars -->
        <div class="flex items-center">
            @for($i = 1; $i <= $maxStars; $i++)
                @if($isFullStar($i))
                    <x-heroicon-s-star class="{{ $getStarSizeClass() }} text-yellow-400" />
                @elseif($isHalfStar($i))
                    <div class="relative {{ $getStarSizeClass() }}">
                        <x-heroicon-o-star class="absolute {{ $getStarSizeClass() }} text-gray-300" />
                        <div class="absolute overflow-hidden" style="width: 50%;">
                            <x-heroicon-s-star class="{{ $getStarSizeClass() }} text-yellow-400" />
                        </div>
                    </div>
                @else
                    <x-heroicon-o-star class="{{ $getStarSizeClass() }} text-gray-300" />
                @endif
            @endfor
        </div>

        <!-- Rating Text -->
        @if($showRating && $rating > 0)
            <span class="ml-1 {{ $getTextSizeClass() }} text-gray-600">
                {{ number_format($rating, 1) }}
            </span>
        @endif

        <!-- Review Count -->
        @if($showCount && $reviewCount > 0)
            <span class="ml-1 {{ $getTextSizeClass() }} text-gray-500">
                ({{ number_format($reviewCount) }})
            </span>
        @endif
    @else
        <span class="{{ $getTextSizeClass() }} text-gray-400">Chưa có đánh giá</span>
    @endif
</div>