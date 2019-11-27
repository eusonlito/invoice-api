<?php
namespace App\Services\Sign\KSI;

abstract class CommandAbstract
{
    /**
     * @var array
     */
    protected array $settings = [];

    /**
     * @var \App\Services\Sign\KSI\CommandExec
     */
    protected CommandExec $exec;

    /**
     * @return self
     */
    public function __construct()
    {
        $this->setSettings();
        $this->setExec();
        $this->setBin();
    }

    /**
     * @return self
     */
    protected function setSettings(): self
    {
        $base = base_path('resources/ksi/bin');

        $this->settings = [
            'base' => $base,
            'certsj' => $base.'/cert_sj',
            'crt' => $base.'/crt',
        ];

        return $this;
    }

    /**
     * @return self
     */
    protected function setExec(): self
    {
        $this->exec = new CommandExec($this->settings['base']);

        return $this;
    }

    /**
     * @param array $options
     * @param array $params
     * @param bool $required
     *
     * @return string
     */
    protected function getOptions(array $options, array $params, bool $required): string
    {
        $cmd = '';

        foreach ($options as $option) {
            $cmd .= $this->getOption($option, $params, $required);
        }

        return $cmd;
    }

    /**
     * @param string $option
     * @param array $params
     * @param bool $required
     *
     * @return string
     */
    protected function getOption(string $option, array $params, bool $required): string
    {
        if (array_key_exists($option, $params) === false) {
            return '';
        }

        $value = $params[$option];
        $option = strtoupper($option);

        if (strlen($value) === 0) {
            return $required ? (' /'.$option.':') : '';
        }

        if (strstr($value, '/'.$option) === false) {
            return ' /'.$option.':"'.$value.'"';
        }

        return ' '.$value;
    }

    /**
     * @param int $action
     * @param array $params
     * @param string $position
     *
     * @return string
     */
    protected function exec(int $action, array $params, string $position): string
    {
        $cmd = $this->settings['bin'].' '.(string)$action
            .$this->getOptions($this->required, $params, true)
            .$this->getOptions($this->optional, $params, false);

        return $this->exec->cmd($cmd, $position);
    }
}
