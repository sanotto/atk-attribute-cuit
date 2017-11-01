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
        if ($strvalue != '' && $strvalue != '...') 
				{
 						if(!this::isValidCUIT($strvalue))
						{
                Tools::triggerError($record, $this->fieldName(), 'CUIT No v&aacute;lido');
						}
        }
        parent::validate($record, $mode);
    }

		public static function isValidCUIT($cuit)
		{
			$coeficiente[0]=5;
			$coeficiente[1]=4;
			$coeficiente[2]=3;
			$coeficiente[3]=2;
			$coeficiente[4]=7;
			$coeficiente[5]=6;
			$coeficiente[6]=5;
			$coeficiente[7]=4;
			$coeficiente[8]=3;
			$coeficiente[9]=2;
 
			$resultado=1;
 
			for ($i=0; $i < strlen($cuit); $i= $i +1) 
			{    //separo cualquier caracter que no tenga que ver con numeros
				if ((Ord(substr($cuit, $i, 1)) >= 48) && (Ord(substr($cuit, $i, 1)) <= 57))
				{
					$cuit_rearmado = $cuit_rearmado . substr($cuit, $i, 1);
				}
			}
 
			if (strlen($cuit_rearmado) <> 11) 
			{  // si to estan todos los digitos
					return false;
			} 
			else 
			{
				$sumador = 0;
				$verificador = substr($cuit_rearmado, 10, 1); //tomo el digito verificador
 
				for ($i=0; $i <=9; $i=$i+1) 
				{
					$sumador = $sumador + (substr($cuit_rearmado, $i, 1)) * $coeficiente[$i];//separo cada digito y lo multiplico por el coeficiente
				}
 
				$resultado = $sumador % 11;
				$resultado = 11 - $resultado;  //saco el digito verificador
				$veri_nro = intval($verificador);
 
				if ($veri_nro <> $resultado) 
				{
					return false;
				} 
				$cuit_rearmado = substr($cuit_rearmado, 0, 2) . "-" . substr($cuit_rearmado, 2, 8) . "-" . substr($cuit_rearmado, 10, 1);
				return true;
			}
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
		$value= Tools::atkArrayNvl($rec, $this->fieldName());
        return $value; 
    }
}
