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
            ->assertStatus(422);
    }

    /**
     * @return void
     */
    public function testSignupNameFail(): void
    {
        $row = factory(Model::class)->make()->toArray();
        $row['name'] = '';
        $row['conditions'] = true;

        $this->json('POST', $this->route('signup'), $row)
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testSignupUserFail(): void
    {
        $row = factory(Model::class)->make()->toArray();
        $row['user'] = 'fail';
        $row['conditions'] = true;

        $this->json('POST', $this->route('signup'), $row)
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testSignupPasswordFail(): void
    {
        $row = factory(Model::class)->make()->toArray();
        $row['password'] = 'fail';
        $row['conditions'] = true;

        $this->json('POST', $this->route('signup'), $row)
            ->assertStatus(422)
            ->assertSee('6 caracteres');
    }

    /**
     * @return void
     */
    public function testSignupPasswordRepeatFail(): void
    {
        $row = factory(Model::class)->make()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = 'failfail';
        $row['conditions'] = true;

        $this->json('POST', $this->route('signup'), $row)
            ->assertStatus(422)
            ->assertSee('no coincide');
    }

    /**
     * @return void
     */
    public function testSignupConditionsFail(): void
    {
        $row = factory(Model::class)->make()->toArray();
        $row['password'] = $row['user'];
        $row['password_repeat'] = $row['user'];

        $this->json('POST', $this->route('signup'), $row)
            ->assertStatus(422)
            ->assertSee('condiciones');
    }

    /**
     * @return void
     */
    public function testSignupConfirmEmptyFail(): void
    {
        $this->json('POST', $this->route('confirm.start'))
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testSignupConfirmNoUserFail(): void
    {
        $this->json('POST', $this->route('confirm.start'), ['user' => 'fail'])
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testSignupConfirmNoExistsFail(): void
    {
        $this->json('POST', $this->route('confirm.start'), ['user' => 'fail@fail.com'])
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testSignupPasswordResetEmptyFail(): void
    {
        $this->json('POST', $this->route('password.reset.start'))
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testSignupPasswordResetNoUserFail(): void
    {
        $this->json('POST', $this->route('password.reset.start'), [
            'user' => 'fail'
        ])->assertStatus(422)->assertSee('correo electr\u00f3nico');
    }

    /**
     * Fake 200 response
     *
     * @return void
     */
    public function testSignupPasswordResetNoExistsFail(): void
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
            ->assertJsonStructure($this->structure());
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
            ->assertJsonStructure($this->structure());
    }

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
        $row = $this->user();

        $this->json('POST', $this->route('password.reset.start'), ['user' => $row->user])
            ->assertStatus(200);

        $hash = Models\UserPasswordReset::orderBy('id', 'DESC')->first()->hash;

        $this->json('POST', $this->route('password.reset.finish', $hash), [
            'password' => $row->user,
            'password_repeat' => $row->user,
        ])->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('POST', $this->route('auth.credentials'), [
            'user' => $row->user,
            'password' => $row->user
        ])->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testAuthSuccess(): void
    {
        $row = $this->user();

        $this->json('POST', $this->route('auth.credentials'), [
            'user' => $row->user,
            'password' => $row->user
        ])->assertStatus(200)->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testProfileNoAuthFail(): void
    {
        $row = $this->user();

        $this->json('PATCH', $this->route('update.profile'), [
            'name' => $row->name,
            'user' => $row->user,
            'password_current' => $row->user
        ])->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testProfileNameFail(): void
    {
        $row = $this->user();

        $this->auth()->json('PATCH', $this->route('update.profile'), [
            'name' => '',
            'user' => $row->user,
            'password_current' => $row->user
        ])->assertStatus(422)->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testProfileUserFail(): void
    {
        $row = $this->user();

        $this->auth()->json('PATCH', $this->route('update.profile'), [
            'name' => $row->name,
            'user' => '',
            'password_current' => $row->user
        ])->assertStatus(422)->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testProfilePasswordFail(): void
    {
        $row = $this->user();

        $this->auth()->json('PATCH', $this->route('update.profile'), [
            'name' => $row->name,
            'user' => $row->user,
            'password_current' => $row->user.'1'
        ])->assertStatus(422)->assertSee('contrase\u00f1a actual');
    }

    /**
     * @return void
     */
    public function testProfileSuccess(): void
    {
        $row = $this->user();

        $this->auth()->json('PATCH', $this->route('update.profile'), [
            'name' => $row->name,
            'user' => $row->user,
            'password' => $row->user,
            'password_current' => $row->user
        ])->assertStatus(200)->assertJsonStructure($this->structure()['user']);
    }

    /**
     * @return void
     */
    public function testPasswordNoAuthFail(): void
    {
        $row = $this->user();

        $this->json('PATCH', $this->route('update.password'), [
            'password' => $row->user,
            'password_repeat' => $row->user,
            'password_current' => $row->user,
        ])->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testPasswordPasswordFail(): void
    {
        $row = $this->user();

        $this->auth()->json('PATCH', $this->route('update.password'), [
            'password' => 'fail',
            'password_repeat' => 'fail',
            'password_current' => $row->user,
        ])->assertStatus(422)->assertSee('6 caracteres');
    }

    /**
     * @return void
     */
    public function testPasswordRepeatFail(): void
    {
        $row = $this->user();

        $this->auth()->json('PATCH', $this->route('update.password'), [
            'password' => $row->user,
            'password_repeat' => $row->user.'1',
            'password_current' => $row->user,
        ])->assertStatus(422)->assertSee('repetici\u00f3n');
    }

    /**
     * @return void
     */
    public function testPasswordCurrentFail(): void
    {
        $row = $this->user();

        $this->auth()->json('PATCH', $this->route('update.password'), [
            'password' => $row->user,
            'password_repeat' => $row->user,
            'password_current' => $row->user.'1',
        ])->assertStatus(422)->assertSee('contrase\u00f1a actual');
    }

    /**
     * @return void
     */
    public function testPasswordSuccess(): void
    {
        $row = $this->user();

        $this->auth()->json('PATCH', $this->route('update.password'), [
            'password' => $row->user,
            'password_repeat' => $row->user,
            'password_current' => $row->user,
        ])->assertStatus(200)->assertJsonStructure($this->structure()['user']);
    }

    /**
     * @return array
     */
    protected function structure(): array
    {
        return ['token', 'user' => ['confirmed_at', 'id', 'user', 'name', 'language']];
    }
}
