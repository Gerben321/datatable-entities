<?php
namespace Artoroz\Datatable\Types\Field;

class ColumnField extends Field
{
    public $title = '';
    public $data = '';
    public $searchable = true;
    public $orderable = true;
    public $visible = true;
    public $className = null;
    public $transformer = null;

    public function parseOptions(array $options)
    {
        $this->title = $options['title'] ?? '';
        $this->data = $options['data'] ?? $this->name;
        $this->searchable = $options['searchable'] ?? true;
        $this->orderable = $options['orderable'] ?? true;
        $this->visible = $options['visible'] ?? true;
        $this->className = $options['className'] ?? '';
        $this->transformer = $options['transformer'] ?? null;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'title' => $this->title,
            'data' => $this->data,
            'searchable' => $this->searchable,
            'orderable' => $this->orderable,
            'visible' => $this->visible,
            'className' => $this->className,
        ]);
    }

    public function parseField($entity)
    {
        $entry = $this->getFromEntity($entity);
        if (is_callable($this->transformer)) {
            return call_user_func($this->transformer, $entry);
        }
        return $entry;
    }
}