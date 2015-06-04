

<!doctype html>
<html lang='en' class='no-js'>
<head>
<meta charset='utf-8' />
<title>Example two</title>
<link rel="stylesheet" type="text/css" href="css/phTable.css">
<script src="js/jquery-2.1.4.js"></script>
</head>
<body>

<h2 class='headingNewPart'>Table two</h2>


<form action='' method='post' id='secondForm'>
    <input type='hidden' name='getData'>
    <div class='searchFieldWrapper'>
        <h3>Search fields</h3>
        <div class='searchFieldContainter'>
            <p class='extraTop'>
                <label class='searchLabel'>ID:</label>
                <input type='text' class='phTable searchField'  name='ID' value=''>

                <label class='searchFieldMarginLeft'>Name:</label>
                <input type='text' class='phTable searchField'  name='Name' value=''>

                <label class='searchFieldMarginLeft'>Continent:</label>
                <input type='text' class='phTable searchField'  name='Continent' value=''>
            </p>
        </div>
        <div class='searchFieldContainter'>
            <p class='extraTop'>
                <label class='searchLabel'>Region:</label>
                <input type='text' class='phTable searchField'  name='Region' value=''>
                <label class='searchFieldMarginLeft'>Local name:</label>
                <input type='text' class='phTable searchField'  name='Local_name' value=''>
                <label class='searchFieldMarginLeft'>GNP:</label>
                <input type='text' class='phTable searchField'  name='GNP' value=''>
            </p>
        </div>
        <div class='searchFieldContainter'>
            <p class='extraTop'>
                <label class='searchLabel'>Surface area:</label>
                <input type='text' class='phTable searchField'  name='Surface_area' value=''>

                <label class='searchFieldMarginLeft'>Indep year:</label>
                <input type='text' class='phTable searchField'  name='Indep_year' value=''>

                <label class='searchFieldMarginLeft'>Population:</label>
                <input type='text' class='phTable searchField'  name='Population' value=''>
            </p>
        </div>
        <div class='searchFieldContainter'>
            <p class='extraTop'>
                <label class='searchLabel'>Life expectancy:</label>
                <input type='text' class='phTable searchField'  name='Life_expectancy' value=''>

                <label class='searchFieldMarginLeft'>GNP old:</label>
                <input type='text' class='phTable searchField'  name='GNP_old' value=''>

                <label class='searchFieldMarginLeft'>Government form:</label>
                <input type='text' class='phTable searchField'  name='Government_form' value=''>
            </p>
        </div>

        <div class='searchFieldContainter'>
            <p class='extraTop'>
                <label class='searchLabel'>Capital:</label>
                <input type='text' class='phTable searchField'  name='Capital' value=''>

                <label class='searchFieldMarginLeft'>Code 2:</label>
                <input type='text' class='phTable searchField'  name='Code 2' value=''>

                <label class='searchFieldMarginLeft'>Head of state:</label>
                <input type='text' class='phTable searchField'  name='Head_of_state' value=''>
            </p>
        </div>
        <p class='extraTop'>
            <input class='phTable-checkboxx' type='checkbox' id='phTable-noHideT2' name='noHide'/><label class='phTable-checkboxx'  for='phTable-noHideT2'>Show all</label>
            <input class='phTable-checkboxx' type='checkbox' id='phTable-getAllT2' name='getAll'/><label class='phTable-checkboxx'  for='phTable-getAllT2'>Get all</label>
            <input type='submit' class='phTable' value='Search' name='search'>
        </p>
    </div>
</form>


<table id='secondTable'></table>

<script type="text/javascript" src="js/phTable_v1.0/phTable.js"></script>
<script type="text/javascript">

    $(function() {
        $("#secondForm").phTable({
            tableID: 'secondTable',
            phpScript: 'php-script/data2.php',
            tableHeading: ['ID','Name','Continent','Region','Local_name','GNP'],
            tableColumnsToHide: ['Surface_area', 'Indep_year', 'Population', 'Life_expectancy', 'GNP_old', 'Government_form', 'Capital', 'Code_2', 'Head_of_state'],
            noEditableTableColumn: ['ID'],
            validationInputValues: {
                Name: { regex: '^[A-Za-z ]+$', description: 'Only letters and spaces', nullAble: false},

                Continent: {values: ['Asia','Europe','North America','Africa','Oceania','Antarctica','South America'], description: 'Only exceptable values: Asia, Europe, North America, Africa, Oceania, Antarctica, South America' , nullAble: false},

                Region: {regex: '^[A-Za-z ]+$', description: 'Only letters and spaces', nullAble: false},

                Local_name: {regex: '^[A-Za-z ]+$', description: 'Only letters and spaces', nullAble: false},
                GNP: {regex: '^[0-9]+(\.[0-9]{1,2})?$', description: 'Only a number with a maximum of 2 deciamls or empty', nullAble: true},
                Surface_area: { regex: '^[0-9]+(\.[0-9]{1,2})?$', description: 'Any number and a maximum of 2 deciamls', nullAble: false},
                Indep_year: { regex: '^[0-9]+$', description: 'A number', nullAble: true},
                Population: { regex: '^[0-9]+$', description: 'A number', nullAble: false},

                Government_form: {regex: '^[A-Za-z ]+$', description: 'Only letters and spaces', nullAble: false},
                Capital: { regex: '^[0-9]+$', description: 'Only a number or empty', nullAble: true},
                Code_2: { regex: '^[A-Za-z]{2}$', description: 'Two letters', nullAble: false},
                Head_of_state: {regex: '^[A-Za-z ]+$', description: 'Only letters and spaces or empty', nullAble: true},

                Life_expectancy: {regex: '^[0-9]+(\.[0-9]{1})?$', description: 'Only a number with a maximum of 1 deciamls or empty', nullAble: true},
                GNP_old: {regex: '^[0-9]+(\.[0-9]{1,2})?$', description: 'Only a number with a maximum of 2 deciamls or empty', nullAble: true},
                                   },
            hits: [10,20,'All'],
        });
     });
</script>

</body>
