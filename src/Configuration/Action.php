<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Configuration;

use EasyCorp\Bundle\EasyAdminBundle\Dto\ActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\PropertyModifierTrait;

final class Action
{
    use PropertyModifierTrait;

    private $name;
    private $label;
    private $icon;
    private $cssClass;
    private $htmlElement = 'a';
    private $htmlAttributes = [];
    private $templatePath;
    private $permission;
    private $crudActionName;
    private $routeName;
    private $routeParameters;
    private $translationDomain;
    private $translationParameters = [];
    private $displayCallable;

    private function __construct()
    {
    }

    public function __toString()
    {
        return $this->name;
    }

    public static function new(string $name, ?string $label = null, ?string $icon = null): self
    {
        $actionConfig = new self();
        $actionConfig->name = $name;
        $actionConfig->label = $label ?? ucfirst($name);
        $actionConfig->icon = $icon;

        return $actionConfig;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function setCssClass(string $cssClass): self
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    public function setHtmlElement(string $element): self
    {
        if (!\in_array($element, ['a', 'button'])) {
            throw new \InvalidArgumentException(sprintf('The HTML element used to display an action can only be "a" for links or "button" for buttons ("%s" was given).', $element));
        }

        $this->htmlElement = $element;

        return $this;
    }

    public function setHtmlAttributes(array $attributes): self
    {
        $this->htmlAttributes = $attributes;

        return $this;
    }

    public function setTemplate(string $templatePath): self
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function setPermission(string $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    public function linkToCrudAction(string $crudActionName): self
    {
        $this->crudActionName = $crudActionName;

        return $this;
    }

    public function linkToRoute(string $routeName, array $routeParameters = []): self
    {
        $this->routeName = $routeName;
        $this->routeParameters = $routeParameters;

        return $this;
    }

    /**
     * If not defined, actions use the same domain as configured for the entire dashboard.
     */
    public function setTranslationDomain(string $domain): self
    {
        $this->translationDomain = $domain;

        return $this;
    }

    public function setTranslationParameters(string $parameters): self
    {
        $this->translationParameters = $parameters;

        return $this;
    }

    public function displayIf(callable $callable): self
    {
        $this->displayCallable = $callable;

        return $this;
    }

    public function getAsDto(): ActionDto
    {
        if (null === $this->label && null === $this->icon) {
            throw new \InvalidArgumentException(sprintf('The label and icon of an action cannot be null at the same time. Either set the label, the icon or both.'));
        }

        if (null === $this->crudActionName && null === $this->routeName) {
            throw new \InvalidArgumentException(sprintf('Actions must link to either a route or a CRUD action. Set the "linkToCrudAction()" or "linkToRoute()" method for the "%s" action.', $this->name));
        }

        if (null === $this->label) {
            $this->htmlAttributes = array_merge(['title' => $this->name], $this->htmlAttributes);
        }

        return new ActionDto($this->name, $this->label, $this->icon, $this->cssClass, $this->htmlElement, $this->htmlAttributes, $this->templatePath, $this->permission, $this->crudActionName, $this->routeName, $this->routeParameters, $this->translationDomain, $this->translationParameters, $this->displayCallable ?? static function () { return true; });
    }
}
