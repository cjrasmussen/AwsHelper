<?php

namespace cjrasmussen\AwsHelper;

use Aws\Result;
use Aws\S3\S3Client;
use RuntimeException;

class S3
{
	private S3Client $s3;

	public function __construct(S3Client $s3)
	{
		$this->s3 = $s3;
	}

	/**
	 * Copy a specified file to an S3 bucket/path
	 *
	 * @param string $bucket
	 * @param string $path
	 * @param string $file
	 * @param bool $allow_overwrite
	 * @param bool $remove_source
	 * @return bool
	 */
	public function createObjectFromFile(string $bucket, string $path, string $file, bool $allow_overwrite = true, bool $remove_source = false): bool
	{
		if (!file_exists($file)) {
			$msg = 'Specified local file ' . $file . ' does not exist';
			throw new RuntimeException($msg);
		}

		$result = null;

		if (($allow_overwrite) || (!$this->s3->doesObjectExist($bucket, $path))) {
			$result = $this->s3->putObject([
				'Bucket' => $bucket,
				'Key' => $path,
				'SourceFile' => $file,
			]);
		}

		if (($result !== null) && ($result['ETag'])) {
			if ($remove_source) {
				@unlink($file);
			}

			return true;
		}

		return false;
	}

	/**
	 * List all the contents of a bucket, regardless of pagination
	 *
	 * @param string $bucket
	 * @param string $prefix
	 * @return array
	 */
	public function listBucketContents(string $bucket, string $prefix = ''): array
	{
		$output = [];

		$args = [
			'Bucket' => $bucket,
		];

		if ($prefix) {
			$args['Prefix'] = $prefix;
		}

		$results = $this->s3->getPaginator('ListObjects', $args);
		foreach ($results AS $result) {
			foreach ($result['Contents'] AS $object) {
				$output[] = $object;
			}
		}

		return $output;
	}

	/**
	 * Delete a specified object (or array of objects) from a specified S3 bucket
	 *
	 * @param string $bucket
	 * @param string|array $files
	 * @return Result
	 */
	public function deleteObject(string $bucket, $files): Result
	{
		if (!is_array($files)) {
			$files = [$files];
		}

		$delete = [
			'Objects' => [],
		];

		foreach ($files AS $file) {
			if ($this->s3->doesObjectExistV2($bucket, $file)) {
				$delete['Objects'][] = [
					'Key' => $file,
				];
			}
		}

		return $this->s3->deleteObjects([
			'Bucket' => $bucket,
			'Delete' => $delete,
		]);
	}
}
