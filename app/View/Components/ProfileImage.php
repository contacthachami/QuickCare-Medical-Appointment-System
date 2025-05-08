<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProfileImage extends Component
{
    public $user;
    public $size;
    public $rounded;
    public $imgClass;
    public $containerClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user, $size = 'md', $rounded = 'full', $imgClass = '', $containerClass = '')
    {
        $this->user = $user;
        $this->size = $size;
        $this->rounded = $rounded;
        $this->imgClass = $imgClass;
        $this->containerClass = $containerClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.profile-image');
    }
} 