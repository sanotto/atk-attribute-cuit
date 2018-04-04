<?php

namespace Sintattica\Atk\Attributes;

use Sintattica\Atk\Core\Tools;
use Sintattica\Atk\Core\Language;
use Sintattica\Atk\Utils\IpUtils;

/**
 * The CUIT  attribute provides a widget to edit CUIT's in atk9
 *
 * @author Santiago Ottonello <sanotto@gmail.com>
 */
class CUITAttribute extends Attribute
{

    /**
     * Constructor.
     *
     * @param string $name attribute name
     * @param int $flags attribute flags.
     */
    public function __construct($name, $flags = 0)
    {
        parent::__construct($name, $flags);
        $this->setAttribSize(11);
    }

    /**
     * Fetch value.
     *
     * @param array $postvars post vars
     *
     * @return string fetched value
     */
    public function fetchValue($postvars)
    {
        $value = parent::fetchValue($postvars);
        return $value;
    }

    public function edit($record, $fieldprefix, $mode)
    {
        return parent::edit($record, $fieldprefix, $mode);
    }

    /**
     * Checks if the value is a valid YearMonth value.
     *
     * @param array $record The record that holds the value for this
     *                       attribute. If an error occurs, the error will
     *                       be stored in the 'atkerror' field of the record.
     * @param string $mode The mode for which should be validated ("add" or
     *                       "update")
     */
    public function validate(&$record, $mode)
    {
        // Check for valid ip string
        $strvalue = Tools::atkArrayNvl($record, $this->fieldName(), '');
        if ($strvalue != '' && $strvalue != '...') {
            if (!$this::isValidCUIT($strvalue)) {
                Tools::triggerError($record, $this->fieldName(), 'CUIT No v&aacute;lido');
            }
        }
        parent::validate($record, $mode);
    }

    /**
     * Validates if the given CUIT is valid.
     *
     * @param string $cuit The CUIT to validate.
     * @return bool True if the CUIT is valid.
     */
 	public static function isValidCUIT($cuit)
	{
		if(!( $cuit = self::normalizeCUIT($cuit))){
			return false;
		}
		$acumulado = 0;
		$digitos = str_split( $cuit );
        $digito = end($digitos);
        
        $verif = self::calcVerifyDigit($cuit);
		return ($digito == $verif);
    }

    /**
     * Calculate the Verification digit of a given CUIT.
     *
     * @param string $cuit The CUIT to validate.
     * @return int $verif The verification digit, 10 implies invalid digit.
     */
 	public static function calcVerifyDigit($cuit)
	{
		if(!( $cuit = self::normalizeCUIT($cuit))){
			return 10;
        }
		$acumulado = 0;
		$digitos = str_split( $cuit );
		$digito = array_pop( $digitos );

        for( $i = 0; $i < count( $digitos ); $i++ ){
            $mult = ( 2 + ( $i % 6 ) );
            $d = $digitos[ 9 - $i ];
			$acumulado += $digitos[ 9 - $i ] * ( 2 + ( $i % 6 ) );
		}
		$verif = 11 - ( $acumulado % 11 );
        $verif = $verif == 11? 0 : $verif;
		return $verif;
	}
 

    /**
     * Calculate the cuit for a given DNI number and sex. 
     *
     * @param string $dni  The DNI number
     * @param string $sexo The sex as 'M','F' 
     */
    public static function calculateCUIL($dni, $sexo)
    {
        switch($sexo){
            case 'F':
                $prefix='27';
                break;
            case 'M':
                $prefix='20';
                break;
            case 'J':
                $prefix='30';
                break;
            default:
                return null;

        }
		$cuit = $prefix.$dni.'0';
		$digit = self::calcVerifyDigit($cuit);
		if ($digit <> 10){
			return $prefix.$dni.$digit;
		}
		$cuit = '23'.$dni.'0';	
		$digit = self::calcVerifyDigit($cuit);
		return  '23'.$dni.$digit;
	}

   	/**
	 * Returns only the digits of the CUIT number striping all
     * non digits chars.
     * @param string $cuit The CUIT to normalize,
     * @return variant The normalized CUIT or false if can not
     *                 be normalized..
 	 */
	public static function normalizeCUIT($cuit)
	{
		$cuit = preg_replace( '/[^\d]/', '', (string) $cuit );
		if( strlen( $cuit ) != 11 ){
			return false;
		}
		return $cuit;
	}
 
	/**
     * Converts the internal attribute value to one that is understood by the
     * database.
     *
     * @param array $rec The record that holds this attribute's value.
     *
     * @return string The database compatible value
     */
    public function value2db($rec)
    {
        $value = Tools::atkArrayNvl($rec, $this->fieldName());
        return $value;
    }
}
