<?php

declare(strict_types=1);

use App\Http\Resources\Showcase\ShowcaseResource;

it('transforms flat showcase view data correctly', function (): void {
    // Generate an anonymous object representing a row from the product_showcase_view
    $data = (object) [
        'item_id'     => '11111111-1111-1111-1111-111111111111',
        'product_id'  => '22222222-2222-2222-2222-222222222222',
        'title'       => 'Some Title',
        'description' => 'Some Description',
        'media_url'   => 'https://example.com/img.png',
        'content'     => '<p>Html</p>',
        'sort_order'  => 5,
    ];

    $resource = new ShowcaseResource($data);
    $array    = $resource->resolve();

    expect($array)->toBe([
        'id'          => '11111111-1111-1111-1111-111111111111',
        'product_id'  => '22222222-2222-2222-2222-222222222222',
        'title'       => 'Some Title',
        'description' => 'Some Description',
        'media_url'   => 'https://example.com/img.png',
        'content'     => '<p>Html</p>',
        'sort_order'  => 5,
    ]);
});
