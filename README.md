# Simple EU VAT number validator for PHP

## Installation

EuVat PHP requires Soap extension to be present on the system. Download the files and require EuVat.class.php located in
/src.

## Usage

Examples in code can be found at example.php (request payment), example_webhook.php (webhook call).

Testing example:

```php
require_once __DIR__ . '/src/EuVat.class.php';

//Simple check for validity of a given VAT number:
echo 'VAT valid: ' . EuVat::isVatValid('SI', '00000000') ? 'yes' : 'no';

//Obtaining full data for a given VAT number:
$vatData = EuVat::getVatData('SI', '00000000');

echo 'Request status: ' . $vatData->getStatus();
echo '<br>VAT valid: ' . ($vatData->isVatValid() ? 'yes' : 'no');
echo '<br>Name: ' . $vatData->getName();
echo '<br>Address: ' . $vatData->getAddress();
```

## License

The MIT License (MIT)

Copyright (c) 2022 THK

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
