<?php

require __DIR__ . '/vendor/autoload.php';

use Beter\Exception\ExceptionWithContext;
use Beter\Exception\ExceptionWithContextInterface;
use Beter\Exception\ExceptionWithContextTrait;

$e = new ExceptionWithContext('message', 1, null, ['key' => 'value']);

echo '<pre>';
echo "Exception with a context.<br />";
echo 'Context: ';
echo var_export($e->getContext());
echo '</pre>';

echo '<pre>';
echo "Resetting context.<br />";
$e->setContext(['newkey' => 'newvalue']);
echo 'Context: ';
echo var_export($e->getContext());
echo '</pre>';

class CustomException extends \Exception implements ExceptionWithContextInterface
{
    use ExceptionWithContextTrait;

    public function __construct($message = "", $code = 0, Throwable $previous = null, array $context = [])
    {
        $this->context = $context;

        parent::__construct($message, $code, $previous);
    }
}

$e = new CustomException('message', 1, null, ['traitkey' => 'traitvalue']);

echo '<pre>';
echo "Exception with a context.<br />";
echo 'Context: ';
echo var_export($e->getContext());
echo '</pre>';

echo '<pre>';
echo "Resetting context.<br />";
$e->setContext(['newtraitkey' => 'newtraitvalue']);
echo 'Context: ';
echo var_export($e->getContext());
echo '</pre>';
