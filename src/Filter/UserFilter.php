<?php

namespace App\Filter;

use App\Annotation\UserAware;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class UserFilter extends SQLFilter
{
    protected $reader;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if(empty($this->reader)) {
            return '';
        }

         // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        $userAware = $this->reader->getClassAnnotation($targetEntity->getReflectionClass(), UserAware::class);
        if (!$userAware) {
            return '';
        }

        $fieldName = $userAware->userFieldName;
        try {
            // Don't worry, getParameter automatically escapes parameters
            $userId = $this->getParameter('id');
        } catch (\InvalidArgumentException $e) {
            // No user id has been defined
            return '';
        }

        if (empty($fieldName) || empty($userId)) {
            return '';
        }

        return sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $userId);
    }

    public function setAnnotationReader(Reader $reader): void
    {
        $this->reader = $reader;
    }
    
}