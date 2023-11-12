<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Dump;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DumpTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_has_articles_relationship() {
        $dump = Dump::factory()->create();
        Article::factory(10)->create([
            'dump_id' => $dump->id
        ]);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $dump->articles);
        $this->assertEquals(10, $dump->articles()->count());
    }

    public function test_it_has_files_relationship() {
        $dump = Dump::factory()->create();
        File::factory(10)->create([
            'dump_id' => $dump->id
        ]);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $dump->files);
        $this->assertEquals(10, $dump->files()->count());
    }

}
