<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Imagick Class
 *
 * Work with Imagick much easier than using the native PHP Imagick ways.
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Max Lau
 */

class Imagick_lib {

	private $debug;

	protected $_Imagickdraw;
	protected $_Imagick;

	public $font;
	public $font_path;
	public $color;
	public $size;
	public $font_image_width;
	public $font_image_height;
	public $font_border_size;
	public $image_format;
	public $string;
	public $out_put_path;
	public $file_name;
	public $background;

	function __construct($config){

		foreach ($config as $key => $value) {
			$this->$key = $value;
		}
		$this->_imagickdraw = new ImagickDraw(); 
		$this->_imagick = new Imagick();
}

	function render($reutrn_object = false){
		$this->_set();
		$this->_draw();

		if($this->background){
			$this->background->compositeImage($this->_imagick, Imagick::COMPOSITE_OVER,$this->font_border_size,$this->font_border_size);
		}

		if($reutrn_object){
			return $this;
		}else{
			$this->background->writeImage($this->out_put_path.$this->file_name);
		}
	}

	private function str_split_unicode($str, $l = 0) {
		if ($l > 0) {
			$ret = array();
			$len = mb_strlen($str, "UTF-8");
		for ($i = 0; $i < $len; $i += $l) {
			$ret[] = mb_substr($str, $i, $l, "UTF-8");
		}
			return $ret;
		}
			return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
	}

	private function _draw(){
		$str_arr = $this->str_split_unicode($this->string);
		$x = 0;
		$y = 0;

		foreach ($str_arr as $key => $word) {
			if(ord($word) == 13 OR ord($word) == 10 ){
				$y = $y+$this->size+$this->word_line_distance;
				$x = $this->size * 2+$this->word_distence;
				continue;
			}elseif($key == 0){
				$x = $this->size * 2+$this->word_distence;
			}elseif (ord($word) == 9) {
				continue;
			}

			$this->_imagick->annotateImage($this->_imagickdraw,$x, $y, 0, $word);
			$properties = $this->_imagick->queryFontMetrics($this->_imagickdraw, $word );
			$x = $x+$properties['textWidth']+$this->word_distence;
			if(intval($x) >=intval($this->font_image_width - $this->font_border_size - $properties['textWidth'])){
				$y=$y+$this->size+$this->word_line_distance;
				$x = 0;
			}
		}
	}

	private function _set(){
		$this->_imagickdraw->setGravity( Imagick::GRAVITY_NORTHWEST );
		$this->_imagickdraw->setFont($this->font_path.$this->font.'.ttf');
		$this->_imagickdraw->setFontSize($this->size);
		$this->_imagickdraw->setFillColor( new ImagickPixel($this->color) );
		$this->_imagick->newImage($this->font_image_width,$this->font_image_height,new ImagickPixel('transparent'));
		$this->_imagick->setImageFormat($this->image_format);
	}

	public function set_format($format){
		$this->$image_format = $format;
	}

	public function create_background(){
		$this->background = new Imagick();
		$this->background->newImage($this->font_image_width+$this->font_border_size*2,$this->font_image_height+$this->font_border_size,new ImagickPixel('#FF0000'));
		$this->background->setImageFormat($this->image_format);
		return $this;
	}

	public function set_font($font = ''){
		$this->font = $font;
		return $this;
	}

	public function set_size($size = ''){
		$this->size = $size;
		return $this;
	}

	public function set_color($color = ''){
		$this->color = $color;
		return $this;
	}

	public function set_image_size($font_image_width,$font_image_height){
		$this->font_image_width  = $font_image_width;
		$this->font_image_height = $font_image_height;
		return $this;
	}

	// public function clear(){
		// unset($this);
		// return;
	// }

}
