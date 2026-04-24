<?php

namespace App\Foundation;

use App\Facades\Metabox as MetaboxFacade;
use Illuminate\Support\Facades\Blade;

class Metabox
{
    protected ?string $id = null;

    public ?string $location = null;

    public int $priority = 10;

    protected ?\Closure $condition = null;

    protected ?\Closure $callback = null;

    protected array $with = [];

    public function id(string $id): self
    {
        $this->id = $id;

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

    public function fields(callable|array $fields): self
    {
        $this->callback(function ($args) use ($fields) {
            if (is_callable($fields)) {
                $fields = $fields($args);
            }

            // TODO: Maybe Fields API?
            return view('components.fields.fields', [
                'fields' => $fields,
            ]);
        });

        return $this;
    }

    public function blade($string): self
    {
        $this->callback(fn () => Blade::render($string));

        return $this;
    }

    public function with(array|string $key, mixed $value = null): self
    {
        if (is_array($key)) {
            $this->with = array_merge($this->with, $key);
        } else {
            $this->with[$key] = $value;
        }

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
            return view($wrapper, [
                'callback' => ($this->callback)($args),
                ...$this->with,
            ]);
        }
    }
}
