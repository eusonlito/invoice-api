<?php declare(strict_types=1);

namespace Database\Fake;

use Illuminate\Database\Seeder;
use Faker;

class FakeAbstract extends Seeder
{
    /**
     * @var \Faker\Generator
     */
    protected Faker\Generator $faker;

    /**
     * @return \Faker\Generator
     */
    protected function faker(): Faker\Generator
    {
        return $this->faker ?? ($this->faker = Faker\Factory::create('es_ES'));
    }
}
