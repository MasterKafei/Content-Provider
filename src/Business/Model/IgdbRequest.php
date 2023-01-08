<?php

namespace App\Business\Model;

class IgdbRequest
{
    private array $fields = [];

    private array $conditions = [];

    private int $limit = 50;

    private int $offset = 0;

    private ?string $fieldOrder = null;

    private string $sortOrder = 'ASC';

    private string $routeName;

    private array $routeParameters = [];

    private ?string $search = null;

    private array $fieldsNotNullable = [];
    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): self
    {
        $this->fields = [];
        foreach ($fields as $field) {
            $this->addField($field);
        }
        return $this;
    }

    public function addField(string $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function setConditions(array $conditions): self
    {
        $this->conditions = [];
        foreach($conditions as $condition) {
            $this->addCondition($condition);
        }
        return $this;
    }

    public function addCondition(string $condition): self
    {
        $this->conditions[] = $condition;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): self
    {
        $this->routeName = $routeName;
        return $this;
    }

    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    public function setRouteParameters(array $routeParameters): self
    {
        $this->routeParameters = $routeParameters;
        return $this;
    }

    public function getFieldOrder(): ?string
    {
        return $this->fieldOrder;
    }

    public function setFieldOrder(?string $fieldOrder): self
    {
        $this->fieldOrder = $fieldOrder;
        return $this;
    }

    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    public function setSortOrder(string $sortOrder): self
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function getFieldsNotNullable(): array
    {
        return $this->fieldsNotNullable;
    }

    public function setFieldsNotNullable(array $fieldsNotNullable): self
    {
        $this->fieldsNotNullable = $fieldsNotNullable;
        return $this;
    }
}
