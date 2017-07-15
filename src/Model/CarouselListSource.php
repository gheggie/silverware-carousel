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
use SilverStripe\View\ViewableData;
use SilverWare\Extensions\Lists\ListSourceExtension;
use SilverWare\Extensions\Model\LinkToExtension;

/**
 * An extension of the carousel slide class which creates a series of slides from a list source.
 *
 * @package SilverWare\Carousel\Model
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-carousel
 */
class CarouselListSource extends CarouselSlide
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'List Source';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'List Sources';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'Shows list items as a series of slides';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware-carousel/admin/client/dist/images/icons/CarouselListSource.png';
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'ImageItems' => 1
    ];
    
    /**
     * Defines the extension classes to apply to this object.
     *
     * @var array
     * @config
     */
    private static $extensions = [
        ListSourceExtension::class
    ];
    
    /**
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Remove Field Objects:
        
        $fields->removeFieldsFromTab('Root.Main', ['Image', 'Caption', 'LinkTo']);
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a list of the enabled slides within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledSlides()
    {
        $slides = ArrayList::create();
        
        foreach ($this->getListItems() as $item) {
            
            if ($slide = $this->createSlide($item)) {
                $slides->push($slide);
            }
            
        }
        
        return $slides;
    }
    
    /**
     * Answers true if the slide is disabled within the template.
     *
     * @return boolean
     */
    public function isDisabled()
    {
        return !$this->hasListItems();
    }
    
    /**
     * Creates a carousel slide for the given list item.
     *
     * @param ViewableData $item
     *
     * @return CarouselSlide
     */
    protected function createSlide(ViewableData $item)
    {
        if ($item->hasMetaImage()) {
            
            // Create Slide Object:
            
            $slide = CarouselSlide::create([
                'Title' => $item->MetaTitle,
                'ImageID' => $item->MetaImageID,
                'Caption' => $item->MetaImageCaption,
                'ParentID' => $this->ParentID,
                'HideTitle' => $this->HideTitle,
                'HideCaption' => $this->HideCaption
            ]);
            
            // Define Slide Link:
            
            $slide->LinkTo = LinkToExtension::MODE_URL;
            $slide->LinkURL = $item->MetaAbsoluteLink;
            $slide->LinkDisabled = $this->LinkDisabled;
            $slide->OpenLinkInNewTab = $this->OpenLinkInNewTab;
            
            // Answer Slide Object:
            
            return $slide;
            
        }
    }
}
