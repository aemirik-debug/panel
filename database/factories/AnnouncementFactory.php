<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'content' => fake()->paragraphs(3, true),
            'image' => null,
            'button_text' => fake()->randomElement(['Detayları Gör', 'Hemen İncele', 'Teklif Al', null]),
            'button_url' => fake()->optional()->url(),
            'type' => fake()->randomElement(['modal', 'banner', 'popup']),
            'color_scheme' => fake()->randomElement(['primary', 'success', 'warning', 'danger', 'info']),
            'starts_at' => now()->subDays(rand(0, 5)),
            'ends_at' => now()->addDays(rand(10, 30)),
            'is_active' => true,
            'view_count' => 0,
        ];
    }

    /**
     * Indicate that the announcement is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the announcement has no end date.
     */
    public function unlimited(): static
    {
        return $this->state(fn (array $attributes) => [
            'ends_at' => null,
        ]);
    }

    /**
     * Indicate that the announcement is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subDays(30),
            'ends_at' => now()->subDays(1),
        ]);
    }
}
