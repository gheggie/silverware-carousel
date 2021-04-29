<?php

/**
 * An extension of the SilverWare slide class for a carousel slide.
 */
class CarouselSlide extends SilverWareSlide
{
    private static $has_one = array(
        'Carousel' => 'CarouselComponent'
    );
    
    private static $asset_folder = "Slides/Carousel";
    
    /**
     * Answers the parent object for the slide.
     *
     * @return CarouselComponent
     */
    public function getParent()
    {
        return $this->Carousel();
    }
    
    /**
     * Defines the receiver from the given list item.
     *
     * @param Page $item
     * @param CarouselComponent $carousel
     * @return CarouselSlide
     */
    public function fromItem(Page $item, CarouselComponent $carousel)
    {
        // Define Title:
        
        $this->Title = $item->MetaTitle();
        
        // Define Caption:
        
        if ($item->HasMetaImageCaption()) {
            $this->Caption = $item->MetaImageCaption;
        } else {
            $this->Caption = $item->MetaSummary();
        }
        
        // Define Image:
        
        if ($item->HasMetaImage()) {
            $this->ImageID = $item->MetaImage()->ID;
        }
        
        // Define Page Link:
        
        $this->LinkPageID = $item->ID;
        
        // Associate with Carousel:
        
        $this->CarouselID = $carousel->ID;
        
        // Answer Slide:
        
        return $this;
    }
}
