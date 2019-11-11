<?php
namespace App\Services\KSI;

use Exception;
use App\Services\Html\Html;

class ESecure extends SignAbstract
{
    protected $required = ['aut', 'cer', 'cin', 'con', 'cpw', 'dir', 'fca', 'ind', 'key', 'ori', 'out', 'ppw', 'ipw', 'raz', 'sep'];
    protected $optional = ['cif', 'ctf', 'exf', 'inf', 'pdf'];

    final protected function setBin()
    {
        $this->bin = $this->settings['bin'].'/Interfaz_ESecureDLL'
            .' "'.$this->settings['license'].'"'
            .' "'.$this->settings['ini'].'"';
    }

    final public function bin($action, array $params)
    {
        $cmd = $this->bin.' '.$action
            .$this->getOptions($params, $this->required, true)
            .$this->getOptions($params, $this->optional, false);

        return $this->exec($cmd, 'first');
    }

    /**
     * 0 - Firmar fichero PDF, parametros obligatorios:
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
     * 7  - Firmar ficheros arbitrarios, parametros obligatorios:
     * /INF:[ruta a fichero entrada]
     * /OUT:[ruta a fichero salida]
     * /IND:[ruta a fichero original si es firma separada]
     * /CER:[ruta a certificado]
     * /CPW:[Contrasena del certificado]
     * /SEP:(0:firma no separada, 1:firma separada)
     * /FCA:[Fichero con CAs]
     */
    public function sign($file, $certificate, $password)
    {
        if (empty($file) || !is_file($file)) {
            throw new Exception(__('El fichero no es válido'));
        }

        if (empty($certificate) || !is_file($certificate)) {
            throw new Exception(__('Certificado no válido'));
        }

        $isPdf = $this->isPDF($file);

        $input = fileTmp();
        $output = fileTmp();

        copy($file, $input);
        copy($file, $output);

        $this->bin($isPdf ? 0 : 7, [
            'inf' => $input,
            'pdf' => $input,
            'cer' => $certificate,
            'cpw' => $password,
            'ipw' => '',
            'fca' => $this->settings['crt'],
            'out' => $output,
            'ind' => '',
            'sep' => 0,
            'aut' => '',
            'raz' => '',
            'con' => '',
            'dir' => '',
            'ppw' => '',
            'cif' => 0,
            'ctf' => 0,
        ]);

        if ($isPdf) {
            $file = preg_replace('/\.pdf$/i', '.firm.pdf', $file);

            unlink($output);
            rename($input, $file);
        } else {
            $file = $file.'.firm';

            unlink($input);
            rename($output, $file);
        }

        return $file;
    }
}
