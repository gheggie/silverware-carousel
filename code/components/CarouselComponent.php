<?php

/**
 * An extension of the base component class for a carousel component.
 */
class CarouselComponent extends BaseComponent
{
    private static $singular_name = "Carousel Component";
    private static $plural_name   = "Carousel Components";
    
    private static $description = "A component to show a carousel of slides";
    
    private static $icon = "silverware-carousel/images/icons/CarouselComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'Nav' => 'Boolean',
        'Dots' => 'Boolean',
        'Loop' => 'Boolean',
        'Center' => 'Boolean',
        'AutoPlay' => 'Boolean',
        'AutoHeight' => 'Boolean',
        'NumberOfSlides' => 'Int',
        'ImageWidth' => 'Varchar(16)',
        'ImageHeight' => 'Varchar(16)',
        'ImageResize' => 'Varchar(32)',
        'AnimationIn' => 'Varchar(32)',
        'AnimationOut' => 'Varchar(32)',
        'ButtonMargin' => 'Varchar(16)',
        'ButtonMarginUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')",
        'RoundedButtons' => 'Boolean',
        'ShowItems' => 'Varchar(16)',
        'IconPrev' => 'Varchar(64)',
        'IconNext' => 'Varchar(64)'
    );
    
    private static $has_many = array(
        'Slides' => 'CarouselSlide'
    );
    
    private static $defaults = array(
        'Nav' => 1,
        'Dots' => 1,
        'Loop' => 1,
        'Center' => 1,
        'AutoPlay' => 1,
        'HideTitle' => 1,
        'AutoHeight' => 1,
        'ImageItems' => 1,
        'ButtonMargin' => 1,
        'NumberOfItems' => 10,
        'NumberOfSlides' => 1,
        'RoundedButtons' => 0,
        'ShowItems' => 'after',
        'ButtonMarginUnit' => 'rem',
        'IconPrev' => 'fa-chevron-left',
        'IconNext' => 'fa-chevron-right'
    );
    
    private static $extensions = array(
        'ListSourceExtension'
    );
    
    private static $required_css = array(
        'silverware-carousel/thirdparty/owl-carousel/assets/owl.carousel.min.css'
    );
    
    private static $required_themed_css = array(
        'carousel-component'
    );
    
    private static $required_js = array(
        'silverware-carousel/thirdparty/owl-carousel/owl.carousel.min.js'
    );
    
    private static $required_js_templates = array(
        'silverware-carousel/javascript/owl-carousel/owl.carousel.init.js'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Insert Slides Tab:
        
        $fields->insertAfter(
            Tab::create(
                'Slides',
                _t('CarouselComponent.SLIDES', 'Slides')
            ),
            'Main'
        );
        
        // Create Slides Grid Field:
        
        $slides = GridField::create(
            'Slides',
            _t('CarouselComponent.SLIDES', 'Slides'),
            $this->Slides(),
            GridFieldConfig_OrderableEditor::create()
        );
        
        // Add Grid Field to Slides Tab:
        
        $fields->addFieldToTab('Root.Slides', $slides);
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'CarouselComponentStyle',
                $this->i18n_singular_name(),
                array(
                    FieldGroup::create(
                        _t('CarouselComponent.BUTTONMARGIN', 'Button margin'),
                        array(
                            TextField::create(
                                'ButtonMargin',
                                ''
                            )->setAttribute('placeholder', _t('CarouselComponent.MARGIN', 'Margin')),
                            DropdownField::create(
                                'ButtonMarginUnit',
                                '',
                                $this->owner->dbObject('ButtonMarginUnit')->enumValues()
                            )
                        )
                    ),
                    IconDropdownField::create(
                        'IconPrev',
                        _t('CarouselComponent.PREVIOUSICON', 'Previous icon'),
                        $this->config()->prev_button_icons
                    ),
                    IconDropdownField::create(
                        'IconNext',
                        _t('CarouselComponent.NEXTICON', 'Next icon'),
                        $this->config()->next_button_icons
                    ),
                    CheckboxField::create(
                        'RoundedButtons',
                        _t('CarouselComponent.ROUNDEDBUTTONS', 'Rounded buttons')
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'CarouselComponentOptions',
                $this->i18n_singular_name(),
                array(
                    NumericField::create(
                        'NumberOfSlides',
                        _t('CarouselComponent.NUMBEROFSLIDES', 'Number of slides')
                    )->setRightTitle(
                        _t(
                            'CarouselComponent.NUMBEROFSLIDESRIGHTTITLE',
                            'Determines the number of slides visible on screen at a time.'
                        )
                    ),
                    DropdownField::create(
                        'AnimationIn',
                        _t('CarouselComponent.ANIMATIONIN', 'Animation (in)'),
                        SilverWareAnimateExtension::get_animation_in_options()
                    )->setEmptyString(_t('CarouselComponent.DROPDOWNDEFAULT', '(default)')),
                    DropdownField::create(
                        'AnimationOut',
                        _t('CarouselComponent.ANIMATIONOUT', 'Animation (out)'),
                        SilverWareAnimateExtension::get_animation_out_options()
                    )->setEmptyString(_t('CarouselComponent.DROPDOWNDEFAULT', '(default)')),
                    FieldGroup::create(
                        _t('CarouselComponent.IMAGEDIMENSIONS', 'Image dimensions'),
                        array(
                            TextField::create('ImageWidth', '')->setAttribute(
                                'placeholder',
                                _t('CarouselComponent.WIDTH', 'Width')
                            ),
                            LiteralField::create('ImageBy', '<i class="fa fa-times"></i>'),
                            TextField::create('ImageHeight', '')->setAttribute(
                                'placeholder',
                                _t('CarouselComponent.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'ImageResize',
                        _t('CarouselComponent.IMAGERESIZE', 'Image resize'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('CarouselComponent.NONE', 'None')),
                    CheckboxField::create(
                        'Loop',
                        _t('CarouselComponent.LOOP', 'Loop')
                    ),
                    CheckboxField::create(
                        'Center',
                        _t('CarouselComponent.CENTER', 'Center')
                    ),
                    CheckboxField::create(
                        'AutoPlay',
                        _t('CarouselComponent.AUTOPLAY', 'Auto play')
                    ),
                    CheckboxField::create(
                        'AutoHeight',
                        _t('CarouselComponent.AUTOHEIGHT', 'Auto height')
                    ),
                    CheckboxField::create(
                        'Dots',
                        _t('CarouselComponent.DOTNAVIGATION', 'Dot navigation')
                    ),
                    CheckboxField::create(
                        'Nav',
                        _t('CarouselComponent.BUTTONNAVIGATION', 'Button navigation')
                    )
                )
            )
        );
        
        // Modify List Source Options:
        
        $fields->insertBefore(
            'ReverseItems',
            DropdownField::create(
                'ShowItems',
                _t('CarouselComponent.SHOWITEMS', 'Show items'),
                array(
                    'before' => _t('CarouselComponent.BEFORESLIDES', 'Before slides'),
                    'after'  => _t('CarouselComponent.AFTERSLIDES', 'After slides')
                )
            )
        );
        
        // Modify Options Fields:
        
        $fields->removeByName('PaginateItems');
        $fields->dataFieldByName('SortItemsBy')->setRightTitle('');
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Call Parent Event:
        
        parent::onBeforeWrite();
        
        // Fix Image Dimensions:
        
        $this->ImageWidth  = SilverWareTools::integer_or_null($this->ImageWidth);
        $this->ImageHeight = SilverWareTools::integer_or_null($this->ImageHeight);
    }
    
    /**
     * Answers the CSS string for the button margin style.
     *
     * @return string
     */
    public function getButtonMarginCSS()
    {
        if ($this->ButtonMargin != '') {
            
            return $this->ButtonMargin . $this->ButtonMarginUnit;
            
        }
    }
    
    /**
     * Answers a unique ID for the carousel element.
     *
     * @return string
     */
    public function getCarouselID()
    {
        return $this->getHTMLID() . "_Carousel";
    }
    
    /**
     * Answers the CSS class for the nav container.
     *
     * @return string
     */
    public function getNavContainerClass()
    {
        $classes = array('owl-nav');
        
        if ($this->RoundedButtons) {
            $classes[] = 'rounded';
        }
        
        return implode(' ', $classes);
    }
    
    /**
     * Answers an array of variables required by the initialisation script.
     *
     * @return array
     */
    public function getJSVars()
    {
        $vars = parent::getJSVars();
        
        $vars['CarouselID'] = $this->getCarouselID();
        
        $vars['NumberOfSlides'] = $this->NumberOfSlides;
        
        $vars['Nav'] = $this->dbObject('Nav')->NiceAsBoolean();
        $vars['Dots'] = $this->dbObject('Dots')->NiceAsBoolean();
        $vars['Loop'] = $this->dbObject('Loop')->NiceAsBoolean();
        $vars['Center'] = $this->dbObject('Center')->NiceAsBoolean();
        $vars['AutoPlay'] = $this->dbObject('AutoPlay')->NiceAsBoolean();
        $vars['AutoHeight'] = $this->dbObject('AutoHeight')->NiceAsBoolean();
        
        $vars['AnimateIn'] = "'" . $this->dbObject('AnimationIn')->JS() . "'";
        $vars['AnimateOut'] = "'" . $this->dbObject('AnimationOut')->JS() . "'";
        
        $vars['ContainerClass'] = "'" . $this->getNavContainerClass() . "'";
        
        $vars['IconPrev'] = $this->IconPrev;
        $vars['IconNext'] = $this->IconNext;
        
        return $vars;
    }
    
    /**
     * Answers a list which contains only the slides which are not disabled.
     *
     * @return ArrayList
     */
    public function EnabledSlides()
    {
        // Create Slides List:
        
        $slides = ArrayList::create();
        
        // Merge Slides from List Source (before):
        
        if ($this->ShowItems == 'before') {
            $slides->merge($this->ListSourceSlides());
        }
        
        // Merge Slides from Carousel:
        
        $slides->merge($this->Slides()->filter('Disabled', 0));
        
        // Merge Slides from List Source (after):
        
        if ($this->ShowItems == 'after') {
            $slides->merge($this->ListSourceSlides());
        }
        
        // Answer Slides List:
        
        return $slides;
    }
    
    /**
     * Answers a list of slides created for items from the list source (if available).
     *
     * @return ArrayList
     */
    public function ListSourceSlides()
    {
        // Create Slides List:
        
        $slides = ArrayList::create();
        
        // Create Slide Objects:
        
        if ($items = $this->getListItems()) {
            
            foreach ($items as $item) {
                
                $slides->push(CarouselSlide::create()->fromItem($item, $this));
                
            }
            
        }
        
        // Answer Slides List:
        
        return $slides;
    }
}

/**
 * An extension of the base component controller class for a carousel component.
 */
class CarouselComponent_Controller extends BaseComponent_Controller
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
