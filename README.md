# AwsHelper

Simple class for bundling AWS SDK actions that I find to be common.


## Usage

```php
use cjrasmussen\AwsHelper\Aws;

$aws = new Aws($s3client, $ec2client);

// BATCH DELETE A SINGLE S3 OBJECT OR AN ARRAY OF S3 OBJECTS BY KEY
$response = $aws->s3->deleteObject($bucket, $objects);

// GET DETAILS ABOUT A SINGLE EC2 INSTANCE, WHETHER IT IS CURRENTLY RUNNING OR NOT
$response = $aws->ec2->getInstanceStatus('i-xxxxeeee2222');
```

## Installation

Simply add a dependency on cjrasmussen/aws-helper to your composer.json file if you use [Composer](https://getcomposer.org/) to manage the dependencies of your project:

```sh
composer require cjrasmussen/aws-helper
```

Although it's recommended to use Composer, you can actually include the file(s) any way you want.


## License

AwsHelper is [MIT](http://opensource.org/licenses/MIT) licensed.