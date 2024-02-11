<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class AppSanitizerTest extends TestCase
{
    public function test_app_sanitizer_with_script_tags(): void
    {
        $input = "<script>alert('hello');</script>";
        $expected = "alert(&#039hello&#039)";
        $sanitized = app_sanitizer($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_app_sanitizer_with_html_entities(): void
    {
        $input = "&lt;script&gt;alert(&#39;hello&#39;);&lt;/script&gt;";
        $expected = "&ampltscript&ampgtalert(&amp#39hello&amp#39)&amplt/script&ampgt";
        $sanitized = app_sanitizer($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_app_sanitizer_with_whitespace(): void
    {
        $input = "   hello   ";
        $expected = "hello";
        $sanitized = app_sanitizer($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_app_sanitizer_empty_input(): void
    {
        $input = "";
        $expected = "";
        $sanitized = app_sanitizer($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_app_sanitizer_with_css(): void
    {
        $input = "color: red; background-image: url('malicious.png');";
        $expected = "color: red background-image: url(&#039malicious.png&#039)";
        $sanitized = app_sanitizer($input);
        $this->assertEquals($expected, $sanitized);
    }

    public function test_app_sanitizer_with_html(): void
    {
        $input = "<p><a href='http://example.com'>Link</a></p>";
        $expected = "Link";
        $sanitized = app_sanitizer($input);
        $this->assertEquals($expected, $sanitized);
    }
}
