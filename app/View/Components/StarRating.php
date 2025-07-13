<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StarRating extends Component
{
    public $rating;
    public $maxStars;
    public $size;
    public $showRating;
    public $showCount;
    public $reviewCount;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $rating = 0,
        $maxStars = 5,
        $size = 'sm',
        $showRating = true,
        $showCount = true,
        $reviewCount = 0
    ) {
        $this->rating = (float) $rating;
        $this->maxStars = $maxStars;
        $this->size = $size;
        $this->showRating = $showRating;
        $this->showCount = $showCount;
        $this->reviewCount = $reviewCount;
    }

    /**
     * Get the star size class
     */
    public function getStarSizeClass()
    {
        switch ($this->size) {
            case 'xs':
                return 'w-3 h-3';
            case 'sm':
                return 'w-4 h-4';
            case 'md':
                return 'w-5 h-5';
            case 'lg':
                return 'w-6 h-6';
            case 'xl':
                return 'w-8 h-8';
            default:
                return 'w-4 h-4';
        }
    }

    /**
     * Get the text size class
     */
    public function getTextSizeClass()
    {
        switch ($this->size) {
            case 'xs':
                return 'text-xs';
            case 'sm':
                return 'text-sm';
            case 'md':
                return 'text-base';
            case 'lg':
                return 'text-lg';
            case 'xl':
                return 'text-xl';
            default:
                return 'text-sm';
        }
    }

    /**
     * Check if star should be full
     */
    public function isFullStar($index)
    {
        return $index <= floor($this->rating);
    }

    /**
     * Check if star should be half
     */
    public function isHalfStar($index)
    {
        return $index == ceil($this->rating) && 
               ($this->rating - floor($this->rating)) >= 0.5 &&
               $index > floor($this->rating);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.star-rating');
    }
}
