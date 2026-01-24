<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Plain implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, 'https://chat.cdn-plain.com')
            ->add(Directive::CONNECT, [
                'https://chat.uk.plain.com',
                'https://prod-uk-services-attachm-attachmentsuploadbucket2-1l2e4906o2asm.s3.eu-west-2.amazonaws.com',
            ])
            ->add(Directive::STYLE, 'https://fonts.googleapis.com')
            ->add(Directive::IMG, [
                'https://prod-uk-services-workspac-workspacefilespublicbuck-vs4gjqpqjkh6.s3.amazonaws.com',
                'https://prod-uk-services-attachm-attachmentsbucket28b3ccf-uwfssb4vt2us.s3.eu-west-2.amazonaws.com',
                'https://i0.wp.com',
            ]);
    }
}
