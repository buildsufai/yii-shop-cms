GIF89a  �  ���   ���������FFFzzz   XXX$$$���������666hhh                                             !�NETSCAPE2.0   !�Created with ajaxload.info !�	
   ,       w  	!�DB�A��H���¬��a��D���@ ^�A�X��P�@�"U���Q#	��B�\;���1�o�:2$v@
$|,3
�_# d�53�"s5e! !�	
   ,       v  i@e9�DA�A������/�`ph$�Ca%@ ���pH���x�F��uS��x#��.�݄�Yf�L_"
p
3B�W��]|L\6�{|z�8�7[7! !�	
   ,       x  �e9�DE"������2r,��qP��� j��`�8��@8bH, *��0-��mFW��9�LP�E3+
(�B"f�{�*BW_/�@_$��~Kr�7Ar7! !�	
   ,       v  �4e9��!H�"�*��Q�/@���-�4�ép4�R+��-��p�ȧ`�P(�6�᠝�U/� 	*,�)(+/]"lO�/�*Ak���K���]A~66�6! !�	
   ,       l  ie9�"���*��� -�80H���=N;���T�E�����q���e��UoK2_WZ�݌V��1jgWe@tuH//w`?��f~#���6��#! !�	
   ,       ~  �,e9��"���*
�;pR�%��#0��`� �'�c�(��J@@���/1�i4��`�V��B�Vu}�"caNi/]))�- Lel	mi} me[+! !�	
   ,       y  Ie9��"M�6�*¨"7E͖��@G((L&�pqj@Z����� ��%@�w�Z) �pl(
���ԭ�q�u*R&c	`))(s_J��>_\'Gm7�$+! !�	
   ,       w  Ie9�*,� (�*�(�B5[1� �Z��Iah!G��exz��J0�e�6��@V|U��4��Dm��%$͛�p
	\G x		} @+|=+
1�-	Ea5l)+! !�	
   ,       y  )�䨞'A�K����ڍ,�����E\(l���&;5 ��5D���0��3�a�0-���-�����ÃpH4V	%i
p[R"|	��#
�	6iZwcw*! !�	
   ,       y  )�䨞,K�*�����0�a�;׋аY8�b`4�n�¨Bb �b�x�,������������(	Ƚ� %
>
2*�i*	/:�+$v*! !�	
   ,       u  )�䨞l[�$�
�Jq[��q3�`Q[�5��:���IX!0�rAD8Cv����HPfi��i Q���AP@pC%D PQ46�iciNj0w�)#! !�	
   ,       y  )��.q��
,G�J r(�J�8�C��*���B�,����&<
�����h�W~-��`�,	����,�>;
8RN<,�<1T]
�c��'qk$
@)#! ;                                                                                                                                                                                                                
		// class name for the relations automatically generated below.
		return array(
                    'media'=>array(self::BELONGS_TO,'Media','media_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'location_id' => 'Vestiging',
			'media_id' => 'Media',
			'type' => 'Type',
			'name' => 'Naam',
			'description' => 'Beschrijving',
		);
	}
        protected function beforeValidate()
        {
            
            if(parent::beforeValidate())
            {
                if($this->isNewRecord)
                {
                    //Dont do this when linking media for now its always new
                    $media_id = $this->createMediaItem();
                    if($media_id != false)
                    {
                        $this->name = $this->file->name;
                        $this->media_id = $media_id;
                        $this->type = self::MEDIA_UNPUBLISHED;
                    }
                    else
                        return false;
                }
                return true;
            }
            else
                return false;
        }
        
        protected function createMediaItem()
        {
            $media = new Media;
            $media->file = $this->file;
            $media->name = $this->file->name;
            $media->filename = $this->file->name;
            $media->path = "location/".$this->location_id;
            $media->file_type = $this->file->type;
            $media->file_size = $this->file->size;
            $media->create_date = date('Y-m-d H:m:s');
            
            $saveTo = "files/".$media->path."/".$media->filename;
            
            if ($media->save()) // && $this->file->saveAs($saveTo))
            {
                return $media->id;
            }
            else
                return false;
        }

}