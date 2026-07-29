<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP tutorial</title>
</head>
<body>
    <div class="container">This is first php website</div>
    <?php
    echo "Hello world and this is printed using php"
    // secret algo
    ?>

    <?php
    // echo "Hello world again Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dolore sunt praesentium rerum perspiciatis quas ea quo quia iure fugiat, facilis optio odio molestias inventore, maxime numquam. Dignissimos magnam, nisi aut quibusdam repellendus expedita laboriosam asperiores nulla natus iste. Veritatis neque maiores eos voluptates, incidunt natus reprehenderit dicta nemo accusamus quasi sunt velit facilis? Eveniet placeat laborum aspernatur accusantium doloremque ad deserunt quam illo qui ut! Repellat sequi ea facilis eveniet voluptatum impedit, sapiente laboriosam distinctio corrupti veritatis magni reiciendis fugiat, sed totam neque ipsum blanditiis est. Sed dignissimos a reiciendis molestiae velit porro maiores necessitatibus labore cupiditate, distinctio, laudantium molestias."

    $variable1=22;
    $variable2=55;

    echo $variable1, $variable2;
    echo "<br>";

    // Arithmetic operators
    echo "the value of variable 1+2 is ";
    echo $variable1+$variable2;

    //Assignment op
    echo "<br>";
    $new=$variable1;
    echo "value of new varible is ";
    echo $new;
    echo "<br>";
    $new+=3;
    echo $new;
    echo "<br>";
    //Comparison operators
    echo "the value of 1==4";
    echo var_dump(1==4);
    echo "<br>";
    //Increment/Decrement operators
    echo ++$variable1;
    echo "<br>";
    echo --$variable2;
    ?>
</body>
</html>