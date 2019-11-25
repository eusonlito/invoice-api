<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models;
use App\Models\User as Model;

class UserTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'user';

    /**
     * @var array
     */
    protected array $structure = [
        'token', 'user' => ['confirmed_at', 'id', 'user', 'name', 'language']
    ];

    /**
     * @return void
     */
    public function setup(): void
    {
        parent::setup();

        Models\Fail::truncate();
        Models\IpLock::truncate();
    }

    /**
     * Rules:
     *
     * 'name' => 'required',
     * 'user' => 'required|email|disposable_email',
     * 'password' => 'required|min:6',
     * 'password_repeat' => 'required|same:password',
     * 'conditions' => 'required|accepted'
     */

    /**
     * @return void
     */
    public function testSignupEmptyFail(): void
    {
        $this->json('POST', $this->route('signup'))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' name ')
            ->assertDontSee(' user ')
            ->assertDontSee(' password ')
            ->assertDontSee(' password repeat ')
            ->assertDontSee(' conditions ')
            ->assertSee($this->t('validator.name-required'))
            ->assertSee($this->t('validator.user-required'))
            ->assertSee($this->t('validator.password-required'))
            ->assertSee($this->t('validator.password_repeat-required'))
            ->assertSee($this->t('validator.conditions-required'));
    }

    /**
     * @return void
     */
    public function testSignupInvalidFail(): void
    {
        $fail = [
            'user' => 'fail',
            'password' => 'fail',
            'password_repeat' => 'failfail',
        ];

        $this->json('POST', $this->route('signup'), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' user ')
            ->assertDontSee(' password ')
            ->assertDontSee(' password repeat ')
            ->assertSee($this->t('validator.user-email'))
            ->assertSee($this->t('validator.password-min', ['min' => 6]))
            ->assertSee($this->t('validator.password_repeat-same'));
    }

    /**
     * Rules:
     *
     * 'user' => 'required|email|disposable_email',
     */

    /**
     * @return void
     */
    public function testConfirmEmptyFail(): void
    {
        $this->json('POST', $this->route('confirm.start'))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' user ')
            ->assertSee($this->t('validator.user-required'));
    }

    /**
     * @return void
     */
    public function testConfirmInvalidFail(): void
    {
        $this->json('POST', $this->route('confirm.start'), ['user' => 'fail'])
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' user ')
            ->assertSee($this->t('validator.user-email'));
    }

    /**
     * @return void
     */
    public function testConfirmNoExistsFail(): void
    {
        $this->json('POST', $this->route('confirm.start'), ['user' => 'fail@fail.com'])
            ->assertStatus(404);
    }

    /**
     * Rules:
     *
     * 'user' => 'required|email|disposable_email',
     */

    /**
     * @return void
     */
    public function testPasswordResetEmptyFail(): void
    {
        $this->json('POST', $this->route('password.reset.start'))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' user ')
            ->assertSee($this->t('validator.user-required'));
    }

    /**
     * @return void
     */
    public function testPasswordResetInvalidFail(): void
    {
        $this->json('POST', $this->route('password.reset.start'), ['user' => 'fail'])
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' user ')
            ->assertSee($this->t('validator.user-email'));
    }

    /**
     * Fake 200 response
     *
     * @return void
     */
    public function testPasswordResetNoExistsFail(): void
    {
        $this->json('POST', $this->route('password.reset.start'), ['user' => 'fail@fail.com'])
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testSignupFirstSuccess(): void
    {
        $row = factory(Model::class)->make()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = $row['user'];
        $row['conditions'] = true;

        $this->json('POST', $this->route('signup'), $row)
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testSignupSuccess(): void
    {
        $row = factory(Model::class)->make()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = $row['user'];
        $row['conditions'] = true;

        $this->json('POST', $this->route('signup'), $row)
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * Rules:
     *
     * 'user' => 'required|email|disposable_email',
     */

    /**
     * @return void
     */
    public function testConfirmSuccess(): void
    {
        $row = $this->user();

        $this->json('POST', $this->route('confirm.start'), ['user' => $row->user])
            ->assertStatus(200);

        $hash = encrypt($row->id.'|'.microtime(true));

        $this->json('POST', $this->route('confirm.finish', $hash))
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testPasswordResetSuccess(): void
    {
        $row = $this->user()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = $row['user'];

        $this->json('POST', $this->route('password.reset.start'), $row)
            ->assertStatus(200);

        $hash = Models\UserPasswordReset::orderBy('id', 'DESC')->first()->hash;

        $this->json('POST', $this->route('password.reset.finish', $hash), $row)
            ->assertStatus(200);
    }

    /**
     * Rules:
     *
     * 'user' => 'required|email|disposable_email',
     * 'password' => 'required',
     */

    /**
     * @return void
     */
    public function testAuthFail(): void
    {
        $row = factory(Model::class)->make()->toArray();
        $row['password'] = $row['user'];

        $this->json('POST', $this->route('auth.credentials'), $row)
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testAuthSuccess(): void
    {
        $row = $this->user()->toArray();
        $row['password'] = $row['user'];

        $this->json('POST', $this->route('auth.credentials'), $row)
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * Rules:
     *
     * 'name' => 'required',
     * 'user' => 'required|email|disposable_email',
     * 'password_current' => 'required|password'
     */

    /**
     * @return void
     */
    public function testProfileNoAuthFail(): void
    {
        $row = $this->user();
        $row->password_current = $row->user;

        $this->json('PATCH', $this->route('update.profile'), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testProfileEmptyFail(): void
    {
        $this->auth()->json('PATCH', $this->route('update.profile'))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' name ')
            ->assertDontSee(' user ')
            ->assertDontSee(' password current ')
            ->assertSee($this->t('validator.name-required'))
            ->assertSee($this->t('validator.user-required'))
            ->assertSee($this->t('validator.password_current-required'));
    }

    /**
     * @return void
     */
    public function testProfileInvalidFail(): void
    {
        $row = $this->user();
        $row->password_current = $row->user;

        $fail = ['user' => 'fail'];

        $this->auth()->json('PATCH', $this->route('update.profile'), $fail + $row->toArray())
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' user ')
            ->assertSee($this->t('validator.user-email'));
    }

    /**
     * @return void
     */
    public function testProfilePasswordCurrentFail(): void
    {
        $row = $this->user();
        $row->password_current = 'fail';

        $this->auth()->json('PATCH', $this->route('update.profile'), $row->toArray())
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' password current ')
            ->assertSee($this->t('validator.password_current-password'));
    }

    /**
     * @return void
     */
    public function testProfileSuccess(): void
    {
        $row = $this->user();
        $row->password_current = $row->user;

        $this->auth()->json('PATCH', $this->route('update.profile'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure['user']);
    }

    /**
     * Rules:
     *
     * 'password_current' => 'required|password',
     * 'password' => 'required|min:6',
     * 'password_repeat' => 'required|same:password',
     */

    /**
     * @return void
     */
    public function testPasswordNoAuthFail(): void
    {
        $row = $this->user()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = $row['user'];
        $row['password_current'] = $row['user'];

        $this->json('PATCH', $this->route('update.password'), $row)
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testPasswordPasswordFail(): void
    {
        $row = $this->user()->toArray();
        $row['password'] = 'fail';
        $row['password_repeat'] = 'fail';
        $row['password_current'] = $row['user'];

        $this->auth()->json('PATCH', $this->route('update.password'), $row)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' password ')
            ->assertSee($this->t('validator.password-min', ['min' => 6]));
    }

    /**
     * @return void
     */
    public function testPasswordRepeatFail(): void
    {
        $row = $this->user()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = 'fail';
        $row['password_current'] = $row['user'];

        $this->auth()->json('PATCH', $this->route('update.password'), $row)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' password repeat ')
            ->assertSee($this->t('validator.password_repeat-same'));
    }

    /**
     * @return void
     */
    public function testPasswordCurrentFail(): void
    {
        $row = $this->user()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = $row['user'];
        $row['password_current'] = 'fail';

        $this->auth()->json('PATCH', $this->route('update.password'), $row)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' password current ')
            ->assertSee($this->t('validator.password_current-password'));
    }

    /**
     * @return void
     */
    public function testPasswordSuccess(): void
    {
        $row = $this->user()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = $row['user'];
        $row['password_current'] = $row['user'];

        $this->auth()->json('PATCH', $this->route('update.password'), $row)
            ->assertStatus(200)
            ->assertJsonStructure($this->structure['user']);
    }
}
