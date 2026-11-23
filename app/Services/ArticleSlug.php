<?php

namespace App\Services;

class ArticleSlug
{
    public function fromJudul(string $judul): string
    {
        $slug = strtolower($judul);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        return trim((string) $slug, '-');
    }

    public function unique(string $slug, callable $exists): string
    {
        $candidate = $slug;
        $suffix = 2;
        while ($exists($candidate)) {
            $candidate = $slug . '-' . $suffix;
            $suffix++;
        }

        return $candidate;
    }
}
