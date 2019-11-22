<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models;
use App\Models\Company as Model;

class CompanyTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'company';

    /**
     * @return void
     */
    public function testDetailNoExistsFail(): void
    {
        $this->auth()->json('GET', $this->route('detail'))
            ->assertStatus(404);
    }

    /**
     * Rules:
     *
     * 'name' => 'required|string',
     * 'address' => 'required|string',
     * 'city' => 'required|string',
     * 'state' => 'required|string',
     * 'postal_code' => 'required|string',
     * 'tax_number' => 'required|string',
     * 'phone' => 'required',
     * 'email' => 'required|email',
     * 'country_id' => 'required|integer|exists:country,id',
     */

    /**
     * @return void
     */
    public function testUpdateFail(): void
    {
        $this->auth()->json('PATCH', $this->route('update'))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.');
    }

    /**
     * @return void
     */
    public function testUpdateNameFail(): void
    {
        $row = factory(Model::class)->make(['name' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testUpdateAddressFail(): void
    {
        $row = factory(Model::class)->make(['address' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('direcci\u00f3n');
    }

    /**
     * @return void
     */
    public function testUpdateCityFail(): void
    {
        $row = factory(Model::class)->make(['city' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('ciudad');
    }

    /**
     * @return void
     */
    public function testUpdateStateFail(): void
    {
        $row = factory(Model::class)->make(['state' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('provincia');
    }

    /**
     * @return void
     */
    public function testUpdatePostalCodeFail(): void
    {
        $row = factory(Model::class)->make(['postal_code' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('c\u00f3digo postal');
    }

    /**
     * @return void
     */
    public function testUpdateTaxNumberFail(): void
    {
        $row = factory(Model::class)->make(['tax_number' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('NIF');
    }

    /**
     * @return void
     */
    public function testUpdatePhoneFail(): void
    {
        $row = factory(Model::class)->make(['phone' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('tel\u00e9fono');
    }

    /**
     * @return void
     */
    public function testUpdateEmailEmptyFail(): void
    {
        $row = factory(Model::class)->make(['email' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testUpdateEmailNoValidFail(): void
    {
        $row = factory(Model::class)->make(['email' => 'email']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testUpdateCountryEmptyFail(): void
    {
        $row = factory(Model::class)->make(['country_id' => '']);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('pa\u00eds');
    }

    /**
     * @return void
     */
    public function testUpdateCountryNoExistsFail(): void
    {
        $row = factory(Model::class)->make(['country_id' => 99999]);

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('pa\u00eds');
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateFirstSuccess(): void
    {
        $row = factory(Model::class)->make();

        $this->auth($this->userFirst())->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()->json('GET', $this->route('detail'))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return array
     */
    protected function structure(): array
    {
        return [
            'id', 'name', 'address', 'city', 'state', 'postal_code', 'tax_number', 'phone', 'email',
            'country' => ['id', 'name']
        ];
    }
}
