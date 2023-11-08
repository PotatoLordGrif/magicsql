<!DOCTYPE html>
<html>
<head>
<?php

require __DIR__ . '/vendor/autoload.php'; // remove this line if you use a PHP Framework.

use Orhanerday\OpenAi\OpenAi;
//if (isset($_POST['submit'])) {
$GLOBALS["open_ai"] = new OpenAi('Your_Token_Here');
?>
<style>
        /* Apply basic styles to the form */
        form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
        }

        /* Style the left side (form inputs) */
        .left-side {
            flex: 1;
            padding: 10px;
        }

        /* Style the right side (output and buttons) */
        .right-side {
            flex: 1;
            background-color: #d0e7f6;
            padding: 10px;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Align items at the bottom */
        }
        .bottom{
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            display:flex;
            width:fit-content;
            justify-content: center;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(1, 1, 1, 0.5);
            flex-wrap: wrap;
            overflow:auto;
        }

        table{
            border-collapse: collapse;
        }
        td{
            border:1pt solid black;
            margin:2px;
            padding:10px;
            overflow:auto;
            text-overflow:ellipsis;
            word-wrap: break-word;
        }

        /* Style the form input fields and text area */
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="password"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Style the submit button */
        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Add some spacing and styling to the form labels */
        label {
            font-weight: bold;
            color: #333;
        }

        /* Apply styles for form validation messages (if needed) */
        .error {
            color: #f00;
        }

        /* Center the form and align labels with inputs */
        label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        /* Style for the "Run" and "Regenerate" buttons */
        .right-side button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }

        .right-side button:hover {
            background-color: #0056b3;
        }

        /* Adjust button placement */
        .right-side button:first-child {
            margin: 0;
        }

    header {
        background-color: #fff; /* Change the background color as needed */
        padding: 20px;
        text-align: center;
    }

    /* Style the logo image */
    .logo img {
        max-width: 300px; /* Adjust the width as needed */
        height: auto;
    }

    /* Style the logo text (optional) */

    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="MagicSQL.png" alt="Sparkles with a vertical line, followed by the text MagicSQL">
        </div>
    </header>
<?php 
if(!isset($_POST["address"])) $_POST["address"] = "";
if(!isset($_POST["username"])) $_POST["username"] = "";
if(!isset($_POST["password"])) $_POST["password"] = "";
if(!isset($_POST["database"])) $_POST["database"] = "";
if(!isset($_POST["input"])) $_POST["input"] = "";
if(!isset($GLOBALS["databaseErr"])) $GLOBALS["databaseErr"] = "";
if(!isset($_POST["output"])) $_POST["output"] = "";

$result = "";
$table = "";
{
    $result = SQLdropdown();
}

function SQLdropdown()
{
   // SQL needs to be steralized: https://tekeye.uk/html/php-sanitize-post-data#:~:text=Use%20PHP%20Filters%20for%20Data,Email
    $address = $_POST["address"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    if(empty($address) && empty($username) && empty ($password))
    {
        return;
    }
    else if(empty($address) || empty($username) || empty ($password))
    {
        $GLOBALS["databaseErr"] = "Incomplete database information.";
    }
    else
    {
        try{$conn = @mysqli_connect($address,$username,$password);}
        catch(Exception $e)
        {
        $GLOBALS["databaseErr"] = "Connection Failed Invalid Login Information";
        return;
        }
        if(!$conn)
        {
            $GLOBALS["databaseErr"] = "Connection Failed Invalid Login Information";
            return "Error";
        }
        else
        {
            $sql = "SHOW DATABASES;";
            $result = mysqli_query($conn,$sql);
        }
    }
    if(isset($result)) { return $result;}
    else {return "Err";}
}

?>

<?php 
if(isset($_POST["submit"])) {
    // Handle form submission here
    // Steralize
    $address = $_POST["address"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $database = $_POST["database"];
    $input = $_POST["input"];
    $result = SQLdropdown();
    $table = "";
    // Add your form submission logic here
    $_POST["output"] = openAI($input,$database);
}

if(isset($_POST["run"])) {
    $result = SQLdropdown();
    $table = runSQL(strip_tags($_POST["output"]),$_POST["database"]);
}

if(isset($_POST["regenerate"])) {
    $address = $_POST["address"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $database = $_POST["database"];
    $input = $_POST["input"];
    $result = SQLdropdown();
    $table = "";
    // Add your form submission logic here
    $_POST["output"] = openAI($input,$database);
}

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="left-side">
        Server Address: <input type="text" name="address" value="<?php echo $_POST["address"];?>"><br>
        Username: <input type="text" name="username" value="<?php echo $_POST["username"];?>"><br>
        Password: <input type="password" name="password" value="<?php echo $_POST["password"];?>"><br>
        <input type="submit" name="connect" value="Connect">
        <span class="error"><?php echo $databaseErr;?></span><br><br>
        <label>Select the Database</label>
        <select name="database">
            <?php
                if(!isset($result)) {?>
                    <option value="Not Connected">Not Connected</option>
                <?php }
                else{
                while($db = mysqli_fetch_array($result,MYSQLI_ASSOC)):; ?>
                    <?php echo '<option value="' . $db["Database"] . '"'; if($_POST["database"] == $db["Database"]) {echo " selected";} else {echo "";}
                    echo '>';?>
                    <?php echo $db["Database"];?>
                    </option>
                <?php endwhile; }?>
        </select>
            <br><br>
        Prompt: <textarea name="input" rows="5" cols="40"><?php echo $_POST["input"];?></textarea><br>
        <input type="submit" name="submit" value="Submit">
    </div>
    <div class="right-side">
        <!-- <input type="text" name="output" value="<?php //echo $_POST["output"];?>"> -->
        <textarea name="output" rows="25" cols="40"><?php echo $_POST["output"];?></textarea><br>
        <div>
            <button name="run" type="submit">Run</button>
            <button name="regenerate" type="submit">Regenerate</button>
        </div>
    </div>
</form>
<?php
if($table != "" && $table != NULL) {?>
<div class="bottom">
            <?php
            {
            echo '<table class="data-table">
            <tr class="data-heading">';  //initialize table tag
            while ($property = mysqli_fetch_field($table)) {
                echo '<td>' . htmlspecialchars($property->name) . '</td>';  //get field name for header
            }
            echo '</tr>'; //end tr tag

            //showing all data
            while ($row = mysqli_fetch_row($table)) {
                echo "<tr>";
                foreach ($row as $item) {
                    echo '<td>' . htmlspecialchars($item) . '</td>'; //get items 
                }
                echo '</tr>';
            }
            echo "</table>";
            }
        ?>
    </div>
<?php } ?>
</body>
</html>


<?php
//MySQL Connection
function mySQLConnect()
{
    try{$conn = @mysqli_connect($_POST["address"],$_POST["username"],$_POST["password"]);}
        catch(Exception $e)
        {
        $GLOBALS["databaseErr"] = "Connection Failed Invalid Login Information";
        return;
        }
    return $conn;
}

function runSQL(string $input, string $database)
{
    //echo $input;
    $conn = mySQLConnect();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_select_db($conn,$database);
    $sql = $input;
    $result = mysqli_query($conn, $sql);
    return $result;
}

// OpenAI integration Segment
function openAI(string $input, string $dbname)
{   
    $open_ai = $GLOBALS["open_ai"];
    $conn = mySQLConnect();
    $parsedReturn = "";
    $examples = $GLOBALS["exampleParse"];
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT TABLE_SCHEMA,TABLE_NAME,COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $dbname . "';";
    $result = mysqli_query($conn, $sql);
    echo '<br>';
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
        $parsedReturn = $parsedReturn . " table: " . $row["TABLE_NAME"] . " column name: " . $row["COLUMN_NAME"] . " datatype:  " . $row["DATA_TYPE"] . ",\n";
        }
    } else {
        echo "0 results";
    }
    $chat = $open_ai->chat([
        'model' => 'gpt-4',
        'messages' => [
            [
                "role" => "system",
                "content" => "You are a mySQL design assistant. I will give you a list of fields for each table. ONLY USE THOSE FIELDS."
            ],
            [
                "role" => "user",
                "content" => "Using only these values " . $examples . " Give me a select statement that gives every course ID from the schema"
            ],
            [
                "role" => "assistant",
                "content" => "SELECT course_id FROM courses;"
            ],
            [
              "role" => "user",
              "content" => "Using only these values " . $examples . " List every course that has at least one student enrolled."
            ],
            [
              "role" => "assistant",
              "content" => 'SELECT course_name FROM courses INNER JOIN student_enrollment ON courses.course_id = student_enrollment.course_id GROUP BY courses.course_id HAVING Count(student_enrollment.course_id) > 1;'
            ],
            [
                "role" => "user",
                "content" => "Using only these values " . $parsedReturn . " " . $_POST["input"]
            ],
        ],
        'temperature' => 1.0,
        'max_tokens' => 3500,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
      ]);

      // decode response
      $d = json_decode($chat);
      // Get Content
      #echo(nl2br($parsedReturn));
      return str_replace('\n', ' ', $d->choices[0]->message->content); 
}
$GLOBALS["exampleParse"] = "table: course column name: courseID datatype: int, table: course column name: departmentID datatype: int, table: course column name: name datatype: varchar, table: department column name: departmentID datatype: int, table: department column name: departmentName datatype: varchar, table: departmentchair column name: departmentID datatype: int, table: departmentchair column name: facultyID datatype: int, table: dormitory column name: dormID datatype: int, table: dormitory column name: name datatype: varchar, table: faculty column name: facultyID datatype: int, table: faculty column name: name datatype: varchar, table: faculty column name: position datatype: varchar, table: facultycourse column name: courseID datatype: int, table: facultycourse column name: facultyID datatype: int, table: student column name: age datatype: tinyint, table: student column name: classmen datatype: varchar, table: student column name: name datatype: varchar, table: student column name: studentID datatype: int, table: studentcourse column name: courseID datatype: int, table: studentcourse column name: enrolldate datatype: date, table: studentcourse column name: gpa datatype: float, table: studentcourse column name: paid datatype: tinyint, table: studentcourse column name: studentID datatype: int, table: studentdorm column name: dormID datatype: int, table: studentdorm column name: paid datatype: tinyint, table: studentdorm column name: roomNo datatype: int, table: studentdorm column name: studentID datatype: int,";
