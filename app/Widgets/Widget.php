<?php

namespace App\Widgets;

use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use ReflectionClass;
use ReflectionProperty;

abstract class Widget
{
    /**
     * Get all public properties of the widget.
     */
    protected function getPublicProperties(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $data = [];
        foreach ($properties as $property) {
            $name = $property->getName();
            // Only include actual properties, not methods
            if (!$reflection->hasMethod($name)) {
                $data[$name] = $property->getValue($this);
            }
        }

        return $data;
    }

    /**
     * Resolve the view instance for the widget.
     */
    protected function view(string $view, array $additionalData = []): View
    {
        $publicProperties = $this->getPublicProperties();

        // Merge: data() method takes precedence, then public properties, then additional data
        $viewData = array_merge(
            $publicProperties,
            $additionalData
        );

        return view($view, $viewData);
    }

    public function render()
    {
        $view = $this->view;
        // make card
        $html = '<div class="' . $this->width . '">';
        $html .= '<div class="card">';
        $html .= '<div class="card-body">';
            $html .= view($view, $this->getPublicProperties())->render();
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return new HtmlString($html);
    }
}
