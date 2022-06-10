# exception-with-context

## Requirements

PHP >= 7.4.0

## Installation

The preferred way to install this extension is through [composer](https://getcomposer.org/).

Either run

```
composer require beter/exception-with-context
```

or add

```
"beter/exception-with-context": "~1.0.0"  // put the actual version
```

to the `require` section of your composer.json.

## Usage

Usage of the `ExceptionWithContext`:

```php
use Beter\Exception\ExceptionWithContext;

$exceptionCode = 0;
$previousException = null;
$context = [
    'userIp' => '1.2.3.4',
    'userId' => 1,
    'request' => [
        'headers' => [ /* put headers data, for example */ ]
    ],
];

$e = new ExceptionWithContext('Action is not allowed for the user', $exceptionCode, $previousException, $context);

// or you may not to pass the context

$e = new ExceptionWithContext('Action is not allowed for the user', $exceptionCode, $previousException);

// or even skip $exceptionCode and $previousException

$e = new ExceptionWithContext('Action is not allowed for the user');

// or set context via setContext method call
$e = new ExceptionWithContext('Action is not allowed for the user');
$e->setContext($context);

// and get context back
var_dump($e->getContext());
```

You may create a chain of exceptions too:

```php
use Beter\Exception\ExceptionWithContext;

try {
    do_smth();
} catch (\Throwable $catched) {
    $e = new ExceptionWithContext('Something went wrong', 0, $catched, ['key' => 'value']);
}
```

You may redefine your base exceptions and add context trait to them. They will behave the same way.

```php
use Beter\Exception\ExceptionWithContextInterface;
use Beter\Exception\ExceptionWithContextTrait;

class CustomException extends \Exception implements ExceptionWithContextInterface
{
    use ExceptionWithContextTrait;

    public function __construct($message = "", $code = 0, Throwable $previous = null, array $context = [])
    {
        $this->context = $context;

        parent::__construct($message, $code, $previous);
    }
}

$e = new CustomException('Message text', 0, null, ['key' => 'value']);
$e->setContext(['newkey' => 'newvalue']);
var_dump($e->getContext());
```

Also, you may implement your own custom exceptions, you even don't need to use traits.
Just implement `Beter\Exception\ExceptionWithContextInterface`.

### Ideas for usage

* add context to log messages;
* protect from flooding exception message with placeholded values right into the message;
* pass more data to sentry/logentries/datadog/newrelic and so on.

> You need to implement that support by yourself. There are only ideas.

## Development and testing

Follow the [development and testing doc](doc/development-and-testing.md).

## Related projects

These projects use `beter/exception-with-context`:

* Bulletproof logging for yii2 projects - [BETER-CO/yii2-beter-logging](https://github.com/BETER-CO/yii2-beter-logging)
* Yii2 component to generate log entries with a context for the request and response events for the CLI and WEB -
[BETER-CO/yii2-log-request-response](https://github.com/BETER-CO/yii2-log-request-response)
