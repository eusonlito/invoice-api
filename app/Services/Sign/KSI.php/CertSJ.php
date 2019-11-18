<?php
namespace App\Services\KSI;

use Exception;

class CertSJ extends SignAbstract
{
    protected $required = ['cin', 'cpw', 'out', 'pfx'];
    protected $optional = ['cer'];

    final protected function setBin()
    {
        $this->bin = $this->settings['bin'].'/cert_sj';
    }

    final public function bin($action, array $params)
    {
        $cmd = $this->bin.' '.$action
            .$this->getOptions($params, $this->required, true)
            .$this->getOptions($params, $this->optional, false);

        return $this->exec($cmd, 'last');
    }

    /**
     * 0 - Separar llave publica de privada (solo primer certificado con llave privada), parametros obligatorios:
     * /PFX:[ruta a certificado], /CPW:[Contrasena del certificado PFX], /OUT:[ruta a llave publica de salida en CER]
     */
    public function keySplit(array $params)
    {
        if (empty($params['out'])) {
            throw new Exception(__('No se ha indicado un certificado de salida'));
        }

        $dir = dirname($params['out']);

        if (is_dir($dir) === false) {
            mkdir($dir, 0700, true);
        }

        return $this->bin(0, $params);
    }

    /**
     * 1 - Juntar llaves publicas en un unico fichero:
     * /OUT:[ruta a almacen de salida en PKCS7], /CER:[ruta a certificado 1], ..., /CER:[ruta a certificado N]
     */
    public function repository(array $certificates)
    {
        $repository = fileTmp();

        $this->bin(1, [
            'out' => $repository,
            'cer' => trim(' /CER:"'.implode('" /CER:"', $certificates).'"')
        ]);

        return $repository;
    }

    /**
     * 2 - Sacar huella SHA1 de llave publica, unico parametro obligatorio:
     * /CIN: [llave publica de certificado a extraer huella SHA1]
     */
    public function getSHA1($certificate)
    {
        return (bool)$this->bin(2, [
            'cin' => $certificate
        ]);
    }
}
