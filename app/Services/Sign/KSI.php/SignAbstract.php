<?php
namespace App\Services\Sign\KSI;

use Exception;

abstract class SignAbstract
{
    protected $settings = [];
    protected $logs = [];

    protected $bin;

    final public function __construct($settings = [])
    {
        $this->setSettings($settings);
        $this->setBin($settings);
    }

    final public function setSettings(array $settings)
    {
        $this->settings = $settings + $this->getSettingsDefault();
    }

    final private function getSettingsDefault()
    {
        $base = base_path('resources/ksi/bin');

        return [
            'bin' => $base,
            'license' => $base.'/license',
            'conv' => $base.'/conv',
            'ini' => $base.'/ini',
            'crt' => $base.'/crt',
        ];
    }

    final public function getCommands($all = null)
    {
        return ($all === null) ? end($this->logs) : $this->logs;
    }

    final public function getLastCode()
    {
        return end($this->logs)['code'];
    }

    final protected function xml2array($xml)
    {
        $xml = trim($xml);

        if (strpos($xml, '<') !== 0) {
            return array();
        }

        return json_decode(json_encode(simplexml_load_string($xml)), true);
    }

    final protected function getOptions(array $params, array $options, $required)
    {
        $cmd = '';

        foreach ($options as $option) {
            if (!array_key_exists($option, $params)) {
                continue;
            }

            $option_up = strtoupper($option);
            $value = $params[$option];

            if (strlen($value)) {
                if (strstr($value, '/'.$option_up) === false) {
                    $cmd .= ' /'.$option_up.':"'.$value.'"';
                } else {
                    $cmd .= ' '.$value;
                }
            } elseif ($required) {
                $cmd .= ' /'.$option_up.':';
            }
        }

        return $cmd;
    }

    final protected function exec($cmd, $position)
    {
        $cmd = 'export LANG=utf-8;'
            .'export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:"'.$this->settings['bin'].'";'
            .$cmd;

        list($response, $code, $message) = $this->getResponseCodeMessage(shell_exec($cmd), $position);

        $this->logs[] = array(
            'command' => $cmd,
            'response' => $response,
            'message' => $message,
            'code' => $code,
        );

        if (($code === null) || ($code < 0)) {
            throw new Exception(__('ksi_code_'.$code));
        }

        return __('ksi_code_'.$code);
    }

    final private function getResponseCodeMessage($response, $position)
    {
        if ($response === null) {
            return [null, null, ''];
        }

        $response = trim($response);

        if (empty($response)) {
            return [$response, 0, ''];
        }

        $message = explode("\n", $response);

        if ($position === 'first') {
            $code = array_shift($message);
        } else {
            $code = array_pop($message);
        }

        $message = implode("\n", $message);

        return [$response, (int)$code, $message];
    }

    final public function getLicense()
    {
        return file_get_contents($this->settings['license']);
    }
}
