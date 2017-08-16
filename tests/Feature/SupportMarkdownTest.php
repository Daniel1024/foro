<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class SupportMarkdownTest extends FeatureTestCase
{

    public function test_the_post_content_support_markdown()
    {
        $importantText = "Un texto muy importante";

        $post = $this->createPost([
            'content' => "La primera parte del texto. **{$importantText}**. La última parte del texto"
        ]);

        $this->visit($post->url)
            ->seeInElement('strong', $importantText);
    }

    public function test_the_code_in_the_post_is_scaped()
    {
        $xssAttack = "<script>alert('Sharing code')</script>";

        $post = $this->createPost([
            'content' => "`{$xssAttack}`. Texto normal.",
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal')
            ->seeText($xssAttack);
    }

    public function test_xss_attack()
    {
        $xssAttack = "<script>alert('Malicious JS!')</script>";

        $post = $this->createPost([
            'content' => "Texto primero. **{$xssAttack}**. Texto despues",
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto despues');
    }

    public function test_xss_attack_with_html()
    {
        $xssAttack = "<img src='img.jpg'>";

        $post = $this->createPost([
            'content' => "Texto primero. **{$xssAttack}**. Texto despues",
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack);
    }
}
