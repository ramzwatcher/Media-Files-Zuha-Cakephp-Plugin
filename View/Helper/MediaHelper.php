<?php

App::uses('AppHelper', 'View/Helper');
class MediaHelper extends AppHelper {
	
	public $helpers = array ('Html', 'Form', 'Url');
	
	public $options = array(
		'width' => '300',
		'height' => '300',
		'url' => array(),
		'class' => 'media-item',
		'conversion' => 'resizeCrop',
		
	);
	
	public $types = array(
		'image' => array('jpg', 'jpeg', 'gif', 'png'),
		'video' => array('mpg', 'mov', 'wmv', 'rm', '3g2', '3gp', '3gp2', '3gpp', '3gpp2', 'avi', 'divx', 'dv', 'dv-avi', 'dvx', 'f4v', 'flv', 'h264', 'hdmov', 'm4v', 'mkv', 'mp4', 'mp4v', 'mpe', 'mpeg', 'mpeg4', 'mpg', 'nsv', 'qt', 'swf', 'xvid'),
		'audio' => array('aif', 'mid', 'midi', 'mka', 'mp1', 'mp2', 'mp3', 'mpa', 'wav', 'aac', 'flac', 'ogg', 'ra', 'raw', 'wma'),
		'document' => array('pdf', 'doc', 'docx', 'ods', 'odt'),
	);
	
	public function display($item, $options = array()) {
		$this->options = array_merge($this->options, $options);
		if($this->_getType($item['Media'])) {
			$method = $this->type.'Media';
			return $this->$method($item['Media']);	
		}else {
			return '';
		}
	}
	

	public function imageMedia ($item) {
		$imagepath = ROOT.DS.SITE_DIR.'Locale'.DS.'View'.DS.WEBROOT_DIR.DS.'media'.DS.'images' . DS . $item['filename'].'.'.$item['extension'];
		debug($imagepath);
		$thumbImageOptions = array(
				'width' => $this->options['width'],
				'height' => $this->options['height'],
				'alt' => $item['title'],
				'class' => 'thumbnail media-image-thumb',
				'id' => $item['id'],
		);
		$image = $this->Html->image($imagePath, $thumbImageOptions,	array(
    		'conversion' => $this->options['conversion'],
			'quality' => 75,
			'alt' => 'thumbnail',
		));
		debug($image);
		return $this->_View->element('image_display', 
			array(
				'image' => $image,
				'class' => $this->options['class'],
				'url' => $this->options['url']
				));
	}
	
	public function videoMedia ($item) {
		
	}
	
	protected function _getType($item) {
		foreach($this->types as $type => $extensions) {
			if(array_search($item['extension'], $extensions)) {
				$this->type = $type;
				return true;
			}
		}
		 return false;
	}
	
	
	
}