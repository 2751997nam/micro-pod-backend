<?php

namespace App\DTO;

use App\Packages\Traits\ObjectTrait;

class CategoryDTO
{
    use ObjectTrait;
    private $name;
    private $type;
    private $isHidden;
    private $imageUrl;
    private $bigImageUrl;
    private $description;
    private $shortDescription;
    private $slug;
    private $sorder;
    private $parentId;
    private $isDisplayHomePage;
    private $tags;
    private $isShowProductContent;
    private $isValidPrintBack;
    private $sellDesign;
    private $isThreeDimenstion;

    

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of isHidden
     */
    public function getIsHidden()
    {
        return $this->isHidden;
    }

    /**
     * Set the value of isHidden
     */
    public function setIsHidden($isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * Get the value of imageUrl
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set the value of imageUrl
     */
    public function setImageUrl($imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get the value of bigImageUrl
     */
    public function getBigImageUrl()
    {
        return $this->bigImageUrl;
    }

    /**
     * Set the value of bigImageUrl
     */
    public function setBigImageUrl($bigImageUrl): self
    {
        $this->bigImageUrl = $bigImageUrl;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of shortDescription
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set the value of shortDescription
     */
    public function setShortDescription($shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of sorder
     */
    public function getSorder()
    {
        return $this->sorder;
    }

    /**
     * Set the value of sorder
     */
    public function setSorder($sorder): self
    {
        $this->sorder = $sorder;

        return $this;
    }

    /**
     * Get the value of parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set the value of parentId
     */
    public function setParentId($parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get the value of isDisplayHomePage
     */
    public function getIsDisplayHomePage()
    {
        return $this->isDisplayHomePage;
    }

    /**
     * Set the value of isDisplayHomePage
     */
    public function setIsDisplayHomePage($isDisplayHomePage): self
    {
        $this->isDisplayHomePage = $isDisplayHomePage;

        return $this;
    }

    /**
     * Get the value of tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set the value of tags
     */
    public function setTags($tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get the value of isShowProductContent
     */
    public function getIsShowProductContent()
    {
        return $this->isShowProductContent;
    }

    /**
     * Set the value of isShowProductContent
     */
    public function setIsShowProductContent($isShowProductContent): self
    {
        $this->isShowProductContent = $isShowProductContent;

        return $this;
    }

    /**
     * Get the value of isValidPrintBack
     */
    public function getIsValidPrintBack()
    {
        return $this->isValidPrintBack;
    }

    /**
     * Set the value of isValidPrintBack
     */
    public function setIsValidPrintBack($isValidPrintBack): self
    {
        $this->isValidPrintBack = $isValidPrintBack;

        return $this;
    }

    /**
     * Get the value of sellDesign
     */
    public function getSellDesign()
    {
        return $this->sellDesign;
    }

    /**
     * Set the value of sellDesign
     */
    public function setSellDesign($sellDesign): self
    {
        $this->sellDesign = $sellDesign;

        return $this;
    }

    /**
     * Get the value of isThreeDimenstion
     */
    public function getIsThreeDimenstion()
    {
        return $this->isThreeDimenstion;
    }

    /**
     * Set the value of isThreeDimenstion
     */
    public function setIsThreeDimenstion($isThreeDimenstion): self
    {
        $this->isThreeDimenstion = $isThreeDimenstion;

        return $this;
    }
}
