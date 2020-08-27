<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['detailed-button']))
    {

        if (isset($_POST['age'])) $age = htmlspecialchars($_POST['age']); else $age = null;
        $in_array_age = array(1, 2, 3, 4);
        $min_age = array(0, 1, 12, 48, 96);
        $max_age = array(0, 11, 36, 84, 240);

        
        if (isset($_POST['tags'])) $tag = htmlspecialchars($_POST['tags']); else $tag = null;
        $in_array_tag = array('joueur', 'energique', 'gourmand', 'calineur', 'protecteur', 'obeissant', 'paresseux', 'casanier');

        
        if (isset($_POST['cities'])) $city = htmlspecialchars($_POST['cities']); else $city = null;

        if (isset($_POST['breed'])) $breed_selected = htmlspecialchars($_POST['breed']); else $breed_selected = null;

        
        if (isset($_POST['likes'])) $popularity = htmlspecialchars($_POST['likes']); else $popularity = null;

        $in_array_popularity = array(1, 2, 3, 4);
        $min_popularity = array(0, 1, 100, 250, 500);
        $max_popularity = array(0, 100, 250, 500, 2147483647);


        if (isset($_POST['sort_by'])) $type_form = htmlspecialchars($_POST['sort_by']); else $type_form = null;

        $in_array_type_form = array('sort_age', 'sort_distance', 'sort_popularity', 'sort_tags');

        $latitude_sort = $_SESSION['latitude'];
        $longitude_sort = $_SESSION['longitude'];



        if (isset($age) && !empty($age) && in_array($age, $in_array_age))
        {
            $admins = get_id_user_age($admins, $min_age[$age], $max_age[$age]);
        }
        if (isset($breed_selected) && !empty($breed_selected) && array_key_exists($breed_selected, $new_breeds_search))
        {
            $admins = get_id_user_breed($admins, $breed_selected);
        }
        if (isset($tag) && !empty($tag) && in_array($tag, $in_array_tag))
        {
            $admins = get_id_user_tag($admins, $tag);
        }
        if (isset($city) && !empty($city) && $city !== 0 && in_array($city, $cities))
        {
            $admins = get_id_user_city($admins, $city);
        }
        if (isset($popularity) && !empty($popularity) && in_array($popularity, $in_array_popularity))
        {
            $admins = get_id_user_popularity($admins, $min_popularity[$popularity], $max_popularity[$popularity]);
        }
        if (isset($type_form) && !empty($type_form) && in_array($type_form, $in_array_type_form))
        {
            $admins = get_array_sorted_by($admins, $type_form, $latitude_sort, $longitude_sort, $hashtag1, $hashtag2, $hashtag3);
        }
    }
}