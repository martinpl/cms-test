<?php

namespace App\Foundation;

use App\Facades\Metabox as MetaboxFacade;

class Metabox
{
    protected ?string $id = null;

    public ?string $title = null;

    public ?string $location = null;

    public int $priority = 10;

    public ?\Closure $condition = null;

    public ?\Closure $callback = null;

    public ?string $wrapper = null;

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function title(string|false $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function location(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function priority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function when(callable $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    public function callback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function fields(array $fields): self
    {
        $this->callback(function () use ($fields) {
            // TODO: Maybe Fields API?
            return view('components.fields.fields', [
                'fields' => $fields,
            ]);
        });

        return $this;
    }

    public function wrapper(string $view): self
    {
        $this->wrapper = $view;

        return $this;
    }

    public function register(): void
    {
        if (! $this->id) {
            throw new \InvalidArgumentException('Metabox requires id.');
        }

        MetaboxFacade::register($this);
    }

    public function render($args, $wrapper)
    {
        if ($this->condition == null || ($this->condition)($args)) {
            // TODO: Default view
            return view($this->wrapper ?? $wrapper, [
                'title' => $this->title,
                'callback' => ($this->callback)($args),
            ]);
        }
    }
}
