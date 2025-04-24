<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Route;
use Mockery\MockInterface;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertStringContainsString;
use Spatie\Csp\AddCspHeaders;
use Spatie\Csp\Directive;
use Spatie\Csp\Exceptions\InvalidPreset;
use Spatie\Csp\Exceptions\InvalidValueSet;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Presets\Basic;
use Spatie\Csp\Scheme;
use Spatie\Csp\Value;
use Symfony\Component\HttpFoundation\HeaderBag;

function getResponseHeaders(string $url = 'test-route'): HeaderBag
{
    return test()
        ->get($url)
        ->assertSuccessful()
        ->headers;
}

beforeEach(function (): void {
    app(Kernel::class)->pushMiddleware(AddCspHeaders::class);

    Route::get('test-route', function (): string {
        return 'ok';
    });
});

it('will set csp headers with default configuration', function (): void {
    $headers = getResponseHeaders();

    assertStringContainsString("default-src 'self';", $headers->get('Content-Security-Policy'));
    assertNull($headers->get('Content-Security-Policy-Report-Only'));
});

it('will add additional directives', function (): void {
    config([
        'csp.nonce_enabled' => false,
        'csp.directives' => [
            [Directive::SCRIPT, [Keyword::UNSAFE_EVAL]],
        ],
    ]);

    $headers = getResponseHeaders();

    assertStringContainsString("script-src 'self' 'unsafe-eval';", $headers->get('Content-Security-Policy'));
    assertNull($headers->get('Content-Security-Policy-Report-Only'));
});

it('can set report only csp headers', function (): void {
    config([
        'csp.presets' => [],
        'csp.report_only_presets' => [Basic::class],
    ]);

    $headers = getResponseHeaders();

    assertStringContainsString("default-src 'self';", $headers->get('Content-Security-Policy-Report-Only'));
    assertNull($headers->get('Content-Security-Policy'));
});

it('will add additional report only directives', function (): void {
    config([
        'csp.report_only_directives' => [
            [Directive::SCRIPT, [Keyword::UNSAFE_EVAL]],
        ],
    ]);

    $headers = getResponseHeaders();

    assertStringContainsString("script-src 'unsafe-eval'", $headers->get('Content-Security-Policy-Report-Only'));
});

it('wont set any headers if not enabled in the config', function (): void {
    config([
        'csp.enabled' => false,
    ]);

    $headers = getResponseHeaders();

    assertNull($headers->get('Content-Security-Policy'));
});

test('a report uri can be set in the config', function (): void {
    config(['csp.report_uri' => 'https://report-uri.com']);

    $headers = getResponseHeaders();

    $cspHeader = $headers->get('Content-Security-Policy');

    assertStringContainsString('report-uri https://report-uri.com', $cspHeader);
});

it('will throw an exception when using an invalid policy class', function (): void {
    withoutExceptionHandling();

    $invalidPolicyClassName = get_class(new class {
    });

    config(['csp.presets' => [$invalidPolicyClassName]]);

    getResponseHeaders();
})->throws(InvalidPreset::class);

it('will throw an exception when passing none with other values', function (): void {
    withoutExceptionHandling();

    $invalidPolicy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::CONNECT, [Keyword::NONE, 'connect']);
        }
    };

    config(['csp.presets' => [$invalidPolicy::class]]);

    getResponseHeaders();
})->throws(InvalidValueSet::class);

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

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        'frame-src src-1 src-2;form-action action-1 action-2',
        $headers->get('Content-Security-Policy')
    );
});

it('can use multiple presets', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy
                ->add(Directive::FRAME, 'src-1')
                ->add(Directive::FORM_ACTION, 'action-1');
        }
    };

    $anotherPolicy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy
                ->add(Directive::FRAME, 'src-2')
                ->add(Directive::FORM_ACTION, 'action-2');
        }
    };

    config(['csp.presets' => [$policy::class, $anotherPolicy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        'frame-src src-1 src-2;form-action action-1 action-2',
        $headers->get('Content-Security-Policy')
    );
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

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        'connect-src \'none\';frame-src src-1',
        $headers->get('Content-Security-Policy')
    );
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

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        'connect-src \'self\';frame-src src-1',
        $headers->get('Content-Security-Policy')
    );
});

it('can add multiple values for the same directive in one go', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::FRAME, ['src-1', 'src-2']);
        }
    };

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        'frame-src src-1 src-2',
        $headers->get('Content-Security-Policy')
    );
});

it('will automatically quote special directive values', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::SCRIPT, [Keyword::SELF]);
        }
    };

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        "script-src 'self'",
        $headers->get('Content-Security-Policy')
    );
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

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        "script-src 'sha256-hash1' 'sha384-hash2' 'sha512-hash3'",
        $headers->get('Content-Security-Policy')
    );
});

it('will not output the same directive values twice', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::SCRIPT, [Keyword::SELF, Keyword::SELF]);
        }
    };

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        "script-src 'self'",
        $headers->get('Content-Security-Policy')
    );
});

test('route middleware will overwrite global middleware for that route', function (): void {
    withoutExceptionHandling();

    $customPolicy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::BASE, 'custom-policy');
        }
    };

    Route::get('other-route', function (): string {
        return 'ok';
    })->middleware(AddCspHeaders::class.':'.$customPolicy::class);

    $headers = getResponseHeaders('other-route');

    assertEquals(
        'base-uri custom-policy',
        $headers->get('Content-Security-Policy')
    );
});

test('route middleware is skipped when laravel renders exceptions', function (): void {
    config(['app.debug' => true]);

    Route::get('other-route', function (): string {
        throw new Exception('I am a server error');
    })->middleware(AddCspHeaders::class.':'.Basic::class);

    $headers = test()
        ->get('other-route')
        ->assertServerError()
        ->headers;

    assertFalse($headers->has('content-security-policy'));
});

test('route middleware is skipped when vite is hot reloading', function (): void {
    config(['app.debug' => true]);

    $this->mock(Vite::class, function (MockInterface $mock): void {
        $mock->shouldReceive('isRunningHot')->andReturn(true);
    });

    Route::get('other-route', function () {
        return 'ok';
    })->middleware(AddCspHeaders::class.':'.Basic::class);

    $headers = getResponseHeaders('other-route');

    assertFalse($headers->has('content-security-policy'));
});

it('will handle scheme values', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::IMG, [
                Scheme::DATA,
                Scheme::HTTPS,
                Scheme::WS,
                Scheme::WSS,
            ]);
        }
    };

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        'img-src data: https: ws: wss:',
        $headers->get('Content-Security-Policy')
    );
});


it('removes null values', function (): void {
    $policy = new class implements Preset {
        public function configure(Policy $policy): void
        {
            $policy->add(Directive::IMG, [
                Scheme::DATA,
                null,
                Scheme::HTTPS,
                null,
            ]);
        }
    };

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        'img-src data: https:',
        $headers->get('Content-Security-Policy')
    );
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

    config(['csp.presets' => [$policy::class]]);

    $headers = getResponseHeaders();

    assertEquals(
        'upgrade-insecure-requests;block-all-mixed-content',
        $headers->get('Content-Security-Policy')
    );
});

it('can apply report_uri to the report-only CSP policy when configured', function (): void {
    config([
        'csp.report_only_presets' => [Basic::class],
        'csp.report_uri' => 'https://report-uri-report-only.com',
    ]);

    $headers = getResponseHeaders();

    $reportOnlyHeader = $headers->get('Content-Security-Policy-Report-Only');

    assertStringContainsString('report-uri https://report-uri-report-only.com', $reportOnlyHeader);
});
