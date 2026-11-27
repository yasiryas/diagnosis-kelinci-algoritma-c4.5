<?php

declare(strict_types=1);

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        if ($path === '') {
            return 'http://example.test';
        }

        return 'http://example.test/' . ltrim($path, '/');
    }
}

if (!function_exists('route_to')) {
    function route_to(string $name): string
    {
        return 'http://example.test/' . $name;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_test_name" value="token">';
    }
}

if (!function_exists('esc')) {
    function esc($value, string $context = 'html'): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = null)
    {
        return $default;
    }
}

if (!function_exists('session')) {
    function session(string $key)
    {
        return $GLOBALS['dkSession'][$key] ?? null;
    }
}

if (!function_exists('user')) {
    function user()
    {
        return $GLOBALS['dkCurrentUser'] ?? null;
    }
}

if (!function_exists('view')) {
    function view(string $name, array $data = [], array $options = []): string
    {
        return '';
    }
}

if (!function_exists('lang')) {
    function lang(string $key): string
    {
        return $key;
    }
}

final class ViewRenderer
{
    private array $data = [];
    private array $sections = [];
    private ?string $layout = null;
    private string $currentSection = '';

    public function renderView(string $path, array $data = []): string
    {
        $this->reset($data);

        return $this->capture($path);
    }

    public function renderPage(string $path, array $data = []): string
    {
        $this->reset($data);
        $this->capture($path);

        return $this->capture($this->layoutPath());
    }

    public function renderLayout(string $path, string $content, array $data = []): string
    {
        $this->reset($data);
        $this->sections['content'] = $content;

        return $this->capture($path);
    }

    public function setData(array $data): self
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function extend(string $layout): string
    {
        $this->layout = $layout;

        return '';
    }

    public function section(string $name): string
    {
        $this->currentSection = $name;
        ob_start();

        return '';
    }

    public function endSection(): string
    {
        $this->sections[$this->currentSection] = (string) ob_get_clean();
        $this->currentSection = '';

        return '';
    }

    public function renderSection(string $name): string
    {
        return $this->sections[$name] ?? '';
    }

    public static function authConfig(array $overrides = []): object
    {
        return (object) array_merge([
            'validFields' => ['email'],
            'allowRemembering' => true,
            'allowRegistration' => true,
            'activeResetter' => true,
        ], $overrides);
    }

    private function reset(array $data): void
    {
        $this->data = $data;
        $GLOBALS['dkCurrentUser'] = $data['authenticatedUser'] ?? null;
        $this->sections = [];
        $this->layout = null;
        $this->currentSection = '';
    }

    private function capture(string $path): string
    {
        extract($this->data);
        ob_start();
        include APP_ROOT . '/' . $path;

        return (string) ob_get_clean();
    }

    private function layoutPath(): string
    {
        if ($this->layout === null) {
            throw new RuntimeException('View tidak memanggil extend().');
        }

        return 'app/Views/' . $this->layout . '.php';
    }
}

final class TestDom
{
    public static function document(string $html): DOMDocument
    {
        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $document;
    }

    public static function one(DOMDocument $document, string $query, ?DOMNode $context = null): DOMElement
    {
        $node = self::xpath($document)->query($query, $context)->item(0);

        if (!$node instanceof DOMElement) {
            throw new RuntimeException("Elemen tidak ditemukan: {$query}");
        }

        return $node;
    }

    public static function all(DOMDocument $document, string $query, ?DOMNode $context = null): array
    {
        $nodes = [];

        foreach (self::xpath($document)->query($query, $context) as $node) {
            if ($node instanceof DOMElement) {
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    public static function attributes(DOMDocument $document, string $query, string $attribute): array
    {
        return array_map(
            static fn (DOMElement $node): string => $node->getAttribute($attribute),
            self::all($document, $query)
        );
    }

    private static function xpath(DOMDocument $document): DOMXPath
    {
        return new DOMXPath($document);
    }
}
