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
     * @var array
     */
    protected array $structure = [
        'id', 'name', 'address', 'city', 'state', 'postal_code', 'tax_number',
        'phone', 'email', 'country' => ['id', 'name']
    ];

    /**
     * @return void
     */
    public function testDetailNoAuthFail(): void
    {
        $this->json('GET', $this->route('detail'))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testDetailNoExistsFail(): void
    {
        $this->auth()
            ->json('GET', $this->route('detail'))
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
    public function testCreateEmptyFail(): void
    {
        $this->auth()
            ->json('POST', $this->route('create'))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' name ')
            ->assertDontSee(' address ')
            ->assertDontSee(' city ')
            ->assertDontSee(' state ')
            ->assertDontSee(' postal code ')
            ->assertDontSee(' tax number ')
            ->assertDontSee(' phone ')
            ->assertDontSee(' email ')
            ->assertDontSee(' country id ')
            ->assertSee($this->t('validator.name-required'))
            ->assertSee($this->t('validator.address-required'))
            ->assertSee($this->t('validator.city-required'))
            ->assertSee($this->t('validator.state-required'))
            ->assertSee($this->t('validator.postal_code-required'))
            ->assertSee($this->t('validator.tax_number-required'))
            ->assertSee($this->t('validator.phone-required'))
            ->assertSee($this->t('validator.email-required'))
            ->assertSee($this->t('validator.country_id-required'));
    }

    /**
     * @return void
     */
    public function testCreateInvalidFail(): void
    {
        $fail = [
            'email' => 'fail',
            'country_id' => 99999
        ];

        $this->auth()
            ->json('POST', $this->route('update'), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' email ')
            ->assertDontSee(' country id ')
            ->assertSee($this->t('validator.email-email'))
            ->assertSee($this->t('validator.country_id-required'));
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $this->json('POST', $this->route('update'))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateFail(): void
    {
        $row = Model::factory()->make();

        $this->auth()
            ->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testCreateFirstSuccess(): void
    {
        $row = Model::factory()->make();

        $this->auth($this->userFirst())
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = Model::factory()->make();

        $this->auth()
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testCreateFail(): void
    {
        $row = Model::factory()->make();

        $this->auth()
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function testUpdateEmptyFail(): void
    {
        $this->auth()
            ->json('PATCH', $this->route('update'))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' name ')
            ->assertDontSee(' address ')
            ->assertDontSee(' city ')
            ->assertDontSee(' state ')
            ->assertDontSee(' postal code ')
            ->assertDontSee(' tax number ')
            ->assertDontSee(' phone ')
            ->assertDontSee(' email ')
            ->assertDontSee(' country id ')
            ->assertSee($this->t('validator.name-required'))
            ->assertSee($this->t('validator.address-required'))
            ->assertSee($this->t('validator.city-required'))
            ->assertSee($this->t('validator.state-required'))
            ->assertSee($this->t('validator.postal_code-required'))
            ->assertSee($this->t('validator.tax_number-required'))
            ->assertSee($this->t('validator.phone-required'))
            ->assertSee($this->t('validator.email-required'))
            ->assertSee($this->t('validator.country_id-required'));
    }

    /**
     * @return void
     */
    public function testUpdateInvalidFail(): void
    {
        $fail = [
            'email' => 'fail',
            'country_id' => 99999
        ];

        $this->auth()
            ->json('PATCH', $this->route('update'), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' email ')
            ->assertDontSee(' country id ')
            ->assertSee($this->t('validator.email-email'))
            ->assertSee($this->t('validator.country_id-required'));
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $this->json('PATCH', $this->route('update'))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateFirstSuccess(): void
    {
        $row = Model::factory()->make();

        $this->auth($this->userFirst())
            ->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        $row = Model::factory()->make();

        $this->auth()
            ->json('PATCH', $this->route('update'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()
            ->json('GET', $this->route('detail'))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }
}
