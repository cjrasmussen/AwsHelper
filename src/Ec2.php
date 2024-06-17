<?php

namespace cjrasmussen\AwsHelper;

use Aws\Ec2\Ec2Client;

class Ec2
{
	private Ec2Client $ec2;

	public function __construct(Ec2Client $ec2)
	{
		$this->ec2 = $ec2;
	}

	/**
	 * Get the EC2Client back out of the helper class
	 *
	 * @return Ec2Client
	 */
	public function getClient(): Ec2Client
	{
		return $this->ec2;
	}

	/**
	 * Get the status of a single EC2 instance by ID
	 *
	 * The instance does not have to be running to have its status returned.
	 *
	 * @param string $instance_id
	 * @return array
	 */
	public function getInstanceStatus(string $instance_id): array
	{
		$data = $this->ec2->describeInstances([
			'InstanceIds' => [
				$instance_id
			],
			'IncludeAllInstances' => true,
		]);

		return $data['Reservations'][0]['Instances'][0];
	}
}