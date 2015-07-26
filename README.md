# Restore Deleted S3 Objects
Simple PHP script to restore S3 objects on the command line

To use:

1. Edit restore.php to enter your bucket name and region, prefix is optional.
2. Run `composer install`
3. Run `php restore.php`

A few notes:
* You'll need to have enabled [Versioning](http://docs.aws.amazon.com/AmazonS3/latest/dev/Versioning.html) on your S3 bucket. If you haven't then this won't be able to help you, sorry.
* You'll need PHP and [Composer](https://getcomposer.org/) installed. Composer will install everything else
* Ensure ListBucketVersions is present in your AWS IAM Policy
