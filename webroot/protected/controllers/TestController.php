<?php
class TestController extends XAdminBase{
    public function actionIndex(){
        $model = new Admin();
        $handle = @fopen("/tmp/inputfile.txt", "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                echo $buffer;
            }
            fclose($handle);
        }
    }
}