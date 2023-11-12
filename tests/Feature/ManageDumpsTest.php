<?php

namespace Tests\Feature;

use App\Jobs\ParseSqlDump;
use App\Models\Article;
use App\Models\Dump;
use App\Models\User;
use App\Services\DataImporterService;
use App\Services\SqlParserService;
use App\Services\TableCreatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ManageDumpsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_it_displays_create_view()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dumps.create'));

        $response->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Dump/Create')
            );
    }

    public function test_it_stores_and_dispatches_jobs()
    {
        $this->actingAs($this->user);

        $this->mock('overload:'.SqlParserService::class, function ($mock) {
            $mock->shouldReceive('extractInsertQuery')
                ->andReturn('insert statement');
        });

        $this->mock('overload:'.TableCreatorService::class, function ($mock) {
            $mock->shouldReceive('createTable')->andReturn('table_name');
        });

        $this->mock('overload:'.DataImporterService::class, function ($mock) {
            $mock->shouldReceive('importData');
        });

        $file = UploadedFile::fake()->create('test.sql', 100);
        Queue::fake();

        $response = $this->post(route('dumps.store'), [
            'files' => [$file],
        ]);

        $response->assertRedirect()
            ->assertSessionHas('message', 'Dump parsing has been initiated!');
        Queue::assertPushed(ParseSqlDump::class);
    }

    public function test_it_destroys_a_dump()
    {
        $this->actingAs($this->user);

        $dump = Dump::factory()
            ->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('dumps.destroy', $dump->id));

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Dump deleted successfully!');
        $this->assertDatabaseMissing('dumps', ['id' => $dump->id]);
    }

    public function test_index()
    {
        $this->actingAs($this->user);
        $dump = Dump::factory()->create([
            'user_id' => $this->user,
        ]);

        $response = $this->get(route('dumps.index'));

        $response->assertSee($dump->filename)
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Dump/Index')
            );
    }



    public function test_show()
    {

        $this->actingAs($this->user);

        $dump = Dump::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $article = Article::factory()->create([
            'dump_id' => $dump->id
        ]);

        $response = $this->get(route('dumps.show', $dump->id));
        $response->assertSee($dump->filename)
            ->assertSee($article->title)
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Dump/Show')
            );

    }

}
