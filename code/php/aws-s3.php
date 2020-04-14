<?php 

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Awsup extends Controller
{
  private $key = 'private-key-of-your-aws-app';
  private $secret = 'secret-key-of-your-aws-app';

  public function index(){

    $s3Client = new Aws\S3\S3Client([
      'region' => 'ap-south-1',
      'version' => 'latest',
      'credentials' => [
        'key' => $this->key,
        'secret' => $this->secret
      ]]);

    try {
      $objects = $s3Client->listObjects([
       'Bucket' => 'name-of-bucket',
       'Prefix' => 'images/'
     ]);
    } catch (S3Exception $e) {
      echo $e->getMessage() . PHP_EOL;
    }

    foreach ($objects['Contents']  as $object) {
      $cmd = $s3Client->getCommand('GetObject', [
        'Bucket' => 'name-of-bucket',
        'Key' => $object['Key']
      ]);
      $request = $s3Client->createPresignedRequest($cmd, '+10 minutes');
      $imageUrl[] = (string) $request->getUri();
    }
    $data['images'] = $imageUrl;
    $data['page'] = 'aws_view';

    return view('templates/master', $data);
  }

  public function upload_files()
  {
   if (isset($_FILES['imgfile'])){
     try {

       $file_name = $_FILES['imgfile']['name'];
       $temp_file_location = $_FILES['imgfile']['tmp_name'];

       $s3 = new Aws\S3\S3Client([
        'region' => 'ap-south-1',
        'version' => 'latest',
        'credentials' => [
          'key' => $this->key,
          'secret' => $this->secret
        ]]);

       $result = $s3->putObject([
                  'Bucket' => 'ndcbde4.org',
                  'Key' => 'images/'.$file_name,
                  'SourceFile' => $temp_file_location
                ]);
       return $result;
     } catch (S3Exception $e) {
       echo $e->getMessage() . PHP_EOL;
     }
   }
  }
}

