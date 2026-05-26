<?php

namespace App\ClasslessLivewire;

use Livewire\Component;

class Factory extends \Livewire\Factory\Factory
{
    public function resolveComponentNameAndClass($name): array
    {
        $name = $this->finder->normalizeName($name);

        $class = null;

        if (isset($this->resolvedComponentCache[$name])) {
            return [$name, $this->resolvedComponentCache[$name]];
        }

        if ($name) {
            $class = $this->finder->resolveClassComponentClassName($name);

            if (! $class) {
                $path = $this->finder->resolveMultiFileComponentPath($name);

                if (! $path) {
                    $path = $this->finder->resolveSingleFileComponentPath($name);
                }

                if ($path) {
                    $class = $this->compiler->compile($path);
                }
            }
        }

        if (! $class || ! class_exists($class) || ! is_subclass_of($class, Component::class)) {
            foreach ($this->missingComponentResolvers as $resolver) {
                if ($class = $resolver($name)) {
                    $this->finder->addComponent(name: $name, class: $class);

                    break;
                }
            }
        }

        // if (! $class || ! class_exists($class) || ! is_subclass_of($class, Component::class)) {
        //     throw new ComponentNotFoundException(
        //         "Unable to find component: [{$name}]"
        //     );
        // }

        $this->resolvedComponentCache[$name] = $class;

        return [$name, $class];
    }
}
