<?php
$db = sqlite_open('db/schema.sqlite');
print_r(sqlite_query($db, 'select * from tasks'));

function sqlite_open($location)
{
    $handle = new SQLite3($location);
    return $handle;
}

function sqlite_query($dbhandle, $query)
{
 /*   $array['dbhandle'] = $dbhandle;
    $array['query'] = $query;*/
    $result = $dbhandle->query($query);
    $data = array();
    while ($row = $result->fetchArray()) {
        $data[] = $row;
    }
    return $data;
}

function sqlite_fetch_array(&$result)
{
    #Get Columns
    $i = 0;
    while ($result->columnName($i)) {
        $columns[] = $result->columnName($i);
        $i++;
    }

    $resx = $result->fetchArray(SQLITE3_ASSOC);
    return $resx;
}

//phpinfo();
?>