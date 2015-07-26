<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use Aws\S3\S3Client;

$prefix = '';
$region = 'eu-west-1';
$bucket = 'bucket.example.com';

$s3client = new S3Client([
    'version' => 'latest',
    'region'  => $region
]);

$objects = array();

$lookup = array(
	'Bucket' => $bucket,
	'Prefix' => $prefix,
);

$count = 0;

try {
	do {
		$bucketContent = $s3client->ListObjectVersions($lookup);

		$versions = $bucketContent->get('Versions');
		$deleteMarkers = $bucketContent->get('DeleteMarkers');

		if (!is_null($deleteMarkers)) {
			foreach($deleteMarkers AS $deleteMarker) {
				if ($count < 1000) {
					$count++;
					print "Restoring " . $deleteMarker['Key'] . " by removing Delete Marker with VersionId: " . $deleteMarker['VersionId'] . "\n";
					array_push($objects, array('Key' => $deleteMarker['Key'], 'VersionId' => $deleteMarker['VersionId']));
				} else {
					break;
				}
			}
		}

		if (!is_null($bucketContent->get('NextVersionIdMarker')) && ($bucketContent->get('NextVersionIdMarker') !== 'null')) {
			$lookup['VersionIdMarker'] = $bucketContent->get('NextVersionIdMarker');
		} else {
			unset($lookup['version-id-marker']);
		}

		if (!is_null($bucketContent->get('NextKeyMarker')) && ($bucketContent->get('NextKeyMarker') !== 'null')) {
			$lookup['KeyMarker'] = $bucketContent->get('NextKeyMarker');
		} else {
			unset($lookup['key-marker']);
		}
	} while ($bucketContent->get('IsTruncated') && ($count < 1000));

	if (count($objects) > 0) {
		print "Restoring " . count($objects) . " objects\n";

		$result = $s3client->deleteObjects( array(
			'Bucket'  => $bucket,
			'Delete' => array( 'Objects' => $objects )
		) );

		print_r($result);
	} else {
		print "No objects to restore\n";
	}
} catch ( Exception $e ) {
	print "Error restoring files from S3: " . $e->getMessage() . "\n";
}
