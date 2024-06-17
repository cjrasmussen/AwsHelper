<?php

namespace cjrasmussen\AwsHelper;

use Aws\Ec2\Ec2Client;
use Aws\S3\S3Client;
use RuntimeException;

class Aws
{
	public S3 $s3;
	public Ec2 $ec2;

	public function __construct(...$args)
	{
		foreach ($args AS $arg) {
			if ($arg instanceof S3Client) {
				$this->s3 = new S3($arg);
			} elseif ($arg instanceof Ec2Client) {
				$this->ec2 = new Ec2($arg);
			} else {
				$msg = 'Unsupported argument type ' . gettype($arg) . ' found.';
				throw new RuntimeException($msg);
			}
		}
	}

	/**
	 * Set the S3 client
	 *
	 * @param S3Client $s3
	 * @return void
	 */
	public function setS3(S3Client $s3): void
	{
		$this->s3 = new S3($s3);
	}

	/**
	 * Set the EC2 client
	 *
	 * @param Ec2Client $ec2
	 * @return void
	 */
	public function setEc2(Ec2Client $ec2): void
	{
		$this->ec2 = new Ec2($ec2);
	}
}