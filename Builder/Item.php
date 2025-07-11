<?php

/**
 * This file is part of the pd-admin pd-menu package.
 *
 * @package     pd-menu
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-menu
 */

namespace Pd\MenuBundle\Builder;

/**
 * Menu Item Properties.
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
 */
class Item implements ItemInterface
{
    protected $id;
    protected string $label = '';
    protected string $labelAfterHtml = '';
    protected string $link = '';
    protected string $linkAfterHtml = '';
    protected $order;
    protected array $route = [];
    protected array $linkAttr = [];
    protected array $listAttr = [];
    protected array $childAttr = [];
    protected array $labelAttr = [];
    protected array $extra = [];
    protected array $roles = [];

    /**
     * @var ItemInterface[]
     */
    protected array $child = [];
    protected $parent;
    protected bool $event;

    public function __construct(string $id, $event)
    {
        $this->id = $id;
        $this->event = $event;
    }

    public function isEvent(): bool
    {
        return (int)$this->event;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id = null): ItemInterface
    {
        $this->id = $id;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): ItemInterface
    {
        $this->label = $label;

        return $this;
    }

    public function getLabelAfterHtml(): string
    {
        return $this->labelAfterHtml;
    }

    public function setLabelAfterHtml(string $html): ItemInterface
    {
        $this->labelAfterHtml = $html;

        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): ItemInterface
    {
        $this->link = $link;

        return $this;
    }

    public function getLinkAfterHtml(): string
    {
        return $this->linkAfterHtml;
    }

    public function setLinkAfterHtml(string $html): ItemInterface
    {
        $this->linkAfterHtml = $html;

        return $this;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): ItemInterface
    {
        $this->order = $order;

        return $this;
    }

    public function getRoute(): array
    {
        return $this->route;
    }

    public function setRoute(string $route, array $params = []): ItemInterface
    {
        $this->route = [
            'name' => $route,
            'params' => $params,
        ];

        return $this;
    }

    public function getLinkAttr(): array
    {
        return $this->linkAttr;
    }

    public function setLinkAttr(array $linkAttr): ItemInterface
    {
        $this->linkAttr = array_merge($this->linkAttr, $linkAttr);

        return $this;
    }

    public function getListAttr(): array
    {
        return $this->listAttr;
    }

    public function setListAttr(array $listAttr): ItemInterface
    {
        $this->listAttr = array_merge($this->listAttr, $listAttr);

        return $this;
    }

    public function getChildAttr(): array
    {
        return $this->childAttr;
    }

    public function setChildAttr(array $childAttr): ItemInterface
    {
        $this->childAttr = array_merge($this->childAttr, $childAttr);

        return $this;
    }

    public function getLabelAttr(): array
    {
        return $this->labelAttr;
    }

    public function setLabelAttr(array $labelAttr): ItemInterface
    {
        $this->labelAttr = array_merge($this->labelAttr, $labelAttr);

        return $this;
    }

    public function getExtra(string $name, $default = false): mixed
    {
        if (\is_array($this->extra) && isset($this->extra[$name])) {
            return $this->extra[$name];
        }

        return $default;
    }

    public function setExtra(string $name, mixed $value): ItemInterface
    {
        if (\is_array($this->extra)) {
            $this->extra[$name] = $value;
        } else {
            $this->extra = [$name => $value];
        }

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): ItemInterface
    {
        $this->roles = array_merge($this->roles, $roles);

        return $this;
    }

    public function getChild(): array
    {
        return $this->child;
    }

    public function setChild(array $child): ItemInterface
    {
        $this->child = $child;

        return $this;
    }

    public function addChild($child, $order = null): ItemInterface
    {
        // Create New Item
        if (!$child instanceof ItemInterface) {
            $child = new self($child, $this->event);
        }

        // Child Set Parent & ID
        $child
            ->setOrder($order ?? \count($this->child))
            ->setParent($this);

        // Add Child This
        $this->child[$child->getId()] = $child;

        return $child;
    }

    public function addChildParent($child, $order = null): ItemInterface
    {
        return $this->parent->addChild($child, $order);
    }

    public function getParent(): ?ItemInterface
    {
        return $this->parent;
    }

    public function setParent(ItemInterface $item): ItemInterface
    {
        if ($item === $this) {
            throw new \InvalidArgumentException('Item cannot be a child of itself');
        }

        $this->parent = $item;

        return $this;
    }

    public function isRoot(): bool
    {
        return null === $this->parent;
    }

    public function getLevel(): int
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    public function offsetExists(mixed $childId): bool
    {
        return isset($this->child[$childId]);
    }

    public function offsetGet(mixed $childId): mixed
    {
        return $this->child[$childId];
    }

    public function offsetSet(mixed $childId, mixed $order): void
    {
        $this->addChild($childId, $order);
    }

    public function offsetUnset(mixed $childId): void
    {
        if ($this->offsetExists($childId)) {
            unset($this->child[$childId]);
        }
    }
}
