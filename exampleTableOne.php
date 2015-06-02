

<!doctype html>
<html lang='en' class='no-js'>
<head>
<meta charset='utf-8' />
<title>Example one</title>
<link rel="stylesheet" type="text/css" href="css/phTable.css">
<script src="js/jquery-2.1.4.js"></script>
</head>
<body>


<h2 >Table one</h2>

<form action='' method='post' id='firstForm'>
    <input type='hidden' name='getData'>
    <div class='searchFieldWrapper'>
        <h3>Search fields</h3>
        <div class='searchFieldContainter'>
            <p>
                <label class='searchLabel'>Film id:</label>
                <input type='text' class='phTable searchField'  name='Film_id' value=''>

                <label class='searchFieldMarginLeft'>Title:</label>
                <input type='text' class='phTable searchField'  name='Title' value=''>

                <label class='searchFieldMarginLeft'>Description:</label>
                <input type='text' class='phTable searchField'  name='Description' value=''>

            </p>
        </div>
        <div class='searchFieldContainter'>
            <p>
                <label class='searchLabel'>Release year:</label>
                <input type='text' class='phTable searchField'  name='Release_year' value=''>
                <label class='searchFieldMarginLeft'>Language:</label>
                <input type='text' class='phTable searchField'  name='Language' value=''>
                <label class='searchFieldMarginLeft'>Rental duration:</label>
                <input type='text' class='phTable searchField'  name='Rental_duration' value=''>
            </p>
        </div>
        <div class='searchFieldContainter'>
            <p>
                <label class='searchLabel'>Rental rate:</label>
                <input type='text' class='phTable searchField'  name='Rental_rate' value=''>
                <label class='searchFieldMarginLeft'>Length:</label>
                <input type='text' class='phTable searchField'  name='Length' value=''>
                <label class='searchFieldMarginLeft'>Replacement cost:</label>
                <input type='text' class='phTable searchField'  name='Replacement_cost' value=''>
            </p>
        </div>
        <div class='searchFieldContainter'>
            <p>
                <label class='searchLabel'>Special features:</label>
                <input type='text' class='phTable searchField'  name='Special_features' value=''>

                <label class='searchFieldMarginLeft searchLabel'>Last update:</label>
                <input type='text' class='phTable searchField'  name='Last_update' value=''>
            </p>
        </div>

        <p>
            <input class='phTable-checkboxx' type='checkbox' id='noHide' name='noHide'/><label class='phTable-checkboxx'  for='noHide'>Show all</label>
            <input class='phTable-checkboxx' type='checkbox' id='getAll' name='getAll'/><label class='phTable-checkboxx'  for='getAll'>Get all</label>
            <input type='submit' class='phTable' value='Search' name='search'>
        </p>
    </div>
</form>


<table id='firstTable'></table>


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
</body>
