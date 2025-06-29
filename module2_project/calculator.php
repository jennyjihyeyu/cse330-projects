<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Calculator</title>
</head>
<body>
    <form name = "input" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method = "get">
        Number 1:<input type="text" name="number1"/>
        Number 2:<input type="text" name="number2"/> <br>
        Select your operation:
        <input type = "radio" name = "operation" value = "+" id = "+"/> <label for = "+"> Plus </label>
        <input type = "radio" name = "operation" value = "-" id = "-"/> <label for = "-"> Minus </label>
        <input type = "radio" name = "operation" value = "*" id = "*"/> <label for = "*"> Multiply </label>
        <input type = "radio" name = "operation" value = "/" id = "/"/> <label for = "/"> Divide </label> <br>

        <input type="submit" value="Calculate please"/>
        <input type="reset">
        

    </form>
<?php
if (isset($_GET['number1']) && isset($_GET['number2']) && isset($_GET['operation']))
{
    $first_number = $_GET['number1'];
    $second_number = $_GET['number2'];
    $operation = $_GET['operation'];
    if($operation == '+'){
        $result = $first_number + $second_number;
        echo "Calculated number: ".$result;
    }
    else if($operation == '-'){
        $result = $first_number - $second_number;
        echo "Calculated number: ".$result;
    }
    else if($operation == '*'){
        $result = $first_number * $second_number;
        echo "Calculated number: ".$result;
    }
    else if($operation == '/'){
        if($second_number == 0){
            echo "Invalid input! You cannot divide number by 0.";
        }
        else{
            $result = $first_number / $second_number;
            echo "Calculated number: ".$result;
        }
 }
}
?>
</body>
</html>
