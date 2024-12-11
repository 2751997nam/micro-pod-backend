<?php

namespace App\Packages\RequestInput;

use App\Packages\DTO\UserDTO;
use App\Packages\Traits\JsonTrait;
use App\Packages\Traits\ObjectTrait;

class SaveProductInput implements \JsonSerializable {
    use ObjectTrait;
    use JsonTrait;
    
    private $id;
    private $sku;
    private $price;
    private $highPrice;
    private $content;
    private $description;
    private $brandId;
    private $imageUrl;
    private $gtin;
    private $name;
    private $slug;
    private $status;
    private $statusOutStock;
    private $addShippingFee;
    private $weight;
    private $barcode;
    private $inventory;
    private $note;
    private $isTrademark;
    private $isViolation;
    private $approveAdvertising;

    private UserDTO $user;

    private UserDTO $creater;

    private array $metas = [];
    private $categoryId;
    private array $tagIds = [];
    private array $gallery = [];
    private array $variants = [];
    private array $productVariants = [];


    

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of sku
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set the value of sku
     */
    public function setSku($sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     */
    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of highPrice
     */
    public function getHighPrice()
    {
        return $this->highPrice;
    }

    /**
     * Set the value of highPrice
     */
    public function setHighPrice($highPrice): self
    {
        $this->highPrice = $highPrice;

        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     */
    public function setContent($content): self
    {
        $this->content = $content;

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
     * Get the value of brandId
     */
    public function getBrandId()
    {
        return $this->brandId;
    }

    /**
     * Set the value of brandId
     */
    public function setBrandId($brandId): self
    {
        $this->brandId = $brandId;

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
     * Get the value of gtin
     */
    public function getGtin()
    {
        return $this->gtin;
    }

    /**
     * Set the value of gtin
     */
    public function setGtin($gtin): self
    {
        $this->gtin = $gtin;

        return $this;
    }

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
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of statusOutStock
     */
    public function getStatusOutStock()
    {
        return $this->statusOutStock;
    }

    /**
     * Set the value of statusOutStock
     */
    public function setStatusOutStock($statusOutStock): self
    {
        $this->statusOutStock = $statusOutStock;

        return $this;
    }

    /**
     * Get the value of addShippingFee
     */
    public function getAddShippingFee()
    {
        return $this->addShippingFee;
    }

    /**
     * Set the value of addShippingFee
     */
    public function setAddShippingFee($addShippingFee): self
    {
        $this->addShippingFee = $addShippingFee;

        return $this;
    }

    /**
     * Get the value of weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the value of weight
     */
    public function setWeight($weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get the value of barcode
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * Set the value of barcode
     */
    public function setBarcode($barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * Get the value of inventory
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * Set the value of inventory
     */
    public function setInventory($inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }

    /**
     * Get the value of note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set the value of note
     */
    public function setNote($note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get the value of isTrademark
     */
    public function getIsTrademark()
    {
        return $this->isTrademark;
    }

    /**
     * Set the value of isTrademark
     */
    public function setIsTrademark($isTrademark): self
    {
        $this->isTrademark = $isTrademark;

        return $this;
    }

    /**
     * Get the value of isViolation
     */
    public function getIsViolation()
    {
        return $this->isViolation;
    }

    /**
     * Set the value of isViolation
     */
    public function setIsViolation($isViolation): self
    {
        $this->isViolation = $isViolation;

        return $this;
    }

    /**
     * Get the value of approveAdvertising
     */
    public function getApproveAdvertising()
    {
        return $this->approveAdvertising;
    }

    /**
     * Set the value of approveAdvertising
     */
    public function setApproveAdvertising($approveAdvertising): self
    {
        $this->approveAdvertising = $approveAdvertising;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser(): UserDTO
    {
        return $this->user;
    }

    /**
     * Set the value of user
     */
    public function setUser(UserDTO $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getCreater(): UserDTO
    {
        return $this->creater;
    }

    /**
     * Set the value of user
     */
    public function setCreater(UserDTO $creater): self
    {
        $this->creater = $creater;

        return $this;
    }

    /**
     * Get the value of metas
     */
    public function getMetas(): array
    {
        return $this->metas;
    }

    /**
     * Set the value of metas
     */
    public function setMetas(array $metas): self
    {
        $this->metas = $metas;

        return $this;
    }

    /**
     * Get the value of categoryId
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set the value of categoryId
     */
    public function setCategoryId($categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get the value of tagIds
     */
    public function getTagIds(): array
    {
        return $this->tagIds;
    }

    /**
     * Set the value of tagIds
     */
    public function setTagIds(array $tagIds): self
    {
        $this->tagIds = $tagIds;

        return $this;
    }

    /**
     * Get the value of gallery
     */
    public function getGallery(): array
    {
        return $this->gallery;
    }

    /**
     * Set the value of gallery
     */
    public function setGallery(array $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get the value of variants
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    /**
     * Set the value of variants
     */
    public function setVariants(array $variants): self
    {
        $this->variants = $variants;

        return $this;
    }

    /**
     * Get the value of productVariants
     */
    public function getProductVariants(): array
    {
        return $this->productVariants;
    }

    /**
     * Set the value of productVariants
     */
    public function setProductVariants(array $productVariants): self
    {
        $this->productVariants = $productVariants;

        return $this;
    }
}