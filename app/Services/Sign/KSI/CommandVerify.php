<?php
namespace App\Services\Sign\KSI;

use App\Exceptions\UnexpectedValueException;

class CommandVerify extends CommandAbstract
{
    /**
     * @var array
     */
    protected array $required = ['cpw', 'out', 'pfx'];

    /**
     * @var array
     */
    protected array $optional = [];

    /**
     * @return self
     */
    protected function setBin(): self
    {
        $this->settings['bin'] = $this->settings['base'].'/cert_sj';

        return $this;
    }

    /**
     * 0 - Separar llave publica de privada. Parametros obligatorios:
     *
     * /PFX:[ruta a certificado]
     * /CPW:[Contrasena del certificado PFX]
     * /OUT:[ruta a llave publica de salida en CER]
     *
     * @param string $certificate
     * @param string $password = ''
     *
     * @return string
     */
    public function verify(string $certificate, string $password = ''): string
    {
        if (empty($certificate) || !is_file($certificate)) {
            throw new UnexpectedValueException(__('exception.certificate-invalid'));
        }

        $output = tempnam(sys_get_temp_dir(), uniqid());

        $this->exec(0, $this->params($certificate, $password, $output), 'last');

        return $output;
    }

    /**
     * @param string $certificate
     * @param string $password
     * @param string $output
     *
     * @return array
     */
    protected function params(string $certificate, string $password, string $output): array
    {
        return [
            'pfx' => $certificate,
            'cpw' => $password,
            'out' => $output,
        ];
    }
}
