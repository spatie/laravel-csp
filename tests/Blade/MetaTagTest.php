<?php

use Illuminate\View\ViewException;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Scheme;
use Spatie\Csp\Value;

function renderView($policyName = null)
{
    return app('view')
        ->file(__DIR__.'/../fixtures/csp-meta-tags.blade.php')
        ->with('policyName', $policyName)
        ->render();
}

function metaTagRegex(string $headerName = 'Content-Security-Policy', $content = '.*')
{
    return "/<head><meta http-equiv=\"{$headerName}\" content=\"{$content}\"><\/head>/";
}

it('will output csp headers with the default configuration', function (): void {
    expect(renderView())
        ->toMatch(metaTagRegex())
        ->toContain("default-src 'self';");
});

it('wont output any meta tag if not enabled in the config', function (): void {
    config([
        'csp.enabled' => false,
    ]);

    expect(renderView())->toContain('<head></head>');
});

it('will use configuration when passing no policy class', function (): void {
    config([
        'csp.nonce_enabled' => false,
        'csp.directives' => [
            [Directive::SCRIPT, [Keyword::UNSAFE_EVAL]],
        ],
    ]);

    expect(renderView())->toHaveMetaContent("base-uri 'self';connect-src 'self';default-src 'self';font-src 'self';form-action 'self';frame-src 'self';img-src 'self';media-src 'self';object-src 'none';script-src 'self' 'unsafe-eval';style-src 'self'");
});

it('will throw an exception when using an invalid policy class', function (): void {
    $invalidPolicyClassName = get_class(new class {
    });

    renderView($invalidPolicyClassName);
})->throws(ViewException::class, 'A valid policy extends');

it('will throw an exception when passing none with other values', function (): void {
    $invalidPolicy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::CONNECT, [Keyword::NONE, 'connect']);
        }
    };

    renderView($invalidPolicy::class);
})->throws(ViewException::class, 'The keyword none can only be used on its own');

it('can use multiple values for the same directive', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy
                ->add(Directive::FRAME, 'src-1')
                ->add(Directive::FRAME, 'src-2')
                ->add(Directive::FORM_ACTION, 'action-1')
                ->add(Directive::FORM_ACTION, 'action-2');
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent('frame-src src-1 src-2;form-action action-1 action-2');
});

it('can render multiple presets', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy
                ->add(Directive::FRAME, 'src-1')
                ->add(Directive::FRAME, 'src-2');
        }
    };

    $anotherPolicy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy
                ->add(Directive::FORM_ACTION, 'action-1')
                ->add(Directive::FORM_ACTION, 'action-2');
        }
    };

    expect(renderView([$policy::class, $anotherPolicy::class]))
        ->toHaveMetaContent('frame-src src-1 src-2;form-action action-1 action-2');
});

test('none overrides other values for the same directive', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy
                ->add(Directive::CONNECT, 'connect-1')
                ->add(Directive::FRAME, 'src-1')
                ->add(Directive::CONNECT, Keyword::NONE);
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent('connect-src \'none\';frame-src src-1');
});

test('values override none value for the same directive', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy
                ->add(Directive::CONNECT, Keyword::NONE)
                ->add(Directive::FRAME, 'src-1')
                ->add(Directive::CONNECT, Keyword::SELF);
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent('connect-src \'self\';frame-src src-1');
});

it('can add multiple values for the same directive in one go', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::FRAME, ['src-1', 'src-2']);
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent('frame-src src-1 src-2');
});

it('will automatically quote special directive values', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::SCRIPT, [Keyword::SELF]);
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent("script-src 'self'");
});

it('will automatically quote hashed values', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::SCRIPT, [
                'sha256-hash1',
                'sha384-hash2',
                'sha512-hash3',
            ]);
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent("script-src 'sha256-hash1' 'sha384-hash2' 'sha512-hash3'");
});

it('will not output the same directive values twice', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::SCRIPT, [Keyword::SELF, Keyword::SELF]);
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent("script-src 'self'");
});

it('will handle scheme values', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::IMG, [
                Scheme::DATA,
                Scheme::HTTPS,
                Scheme::WS,
            ]);
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent('img-src data: https: ws:');
});

it('can use an empty value for a directive', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy
                ->add(Directive::UPGRADE_INSECURE_REQUESTS, Value::NO_VALUE)
                ->add(Directive::BLOCK_ALL_MIXED_CONTENT, Value::NO_VALUE);
        }
    };

    expect(renderView($policy::class))->toHaveMetaContent('upgrade-insecure-requests;block-all-mixed-content');
});
