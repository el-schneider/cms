<?php

namespace Tests\Data\Structures;

use Illuminate\Support\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\Structure as StructureAPI;
use Statamic\Structures\Page;
use Statamic\Structures\Pages;
use Statamic\Structures\Structure;
use Tests\TestCase;

abstract class StructureTestCase extends TestCase
{
    abstract function structure($handle = null);

    /** @test */
    function the_tree_root_cannot_have_children_when_expecting_root()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Root page cannot have children');

        $this->structure()->expectsRoot(true)->validateTree([
            [
                'entry' => '123',
                'children' => [
                    [
                        'entry' => '456'
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    function the_tree_root_can_have_children_when_not_expecting_root()
    {
        $this->assertNull($this->structure()->expectsRoot(false)->validateTree([
            [
                'entry' => '123',
                'children' => [
                    [
                        'entry' => '456'
                    ]
                ]
            ]
        ]));
    }

    /** @test */
    function the_root_must_be_an_entry_when_expecting_root()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Root page must be an entry');

        $this->structure()->expectsRoot(true)->validateTree([
            [
                'title' => 'Not an entry',
                'url' => '/test',
            ]
        ]);
    }

    /** @test */
    function the_root_doesnt_need_to_be_an_entry_if_the_tree_is_empty()
    {
        $this->assertNull($this->structure()->expectsRoot(true)->validateTree([]));
    }

    /** @test **/
    function the_root_doesnt_need_to_be_an_entry_when_not_expecting_root()
    {
        $this->assertNull($this->structure()->expectsRoot(false)->validateTree([
            [
                'title' => 'Not an entry',
                'url' => '/test',
            ]
        ]));
    }
}