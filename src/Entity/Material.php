<?php

namespace App\Entity;

use App\Repository\MaterialRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Vich\Uploadable]

/**
 * @ORM\Entity(repositoryClass=MaterialRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Material
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @Assert\Length(min=5, max=255)
     * @ORM\Column(type="string", length=255)
     */
    private ?string $titre = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $badge = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $salle = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $localite = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $nombre = null;


    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $filename = null;


    #[Vich\UploadableField(mapping: 'material_image', fileNameProperty: 'filename')]
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $idClass = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"titre"})
     */
    private ?string $slugs = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $possibilite = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $modeEmploi = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $caracteristique = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $liens = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        $this->setSlugs($titre); // Ajoutez cette ligne pour initialiser le slug

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getSalle(): ?string
    {
        return $this->salle;
    }

    public function setSalle(string $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getLocalite(): ?string
    {
        return $this->localite;
    }

    public function setLocalite(string $localite): self
    {
        $this->localite = $localite;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getIdClass(): ?string
    {
        return $this->idClass;
    }

    public function setIdClass(string $idClass): self
    {
        $this->idClass = $idClass;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function getSlugs()
    {
        $slugs = $this->slugs;

        if (!$slugs) {
            $slugs = $this->titre;
        }

        $slugs = preg_replace('/[^a-z0-9]+/', '-', strtolower($slugs));
        $slugs = trim($slugs, '-');

        return $slugs;
    }

    public function setSlugs(string $slugs): self
    {
        $this->slugs = $slugs;

        return $this;
    }

    public function getPossibilite(): ?string
    {
        return $this->possibilite;
    }

    public function setPossibilite(?string $possibilite): self
    {
        $this->possibilite = $possibilite;

        return $this;
    }

    public function getModeEmploi(): ?string
    {
        return $this->modeEmploi;
    }

    public function setModeEmploi(?string $modeEmploi): self
    {
        $this->modeEmploi = $modeEmploi;

        return $this;
    }

    public function getCaracteristique(): ?string
    {
        return $this->caracteristique;
    }

    public function setCaracteristique(?string $caracteristique): self
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    public function getLiens(): ?string
    {
        return $this->liens;
    }

    public function setLiens(string $liens): self
    {
        $this->liens = $liens;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateSlug(): void
    {
        $slugify = new Slugify();
        $this->slugs = $slugify->slugify($this->titre);
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }


    public function setUpdatedAt(?\DateTime $updated_at): Material
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Material
     */
    public function setFilename(?string $filename): Material
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Material
     */
    public function setImageFile(?File $imageFile): Material
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }




}