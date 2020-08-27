<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['sorted-button']))
    {
        $type_form = htmlspecialchars($_POST['suggestion-sorted']);
        
        $in_array_type_form = array('sort_age', 'sort_distance', 'sort_popularity', 'sort_tags');

        $latitude_sort = $_SESSION['latitude'];
        $longitude_sort = $_SESSION['longitude'];
        
        
        if (isset($type_form) && !empty($type_form) && in_array($type_form, $in_array_type_form))
        {
            $admins = get_array_sorted_by($admins, $type_form, $latitude_sort, $longitude_sort, $hashtag1, $hashtag2, $hashtag3);
        }
    }
}

?>