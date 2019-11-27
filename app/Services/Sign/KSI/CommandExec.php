<?php
namespace App\Services\Sign\KSI;

use App\Exceptions\UnexpectedValueException;

class CommandExec
{
    /**
     * @var string
     */
    protected string $cmd;

    /**
     * @var array
     */
    protected array $logs = [];

    /**
     * @param string $path
     *
     * @return self
     */
    public function __construct(string $path)
    {
        $this->cmd = 'export LANG=utf-8; export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:"'.$path.'";';
    }

    /**
     * @param string $cmd
     * @param string $position
     *
     * @return string
     */
    public function cmd(string $cmd, string $position): string
    {
        [$response, $code, $message] = $this->response(shell_exec($this->cmd.$cmd), $position);

        $return = is_int($code) ? __('ksi.code_'.$code) : '';

        $this->logs[] = [
            'command' => $this->cmd.$cmd,
            'response' => $response,
            'message' => $message,
            'code' => $code,
            'return' => $return
        ];

        if (($code === null) || ($code < 0)) {
            throw new UnexpectedValueException($return);
        }

        return $return;
    }

    /**
     * @param ?string $response
     * @param string $position
     *
     * @return array
     */
    protected function response(?string $response, string $position): array
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
}
