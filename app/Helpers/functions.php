<?php
function pluralOrSingular($count,$text){
    if($count == 1){
        return $text;
    }else if($count == 0){
        return $text.'s';
    }else{
        return $text.'s';
    }

}
?>