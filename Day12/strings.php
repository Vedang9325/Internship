<?php
    $str="This is a string";
    echo $str;
    $lenn=strlen($str);
    echo "<br>";
    //string concat
    echo "The length of this string is ". $lenn. "that was the length <br>";
    echo "The wordcount of this string is ".str_word_count($str)." thank you <br>";
    echo "The reverese of this string is ".strrev($str)." thank you <br>";
    echo "The position of this string is ".strpos($str, "is")." thank you <br>";
    echo "The replacement of this string is ".str_replace("a", "is",$str)." thank you <br>";
    // echo $lenn;
?>