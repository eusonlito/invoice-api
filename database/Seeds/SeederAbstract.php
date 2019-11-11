<?php declare(strict_types=1);

namespace Database\Seeds;

use Closure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeederAbstract extends Seeder
{
    /**
     * @param string $name
     *
     * @return string
     */
    protected function file(string $name): string
    {
        return __DIR__.'/data/'.$name;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function contents(string $name): string
    {
        return file_get_contents($this->file($name));
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function json(string $name): array
    {
        return json_decode($this->contents($name.'.json'), true);
    }

    /**
     * @param \Closure $function
     *
     * @return void
     */
    protected function transaction(Closure $function)
    {
        DB::transaction(function () use ($function) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $function();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }

    /**
     * @param string ...$tables
     *
     * @return void
     */
    protected function truncate(string ...$tables)
    {
        $this->transaction(static function () use ($tables) {
            foreach ((array)$tables as $table) {
                DB::statement('TRUNCATE TABLE `'.$table.'`;');
            }
        });
    }
}
