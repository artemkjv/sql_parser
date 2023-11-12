<?php

namespace Tests\Unit;

use App\Models\Dump;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_has_dump_relationship() {
        $file = File::factory()->create();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $file->dump());
        $this->assertInstanceOf(Dump::class, $file->dump);
    }

}
