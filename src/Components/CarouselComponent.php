<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Carousel\Components
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-carousel
 */

namespace SilverWare\Carousel\Components;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\NumericField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\SSViewer;
use SilverWare\Carousel\Model\CarouselSlide;
use SilverWare\Components\BaseComponent;
use SilverWare\Extensions\Model\ImageResizeExtension;
use SilverWare\Forms\FieldSection;
use SilverWare\Tools\ViewTools;

/**
 * An extension of the base component class for a carousel component.
 *
 * @package SilverWare\Carousel\Components
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-carousel
 */
class CarouselComponent extends BaseComponent
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Carousel Component';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Carousel Components';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A component which shows a carousel of slides';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware-carousel/admin/client/dist/images/icons/CarouselComponent.png';
    
    /**
     * Defines an ancestor class to hide from the admin interface.
     *
     * @var string
     * @config
     */
    private static $hide_ancestor = BaseComponent::class;
    
    /**
     * Defines the default child class for this object.
     *
     * @var string
     * @config
     */
    private static $default_child = CarouselSlide::class;
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'SlideInterval' => 'Int',
        'HeadingLevel' => 'Varchar(2)',
        'ShowControls' => 'Boolean',
        'ShowIndicators' => 'Boolean',
        'ShowIcons' => 'Boolean'
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'HideTitle' => 1,
        'ShowIcons' => 1,
        'ShowControls' => 1,
        'ShowIndicators' => 1,
        'SlideInterval' => 5000
    ];
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = [
        CarouselSlide::class,
        CarouselListSource::class
    ];
    
    /**
     * Maps field and method names to the class names of casting objects.
     *
     * @var array
     * @config
     */
    private static $casting = [
        'WrapperAttributesHTML' => 'HTMLFragment'
    ];
    
    /**
     * Defines the extension classes to apply to this object.
     *
     * @var array
     * @config
     */
    private static $extensions = [
        ImageResizeExtension::class
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
        
        // Define Placeholder:
        
        $placeholder = _t(__CLASS__ . '.DROPDOWNDEFAULT', '(default)');
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            FieldSection::create(
                'CarouselStyle',
                $this->fieldLabel('CarouselStyle'),
                [
                    DropdownField::create(
                        'HeadingLevel',
                        $this->fieldLabel('HeadingLevel'),
                        $this->getTitleLevelOptions()
                    )->setEmptyString(' ')->setAttribute('data-placeholder', $placeholder)
                ]
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            FieldSection::create(
                'CarouselOptions',
                $this->fieldLabel('CarouselOptions'),
                [
                    NumericField::create(
                        'SlideInterval',
                        $this->fieldLabel('SlideInterval')
                    ),
                    CheckboxField::create(
                        'ShowControls',
                        $this->fieldLabel('ShowControls')
                    ),
                    CheckboxField::create(
                        'ShowIndicators',
                        $this->fieldLabel('ShowIndicators')
                    )
                ]
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers the labels for the fields of the receiver.
     *
     * @param boolean $includerelations Include labels for relations.
     *
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        // Obtain Field Labels (from parent):
        
        $labels = parent::fieldLabels($includerelations);
        
        // Define Field Labels:
        
        $labels['ShowIcons'] = _t(__CLASS__ . '.SHOWICONS', 'Show icons');
        $labels['ShowControls'] = _t(__CLASS__ . '.SHOWCONTROLS', 'Show controls');
        $labels['ShowIndicators'] = _t(__CLASS__ . '.SHOWINDICATORS', 'Show indicators');
        $labels['SlideInterval'] = _t(__CLASS__ . '.SLIDEINTERVALINMS', 'Slide interval (in milliseconds)');
        $labels['HeadingLevel'] = _t(__CLASS__ . '.HEADINGLEVEL', 'Heading level');
        $labels['CarouselStyle'] = $labels['CarouselOptions'] = _t(__CLASS__ . '.CAROUSEL', 'Carousel');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers the heading tag for the receiver.
     *
     * @return string
     */
    public function getHeadingTag()
    {
        if ($tag = $this->getField('HeadingLevel')) {
            return $tag;
        }
    }
    
    /**
     * Answers a list of all slides within the receiver.
     *
     * @return DataList
     */
    public function getSlides()
    {
        return $this->AllChildren();
    }
    
    /**
     * Answers a list of the enabled slides within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledSlides()
    {
        $slides = ArrayList::create();
        
        foreach ($this->getSlides() as $slide) {
            $slides->merge($slide->getEnabledSlides());
        }
        
        return $slides;
    }
    
    /**
     * Answers an array of HTML tag attributes for the wrapper.
     *
     * @return array
     */
    public function getWrapperAttributes()
    {
        $attributes = [
            'id' => $this->WrapperID,
            'class' => $this->WrapperClass
        ];
        
        $this->extend('updateWrapperAttributes', $attributes);
        
        $attributes = array_merge($attributes, $this->getWrapperDataAttributes());
        
        return $attributes;
    }
    
    /**
     * Answers an array of data attributes for the wrapper.
     *
     * @return array
     */
    public function getWrapperDataAttributes()
    {
        $attributes = [
            'data-ride' => 'carousel',
            'data-interval' => $this->SlideInterval
        ];
        
        $this->extend('updateWrapperDataAttributes', $attributes);
        
        return $attributes;
    }
    
    /**
     * Answers the HTML tag attributes for the wrapper as a string.
     *
     * @return string
     */
    public function getWrapperAttributesHTML()
    {
        return $this->getAttributesHTML($this->getWrapperAttributes());
    }
    
    /**
     * Answers an array of wrapper class names for the HTML template.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        $classes = $this->styles('carousel', 'carousel.slide');
        
        $this->extend('updateWrapperClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers a unique ID for the wrapper element.
     *
     * @return string
     */
    public function getWrapperID()
    {
        return sprintf('%s_Wrapper', $this->getHTMLID());
    }
    
    /**
     * Answers a unique CSS ID for the wrapper element.
     *
     * @return string
     */
    public function getWrapperCSSID()
    {
        return $this->getCSSID($this->getWrapperID());
    }
    
    /**
     * Answers an array of inner class names for the HTML template.
     *
     * @return array
     */
    public function getInnerClassNames()
    {
        $classes = $this->styles('carousel.inner');
        
        $this->extend('updateInnerClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers an array of controls class names for the HTML template.
     *
     * @return array
     */
    public function getControlsClassNames()
    {
        $classes = $this->styles('carousel.controls');
        
        $this->extend('updateControlsClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers an array of previous control class names for the HTML template.
     *
     * @return array
     */
    public function getControlPreviousClassNames()
    {
        $classes = $this->styles('carousel.control-previous');
        
        $this->extend('updateControlPreviousClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers an array of previous control icon class names for the HTML template.
     *
     * @return array
     */
    public function getControlPreviousIconClassNames()
    {
        $classes = $this->styles('carousel.control-previous-icon');
        
        $this->extend('updateControlPreviousIconClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers an array of next control class names for the HTML template.
     *
     * @return array
     */
    public function getControlNextClassNames()
    {
        $classes = $this->styles('carousel.control-next');
        
        $this->extend('updateControlNextClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers an array of next control icon class names for the HTML template.
     *
     * @return array
     */
    public function getControlNextIconClassNames()
    {
        $classes = $this->styles('carousel.control-next-icon');
        
        $this->extend('updateControlNextIconClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers an array of control text class names for the HTML template.
     *
     * @return array
     */
    public function getControlTextClassNames()
    {
        $classes = $this->styles('carousel.control-text');
        
        $this->extend('updateControlTextClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers an array of indicators class names for the HTML template.
     *
     * @return array
     */
    public function getIndicatorsClassNames()
    {
        $classes = $this->styles('carousel.indicators');
        
        $this->extend('updateIndicatorsClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers the class for an indicator list item.
     *
     * @return string
     */
    public function getIndicatorClass($isFirst = false)
    {
        return $isFirst ? $this->style('carousel.indicator-active') : '';
    }
    
    /**
     * Answers the text for the previous control.
     *
     * @return string
     */
    public function getControlPreviousText()
    {
        return _t(__CLASS__ . '.PREVIOUSTEXT', 'Previous');
    }
    
    /**
     * Answers the text for the next control.
     *
     * @return string
     */
    public function getControlNextText()
    {
        return _t(__CLASS__ . '.NEXTTEXT', 'Next');
    }
    
    /**
     * Answers true if the controls are to be shown in the template.
     *
     * @return boolean
     */
    public function getControlsShown()
    {
        return (boolean) $this->ShowControls;
    }
    
    /**
     * Answers true if the indicators are to be shown in the template.
     *
     * @return boolean
     */
    public function getIndicatorsShown()
    {
        return (boolean) $this->ShowIndicators;
    }
    
    /**
     * Answers the template used to render the receiver.
     *
     * @return string|array|SSViewer
     */
    public function getTemplate()
    {
        $viewer = new SSViewer(static::class);
        
        return $viewer->dontRewriteHashlinks();
    }
    
    /**
     * Answers true if the object is disabled within the template.
     *
     * @return boolean
     */
    public function isDisabled()
    {
        if (!$this->getEnabledSlides()->exists()) {
            return true;
        }
        
        return parent::isDisabled();
    }
}
