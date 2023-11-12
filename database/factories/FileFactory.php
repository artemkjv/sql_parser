<?php

namespace Database\Factories;

use App\Enums\FileType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileType = FileType::random();
        return [
            'type' => $fileType->name,
            'path' => "/files/{$this->faker->word}.$fileType->value",
            'dump_id' => function() {
                return \App\Models\Dump::factory()->create()->id;
            },
        ];
    }
}
