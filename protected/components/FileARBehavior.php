<?php
/**
 * This behavior manage a file associated to a model attribute of a CActiveRecord.
 * It will write an uploaded file after saving a model if one is provided,
 * and delete it after removing the model from db.
 * The file name will be calculated with attribute(s) of the model.
 * 
 * You can create multiple files from the one sended by the user, by using formats,
 * and use a processor to apply some process on each format.
 * Each format will create a file with a unique suffix in the file name.
 * 
 * For an example, see example file.
 */

/*
 * TODO: 
 * - save file should not create thumbnails
 * - getFileUrl should check is a file with the format is created
 * - processor part can be removed
 */
class FileARBehavior extends CActiveRecordBehavior {
	/**
	 * this attribute (or array of attributes) will determine a part of the file name. default to primary key(s).
	 */
	public $attributeForName;
	
	/**
	 * the attribute filled by a filefield on the form. Must be set.
	 */
	public $attribute;
	
	/**
	 * possible extensions of the file name, comma separated. Must be set.
	 */
	public $extensions;
	
	/**
	 * without the first and last /, the folder where to put the image relative to webroot. must be set.
	 */
	public $relativeWebRootFolder;
        public $attributeForPath;
	
	/**
	 * file prefix. can be used to avoid name clashes for example.
	 */
	public $prefix = '';
	
	/**
	 * default separator when attributeForName is an array and must be joined.
	 */
	public $attributeSeparator = '_';
	
	/**
	 * key => value which list all the desired formats. can be null (the 'normal' => _normalFormat is used then)
	 * example:
	 * 'thumb' => array(
	 *   'suffix' => '_thumb',
	 *   'process' => array('resize' => array(60, 60))
	 * )
	 */
	public $formats = array();
        public $processedImagesFolder;

	
	// normal format
	private static $_normalFormat = array('suffix' => '', 'process' => array());
	
	private $_fileName;
        
        private $_extension;
	
	// override to init some things
	public function setEnabled($enable) {
		parent::setEnabled($enable);
		if (!$enable) return;
		if (empty($this->attribute)) throw new CException('Attribute property must be set.');
		if (empty($this->extensions)) throw new CException('Extension property must be set.');
		
		if (array_key_exists('normal', $this->formats)) $this->formats['normal'] = array_merge(self::$_normalFormat, $this->formats['normal']);
		else $this->formats['normal'] = self::$_normalFormat;
		// set suffixes if not defined
		foreach ($this->formats as $name => $f) {
			if (! array_key_exists('suffix', $f)) $f['suffix'] = $name;
		}
	}
	
	/**
	 * return the file path corresponding to the format, or null if file does not exists.
	 *
	public function getFilePath($format = 'normal') {
		$fs = $this->getFilesPath();
		return isset($fs[$format]) ? $fs[$format] : null;
	}
        */
	
	/**
	 * get the file name without extension and without suffix
	 */
	protected function getFileName()
        {
            if (!isset($this->_fileName))
            {
                    if (!isset($this->attributeForName)) $this->attributeForName = $this->owner->tableSchema->primaryKey;
                    if (!is_array($this->attributeForName)) $partName = $this->owner->{$this->attributeForName};

                    $this->_fileName = $this->prefix.pathinfo($partName, PATHINFO_FILENAME);
                    $this->_extension = pathinfo($partName, PATHINFO_EXTENSION);
            }
            return $this->_fileName;
	}
	
	/**
	 * get the path folder of the stored files
	 */
	protected function getFolderPath() {
		return Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.$this->relativeWebRootFolder.DIRECTORY_SEPARATOR.$this->owner->{$this->attributeForPath}; //relativeWebRootFolder;
	}
        
        protected function getFilePath() {
            return $this->getFolderPath().DIRECTORY_SEPARATOR.$this->getFileName().".".$this->_extension;
        }
        
        protected function getImagePath() {
            return Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.$this->processedImagesFolder.DIRECTORY_SEPARATOR.$this->owner->{$this->attributeForPath};
        }
        
        /**
         * REturn an array with cache items for this image
         */
        public function getCache()
        {
            $result = array();
            $path = $this->getImagePath();
            
            foreach($this->formats as $name => $options)
            {
                $item = array();
                $item['name'] = $name;

                $suffix = $options['suffix'];
                $imagefilepath = $path.DIRECTORY_SEPARATOR.$this->getFileName().$suffix.".".$this->_extension;
                
                $item['exists'] = (file_exists($imagefilepath));
                $item['url'] = Yii::app()->baseUrl.'/'.$this->processedImagesFolder."/".$this->owner->{$this->attributeForPath}.'/'.$this->getFileName().$suffix.".".$this->_extension;
                
                $result[] = $item;
            }
            return $result;
        }
        
        /**
	 * Retrieve url for a given format.
	 */
	public function getFileUrl() {

            $filepath = $this->getFilePath();
            
            //$fs = $this->getExistingFileName($path, $filename);
            if (file_exists($filepath)) 
                return Yii::app()->baseUrl.'/'.$this->relativeWebRootFolder."/".$this->owner->{$this->attributeForPath}.'/'.$this->getFileName().".".$this->_extension;
            else
		return null;
	}
        
        public function getImageUrl($format = 'normal')
        {
            $filepath = $this->getFilePath();
            if(!file_exists($filepath))
                return $filepath." does not excists";            //Yii::log($filepath, 'error');
            if(strpos($this->extensions, strtolower($this->_extension)) !== FALSE)
            {
                $suffix = $this->formats[$format]['suffix'];
                $path = $this->getImagePath();
                $imagefilepath = $path.DIRECTORY_SEPARATOR.$this->getFileName().$suffix.".".$this->_extension;
                //$fs = $this->getExistingFileName($path, $this->getFileName().$suffix);
                if(!file_exists($imagefilepath))
                    $this->createImage($filepath, $format);

                return Yii::app()->baseUrl.'/'.$this->processedImagesFolder."/".$this->owner->{$this->attributeForPath}.'/'.$this->getFileName().$suffix.".".$this->_extension;
            }
            else // it's not an image
            {
                $path = Yii::app()->theme->baseUrl.'/images/filetypes/';
                if(file_exists('../'.$path.$this->_extension.'.png'))
                    return $path.$this->_extension.'.png';
                else
                    return $path.'unknown.png';
            }
        }

        protected function createImage($filepath, $format)
        {
            $imagepath = $this->getImagePath();
            
            $suffix = $this->formats[$format]['suffix'];
                
            //$size = $this->formats[$format]['process']['resize'];
            $max_height = $this->formats[$format]['max_height'];
            $max_width = $this->formats[$format]['max_width'];

            //$image = Yii::app()->thumb->load($filepath);
            $image = Yii::app()->image->load($filepath);
            if($this->formats[$format]['action'] == 'resize_h')
                $image->resize($max_width, $max_height, Image::HEIGHT);
            if($this->formats[$format]['action'] == 'resize_w')
                $image->resize($max_width, $max_height, Image::WIDTH);
            if($this->formats[$format]['action'] == 'resize')
                $image->resize($max_width, $max_height, Image::AUTO);
            
            $fname = $this->getFileName().$suffix.".".$this->_extension;
            $helemaal = $imagepath.'/'.$fname;
            //echo $filesource;
            $image->save($helemaal);
        }
	
	/**
	 * Instanciate a processor.
	 */
	protected function createProcessor($klass, $srcPath) {
		return new $klass($srcPath);
	}
	
	/**
	 * apply some processing to $processor. each key of $option must be a method of $processor clkass.
	 */
	protected function process($processor, $options) {
		foreach ($options as $method => $args) {
			call_user_func_array( array($processor, $method), is_array($args) ? $args : array());
		}
	}
	
	
	
	/**
	 * Save every files, after processing them if needed.
	 */
	protected function saveFile($file, $filePath, $fileName, $ext) {
		$klass = null;
		if (isset($this->processor)) {
			Yii::import($this->processor);
			$tmp = strrchr($this->processor, '.');
			$klass = ($tmp !== false) ? substr($tmp, 1) : $this->processor;
		}
		
		$path = $filePath.DIRECTORY_SEPARATOR.$fileName;
		$real_ext = '.'.(isset($this->forceExt) ? $this->forceExt : $ext);
		
		// optimize if we have only normal format and no processor
		if (count($this->formats) == 1 && !isset($klass)) {
			$file->saveAs($path.$real_ext);
		} else if (isset($klass)) {
			// create a file for each format
			foreach ($this->formats as $f) {
				$processor = $this->createProcessor($klass, $file->tempName);
				if (!empty($f['process'])) $this->process($processor, $f['process']);
				$processor->save($path.$f['suffix'].$real_ext);
			}
		} else {
			// I don't know if it is usefull... There's no processor and multiple formats,
			// ie it is a simple copy multiple times with different suffixes.
			// maybe I have to throw an exception instead.
			foreach ($this->formats as $f) $file->saveAs($path.$f['suffix'].$real_ext, false);
		}
	}
	
	/**
	 * Delete the files on delete.
	 */
	public function afterDelete($evt) {
		$this->deleteFile($this->getFolderPath(), $this->getFileName());
                //print_r($this->getImageCache($this->getImagePath()));
                $this->deleteImageCache();
	}
	
	/**
	 * Delete the files retrieved by getExistingFilesName
	 */
	protected function deleteFile($path, $fname) {
		//$fs = $this->getAnyExistingFiles($path, $fname);
		//foreach ($fs as $f) unlink($f);
            $filepath = $path.DIRECTORY_SEPARATOR.$fname.'.'.$this->_extension;
            unlink($filepath);
	}
        
        protected function deleteImageCache()
        {
            $fs = $this->getImageCache();
            foreach ($fs as $f)
            {
               @unlink($f);
            }
        }
        
        /*
	 * get existing files matching filename with all suffixes
	 */
	protected function getImageCache() {
		$suffixes = array();
                $path = $this->getImagePath();
		foreach ($this->formats as $f) {
			$s = $f['suffix'];
			if (!empty($f))
                        {
                            $suffixes[] = $path.DIRECTORY_SEPARATOR.$this->getFileName().$s.'.'.$this->_extension;
                        }
		}
		// this use the glob GLOB_BRACE option
		return $suffixes; //glob($path.DIRECTORY_SEPARATOR.$filename.'{'.join(',', $suffixes).'}'.'.'.$this->_extension, GLOB_NOSORT | GLOB_BRACE );
	}

}