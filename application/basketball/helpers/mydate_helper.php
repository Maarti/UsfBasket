<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('birthdate_category'))
{
    function birthdate_category($agemin,$agemax)
    {
        $annee_actuelle=date("Y");
        $mois_actuel=date("m");
       
            if ($mois_actuel>6) {// Si la date actuelle est après le mois de Juin, on est en début de saison
                $birthdatemax=$annee_actuelle-$agemin;
                $birthdatemin=$annee_actuelle-$agemax;
            }else{
                $birthdatemax=($annee_actuelle-1)-$agemin;
                $birthdatemin=($annee_actuelle-1)-$agemax;
            }
            
            if ($agemax==99){
                $birthdate_text=$birthdatemax.' et moins';
            }else{
                $birthdate_text=$birthdatemin.' - '.$birthdatemax;
            }
            
        return $birthdate_text;
    }
}
 
?>