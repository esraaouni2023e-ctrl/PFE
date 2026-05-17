<?php

namespace Tests\Feature;

use Tests\TestCase;

class DisableRedirectCachingTest extends TestCase
{
    /**
     * Test that redirect responses contain no-cache headers.
     */
    public function test_redirect_responses_contain_nocache_headers(): void
    {
        // /demarrer redirects to /riasec/question
        $response = $this->get('/riasec/demarrer');

        $response->assertRedirect('/riasec/question');
        $response->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private');
        $response->assertHeader('Pragma', 'no-cache');
    }

    /**
     * Test that views inside /riasec contain no-cache headers.
     */
    public function test_riasec_question_page_contains_nocache_headers(): void
    {
        $response = $this->get('/riasec/question');

        // Should redirect to student pipeline, which has no-cache headers
        $response->assertRedirect('/student/pipeline');
        $response->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private');
    }
}
