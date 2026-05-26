<?php

namespace App\ClasslessLivewire;

class SingleFileParser extends \Livewire\Compiler\Parser\SingleFileParser
{
    public static function extractClassPortion(string &$contents): string
    {
        $pattern = '/<\?php\s*.*?\s*\?>/s';

        $classPortion = static::extractPattern($pattern, $contents);

        if ($classPortion === false) {
            $classPortion = "<?php new class extends Livewire\Component { };";
            // throw new \Exception('Class contents not found');
        }

        return $classPortion;
    }
}
