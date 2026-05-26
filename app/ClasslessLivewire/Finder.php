<?php

namespace App\ClasslessLivewire;

class Finder extends \Livewire\Finder\Finder
{
    private const ZAP = "\u{26A1}";

    private const ZAP_VS15 = "\u{26A1}\u{FE0E}";

    private const ZAP_VS16 = "\u{26A1}\u{FE0F}";

    public function resolveSingleFileComponentPath($name): ?string
    {
        $path = null;

        [$namespace, $componentName] = $this->parseNamespaceAndName($name);

        if ($namespace !== null) {
            if (isset($this->viewNamespaces[$namespace])) {
                $locations = [$this->viewNamespaces[$namespace]];
            } else {
                return null;
            }
        } else {
            $componentName = $name;

            // Check if the component is explicitly registered...
            if (isset($this->viewComponents[$name])) {
                $path = $this->viewComponents[$name];

                if (! is_dir($path) && file_exists($path) && $this->hasValidSingleFileComponentSource($path)) {
                    return $path;
                }
            }

            $locations = $this->viewLocations;
        }

        // Check for a component inside locations...
        foreach ($locations as $location) {
            $location = $this->normalizeLocation($location);
            $segments = explode('.', $componentName);

            $lastSegment = last($segments);
            $leadingSegments = implode('.', array_slice($segments, 0, -1));

            $trailingPath = str_replace('.', '/', $lastSegment);
            $leadingPath = $leadingSegments ? str_replace('.', '/', $leadingSegments).'/' : '';

            $paths = [
                'singleFileWithZap' => $location.'/'.$leadingPath.self::ZAP.$trailingPath.'.blade.php',
                'singleFileWithZapVariation15' => $location.'/'.$leadingPath.self::ZAP_VS15.$trailingPath.'.blade.php',
                'singleFileWithZapVariation16' => $location.'/'.$leadingPath.self::ZAP_VS16.$trailingPath.'.blade.php',
                'singleFileAsIndexWithZap' => $location.'/'.$leadingPath.$trailingPath.'/'.self::ZAP.'index.blade.php',
                'singleFileAsIndexWithZapVariation15' => $location.'/'.$leadingPath.$trailingPath.'/'.self::ZAP_VS15.'index.blade.php',
                'singleFileAsIndexWithZapVariation16' => $location.'/'.$leadingPath.$trailingPath.'/'.self::ZAP_VS16.'index.blade.php',
                'singleFileAsSelfNamedWithZap' => $location.'/'.$leadingPath.$trailingPath.'/'.self::ZAP.$trailingPath.'.blade.php',
                'singleFileAsSelfNamedWithZapVariation15' => $location.'/'.$leadingPath.$trailingPath.'/'.self::ZAP_VS15.$trailingPath.'.blade.php',
                'singleFileAsSelfNamedWithZapVariation16' => $location.'/'.$leadingPath.$trailingPath.'/'.self::ZAP_VS16.$trailingPath.'.blade.php',
                'singleFile' => $location.'/'.$leadingPath.$trailingPath.'.blade.php',
                'singleFileAsIndex' => $location.'/'.$leadingPath.$trailingPath.'/index.blade.php',
                'singleFileAsSelfNamed' => $location.'/'.$leadingPath.$trailingPath.'/'.$trailingPath.'.blade.php',
            ];

            foreach ($paths as $filePath) {
                if (! is_dir($filePath)
                    && file_exists($filePath)
                    // && $this->hasValidSingleFileComponentSource($filePath)
                ) {
                    return $filePath;
                }
            }
        }

        return $path;
    }
}
