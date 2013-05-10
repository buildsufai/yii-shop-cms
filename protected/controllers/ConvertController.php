<?php
/**
 * This controller renders all the content the right way.
 */
class ConvertController extends Controller
{
    /**
     * Renders every static content item as a page
     */
    public function actionMedia()
    {
        $content_medias = Media_bak::model()->with('content')->findAllByAttributes(array('content_type'=>1));
        
        echo "INSERT INTO `media` (
            `id` ,
            `name` ,
            `description` ,
            `filename` ,
            `path` ,
            `create_date` ,
            `file_type` ,
            `file_size`,
            `administration_id`
            ) VALUES ";
        foreach($content_medias as $media)
        {
            $color='black';
            //find file
            //$files = $this->find('files/content/Content_'.$media->content_key, $media->filename );
            
            //if(!empty($files))
                //echo "<font color=blue>".$files[0]."</font><br>";
            if(!file_exists('files/content/'.$media->content->administration_id.'/Content_'.$media->content_key.'/'.$media->filename)) 
            {
                $color = 'blue';

            }
            else
            {
                /*
                $path = Yii::getPathOfAlias ('webroot').'/files/content/'.$media->content->administration_id.'/Content_'.$media->content_key.'/';
                if(!file_exists($path))
                    mkdir ($path, 0777, true);
                if($media->content != null && $color !="blue")
                   rename (Yii::getPathOfAlias ('webroot').'/files/content/Content_'.$media->content_key.'/'.$media->filename, Yii::getPathOfAlias ('webroot').'/files/content/'.$media->content->administration_id.'/Content_'.$media->content_key.'/'.$media->filename);
             */
                
            }
            
            if($color == "black")
            {
                $rpath = Yii::getPathOfAlias ('webroot').'/files/content/'.$media->content->administration_id.'/Content_'.$media->content_key.'/';
                $path = 'content/'.$media->content->administration_id.'/Content_'.$media->content_key;
                $filetype = mime_content_type($rpath.$media->filename);
                $filesize = filesize($rpath.$media->filename);
            }
            $date = date('Y-m-d');
            $name = addslashes($media->name);
            $desc = addslashes($media->description);
            $filen = addslashes($media->filename);
            if($media->content == null ) $color = 'red';
            // id, name, description, filename, path, create_date,file_type, file_size, admin
            if($color == "black")
            {
            echo "<font color=$color>($media->id, '$name', '$desc','$filen', '$path', '$date', '$filetype', '$filesize', '{$media->content->administration_id}'  ),<br> \n\r";
        
            echo "</font>";
            }
        }
        
        
    }
    
    public function actionCMK()
    {
        $content_medias = Media_bak::model()->with('content')->findAllByAttributes(array('content_type'=>1));
        
       echo "INSERT INTO `content_has_media` (
            `content_id` ,
            `media_id` ,
            `type` ,
            `name` ,
            `description` ,
            ) VALUES "; 
       foreach($content_medias as $media)
        {
            $color='black';

            if(!file_exists('files/content/'.$media->content->administration_id.'/Content_'.$media->content_key.'/'.$media->filename)) 
                $color = 'blue';
            if($media->content == null ) $color = 'red';
            $name = addslashes($media->name);
            $desc = addslashes($media->description);
            if($color == "black")
            {
                echo "<font color=$color>({$media->content->id}, $media->id, $media->type, '$name', '$desc' ),<br></font> \n\r";
            }
            
        }
    }
    
    public function actionPMK()
    {
        $content_medias = Media_bak::model()->with('product')->findAllByAttributes(array('content_type'=>2));
        
       echo "INSERT INTO `product_has_media` (
            `product_id` ,
            `media_id` ,
            `type` ,
            `name` ,
            `description`
            ) VALUES "; 
       $categories = array(
                "1"=>"Public",
                "3"=>"Forest",
                "4"=>"Fun",
                "5"=>"Park Furniture",
                "6"=>"Mini"
            );
       foreach($content_medias as $media)
        {
            $color='black';

            $path = 'files/product/'.$categories[$media->product->category_id].'/'.$media->product->serial_number.'/'.$media->filename;
            if(!file_exists($path)) $color = 'blue';
            
            if($media->product == null ) $color = 'red';
            $name = addslashes($media->name);
            $desc = addslashes($media->description);
            if($color == "black")
            {
                echo "<font color=$color>({$media->product->id}, $media->id, $media->type, '$name', '$desc' ),<br></font> \n\r";
            }
            
        }
    }
    
    public function actionPMedia()
    {
        $content_medias = Media_bak::model()->with('product')->findAllByAttributes(array('content_type'=>2));
        
        echo "INSERT INTO `media` (
            `id` ,
            `name` ,
            `description` ,
            `filename` ,
            `path` ,
            `create_date` ,
            `file_type` ,
            `file_size`,
            `administration_id`
            ) VALUES ";
        
        foreach($content_medias as $media)
        {
            $color='black';
            //find file
            //$files = $this->find('files/content/Content_'.$media->content_key, $media->filename );
            $categories = array(
                "1"=>"Public",
                "3"=>"Forest",
                "4"=>"Fun",
                "5"=>"Park Furniture",
                "6"=>"Mini"
            );
            //if(!empty($files))
                //echo "<font color=blue>".$files[0]."</font><br>";
            $path = 'files/product/'.$categories[$media->product->category_id].'/'.$media->product->serial_number.'/'.$media->filename;
            if(!file_exists($path)) $color = 'blue';
            if($media->product == null ) $color = 'red';
            if($color == "black")
            {
                $rpath = Yii::getPathOfAlias ('webroot').'/files/product/'.$categories[$media->product->category_id].'/'.$media->product->serial_number.'/';
                $path = 'product/'.$categories[$media->product->category_id].'/'.$media->product->serial_number;
                $filetype = mime_content_type($rpath.$media->filename);
                $filesize = filesize($rpath.$media->filename);
            }
            $date = date('Y-m-d');
            $name = addslashes($media->name);
            $desc = addslashes($media->description);
            $filen = addslashes($media->filename);
            //echo $path."<br>";
            if($color == "black")
                echo "<font color=$color>($media->id, '$name', '$desc','$filen', '$path', '$date', '$filetype', '$filesize', '1'  ),<br></font> \n\r";
        
            //echo "<font color=$color>($media->id, '$media->name', '$media->description', '$media->filename', '$media->type', {$categories[$media->product->category_id]} $media->content_key {$media->product->id}, '{$media->product->serial_number}', $path  ),<br> \n\r";

        }
        
        
    }
    
    public function find($dir, $pattern){
        // escape any character in a string that might be used to trick
        // a shell command into executing arbitrary commands
        $dir = escapeshellcmd($dir);
        // get a list of all matching files in the current 
        $files = array();
        if(is_dir())
        $files = scandir("$dir/$name");
                
        //$files = glob("$dir/$name");

        // return all found files
        return $files;
    }
}

