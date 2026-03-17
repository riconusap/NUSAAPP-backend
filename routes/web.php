<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs/api', function () {
    abort_unless(File::exists(base_path('API_DOCUMENTATION.md')), 404);

    $markdown = File::get(base_path('API_DOCUMENTATION.md'));
    $html = Str::markdown($markdown);

    $dom = new DOMDocument('1.0', 'UTF-8');
    $previousLibxmlState = libxml_use_internal_errors(true);

    $dom->loadHTML(
        '<?xml encoding="utf-8" ?><div id="markdown-root">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );

    libxml_clear_errors();
    libxml_use_internal_errors($previousLibxmlState);

    $xpath = new DOMXPath($dom);
    $root = $xpath->query('//*[@id="markdown-root"]')->item(0);
    $tocHeading = $xpath->query('//h2[normalize-space()="Table of Contents"]')->item(0);

    if ($tocHeading !== null) {
        $currentNode = $tocHeading;

        while ($currentNode !== null) {
            $nextNode = $currentNode->nextSibling;

            if (
                $nextNode !== null
                && $nextNode->nodeType === XML_ELEMENT_NODE
                && in_array($nextNode->nodeName, ['h1', 'h2'], true)
            ) {
                $currentNode->parentNode?->removeChild($currentNode);
                break;
            }

            $currentNode->parentNode?->removeChild($currentNode);
            $currentNode = $nextNode;
        }
    }

    $toc = [];
    $slugCounts = [];

    foreach ($xpath->query('//h1 | //h2 | //h3') as $heading) {
        if (! $heading instanceof DOMElement) {
            continue;
        }

        $text = trim($heading->textContent);

        if ($text === '') {
            continue;
        }

        $baseSlug = Str::slug($text);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'section';

        $slugCounts[$baseSlug] = ($slugCounts[$baseSlug] ?? 0) + 1;
        $slug = $slugCounts[$baseSlug] === 1
            ? $baseSlug
            : $baseSlug . '-' . $slugCounts[$baseSlug];

        $heading->setAttribute('id', $slug);

        $toc[] = [
            'id' => $slug,
            'text' => $text,
            'level' => (int) substr($heading->nodeName, 1),
        ];
    }

    $content = '';

    foreach ($root?->childNodes ?? [] as $node) {
        $content .= $dom->saveHTML($node);
    }

    return view('docs.api', [
        'content' => $content,
        'toc' => $toc,
        'lastUpdated' => now()->format('d M Y H:i'),
    ]);
})->name('docs.api');

Route::get('/docs/postman', function () {
    abort_unless(File::exists(base_path('NUSAAPP.postman_collection.json')), 404);

    return response()->file(
        base_path('NUSAAPP.postman_collection.json'),
        [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="NUSAAPP.postman_collection.json"',
        ]
    );
})->name('docs.postman');

Route::get('/docs/postman/download', function () {
    abort_unless(File::exists(base_path('NUSAAPP.postman_collection.json')), 404);

    return response()->download(base_path('NUSAAPP.postman_collection.json'));
})->name('docs.postman.download');
