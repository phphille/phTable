phTable
======
A project in the course: JavaScript, jQuery och AJAX med HTML5, PHP (javascript / DV1483) [Link](http://edu.bth.se/utbildning/utb_kurstillfalle.asp?lang=en&KtAnmkod=KP818&KtTermin=20151)

Read more about the project [here](http://dbwebb.se/javascript/kmom10).(In swedish) 

HOW TO

git clone https://github.com/phphille/phTable


For full documentation and HOW TO check out: http://www.student.bth.se/~phpe14//javascript/projektet/index.php

The plugin comes with examples as well.

Quick installation:
===
Create a search form and give the input fields same names as their corresponding table name in you MySql database table.
Include a `<input type='hidden' name='getData'>` in the form as well.

Include js script in your document:
```
<script type="text/javascript" src="js/phTable_v1.0/phTable.js"></script>
<script type="text/javascript">

    $(function() {
        $("#firstForm").phTable({
            tableID: 'firstTable',
            phpScript: 'php-script/data.php',
            tableHeading: ['Film_id','Title','Release_year','Language','Rental_rate','Rating', 'Last_update'],
            tableColumnsToHide: ['Description', 'Rental_duration', 'Replacement_cost', 'Special_features', 'Length'],
            noEditableTableColumn: ['Film id', 'Replacement cost', 'Last update'],
            validationInputValues: { Title: { regex: '^[A-Za-z ]+$', description: 'Only letters and spaces', nullAble: false},
                                  Release_year: { regex: '^[0-9]{4}$', description: 'A year value, xxxx', nullAble: false},
                                  Language: { regex: '^[A-Za-z]{3}$', description: 'Only three letters', nullAble: false},
                                  Rental_rate: { regex: '^[0-9]+(\.[0-9]{1,2})?$', description: 'Any number and a maximum of 2 deciamls', nullAble: false},
                                  Rental_duration: { regex: '^[1-9]$', description: 'Only one number 1-9', nullAble: false},
                                  Rating: { values: ['G','PG','PG-13','R','NC-17'], description: 'Only exceptable values: G, PG, PG-13, R, NC-17', nullAble: false},
                                  Replacement_cost: { regex: '^[0-9]+(\.[0-9]{1,2})?$', description: 'Any number and a maximum of 2 deciamls', nullAble: false},
                                  Length: { regex: '^[0-9]+$', description: 'Only a number', nullAble: false},

                                  Special_features: { valuesMultiple: ['Trailers','Commentaries','Deleted Scenes','Behind the Scenes'], description: 'Only exceptable values: Trailers, Commentaries, Deleted Scenes, Behind the Scenes. If insert multiple values seperate with ",".', nullAble: false},
                                  Description: { nullAble: true}
                                   },
            hits: [10,20,'All'],
        });
     });
</script>
```

Create a php script that handles the ajax request from the plugin. The parameters that will be sent from the plugin is:
```
// Check if the text fields contains any data and remove any harmful tags.
$title = isset($_POST['Title'])  ? strip_tags($_POST['Title']) : null;
$description = isset($_POST['Description'])  ? strip_tags($_POST['Description']) : null;
$releaseYear = isset($_POST['Release_year'])  ? strip_tags($_POST['Release_year']) : null;
$language = isset($_POST['Language'])  ? strip_tags($_POST['Language']) : null;
$rentalDuration = isset($_POST['Rental_duration']) ? strip_tags($_POST['Rental_duration']) : null;
$rentalRate = isset($_POST['Rental_rate'])  ? strip_tags($_POST['Rental_rate']) : null;
$length = isset($_POST['Length']) ? strip_tags($_POST['Length']) : null;
$replacementCost = isset($_POST['Replacement_cost'])  ? strip_tags($_POST['Replacement_cost']) : null;
$specialFeatures = isset($_POST['Special_features']) ? strip_tags($_POST['Special_features']) : null;
$lastUpdate = isset($_POST['Last_update']) ? strip_tags($_POST['Last_update']) : null;


// Check if the checkboxes are checked.
$noHide = isset($_POST['noHide']) ? strip_tags($_POST['noHide']) : null;
$getAll = isset($_POST['getAll']) ? strip_tags($_POST['getAll']) : null;

// Get the table navigation variables
$hits =  isset($_POST['hits']) && ( is_numeric($_POST['hits']) || $_POST['hits']=='All' ) ? $_POST['hits'] : 20;
$page =  isset($_POST['page']) && is_numeric($_POST['page']) ? $_POST['page'] : 1;
$orderby = isset($_POST['orderby']) ? strtolower(strip_tags($_POST['orderby'])) : 'title';
$order = isset($_POST['order']) ? strtolower(strip_tags($_POST['order']))   : 'asc';

// Check which incoming action is called
$getData = isset($_POST['getData']) ? true : false;
$addData = isset($_POST['addData']) ? true : false;
$deleteData = isset($_POST['deleteData']) ? true : false;
$getCellData = isset($_POST['getCellData']) ? true : false;
$updateCellData = isset($_POST['updateCellData']) ? true : false;

// Variables for update and delete
$id = isset($_POST['id']) && is_numeric($_POST['id']) ? $_POST['id'] : null;
$column = isset($_POST['column']) ? strip_tags($_POST['column']) : null;
$content = isset($_POST['content']) ? strip_tags($_POST['content']) : null;
```


And these variables have to be sent back to the plugin, every time:
```
// Deliver the response, as a JSON object with the requiered data.
header('Content-type: application/json');
echo json_encode(array("output" => $data, 'noHide' => $noHide, 'nbrRows' => $rows, 'hits' => $hits, 'page' => $page));
```

Copyright (c) 2014 Philip Persson, phphille@gmail.com
