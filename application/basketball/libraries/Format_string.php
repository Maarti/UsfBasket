<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Format_string {
	
    /**
     * Renvoie une chaine avec la première lettre en majuscule et le reste en minuscule
     * @param string $lastname : chaine de caractere a formater
     * @return string chaine de caractere aux format souhaité
     */
    function format_lastname($lastname){
        setlocale( LC_CTYPE, 'fr_FR' );
        setlocale( LC_ALL, 'french' );
        return strtoupper($lastname);
    }

        
    function format_firstname($firstname){
        setlocale( LC_CTYPE, 'fr_FR' );
        setlocale( LC_ALL, 'french' );
        $first_letter_upper=strtoupper(substr($firstname,0,1));
        $else_string_lower=strtolower(substr($firstname,1));
        $formatted_string=$first_letter_upper.$else_string_lower;
        return $formatted_string;
    }
}

?>
