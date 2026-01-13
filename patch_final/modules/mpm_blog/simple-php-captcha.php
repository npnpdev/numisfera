<?php
include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');


function simple_php_captcha($config = array()) {

	if( !function_exists('gd_info') )
	{
		throw new Exception('Required GD library is missing');
	}
	$bg_path = dirname(__FILE__) . '/views/img/backgrounds/';
	$font_path = dirname(__FILE__) . '/views/fonts/';
	$captcha_config = array(
		'code' => '',
		'min_length' => 5,
		'max_length' => 5,
		'backgrounds' => array(
			$bg_path . '45-degree-fabric.png',
			$bg_path . 'cloth-alike.png',
			$bg_path . 'grey-sandbag.png',
			$bg_path . 'kinda-jean.png',
			$bg_path . 'polyester-lite.png',
			$bg_path . 'stitched-wool.png',
			$bg_path . 'white-carbon.png',
			$bg_path . 'white-wave.png'
		),
		'fonts' => array(
			$font_path . 'times_new_yorker.ttf'
		),
		'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
		'min_font_size' => 14,
		'max_font_size' => 24,
		'color' => '#3B2FA9',
		'angle_min' => 0,
		'angle_max' => 10,
		'shadow' => true,
		'shadow_color' => '#fff',
		'shadow_offset_x' => -1,
		'shadow_offset_y' => 1
	);

	if( is_array($config) )
	{
		foreach( $config as $key => $value ) $captcha_config[$key] = $value;
	}

	if( $captcha_config['min_length'] < 1 ) $captcha_config['min_length'] = 1;
	if( $captcha_config['angle_min'] < 0 ) $captcha_config['angle_min'] = 0;
	if( $captcha_config['angle_max'] > 10 ) $captcha_config['angle_max'] = 10;
	if( $captcha_config['angle_max'] < $captcha_config['angle_min'] ) $captcha_config['angle_max'] = $captcha_config['angle_min'];
	if( $captcha_config['min_font_size'] < 10 ) $captcha_config['min_font_size'] = 10;
	if( $captcha_config['max_font_size'] < $captcha_config['min_font_size'] ) $captcha_config['max_font_size'] = $captcha_config['min_font_size'];

	srand(microtime() * 100);
	if( empty($captcha_config['code']) ) {
		$captcha_config['code'] = '';
		$length = rand($captcha_config['min_length'], $captcha_config['max_length']);
		while( Tools::strlen($captcha_config['code']) < $length ) {
			$captcha_config['code'] .= Tools::substr($captcha_config['characters'], rand() % (Tools::strlen($captcha_config['characters'])), 1);
		}
	}

	$image_src = Tools::substr(__FILE__, Tools::strlen( realpath($_SERVER['DOCUMENT_ROOT']) )) . '?_CAPTCHA&amp;t=' . urlencode(microtime());
	$image_src = '/' . ltrim(preg_replace('/\\\\/', '/', $image_src), '/');
  Context::getContext()->cookie->_CAPTCHA = serialize($captcha_config);

	return array(
		'code' => $captcha_config['code'],
    'image_src' => _PS_BASE_URL_SSL_.__PS_BASE_URI__ .'/modules/mpm_blog/simple-php-captcha.php?showImage&t=' . urlencode(microtime())
	);
}

if( !function_exists('hex2rgb') ) {
  function hex2rgb($hex_str, $return_string = false, $separator = ',') {
    $hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str); // Gets a proper hex string
    $rgb_array = array();
    if( Tools::strlen($hex_str) == 6 ) {
      $color_val = hexdec($hex_str);
      $rgb_array['r'] = 0xFF & ($color_val >> 0x10);
      $rgb_array['g'] = 0xFF & ($color_val >> 0x8);
      $rgb_array['b'] = 0xFF & $color_val;
    } elseif( Tools::strlen($hex_str) == 3 ) {
      $rgb_array['r'] = hexdec(str_repeat(Tools::substr($hex_str, 0, 1), 2));
      $rgb_array['g'] = hexdec(str_repeat(Tools::substr($hex_str, 1, 1), 2));
      $rgb_array['b'] = hexdec(str_repeat(Tools::substr($hex_str, 2, 1), 2));
    } else {
      return false;
    }
    return $return_string ? implode($separator, $rgb_array) : $rgb_array;
  }
}



if( isset(Context::getContext()->cookie->_CAPTCHA) && Context::getContext()->cookie->_CAPTCHA ) {

	$captcha_config = unserialize(Context::getContext()->cookie->_CAPTCHA);
	if( !$captcha_config ) exit();

//  var_dump($captcha_config); die;

  Context::getContext()->cookie->_CAPTCHA = "";
	srand(microtime() * 100);
	$background = $captcha_config['backgrounds'][rand(0, count($captcha_config['backgrounds']) -1)];
	list($bg_width, $bg_height, $bg_type, $bg_attr) = getimagesize($background);

	$captcha = imagecreatefrompng($background);
	$color = hex2rgb($captcha_config['color']);
	$color = imagecolorallocate($captcha, $color['r'], $color['g'], $color['b']);
	$angle = rand( $captcha_config['angle_min'], $captcha_config['angle_max'] ) * (rand(0, 1) == 1 ? -1 : 1);
	$font = $captcha_config['fonts'][rand(0, count($captcha_config['fonts']) - 1)];

	if( !file_exists($font) ) throw new Exception('Font file not found: ' . $font);

	$font_size = rand($captcha_config['min_font_size'], $captcha_config['max_font_size']);
	$text_box_size = imagettfbbox($font_size, $angle, $font, $captcha_config['code']);
	$box_width = abs($text_box_size[6] - $text_box_size[2]);
	$box_height = abs($text_box_size[5] - $text_box_size[1]);
	$text_pos_x_min = 0;
	$text_pos_x_max = ($bg_width) - ($box_width);
	$text_pos_x = rand($text_pos_x_min, $text_pos_x_max);
	$text_pos_y_min = $box_height;
	$text_pos_y_max = ($bg_height) - ($box_height / 2);
	$text_pos_y = rand($text_pos_y_min, $text_pos_y_max);

	if( $captcha_config['shadow'] ){
		$shadow_color = hex2rgb($captcha_config['shadow_color']);
	 	$shadow_color = imagecolorallocate($captcha, $shadow_color['r'], $shadow_color['g'], $shadow_color['b']);
		imagettftext($captcha, $font_size, $angle, $text_pos_x + $captcha_config['shadow_offset_x'], $text_pos_y + $captcha_config['shadow_offset_y'], $shadow_color, $font, $captcha_config['code']);
	}

	imagettftext($captcha, $font_size, $angle, $text_pos_x, $text_pos_y, $color, $font, $captcha_config['code']);
	header("Content-type: image/png");
	imagepng($captcha);
}