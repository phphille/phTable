

jQuery.fn.phTable = function(o) {

    o = jQuery.extend({
			formId: $(this).attr('id')
		}, o);


    function phTable() {
        $('#'+o.tableID).addClass('phTable');
        this.formMethod = 'POST';
        this.formUrl = o.phpScript,
        this.tableID = '#'+o.tableID,
        this.formID = '#'+o.formId;
        this.tableHeading = o.tableHeading;
        this.columnsToHide = o.tableColumnsToHide;
        this.nbrOfColumns = Object.keys(o.tableHeading).length + 1;
        this.initTable(this.tableHeading);
        this.tableHits = o.hits;
        this.noEditColumnName = o.noEditableTableColumn;
        this.validationData = o.validationInputValues;
        this.sortBy = '';

        this.page = '';
        this.endPage = 0;
        this.hits = '';

        this.getData();
    }

    phTable.prototype = {

        getData: function(){
            var prototype = this;
            //console.log(prototype.formMethod);
            $.ajax({
            type: prototype.formMethod,
            url: prototype.formUrl,
            data: $(prototype.formID).serialize() + prototype.sortBy + prototype.hits + '&page=' + prototype.page,
            //contentType: "application/json; charset=utf-8",
            dataType: "json",
            beforeSend: function(){

            },
            success: function(data){
                prototype.resetTable();
                if(data.output){
                    prototype.printTable(data);
                    prototype.page = data.page;
                    prototype.setContentToTableNav(data.hits, data.page, data.nbrRows);
                }
                if(data.noHide){
                    prototype.expandAllExtraTable();
                }
            },
            complete: function(){

            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Ajax request failed: ' + textStatus + ', ' + errorThrown);
            }

            });
        },


        getDataForTableCell: function(target){
            var prototype = this;
            var rowIdAndColumnName = this.getCellRowIdAndColumnNameFromTable(target);

            $.ajax({
            type: prototype.formMethod,
            url: prototype.formUrl,
            data: {getCellData: true, id: rowIdAndColumnName.id, column: rowIdAndColumnName.column},
            dataType: "json",
            beforeSend: function(){

            },
            success: function(data){
                prototype.makeCellEditable(target, data.output, rowIdAndColumnName.id, rowIdAndColumnName.column);
            },
            complete: function(){

            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Ajax request failed: ' + textStatus + ', ' + errorThrown);
            }

            });
        },



        commitCellEdit: function(){
            var prototype = this;
            console.log($('#phTable-editTd').serialize());
            $.ajax({
            type: prototype.formMethod,
            url: prototype.formUrl,
            data: $('#phTable-editTd').serialize(),
            dataType: "json",
            beforeSend: function(){

            },
            success: function(data){
                if(data.output){
                    $('#editTd').remove();
                    prototype.getData();
                }
            },
            complete: function(){

            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Ajax request failed: ' + textStatus + ', ' + errorThrown);
            }

            });
        },


        addNewData: function(){
            var prototype = this;

            $.ajax({
            type: prototype.formMethod,
            url: prototype.formUrl,
            data: $('form#phTable-addData').serialize(),
            dataType: "json",
            beforeSend: function(){
            },
            success: function(data){
                var message;
                if(data.output){
                    message = $("<div id='phTable-flash' class='phTable-success'> <p>Item has been added!</p><p>It has been given the id "+data.output+"</p> </div>");
                }
                else{
                    message = $("<div id='phTable-flash' class='phTable-error'> <p>Item has NOT been added!</p> </div>");
                }

                message.prependTo('tr.phTable-addData .phTable-tr-td-div');
                $('#phTable-flash').fadeIn(100).delay(3000).fadeOut(100, function(){
                    $(this).remove();
                    prototype.getData();
                });
            },
            complete: function(){

            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Ajax request failed: ' + textStatus + ', ' + errorThrown);
            }

            });
        },



        deleteRow: function(target){
            var id = $(target).parent().next().text();
            var prototype = this;

            $.ajax({
            type: prototype.formMethod,
            url: prototype.formUrl,
            data: {deleteData: 'true', id: id},
            dataType: "json",
            beforeSend: function(){
            },
            success: function(data){
                var message;
                if(data.output){
                    prototype.getData();
                }
                else{
                    message = $("<div id='phTable-flash' class='phTable-error'> <p>Item has NOT been removed!</p> </div>");
                    message.prependTo(prototype.tableID);
                    $('#phTable-flash').fadeIn(100).delay(2000).fadeOut(100, function(){
                        $(this).remove();
                    });
                }

            },
            complete: function(){

            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Ajax request failed: ' + textStatus + ', ' + errorThrown);
            }

            });
        },


        initTable: function(data){
            var tableHeading = '<tr class="phTable">';
            var prototype = this;
            var columns = 0;
            tableHeading += '<th class="phTable phTable-del">Delete<div></div></th>';
            $.each(data, function( index, value ) {
                tableHeading += '<th class="phTable phTable-sortable ">'+value.replace('_', ' ')+'<div></div></th>';
            });
            var table = $(tableHeading);
            this.tableNav();
            $(table).appendTo(this.tableID);
        },


        markNotEditable: function(name){
            var text = '';
            name = name.replace('_', ' ');
            if(this.noEditColumnName.indexOf(name) > -1){
                text = 'phTable-noEdit';
            }

            return text;
        },


        printTable: function(data){
            var prototype = this;
            var tableContent = '';
            $.each(data.output, function( index, value ) {
                tableContent += '<tr class="phTable">';
                var dataToHide = {};
                var printExpandable = false;
                tableContent += '<tr class="phTable"><td class="phTable phTable-noEdit phTable-del"><div class="phTable-DeleteButton"></div></td>';
                $.each(value, function( index, value ) {
                    if(value === null){
                        value = '';
                    }
                    //skriver ut datan i kolumnerna om det är header värdena
                    if(prototype.tableHeading.indexOf(index) > -1){
                        tableContent += "<td class='phTable "+prototype.markNotEditable(index)+"'>"+value+"</td>";
                    }
                    else if(prototype.columnsToHide.indexOf(index) > -1){
                        dataToHide[index] = value;
                        printExpandable = true;
                    }
                });
                tableContent += '</tr>';
                if(printExpandable){
                    tableContent += prototype.expandableContent(prototype.nbrOfColumns, dataToHide);
                }
            });
            tableContent += prototype.inputNewContent(prototype.nbrOfColumns);
            var table = $(tableContent);
            $(table).insertAfter(this.tableID + ' tr:eq(1)');

            prototype.tableNav();
            prototype.setInputRequierment('for add new data table');
        },



        expandableContent: function(nbrColumns, items){
            var row = '', prototype = this;
            $.each(items, function(index, value){
                row +=  "<tr class='phTable phTable-inside'><th class='phTable phTable-inside'>"+index.replace('_', ' ')+"</th><td class='phTable phTable-inside "+prototype.markNotEditable(index)+"'>"+value+"</td></tr>";
            });
            var rows = "<tr class='phTable'><td class='phTable phTable-noBorder phTable-tdButton phTable-noEdit' colspan='"+nbrColumns+"'><div class='phTable-triangleDown'></div></td></tr>";
            rows += "<tr class='phTable phTable-hide phTable-expandable'><td colspan='"+nbrColumns+"' class='phTable-noEdit'>";
            rows += "<div class='phTable-tr-td-div'><div class='phTable-container-table-inside'><table class='phTable-inside'>"+row+"</table></div>";
            rows += "<div class='phTable-tr-td-div-div'></div>";
            rows += "</div></td></tr>";

            return rows;
        },



        inputNewContent: function(nbrColumns){
            var row = '';
            var totalColumns = this.tableHeading.concat(this.columnsToHide);
            var prototype = this;
            $.each(totalColumns, function(index, value){
                if(prototype.noEditColumnName.indexOf(value.replace('_', ' ')) === -1){
                    row +=  "<tr class='phTable phTable-inside'><th class='phTable phTable-inside'>"+value.replace('_', ' ')+"</th><td class='phTable phTable-inside phTable-addData phTable-noEdit'><input type='text' class='phTable phTable-forAdd' name='"+value+"'></td></tr>";
                }
            });
            var rows = "<tr class='phTable'><td class='phTable phTable-tdButton phTable-addData phTable-noEdit' colspan='"+nbrColumns+"'><div class='phTable-triangleDown phTable-AddItemExtraMargin'></div></td></tr>";
            rows += "<tr class='phTable phTable-hide phTable-expandable phTable-addData'><td class='phTable phTable-noEdit' colspan='"+nbrColumns+"'>";
            rows += "<div class='phTable-tr-td-div'><div class='phTable-container-table-inside'><form id='phTable-addData' method='post' action=''><h3>Add new content</h3><input type='hidden' name='addData'><table class='phTable phTable-inside phTable-addData'>"+row+"</table> <input type='button' id='phTable-addNewData' class='phTable-addNewData' value='Add'></form></div>";
            rows += "<div class='phTable-tr-td-div-div'></div>";
            rows += "</div></td></tr>";

            return rows;
        },


        handleEvent: function(e, target){
            //console.log(e.type);
            if(e.type === 'submit'){ this.getData();}
            else if(e.type === 'click' || e.type === 'dblclick' || e.type === 'change'){
                if(target === 'tdButton'){
                    this.toggleEventExtraTable(e.target);
                }
                else if(target === 'thSort'){
                    this.sortByTableHeading(e.target);
                }
                else if(target === 'tableNav'){
                    this.sortByTableNav(e.target);
                }
                else if(target === 'dblClickTd'){
                    if($(e.target).prop('tagName') === 'TD' && !$(e.target).hasClass('phTable-tdButton')){
                        console.log(e.target);
                        this.getDataForTableCell(e.target);
                    }
                }
                else if(target === 'dblClickTdAddData'){
                    this.makeCellEditable();
                }
                 else if(target === 'deleteRow'){
                    this.deleteRow(e.target);
                }
            }
        },





        toggleEventExtraTable: function(target){
            var prototype = this;
            var index = $( target ).parent().index();
            var triangle = $( target ).find('div');
            if($( target ).parent().prop('tagName') === 'TD'){
                index = $( target ).parent().parent().index();
                triangle = target;
            }
            var display = $(this.tableID + ' tr').not('table.phTable-inside tr').slice( parseInt(index) + 1, parseInt(index) + 2).css('display');

            if(display === '' || display === 'none'){
                $(this.tableID + ' tr').not('table.phTable-inside tr').slice( parseInt(index) + 1, parseInt(index) + 2).removeClass('phTable-hide');
            }

            $(this.tableID + ' tr').not('table.phTable-inside tr').slice( parseInt(index) + 1, parseInt(index) + 2).find('td div.phTable-tr-td-div').slideToggle( "fast", function(){

                if(display === 'table-row'){
                    $(prototype.tableID + ' tr').not('table.phTable-inside tr').slice( parseInt(index) + 1, parseInt(index) + 2).addClass('phTable-hide');
                }

                if($(triangle).hasClass('phTable-triangleUp')){
                    $(triangle).removeClass('phTable-triangleUp');
                    $(triangle).addClass('phTable-triangleDown');
                }
                else if($(triangle).hasClass('phTable-triangleDown')){
                    $(triangle).removeClass('phTable-triangleDown');
                    $(triangle).addClass('phTable-triangleUp');
                }
                $(this).find('td div.phTable-inputDescriptionAddNewData').css({ top: -10-$(this).find('td div.phTable-inputDescriptionAddNewData').height() });
            });
        },

        resetTable: function(){
            $(this.tableID +' tbody tr:not(:eq(0),:eq(1))').remove();
            $(this.tableID +' tbody tr:eq(0) div.phTable-tableNav div.phTable-pageHitsContainer >').remove();
            $(this.tableID +' tbody tr:eq(0) div.phTable-tableNav div.phTable-pageNavContainer >').remove();

        },

        expandAllExtraTable: function(){
            $(this.tableID  + ' td.phTable-tdButton:not(.phTable-addData) div').removeClass('phTable-triangleDown');
            $(this.tableID  + ' td.phTable-tdButton:not(.phTable-addData) div').addClass('phTable-triangleUp');
            $(this.tableID  + ' tr:not(.phTable-addData)').not('table.phTable-inside tr').each(function(){
                $(this).removeClass('phTable-hide');
                $(this).find('td div.phTable-tr-td-div').show();
            });
        },



        sortByTableHeading: function(target){

            if(!$(target).hasClass('phTable-del')){
                //tar bort markering på alla utom den man klickar på
                $(this.tableID  + ' tbody tr:eq(1) th div').not($('div', target)).each(function(){
                    $(this).removeClass();
                });

                if(!$('div', target).hasClass( 'phTable-asc' ) && !$('div', target).hasClass( 'phTable-desc' )){
                    $('div', target).addClass('phTable-asc');
                    this.sortBy = '&orderby='+$(target).text().replace(" ", "_") + '&order=asc';
                }
                else if($('div', target).hasClass( 'phTable-asc' )){
                    $('div', target).removeClass( 'phTable-asc' ).addClass('phTable-desc');
                    this.sortBy = '&orderby='+$(target).text().replace(" ", "_") + '&order=desc';
                }
                else if($('div', target).hasClass( 'phTable-desc' )){
                    $('div', target).removeClass( 'phTable-desc' );
                    this.sortBy = '';
                }

                this.getData();
            }
        },


        tableNav: function() {
            var nav = "<tr class='phTable'><td colspan='"+this.nbrOfColumns+"' class='phTable phTable-noEdit'><div class='phTable-tableNav'>";
            nav += "<div class='phTable-pageHitsContainer'></div>";
            nav += "<div class='phTable-pageNavContainer'></div>";
            nav += "</div></td></tr>";
            $(nav).appendTo(this.tableID);
        },



        setContentToTableNav: function(hits, page, max){
            var maxx, minn= 1;
            var prototype = this;

            this.endPage = Math.ceil(parseFloat(max)/parseFloat(hits));

          function getHitsPerPage(hits) {
              var nbrHits = hits;
               var nav = "<label class='phTable-Select'>Hits:<select class='phTable-SelectHits' name='hits' placeholder='Hits'>";
              $.each(prototype.tableHits, function(index, value){
                  var selected = '';
                  var hitclass = '';
                  if(value === parseInt(nbrHits) || nbrHits === 'All'){
                    selected = 'selected';
                  }
                   nav += "<option value='"+value+"' "+selected+" >"+value+"</option>";

              });
                nav += " </select></label>";
                return nav;
            }
            $(getHitsPerPage(hits)).appendTo(this.tableID + ' .phTable-pageHitsContainer');

            function getPageNavigation(hits, page, maxx, min) {
                if(min === undefined){
                    min=1;
                }
              var nav = "<div><label id='phTable-pageFirst' class='phTable-pageNav phTable-pageEndLeft' title='First page'>&#8676;</label> ";
              nav += "<label id='phTable-pagePrev' class='phTable-pageNav phTable-pageLeft' title='Previous page'>&#10096;</label>";

              for(var i = min; i <= maxx; i++) {
                if(i === parseInt(page)){
                    nav += "<label class='phTable-pageNav phTable-pageNav-active'>"+i+"</label>";
                }
                else{
                    if(parseInt(page) < 3  && i <= 5 ){
                        nav += "<label class='phTable-pageNav' >"+i+"</label>";
                    }
                    else if( i > (parseInt(page) - 3) && i <= (parseInt(page) + 2) ){
                        nav += "<label class='phTable-pageNav' >"+i+"</label>";
                    }
                    if(i === maxx){
                        //nav += "<label class='pageNav' >"+i+"</label>";
                    }
                }
              }

                nav += "<label id='phTable-pageNext' class='phTable-pageNav phTable-pageRight' title='Next page'>&#10097;</label> ";
                nav += "<label id='phTable-pageLast' class='phTable-pageNav phTable-pageEndRight' title='Last page'>&#x21e5;</label></div>";
                nav += "<div class='phTable-pageNavInfo'><span>The search generated <span class='phTable-highLight'>"+max+"</span> hits over <span id='phTable-maxPages' class='phTable-highLight'>"+maxx+"</span> pages.</span></div>";

              return nav;
            }

            $(getPageNavigation(hits, page, this.endPage, minn)).appendTo(this.tableID + ' .phTable-pageNavContainer');
        },





        sortByTableNav: function(target){
            var prototype = this;
            if($(target).prop('tagName') === 'SELECT'){
                    prototype.hits = '&hits='+$(target).val();
                    prototype.getData();
            }
            if($(target).prop('tagName') === 'LABEL'){
                if($(target).hasClass('phTable-pageRight')){
                    prototype.page = (parseInt(prototype.page) + 1) > prototype.endPage ? prototype.endPage : (parseInt(prototype.page) + 1);
                }
                else if($(target).hasClass('phTable-pageLeft')){
                    prototype.page = (parseInt(prototype.page) - 1);
                }
                else if($(target).hasClass('phTable-pageEndLeft')){
                    prototype.page = 1;
                }
                else if($(target).hasClass('phTable-pageEndRight')){
                    prototype.page = $(target).parent().parent().find('.phTable-pageNavInfo span span#phTable-maxPages').text();
                }
                else{
                    prototype.page = $(target).text();
                }
                prototype.getData();
            }
        },



        getCellRowIdAndColumnNameFromTable: function(target){
            var id, column;
            if($(target).hasClass('phTable-inside')){
                //index of tr in main table
                var index = $(target).parent().parent().parent().parent().parent().parent().parent().index();
                column = $(target).parent().find('th').text();
                column = column.replace(' ', '_');
                id = $(table.tableID  + ' tbody tr:not("tr.phTable-inside"):eq('+(index - 2)+') td:eq(1)').text();
            }
            else{
                id = $(target).parent().find('td:eq(1)').text();
                //index of tr in main table
                var index = $(target).index();
                var arr = this.tableHeading[index-1].split('_');
                column = arr.join('_');
            }
            return {id: id, column: column};
        },



        makeCellEditable: function(target, data, id, column){
            var form = $("<form id='phTable-editTd' method='post' action=''><input type='hidden' name='updateCellData' value='true'><input type='hidden' name='id' value='"+id+"'><input type='hidden' name='column' value='"+column+"'><input type='text' class='phTable phTable-forCellEdit' name='content' value='"+data+"'></form>");
            form.appendTo(target);
            /*
            $('#editTd textarea.forCellEdit').height($('#editTd input.forCellEdit').prop("scrollHeight"));
            */
            this.setInputRequierment();
        },





        setInputRequierment: function(addNewData){
            var prototype = this;
            if(addNewData){
                $('#phTable-addData table tbody tr').each(function(){
                    var columnName = $(this).find('th').text().replace(' ', '_');
                    if(prototype.validationData[columnName] !== undefined && prototype.validationData[columnName].description !== undefined){
                        var div = $("<div class='phTable-inputDescriptionAddNewData'><p>"+prototype.validationData[columnName].description+"</p></div>");
                        div.prependTo($(this).find('td'));

                    }
                })
            }
            else{
                var inside = $('#phTable-editTd').parent().hasClass('phTable-inside');
                var columnName;
                //kollar om man klickar på en cell som är tillåten att ändra
                if(inside){
                    columnName = $('#phTable-editTd').parent().siblings('th').text();
                }
                else{
                    columnName = $(this.tableID + ' th:not(.phTable-inside)').eq($('#phTable-editTd').parent().index()).text();
                }
                columnName = columnName.replace(" ", "_");
                if(prototype.validationData[columnName] !== undefined && prototype.validationData[columnName].description !== undefined)
                {
                    var div = $("<div id='phTable-inputDescription'><p>"+this.validationData[columnName].description+"</p></div>");
                    div.prependTo('#phTable-editTd');
                }

                $('#phTable-inputDescription').css({ top: -10-$('#phTable-inputDescription').height() });
            }
        },


        validateAllAddNewDataValues: function(){
            var returnValue = true;
            $('#phTable-addData table tbody tr').each(function( index, value ) {
                var th = $(this).find('th').text();
                var value = $(this).find('input').val();
                var element = $(this).find('input');
                if(!table.validateInputValue({element: element, value: value, columnName: th.replace(" ", "_")})){
                    returnValue = false;
                    return false;
                }

            });
            return returnValue;
        },


        validateInputValue: function(target) {
            var returnValue = true;
            if(target){
                if (this.validationData[target.columnName] !== undefined) {
                    if(target.value.trim() === '' && this.validationData[target.columnName].nullAble){
                        target.element.removeClass('phTable-error');
                    }
                    else if(this.validationData[target.columnName].regex !== "undefined") {
                        var regex = new RegExp(this.validationData[target.columnName].regex);
                        if(regex.test(target.value.trim())){
                            target.element.removeClass('phTable-error');
                        }
                        else{
                            target.element.addClass('phTable-error');
                            returnValue = false;
                        }
                    }
                    else if(this.validationData[target.columnName].valuesMultiple !== undefined){
                        var values = target.value.split(",");
                        var valueOK = false;
                        var self = this;
                        $.each(values, function( index, value ) {
                            if(self.validationData[target.columnName].valuesMultiple.indexOf(value) === -1){
                                valueOK = false;
                                return false;
                            }
                            else{
                                valueOK = true;
                            }

                        });
                        if(valueOK){
                            target.element.removeClass('phTable-error');
                        }
                        else{
                            target.element.addClass('phTable-error');
                            returnValue = false;
                        }
                    }
                    else{
                        if(this.validationData[target.columnName].values.indexOf(target.value.trim()) > -1){
                            target.element.removeClass('phTable-error');
                        }
                        else{
                            target.element.addClass('phTable-error');
                            returnValue = false;
                        }
                    }
                }

            }
            else{
                var inputValue = $('#phTable-editTd input.phTable-forCellEdit').val().trim();
                var inside = $('#phTable-editTd').parent().hasClass('phTable-inside');
                var columnName;
                //kollar om man klickar på en cell som är tillåten att ändra
                if(inside){
                    columnName = $('#phTable-editTd').parent().siblings('th').text();
                }
                else{
                    columnName = $(this.tableID +' tr th:not(.phTable-inside)').eq($('#phTable-editTd').parent().index()).text();
                }

                if(this.noEditColumnName.indexOf( columnName ) < 0 ){
                    columnName = columnName.replace(" ", "_");
                    if (this.validationData[columnName] !== undefined) {
                        if( inputValue === '' && this.validationData[columnName].nullAble){
                            console.log('inne');
                            $('#phTable-inputError').remove();
                            this.commitCellEdit();
                        }
                        else if (this.validationData[columnName].regex !== undefined) {
                            var regex = new RegExp(this.validationData[columnName].regex);
                            if(regex.test(inputValue.trim())){
                                $('#phTable-inputError').remove();
                                this.commitCellEdit();
                            }
                            else{
                                $('#phTable-inputDescription').addClass('phTable-error');
                            }
                        }
                        else if(this.validationData[columnName].valuesMultiple !== undefined){
                            var values = inputValue.split(",");
                            var valueOK = false;
                            var self = this;
                            $.each(values, function( index, value ) {
                                if(self.validationData[columnName].valuesMultiple.indexOf(value) === -1){
                                    valueOK = false;
                                    return false;
                                }
                                else{
                                    valueOK = true;
                                }
                            });
                            if(valueOK){
                                $('#phTable-inputError').remove();
                                this.commitCellEdit();
                            }
                            else{
                                $('#phTable-inputDescription').addClass('phTable-error');
                            }
                        }
                        else{
                            if(this.validationData[columnName].values.indexOf(inputValue.trim()) > -1){
                                $('#phTable-inputError').remove();
                                this.commitCellEdit();
                            }
                            else{
                                $('#phTable-inputDescription').addClass('phTable-error');
                            }
                        }
                    }
                    else{
                        this.commitCellEdit();
                    }
                }
            }
            return returnValue;
        }
    }

    var table = new phTable(o);

    //ny sökning
    $('#'+ $(this).attr('id')).submit(function(e){
        e.preventDefault();
        table.handleEvent(e);
    });



    //expandera tabellrad
    $(document).on ("click", table.tableID  + ' td.phTable-tdButton', function (e) {
        table.handleEvent(e, 'tdButton');
    });



    //sortera per th
    $(document).on ("click", table.tableID  + ' tbody tr:eq(1) th', function (e) {
        table.handleEvent(e, 'thSort');
    });


    //editera cell
    $(document).on ("dblclick", table.tableID  + ' tbody tr:not(:eq(0), :eq(1), :last) td, .phTable-addData td', function (e) {
        e.stopPropagation();
        var target = $(e.target);
        //kollar om man klickar på en cell som är tillåten att ändra
        if(target.hasClass('phTable-inside')){
            if(table.noEditColumnName.indexOf( target.siblings('th').text() ) < 0 ){
                table.handleEvent(e, 'dblClickTd');
            }
        }
        else{
            if(table.noEditColumnName.indexOf( $(table.tableID + ' tr:eq(1) th').eq(target.index()).text() ) < 0 ){
                table.handleEvent(e, 'dblClickTd');
            }
        }
    });



    //tar bort textarean om man klickar utanför
    $(document).on ("click", function (e) {
        e.stopPropagation();
        if(!$(e.target).hasClass('phTable-forCellEdit')){
            $('#phTable-editTd').remove();
        }
    });


    //Om enter utan shift trycks ska ändringen ske
    $(document).on('keydown', '#phTable-editTd .phTable-forCellEdit', function(e) {
        if (e.which == 13 ) {
            e.preventDefault();
            console.log('enter');
            table.validateInputValue();
            //table.commitCellEdit();
        }
    });


    //Gömmer informationen gällande det accepterande för kolumnen vid skapande av en ny rad
    $(document).on('blur', '#phTable-addData table tbody tr td input', function(e) {
        e.stopPropagation();
        table.validateInputValue({element: $(this),
                              value: $(this).val(),
                              columnName: $(this).parent().parent().find('th').text().replace(" ", "_")});
        $(this).parent().find('.phTable-inputDescriptionAddNewData').hide();

    });

    //Visa informationen gällande det accepterande för kolumnen vid skapande av en ny rad
    $(document).on('focus', '#phTable-addData table tbody tr td input', function(e) {
        e.stopPropagation();
        $(this).parent().find('.phTable-inputDescriptionAddNewData').show();

    });


    $(document).on ("click", table.tableID  + ' #phTable-addNewData', function (e) {
        e.stopPropagation();
        var ok = table.validateAllAddNewDataValues();
        if(ok){
            table.addNewData();
        }
    });



    $(document).on ("click", table.tableID  + ' .phTable-DeleteButton', function (e) {
        e.stopPropagation();
        table.handleEvent(e, 'deleteRow');
    });



    $(document).on ("change", table.tableID  + ' tbody tr td div.phTable-tableNav div.phTable-pageHitsContainer select.phTable-SelectHits', function (e) {
        table.handleEvent(e, 'tableNav');
    });

    $(document).on ("click", table.tableID  + ' tbody tr td div.phTable-tableNav div.phTable-pageNavContainer label', function (e) {
        table.handleEvent(e, 'tableNav');
    });



};
