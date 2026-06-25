<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

class ContentDocumentLink extends data_model
{
    const ENTITY_NAME = 'ContentDocumentLink';

    protected ?string $id = null;
    protected ?string $contentDocumentId = null;
    protected ?string $LinkedEntityId = null;

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): ContentDocumentLink
    {
        $this->id = $id;
        return $this;
    }

    public function getContentDocumentId(): ?string
    {
        return $this->contentDocumentId;
    }
    public function setContentDocumentId(?string $contentDocumentId): ContentDocumentLink
    {
        $this->contentDocumentId = $contentDocumentId;
        return $this;
    }

    public function getLinkedEntityId(): ?string
    {
        return $this->LinkedEntityId;
    }
    public function setLinkedEntityId(?string $LinkedEntityId): ContentDocumentLink
    {
        $this->LinkedEntityId = $LinkedEntityId;
        return $this;
    }
}