<?php

namespace Urisoft\App\Console;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

class S3Uploader
{
    private $s3Client;
    private $bucketName;

    public function __construct($accessKeyId, $secretAccessKey, $region, $bucketName)
    {
        $this->bucketName = $bucketName;
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $accessKeyId,
                'secret' => $secretAccessKey,
            ],
        ]);
    }

    public function createBucketIfNotExists(): string
    {
        try {
            $buckets = $this->s3Client->listBuckets();
            $bucketExists = false;

            foreach ($buckets['Buckets'] as $bucket) {
                if ($bucket['Name'] === $this->bucketName) {
                    $bucketExists = true;

                    break;
                }
            }

            if ( ! $bucketExists) {
                $this->s3Client->createBucket(['Bucket' => $this->bucketName]);

                return "Bucket created successfully!";
            }
        } catch (AwsException $e) {
            return "Error creating bucket: " . $e->getMessage();
        }
    }

    public function uploadFile($localFilePath, $s3ObjectKey): bool
    {
        try {
            $this->createBucketIfNotExists();

            $this->s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key' => $s3ObjectKey,
                'Body' => fopen($localFilePath, 'rb'), // Open file as a stream
            ]);

            return true;
        } catch (AwsException $e) {
            return false;
        }
    }
}
