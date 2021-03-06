<?php

namespace PhpParser;

use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testGetSet() {
        $comment = new Comment('/* Some comment */', 1, 10);

        $this->assertSame('/* Some comment */', $comment->getText());
        $this->assertSame('/* Some comment */', (string) $comment);
        $this->assertSame(1, $comment->getLine());
        $this->assertSame(10, $comment->getFilePos());
    }

    /**
     * @dataProvider provideTestReformatting
     */
    public function testReformatting($commentText, $reformattedText) {
        $comment = new Comment($commentText);
        $this->assertSame($reformattedText, $comment->getReformattedText());
    }

    public function provideTestReformatting() {
        return [
            ['// Some text' . "\n", '// Some text'],
            ['/* Some text */', '/* Some text */'],
            [
                '/**
     * Some text.
     * Some more text.
     */',
                '/**
 * Some text.
 * Some more text.
 */'
            ],
            [
                '/*
        Some text.
        Some more text.
    */',
                '/*
    Some text.
    Some more text.
*/'
            ],
            [
                '/* Some text.
       More text.
       Even more text. */',
                '/* Some text.
   More text.
   Even more text. */'
            ],
            [
                '/* Some text.
       More text.
         Indented text. */',
                '/* Some text.
   More text.
     Indented text. */',
            ],
            // invalid comment -> no reformatting
            [
                'hallo
    world',
                'hallo
    world',
            ],
        ];
    }
}
