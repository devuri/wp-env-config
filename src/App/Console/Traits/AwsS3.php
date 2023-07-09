<?php

namespace Urisoft\App\Console\Traits;

use Aws\S3\S3Client;

trait AwsS3
{
    /**
     * The AWS S3 client.
     *
     * @var \Aws\S3\S3Client
     */
    protected $s3Client;

    /**
     * Get the S3 client.
     *
     * @return \Aws\S3\S3Client
     */
    public function getAwsClient(): S3Client
    {
        return $this->s3Client;
    }

    /**
     *  Create the s3Client.
     *
     * @param string $accessKeyId     [description]
     * @param string $secretAccessKey [description]
     * @param string $bucketName      [description]
     * @param string $region          [description]
     */
    public function create_s3Client( $accessKeyId, $secretAccessKey, $bucketName, $region ): void
    {
        $this->s3Client = new S3Client(
            [
                'version'     => 'latest',
                'region'      => $region,
                'credentials' => [
                    'key'    => $accessKeyId,
                    'secret' => $secretAccessKey,
                ],
            ]
        );
    }
}
