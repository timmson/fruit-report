function createXmlHttpRequestObject(){
    if(window.ActiveXObject)
    {
        try
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (e)
        {
            xmlHttp = false;
        }
    }
    else
    {
        try
        {
            xmlHttp = new XMLHttpRequest();
                

        }
        catch (e)
        {
            xmlHttp = false;
        }
    }
    if (!xmlHttp)
        alert("Error creating the XMLHttpRequest object.");
    else
        return xmlHttp;
}

function process(){
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0) {
        xmlHttp.open("POST", response_script, true);
        xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlHttp.onreadystatechange = handleServerResponse;
        xmlHttp.send(post_vars);
    }
    else {
        setTimeout('process()', 1000);
    }
}

function handleServerResponse(){
    if (xmlHttp.readyState == 4)
    {
        if (xmlHttp.status == 200)
        {
            response(xmlHttp.responseText);
        }
        else
        {
            alert("There was a problem accessing the server: " + xmlHttp.statusText);
        }
    }
}