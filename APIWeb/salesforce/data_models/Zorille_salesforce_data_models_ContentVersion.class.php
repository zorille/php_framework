<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

class ContentVersion extends data_model
{
    const ENTITY_NAME = 'ContentVersion';

    protected ?string $id = null;
    protected ?string $versionData = null;
    protected ?string $fileExtension = null;
    protected ?string $fileType = null;
    protected ?string $title = null;
    protected ?string $contentSize = null;
    protected ?string $versionDataUrl = null;
    protected bool $isAssetEnabled = false;

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): ContentVersion
    {
        $this->id = $id;
        return $this;
    }

    public function getVersionData(): ?string
    {
        return $this->versionData;
    }
    public function setVersionData(?string $versionData): ContentVersion
    {
        $this->versionData = $versionData;
        return $this;
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }
    public function setFileExtension(?string $fileExtension): ContentVersion
    {
        $this->fileExtension = $fileExtension;
        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }
    public function setFileType(?string $fileType): ContentVersion
    {
        $this->fileType = $fileType;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(?string $title): ContentVersion
    {
        $this->title = $title;
        return $this;
    }

    public function getContentSize(): ?string
    {
        return $this->contentSize;
    }
    public function setContentSize(?string $contentSize): ContentVersion
    {
        $this->contentSize = $contentSize;
        return $this;
    }

    public function getVersionDataUrl(): ?string
    {
        return $this->versionDataUrl;
    }
    public function setVersionDataUrl(?string $versionDataUrl): ContentVersion
    {
        $this->versionDataUrl = $versionDataUrl;
        return $this;
    }

    public function isAssetEnabled(): bool
    {
        return $this->isAssetEnabled;
    }
    public function setIsAssetEnabled(bool $isAssetEnabled): ContentVersion
    {
        $this->isAssetEnabled = $isAssetEnabled;
        return $this;
    }
}