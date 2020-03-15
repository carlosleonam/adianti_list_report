<?php

/**
 * @Author: SisSoftwares WEB (Sistemas PHP)
 * @Date:   2018-09-21 09:04:17
 * @Last Modified by:   Usuario
 * @Last Modified time: 2019-04-10 08:10:36
 */
/**
* Class with General Functins
*/
class AdditionalFunctions
{

	/**
	 * Remove Accents, Trilling Spaces, and Allow only ASCII Chars from a STRING
	 * @param type $string
	 * @return type string
	 */
	public static function getCleanString($string)
	{
		$string_clean = strtoupper( trim( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', self::removerAcentosSoASCII( $string ))));
		return $string_clean;
	}


	/**
     * method numberBR()
     * receives a number, can be float, English type
     * and turns it into a Brazilian type number (ex. 1.524,36)
     * @param $num string with number to transform
     * @returns string return formated value
     */
    public static function numberBR($num, $decimal = 2)
    {
        if($num)
        {
            return number_format($num, $decimal, ',', '.');
        }
    }


    /**
     * method numberUS()
     * receive a number, can be float, Brazilian type
     * and turns it into a number accepted by the database (ex. 1524.36)
     * @param $num string with numbers
     * @returns string with formated numbers
     */
    public static function numberUS($num)
    {
        if($num)
        {
            $source  = array('.', ',');
            $replace = array('', '.');
            return str_replace($source, $replace, $num); //remove os pontos e substitui a virgula pelo ponto
        }
    }


    /**
     * Description: Check if text contain numbers
     * @param string $text
     * @return boolean
     */
    public static function thisContainsNumbers($text){
        return preg_match('/\\d/', $string) > 0;
    }



    /**
     * Extract number from text
     *
     * @param [type] $text
     * @param boolean $signals
     * @return void
     */
    public static function getNumberFromText($text, $signals = false)
    {
		$text_value = $text;
		if ($signals) {
			preg_match('/[+-]{0,1}\d*\.?\d*\.?\d*\.?\d+,\d+/', $text_value, $matches );
		} else {
			preg_match('/\d*\.?\d*\.?\d*\.?\d+,\d+/', $text_value, $matches );
		}

        if (count($matches) === 0) {
            $text_value = 0;
        } else {
            $text_value = $matches[0];
        }

    	return floatval( AdditionalFunctions::numberUS( $text_value ) );
    }


    public static function sendArrayToJS($array, $show_console = false)
    {
        $array_json = json_encode( $array );
        $script_text = "var items_obj = JSON.parse( '" . $array_json . "' );";
        if ($show_console) {
            $script_text .= "console.log(items_obj);";
        }
        TScript::create( $script_text );
    }

    /**
     * Passing PHP array to JS like OBJ
     * @param  array $array       Array to passing
     * @param  string $js_var_name JS variable name
     */
    public static function passArrayToJS($array, $js_var_name = 'var_php_obj')
    {
        $array_json = json_encode( $array );
        $script_text = "var ". $js_var_name ." = JSON.parse( '" . $array_json . "' );
                          console.log(items_obj);";
        TScript::create( $script_text );
    }


    /**
     * Get Current Windowws Dimensions
     * @return array  ['width' => '0000', 'height' => '0000']
     */
    public static function getCurrentWindowSize()
    {
        // Get Current Window Size
        TScript::create("$.post('engine.php?class=SaveDimension&largura='+window.innerWidth+'&altura='+window.innerHeight);");

        $arraySize = array('width' => TSession::getValue('JanelaLargura'), 'height' => TSession::getValue('JanelaAltura') );
        return $arraySize;

    }


    public static function getFormName(BootstrapFormBuilder $form)
    {
        // Pegando o nome da form
        $reflectionProperty = new \ReflectionProperty(BootstrapFormBuilder::class, 'title');
        $reflectionProperty->setAccessible(true);
        // Once the property is made accessible, you can read it..
        $form_name = $reflectionProperty->getValue($form);

        return $form_name;

    }


    public static function checkCookieForLimit($cookie_name)
    {
        if(isset($_COOKIE[ $cookie_name ])){

            $limit_l = $_COOKIE[ $cookie_name ];;
            if (!is_numeric($limit_l) || $limit_l == 0 ) {
                $limit = 10;
            } else {
                $limit = (int) $limit_l;
            }
        } else {
            $limit = 10;
        }

        $result = $limit;

        return $result;
    }


    public static function checkCookieForGroup($cookie_name)
    {
            if(isset($_COOKIE[ $cookie_name ])){
                $group_l = $_COOKIE[ $cookie_name ];;
                if ($group_l == '' ) {
                    $group = 'day';
                } else {
                    $group = $group_l;
                }
            } else {
                $group = 'day';
            }
            $result = $group;

        return $result;
    }


    public static function checkCookieForTDatagrid($cookie_name): array
    {
            if(isset($_COOKIE[ $cookie_name ])){
                $widths_1 = $_COOKIE[ $cookie_name ];;
                if ($widths_1 == '' ) {
                    $widths = null;
                } else {
                    $widths = explode(',', $widths_1);
                }
            } else {
                $widths = null;
            }
            $result = $widths;

        return $result;
    }


    // Wrapper do Wrapper para o SweetAlert JS ( composer require varunsridharan/sweetalert2-php )
    public static function swalert($title = '', $content = '', $type = 'success')
    {
        $data = swal2($title,$content,$type);
        echo '<script>'.$data.'</script>';
    }

}