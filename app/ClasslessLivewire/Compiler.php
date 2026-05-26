<?php

namespace App\ClasslessLivewire;

use Livewire\Compiler\Parser\MultiFileParser;

class Compiler extends \Livewire\Compiler\Compiler
{
    public function compilePath(string $path): void
    {
        $parser = is_file($path)
            ? SingleFileParser::parse($this, $path)
            : MultiFileParser::parse($this, $path);

        $viewFileName = $this->cacheManager->getViewPath($path);

        $placeholderFileName = null;
        $scriptFileName = null;
        $styleFileName = null;
        $globalStyleFileName = null;

        $placeholderContents = $parser->generatePlaceholderContents();
        $scriptContents = $parser->generateScriptContents();
        $styleContents = $parser->generateStyleContents();
        $globalStyleContents = $parser->generateGlobalStyleContents();

        if ($placeholderContents !== null) {
            $placeholderFileName = $this->cacheManager->getPlaceholderPath($path);

            $this->cacheManager->writePlaceholderFile($path, $placeholderContents);
        }

        if ($scriptContents !== null) {
            $scriptFileName = $this->cacheManager->getScriptPath($path);

            $this->cacheManager->writeScriptFile($path, $scriptContents);
        }

        if ($styleContents !== null) {
            $styleFileName = $this->cacheManager->getStylePath($path);

            $this->cacheManager->writeStyleFile($path, $styleContents);
        }

        if ($globalStyleContents !== null) {
            $globalStyleFileName = $this->cacheManager->getGlobalStylePath($path);

            $this->cacheManager->writeGlobalStyleFile($path, $globalStyleContents);
        }

        $this->cacheManager->writeViewFile($path, $parser->generateViewContents());

        // Ensure the class file is the last write, as it's used
        // in the hasBeenCompiled() check, so its presence on
        // disk means everything has been compiled...
        $this->cacheManager->writeClassFile($path, $parser->generateClassContents(
            $viewFileName,
            $placeholderFileName,
            $scriptFileName,
            $styleFileName,
            $globalStyleFileName,
        ));
    }
}
