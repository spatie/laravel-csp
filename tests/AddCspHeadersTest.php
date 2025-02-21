<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertStringContainsString;
use Spatie\Csp\AddCspHeaders;
use Spatie\Csp\Directive;
use Spatie\Csp\Exceptions\InvalidCspPolicy;
use Spatie\Csp\Exceptions\InvalidValueSet;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\BasicPolicy;
use Spatie\Csp\Policies\Policy;
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

it('can set report only csp headers', function (): void {
    config([
        'csp.policies' => [],
        'csp.report_only_policies' => [BasicPolicy::class],
    ]);

    $headers = getResponseHeaders();

    assertStringContainsString("default-src 'self';", $headers->get('Content-Security-Policy-Report-Only'));
    assertNull($headers->get('Content-Security-Policy'));
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

    config(['csp.policies' => [$invalidPolicyClassName]]);

    getResponseHeaders();
})->throws(InvalidCspPolicy::class);

it('will throw an exception when passing none with other values', function (): void {
    withoutExceptionHandling();

    $invalidPolicy = new class extends Policy {
        public function configure(): void
        {
            $this->addDirective(Directive::CONNECT, [Keyword::NONE, 'connect']);
        }
    };

    config(['csp.policies' => [get_class($invalidPolicy)]]);

    getResponseHeaders();
})->throws(InvalidValueSet::class);

it('can use multiple values for the same directive', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this
                ->addDirective(Directive::FRAME, 'src-1')
                ->addDirective(Directive::FRAME, 'src-2')
                ->addDirective(Directive::FORM_ACTION, 'action-1')
                ->addDirective(Directive::FORM_ACTION, 'action-2');
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        'frame-src src-1 src-2;form-action action-1 action-2',
        $headers->get('Content-Security-Policy')
    );
});

it('can use multiple policies', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this
                ->addDirective(Directive::FRAME, 'src-1')
                ->addDirective(Directive::FRAME, 'src-2');
        }
    };

    $anotherPolicy = new class extends Policy {
        public function configure(): void
        {
            $this
                ->addDirective(Directive::FORM_ACTION, 'action-1')
                ->addDirective(Directive::FORM_ACTION, 'action-2');
        }
    };

    config(['csp.policies' => [get_class($policy), get_class($anotherPolicy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        'frame-src src-1 src-2;form-action action-1 action-2',
        $headers->get('Content-Security-Policy')
    );
});

test('none overrides other values for the same directive', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this
                ->addDirective(Directive::CONNECT, 'connect-1')
                ->addDirective(Directive::FRAME, 'src-1')
                ->addDirective(Directive::CONNECT, Keyword::NONE);
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        'connect-src \'none\';frame-src src-1',
        $headers->get('Content-Security-Policy')
    );
});

test('values override none value for the same directive', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this
                ->addDirective(Directive::CONNECT, Keyword::NONE)
                ->addDirective(Directive::FRAME, 'src-1')
                ->addDirective(Directive::CONNECT, Keyword::SELF);
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        'connect-src \'self\';frame-src src-1',
        $headers->get('Content-Security-Policy')
    );
});

test('a policy can be put in report only mode', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this->reportOnly();
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertNull($headers->get('Content-Security-Policy'));
    assertNotNull($headers->get('Content-Security-Policy-Report-Only'));
});

it('can add multiple values for the same directive in one go', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this->addDirective(Directive::FRAME, ['src-1', 'src-2']);
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        'frame-src src-1 src-2',
        $headers->get('Content-Security-Policy')
    );
});

it('will automatically quote special directive values', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this->addDirective(Directive::SCRIPT, [Keyword::SELF]);
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        "script-src 'self'",
        $headers->get('Content-Security-Policy')
    );
});

it('will automatically quote hashed values', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this->addDirective(Directive::SCRIPT, [
                'sha256-hash1',
                'sha384-hash2',
                'sha512-hash3',
            ]);
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        "script-src 'sha256-hash1' 'sha384-hash2' 'sha512-hash3'",
        $headers->get('Content-Security-Policy')
    );
});

it('will automatically check values when they are given in a single string separated by spaces', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this->addDirective(
                Directive::SCRIPT,
                'sha256-hash1 '.Keyword::SELF.'  source'
            );
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        "script-src 'sha256-hash1' 'self' source",
        $headers->get('Content-Security-Policy')
    );
});

it('will not output the same directive values twice', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this->addDirective(Directive::SCRIPT, [Keyword::SELF, Keyword::SELF]);
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        "script-src 'self'",
        $headers->get('Content-Security-Policy')
    );
});

test('route middleware will overwrite global middleware for that route', function (): void {
    withoutExceptionHandling();

    $customPolicy = new class extends Policy {
        public function configure(): void
        {
            $this->addDirective(Directive::BASE, 'custom-policy');
        }
    };

    Route::get('other-route', function (): string {
        return 'ok';
    })->middleware(AddCspHeaders::class.':'.get_class($customPolicy));

    $headers = getResponseHeaders('other-route');

    assertEquals(
        'base-uri custom-policy',
        $headers->get('Content-Security-Policy')
    );
});

it('will handle scheme values', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this->addDirective(Directive::IMG, [
                Scheme::DATA,
                Scheme::HTTPS,
                Scheme::WS,
                Scheme::WSS,
            ]);
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        'img-src data: https: ws: wss:',
        $headers->get('Content-Security-Policy')
    );
});

it('can use an empty value for a directive', function (): void {
    $policy = new class extends Policy {
        public function configure(): void
        {
            $this
                ->addDirective(Directive::UPGRADE_INSECURE_REQUESTS, Value::NO_VALUE)
                ->addDirective(Directive::BLOCK_ALL_MIXED_CONTENT, Value::NO_VALUE);
        }
    };

    config(['csp.policies' => [get_class($policy)]]);

    $headers = getResponseHeaders();

    assertEquals(
        'upgrade-insecure-requests;block-all-mixed-content',
        $headers->get('Content-Security-Policy')
    );
});
