<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Carousel\Model
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-carousel
 */

namespace SilverWare\Carousel\Model;

use SilverStripe\ORM\ArrayList;
use SilverWare\Model\Slide;

/**
 * An extension of the base slide class for a carousel slide.
 *
 * @package SilverWare\Carousel\Model
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-carousel
 */
class CarouselSlide extends Slide
{
    /**
     * Defines the asset folder for uploading images.
     *
     * @var string
     * @config
     */
    private static $asset_folder = 'Slides/Carousel';
    
    /**
     * Answers an array of slide class names for the HTML template.
     *
     * @param boolean $isFirst Slide is first in the list.
     * @param boolean $isMiddle Slide is in the middle of the list.
     * @param boolean $isLast Slide is last in the list.
     *
     * @return array
     */
    public function getSlideClassNames($isFirst = false, $isMiddle = false, $isLast = false)
    {
        // Obtain Class Names (from parent):
        
        $classes = parent::getSlideClassNames($isFirst, $isMiddle, $isLast);
        
        // Define Class Names:
        
        $classes[] = $this->style('carousel.item');
        
        // Add Active Class (if first slide):
        
        if ($isFirst) {
            $classes[] = $this->style('carousel.item-active');
        }
        
        // Answer Class Names:
        
        return $classes;
    }
    
    /**
     * Answers an array of image class names for the HTML template.
     *
     * @return array
     */
    public function getImageClassNames()
    {
        $classes = array_merge(
            parent::getImageClassNames(),
            $this->styles('image.fluid', 'carousel.image')
        );
        
        return $classes;
    }
    
    /**
     * Answers an array of caption class names for the HTML template.
     *
     * @return array
     */
    public function getCaptionClassNames()
    {
        $classes = array_merge(
            parent::getCaptionClassNames(),
            $this->styles('carousel.caption')
        );
        
        return $classes;
    }
    
    /**
     * Answers a list of the enabled slides within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledSlides()
    {
        $slides = ArrayList::create();
        
        if ($this->isEnabled()) {
            $slides->push($this);
        }
        
        return $slides;
    }
}
