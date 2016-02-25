$(document).ready(function(){

    $('#txtKeyword').keypress(function (e) {
        if (e.which == 13) {
            searchProduct();

            return false;    //<---- Add this line
        }
    });
});

function processLongCharacter(str, number)
{
    if (str.toLowerCase() == "rectangular")
        return "Rec -tangular";
    else if (str.toLowerCase() == "multicolored")
        return "Multi -colored";
    else if (str.toLowerCase() == "dimensional")
        return "Dimen -sional";

    var chars = str.split(" ");

    if (chars.length > 0)
    {
        if (chars[0].length > number)
        {
            var tmp = chars[0].substring(0,number) + ' -' + chars[0].substring(number);

            if (chars.length == 1)
                return tmp;
            else
            {
                for (var i=1 ; i < chars.length; i++ )
                    tmp = tmp + ' ' + chars[i];

                return tmp;
            }
        }
    }
    else
        return str;
}

var categoryFilter = 0;
var glazeFilter = [];
var patternFilter = [];
var colorFilter = [];
var sizeFilter = [];
var keyword = '';

function toogleFilterCategory(obj, val)
{
    keyword = '';

    if ($(obj).hasClass('active'))
    {
        $(obj).removeClass('active');

        $('.categoryFilter').show();

        // Remove filter here
        removeFilter(1, val);
    }
    else
    {
        $('.categoryFilter').hide();
        $(obj).addClass('active');
        $(obj).show();

        // Add filter here
        addFilter(1, val);
    }
}

function toogleFilter(obj, type, val)
{
    keyword = '';

    if($(obj).is(':checked'))
        addFilter(type, val);
    else
        removeFilter(type, val);

}


function addFilter(type, val)
{
    if (type == 1)
        categoryFilter = val;
    else if (type == 2) // glaze
    {
        glazeFilter.push(val);
        glazeFilter.sort();
    }
    else if (type == 3) // pattern
    {
        patternFilter.push(val);
        patternFilter.sort();
    }
    else if (type == 4) // color
    {
        colorFilter.push(val);
        colorFilter.sort();
    }
    else //size
    {
        sizeFilter.push(val);
        sizeFilter.sort();
    }

    applyFilter();
}


function removeFilter(type, val)
{
    if (type == 1)
        categoryFilter = 0;
    else if (type == 2) // glaze
    {
        for (var i=0; i < glazeFilter.length; i++)
            if (glazeFilter[i] == val)
            {
                glazeFilter.splice(i,1);
                break;
            }
        glazeFilter.sort();
    }
    else if (type == 3) // pattern
    {
        for (var i=0; i < patternFilter.length; i++)
            if (patternFilter[i] == val)
            {
                patternFilter.splice(i,1);
                break;
            }

        patternFilter.sort();
    }
    else if (type == 4) // color
    {
        for (var i=0; i < colorFilter.length; i++)
            if (colorFilter[i] == val)
            {
                colorFilter.splice(i,1);
                break;
            }

        colorFilter.sort();
    }
    else //size
    {
        for (var i=0; i < sizeFilter.length; i++)
            if (sizeFilter[i] == val)
            {
                sizeFilter.splice(i,1);
                break;
            }

        sizeFilter.sort();
    }

    applyFilter();
}


function applyFilter()
{
    $('#pleaseWaitDialog').modal('show');

    try
    {
        var results = [];
        results = results.concat(data);

        // Apply keyword
        keyword = jQuery.trim(keyword.toLowerCase());
        if (keyword.length > 0)
            for (var i = 0; i < results.length; i++)
                if (!(results[i].name.toLowerCase().indexOf(keyword) >= 0 || results[i].code.toLowerCase().indexOf(keyword)))
                {
                    results.splice(i,1);
                    i = i-1;
                }

        // Apply category
        if (categoryFilter > 0)
            for (var i = 0; i < results.length; i++)
                if (results[i].category.id != categoryFilter)
                {
                    results.splice(i,1);
                    i = i-1;
                }


        var acceptValue = [];
        for(var i = 0; i < results.length; i++)
            acceptValue.push(results[i].id);



        if (glazeFilter.length > 0 || patternFilter.length > 0 || colorFilter.length > 0 || sizeFilter.length > 0)
        {
            var tmp;
            var isAccept;

            if (glazeFilter.length > 0)
            {
                tmp = [];
                for (var i=0; i < glazeFilter.length; i++)
                    for (var j=0; j < dataProductGlaze.length; j++ )
                    {
                        if (dataProductGlaze[j].glaze_id == glazeFilter[i])
                        {
                            tmp.push(dataProductGlaze[j].product_id);
                        }

                        if (dataProductGlaze[j].glaze_id > glazeFilter[i])
                            break;
                    }

                tmp.sort();
                for (var i=1; i < tmp.length; i++)
                    if (tmp[i] == tmp[i-1])
                    {
                        tmp.splice(i,1);
                        i = i -1;
                    }

                for (var i=0; i < acceptValue.length; i++)
                {
                    isAccept = false;

                    for (var j = 0; j < tmp.length; j++)
                        if (tmp[j] == acceptValue[i])
                        {
                            isAccept = true;
                            break;
                        }

                    if (!isAccept)
                    {
                        acceptValue.splice(i,1);
                        i = i -1;
                    }

                }
            }


            if (patternFilter.length > 0)
            {
                tmp = [];
                for (var i=0; i < patternFilter.length; i++)
                    for (var j=0; j < dataProductPattern.length; j++ )
                    {
                        if (dataProductPattern[j].pattern_id == patternFilter[i])
                        {
                            tmp.push(dataProductPattern[j].product_id);
                        }

                        if (dataProductPattern[j].pattern_id > patternFilter[i])
                            break;
                    }

                tmp.sort();
                for (var i=1; i < tmp.length; i++)
                    if (tmp[i] == tmp[i-1])
                    {
                        tmp.splice(i,1);
                        i = i -1;
                    }

                for (var i=0; i < acceptValue.length; i++)
                {
                    isAccept = false;

                    for (var j = 0; j < tmp.length; j++)
                        if (tmp[j] == acceptValue[i])
                        {
                            isAccept = true;
                            break;
                        }

                    if (!isAccept)
                    {
                        acceptValue.splice(i,1);
                        i = i -1;
                    }

                }
            }


            if (colorFilter.length > 0)
            {
                tmp = [];
                for (var i=0; i < colorFilter.length; i++)
                    for (var j=0; j < dataProductColor.length; j++ )
                    {
                        if (dataProductColor[j].color_id == colorFilter[i])
                        {
                            tmp.push(dataProductColor[j].product_id);
                        }

                        if (dataProductColor[j].color_id > colorFilter[i])
                            break;
                    }

                tmp.sort();
                for (var i=1; i < tmp.length; i++)
                    if (tmp[i] == tmp[i-1])
                    {
                        tmp.splice(i,1);
                        i = i -1;
                    }

                for (var i=0; i < acceptValue.length; i++)
                {
                    isAccept = false;

                    for (var j = 0; j < tmp.length; j++)
                        if (tmp[j] == acceptValue[i])
                        {
                            isAccept = true;
                            break;
                        }

                    if (!isAccept)
                    {
                        acceptValue.splice(i,1);
                        i = i -1;
                    }

                }
            }


            if (sizeFilter.length > 0)
            {
                tmp = [];
                for (var i=0; i < sizeFilter.length; i++)
                    for (var j=0; j < dataProductSize.length; j++ )
                    {
                        if (dataProductSize[j].size_id == sizeFilter[i])
                        {
                            tmp.push(dataProductSize[j].product_id);
                        }

                        if (dataProductSize[j].size_id > sizeFilter[i])
                            break;
                    }

                tmp.sort();
                for (var i=1; i < tmp.length; i++)
                    if (tmp[i] == tmp[i-1])
                    {
                        tmp.splice(i,1);
                        i = i -1;
                    }

                for (var i=0; i < acceptValue.length; i++)
                {
                    isAccept = false;

                    for (var j = 0; j < tmp.length; j++)
                        if (tmp[j] == acceptValue[i])
                        {
                            isAccept = true;
                            break;
                        }

                    if (!isAccept)
                    {
                        acceptValue.splice(i,1);
                        i = i -1;
                    }

                }
            }

        }

        // show/hide results
        $('.product-item').hide();
        for(var i = 0; i < acceptValue.length; i++)
            $('#productItem' + acceptValue[i].toString()).show();
    }
    catch(ex){}


    $('#pleaseWaitDialog').modal('hide');
}
