
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$f = popen('python Scripts/PythonTest.py 1234', 'r');
if ($f === false)
{
   echo "popen failed";
  // exit 1;
}


$json = fgets($f);
fclose($f);
echo $json;
?>

