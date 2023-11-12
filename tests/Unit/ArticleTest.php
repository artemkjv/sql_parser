<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Dump;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_has_dump_relationship() {
        $article = Article::factory()->create();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $article->dump());
        $this->assertInstanceOf(Dump::class, $article->dump);
    }

}
