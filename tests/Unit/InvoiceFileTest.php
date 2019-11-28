<?php declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use App\Models;
use App\Models\InvoiceFile as Model;

class InvoiceFileTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'invoice-file';

    /**
     * @var int
     */
    protected int $count = 1;

    /**
     * @var array
     */
    protected array $structure = ['id' , 'name', 'main'];

    /**
     * @return void
     */
    public function testInvoiceNoAuthFail(): void
    {
        $this->json('GET', $this->route('invoice', $this->invoice()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testInvoiceNotAllowedFail(): void
    {
        $this->auth($this->userFirst())->json('GET', $this->route('invoice', $this->invoice()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testInvoiceSuccess(): void
    {
        $this->auth()->json('GET', $this->route('invoice', $this->invoice()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testInvoiceMainNoAuthFail(): void
    {
        $this->json('GET', $this->route('main', $this->invoice()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testInvoiceMainNotAllowedFail(): void
    {
        $this->auth($this->userFirst())->json('GET', $this->route('main', $this->invoice()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testInvoiceMainSuccess(): void
    {
        $this->auth()->json('GET', $this->route('main', $this->invoice()->id))
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testInvoiceAfterMainSuccess(): void
    {
        $this->auth()->json('GET', $this->route('invoice', $this->invoice()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testDeleteSuccess(): void
    {
        $this->auth()->json('DELETE', $this->route('delete', $this->row()->id))
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testInvoiceMainAfterDeleteSuccess(): void
    {
        $this->auth()->json('GET', $this->route('main', $this->invoice()->id))
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testInvoiceAfterDeleteSuccess(): void
    {
        $this->auth()->json('GET', $this->route('invoice', $this->invoice()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * Rules:
     *
     * 'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,odt,ods,jpeg,png',
     */

    /**
     * @return void
     */
    public function testCreateEmptyFail(): void
    {
        $this->auth()->json('POST', $this->route('create', $this->invoice()->id))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' file ')
            ->assertSee($this->t('validator.file-required'));
    }

    /**
     * @return void
     */
    public function testCreateFileInvalidFail(): void
    {
        $fail = ['file' => 'fail'];

        $this->auth()->json('POST', $this->route('create', $this->invoice()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' file ')
            ->assertSee($this->t('validator.file-file'));
    }

    /**
     * @return void
     */
    public function testCreateMimeInvalidFail(): void
    {
        $fail = ['file' => UploadedFile::fake()->create('avatar.zip', 1024)];

        $this->auth()->json('POST', $this->route('create', $this->invoice()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' file ')
            ->assertSee($this->t('validator.file-mimes', ['mimes' => 'pdf, doc, docx, xls, xlsx, odt, ods, jpg y png']));
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $this->json('POST', $this->route('create', $this->invoice()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testCreateFirstSuccess(): void
    {
        $row = ['file' => UploadedFile::fake()->create('avatar.pdf', 1024)];
        $user = $this->userFirst();

        $this->auth($user)->json('POST', $this->route('create', $this->invoiceByUser($user)->id), $row)
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = ['file' => UploadedFile::fake()->create('avatar.pdf', 1024)];

        $this->auth()->json('POST', $this->route('create', $this->invoice()->id), $row)
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testInvoiceAfterSuccess(): void
    {
        $this->auth()->json('GET', $this->route('invoice', $this->invoice()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count + 1);
    }

    /**
     * @return void
     */
    public function testDetailNoAuthFail(): void
    {
        $this->json('GET', $this->route('detail', $this->row()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testDetailNotAllowedFail(): void
    {
        $this->auth($this->userFirst())->json('GET', $this->route('detail', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()->json('GET', $this->route('detail', $this->row()->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testDownloadSuccess(): void
    {
        $this->auth()->json('GET', $this->route('download', $this->row()->id))
            ->assertStatus(200);
    }

    /**
     * @return \App\Models\Invoice
     */
    protected function invoice(): Models\Invoice
    {
        return $this->invoiceByUser($this->user());
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\Invoice
     */
    protected function invoiceByUser(Models\User $user): Models\Invoice
    {
        return Models\Invoice::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    protected function row(): Model
    {
        return Model::orderBy('id', 'DESC')->first();
    }
}
