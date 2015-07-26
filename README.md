# Restore Deleted S3 Objects
Simple PHP script to restore S3 objects on the command line

To use:

1. Edit restore.php to enter your bucket name and region, prefix is optional.
2. Run `composer install`
3. Run `php restore.php`

A few notes:
* This script works by deleting Delete Markers so you'll need to have enabled [Versioning](http://docs.aws.amazon.com/AmazonS3/latest/dev/Versioning.html) on your S3 bucket. If you haven't then this won't be able to help you, sorry.
* You'll need PHP and [Composer](https://getcomposer.org/) installed. Composer will install everything else
* Ensure ListBucketVersions is present in your AWS IAM Policy
* This will restore 1,000 objects each time it is run. If you have more than that then just keep running it until there are no more to restore. Alternatively modify the script to keep looping until there are no more delete markers. If you do modify this please send a pull request for issue #2
