<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityLocationTrait;
use App\Entity\Traits\EntityTimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 */
class Property
{
    use EntityIdTrait;
    use EntityLocationTrait;
    use EntityTimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DealType", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deal_type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $bathrooms_number;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $area;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $parcela;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $year;





    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $bedrooms_number;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $max_guests;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $show_map;

     /**
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     */
    private $level;

     /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $show_slider_homepage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price_type;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $energy_certificate;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $energy_consumption;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $energy_emission;




    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $available_now;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "pending"})
     */
    private $state = 'published';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="property", orphanRemoval=true)
     * @ORM\OrderBy({"sort_order" = "ASC"})
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Feature", inversedBy="properties")
     */
    private $features;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $property_status;




    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $observations;


    /**
     * @ORM\OneToOne(targetEntity=PropertyDescription::class, mappedBy="property", cascade={"persist", "remove"})
     */
    private $propertyDescription;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDealType(): ?DealType
    {
        return $this->deal_type;
    }

    public function setDealType(?DealType $dealType): self
    {
        $this->deal_type = $dealType;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBathroomsNumber(): ?int
    {
        return $this->bathrooms_number;
    }

    public function setBathroomsNumber(?int $bathrooms_number): self
    {
        $this->bathrooms_number = $bathrooms_number;

        return $this;
    }

    public function getBedroomsNumber(): ?int
    {
        return $this->bedrooms_number;
    }

    public function setBedroomsNumber(?int $bedrooms_number): self
    {
        $this->bedrooms_number = $bedrooms_number;

        return $this;
    }

    public function getShowMap(): ?bool
    {
        return $this->show_map;
    }

    public function setShowMap(?bool $show_map): self
    {
        $this->show_map = $show_map;

        return $this;
    }


    public function getShowSliderHomepage(): ?bool
    {
        return $this->show_slider_homepage;
    }

    public function setShowSliderHomepage(?bool $show_slider_homepage): self
    {
        $this->show_slider_homepage = $show_slider_homepage;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceType(): ?string
    {
        return $this->price_type;
    }

    public function setPriceType(?string $price_type): self
    {
        $this->price_type = $price_type;

        return $this;
    }

    public function getAvailableNow(): ?bool
    {
        return $this->available_now;
    }

    public function setAvailableNow(?bool $available_now): self
    {
        $this->available_now = $available_now;

        return $this;
    }

    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProperty($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProperty() === $this) {
                $photo->setProperty(null);
            }
        }

        return $this;
    }

    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
        }

        return $this;
    }

    public function removeFeature(Feature $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
        }

        return $this;
    }

    public function getPriorityNumber(): ?int
    {
        return $this->priority_number;
    }

    public function setPriorityNumber(?int $priority_number): self
    {
        $this->priority_number = $priority_number;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->max_guests;
    }

    public function setMaxGuests(?int $max_guests): self
    {
        $this->max_guests = $max_guests;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function isPublished(): bool
    {
        return 'published' === $this->state;
    }

    public function getPropertyDescription(): ?PropertyDescription
    {
        return $this->propertyDescription;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area): void
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getEnergyCertificate()
    {
        return $this->energy_certificate;
    }

    /**
     * @param mixed $energy_certificate
     */
    public function setEnergyCertificate($energy_certificate): void
    {
        $this->energy_certificate = $energy_certificate;
    }

    /**
     * @return mixed
     */
    public function getEnergyConsumption()
    {
        return $this->energy_consumption;
    }

    /**
     * @param mixed $energy_consumption
     */
    public function setEnergyConsumption($energy_consumption): void
    {
        $this->energy_consumption = $energy_consumption;
    }

    /**
     * @return mixed
     */
    public function getEnergyEmission()
    {
        return $this->energy_emission;
    }

    /**
     * @param mixed $energy_emission
     */
    public function setEnergyEmission($energy_emission): void
    {
        $this->energy_emission = $energy_emission;
    }

    /**
     * @return mixed
     */
    public function getParcela()
    {
        return $this->parcela;
    }

    /**
     * @param mixed $parcela
     */
    public function setParcela($parcela): void
    {
        $this->parcela = $parcela;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

    /**
     * @return string|null
     */
    public function getObservations(): ?string
    {
        return $this->observations;
    }

    /**
     * @return mixed
     */
    public function getPropertyStatus()
    {
        return $this->property_status;
    }

    /**
     * @param mixed $property_status
     */
    public function setPropertyStatus($property_status): void
    {
        $this->property_status = $property_status;
    }



    /**
     * @param mixed $priority_status
     */
    public function setPriorityStatus($priority_status): void
    {
        $this->priority_status = $priority_status;
    }




    public function setPropertyDescription(PropertyDescription $propertyDescription): self
    {
        // set the owning side of the relation if necessary
        if ($propertyDescription->getProperty() !== $this) {
            $propertyDescription->setProperty($this);
        }

        $this->propertyDescription = $propertyDescription;

        return $this;
    }
}
