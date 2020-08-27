let div = document.getElementById('search');
let div1 = document.getElementById('filter_search');


function show_search()
{

    if (div1.style.display == "none")
    {
        if (div.style.display == "none")
        {
            div.style.display = "block";
        }
        else
        {
            div.style.display = "none";
        }
    }
    else
    {
        div1.style.display = "none";
        if (div.style.display == "none")
        {
            div.style.display = "block";
        }
    }
}

function show_filter()
{
    if (div.style.display == "none")
    {
        if (div1.style.display=="none")
        {
            div1.style.display = "block";
        }
        else
        {
            div1.style.display = "none";
        }
    }
    else
    {
        div.style.display = "none";
        if (div1.style.display=="none")
        {
            div1.style.display = "block";
        }
      
    }
}