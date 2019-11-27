<?php
namespace App\Services\Sign\KSI;

use App\Exceptions\UnexpectedValueException;

class CommandSign extends CommandAbstract
{
    /**
     * @var array
     */
    protected array $required = ['aut', 'cer', 'con', 'cpw', 'dir', 'fca', 'ppw', 'ipw', 'raz'];

    /**
     * @var array
     */
    protected array $optional = ['cif', 'ctf', 'pdf'];

    /**
     * @return self
     */
    protected function setBin(): self
    {
        $base = $this->settings['base'];

        $this->settings['bin'] = $base.'/Interfaz_ESecureDLL "'.$base.'/license" "'.$base.'/ini"';

        return $this;
    }

    /**
     * 0 - Firmar fichero PDF. Parametros obligatorios:
     *
     * /PDF:[ruta a fichero PDF]
     * /CER:[ruta a certificado]
     * /CPW:[Contrasena del certificado]
     * /AUT:[Autor]
     * /RAZ:[Razon]
     * /CON:[Contacto]
     * /DIR:[Direccion]
     * /CIF:[0:no cifrar, 1:cifrar con PPW, 2: cifrar con CER]
     * /PPW:[Contrasena de cifrado si CIF=1]
     * /CTF:[0:firma no certificada, 1:firma certificada]
     * /FCA:[Fichero con CAs]
     * /IPW:[password si el PDF de entrada esta cifrado]
     *
     * @param string $file
     * @param string $certificate
     * @param string $password = ''
     *
     * @return string
     */
    public function sign(string $file, string $certificate, string $password = ''): string
    {
        if (empty($file) || !is_file($file)) {
            throw new UnexpectedValueException(__('exception.file-invalid'));
        }

        if (empty($certificate) || !is_file($certificate)) {
            throw new UnexpectedValueException(__('exception.certificate-invalid'));
        }

        $this->exec(0, $this->params($file, $certificate, $password), 'first');

        return $file;
    }

    /**
     * @param string $file
     * @param string $certificate
     * @param string $password
     *
     * @return array
     */
    protected function params(string $file, string $certificate, string $password): array
    {
        return [
            'pdf' => $file,
            'cer' => $certificate,
            'cpw' => $password,
            'aut' => '',
            'raz' => '',
            'con' => '',
            'dir' => '',
            'cif' => 0,
            'ppw' => '',
            'ctf' => 0,
            'fca' => $this->settings['crt'],
            'ipw' => '',
        ];
    }
}
