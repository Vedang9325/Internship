<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP tutorial</title>
</head>
<style>
    *{
        margin:0;
        padding: 0;
        box-sizing:border-box;
    }
    .container{
        max-width:80%;
        background-color:wheat;
        margin:20px;
        padding: 20px;
    }
</style>
<body>
    <div class="container">
        <h1>Lets learn about PHP</h1>
        <p>Your party status here</p>
    <?php
        $age=71;
        if($age>18){
            echo "you can go to party";
        }
        elseif($age==7){
            echo "you are 7 years old";
        }
        else{
            echo "you cannot go to party";
        }
        //arrays
        echo "<br>";
        $languages=array("Python","c","php");
        echo $languages[0];
        echo "<br>";
        echo count ($languages);
        //loops in php
        $a=0;
        while($a<=10){
            echo "<br>the value of a is ";
            echo $a;
            $a++;
        }
        //iterating arrays
        $a=0;
        while ($a < count($languages)) {
            echo "<br>The value from while is ";
            echo $languages[$a];
            $a++;
        }
        //do while loop
        $a=0;
        do{
            echo "<br>The value of a from do while is ";
            echo $a;
            $a++;
        }while($a<10);
        //for loop
        $a=200;
        for ($a=0; $a <10 ; $a++) { 
            echo "<br> value of a from for loop is ";
            echo $a;
        }
        //foreach
        foreach ($languages as $value ) {
            echo "<br> the value from foreach is ";
            echo $value;
        }
        echo "<br>";
        //function
        function print5(){
            echo "FIVE";
        }
        print5();
        function print_number($number){
            echo "<br> Your number is ";
            echo $number;
        }
        print_number(45);
    ?>
    </div>
</body>
</html>