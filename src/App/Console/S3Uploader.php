<?php

namespace Urisoft\App\Console;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

class S3Uploader
{
    private $s3Client;
    private $bucketName;

    public function __construct( $accessKeyId, $secretAccessKey, $bucketName, $region )
    {
        $this->bucketName = $bucketName;
        $this->s3Client   = new S3Client(
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

    public function createBucketIfNotExists(): ?bool
    {
        try {
            $buckets      = $this->s3Client->listBuckets();
            $bucketExists = false;

            foreach ( $buckets['Buckets'] as $bucket ) {
                if ( $bucket['Name'] === $this->bucketName ) {
                    $bucketExists = true;

                    break;
                }
            }

            if ( ! $bucketExists ) {
                $this->s3Client->createBucket(
                    [
                        'Bucket'                    => $this->bucketName,
                        'CreateBucketConfiguration' => [
                            'LocationConstraint' => $this->s3Client->getRegion(),
                        ],
                    ]
                );

                $this->s3Client->putBucketVersioning(
                    [
                        'Bucket'                  => $this->bucketName,
                        'VersioningConfiguration' => [
                            'Status' => 'Enabled',
                        ],
                    ]
                );

                return true;
            }// end if
        } catch ( AwsException $e ) {
            error_log( 'Error(could not create s3 bucket): ' . $e->getMessage() );

            return false;
        }// end try

        return null;
    }

    public function uploadFile( $localFilePath, $s3ObjectKey ): bool
    {
        try {
            $this->createBucketIfNotExists();

            $this->s3Client->putObject(
                [
                    'Bucket' => $this->bucketName,
                    'Key'    => $s3ObjectKey,
                    'Body'   => fopen( $localFilePath, 'rb' ),
                    // Open file as a stream
                ]
            );

            return true;
        } catch ( AwsException $e ) {
            error_log( 'Error( s3 upload failed): ' . $e->getMessage() );

            return false;
        }
    }
}
