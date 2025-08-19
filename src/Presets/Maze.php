<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Maze implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, [
                'https://snippet.maze.co',
            ])
            ->add(Directive::CONNECT, [
                'https://prompts.maze.co',
            ]);
    }
}
