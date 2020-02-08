<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Orm;

use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use Symfony\Component\PropertyAccess\PropertyAccessor;

final class EntityUpdater
{
    private $propertyAccesor;

    public function __construct(PropertyAccessor $propertyAccesor)
    {
        $this->propertyAccesor = $propertyAccesor;
    }

    public function updateProperty(EntityDto $entityDto, string $propertyName, bool $value): void
    {
        if (!$this->propertyAccesor->isWritable($entityDto->getInstance(), $propertyName)) {
            throw new \RuntimeException(sprintf('The "%s" property of the "%s" entity is not writable.', $propertyName, $entityDto->getName()));
        }

        $entityInstance = $entityDto->getInstance();
        $this->propertyAccesor->setValue($entityInstance, $propertyName, $value);
        $entityDto->updateInstance($entityInstance);
    }
}
