<?php

require 'Model/Database/db_connection.php';
require 'vendor/autoload.php';

use Faker\Factory;


$bdd = db_connection();
$faker  =  Factory::create('fr_FR');

function generate_pictures($breed)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL            => "https://dog.ceo/api/breed/".$breed."/images/random",
        CURLOPT_HEADER         => 0,
        CURLOPT_RETURNTRANSFER => 1
    ]);

    $data = curl_exec($curl);

    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200)
    {
        if ($data === false) 
        {
            return false;
        }
        else 
        {
            $data = json_decode($data,  true);
            return $data['message'];;
        }
    }
    else
    {
        return -1;
    }

curl_close($curl);

}

$dogsBreed = 
[
    "affenpinscher" => "Affenpinscher", "african" => "Africain", "airedale" => "Airedale Terrier", "akita" => "Akita Inu", 
    "appenzeller" => "Bouvier de l’Appenzell", "australian" => "Berger australien", "basenji" => "Asenji", "beagle" => "Beagle",
    "bluetick" => "Bluetick coonhound", "borzoi" => "Barzoï", "bouvier" => "Bouvier des Flandres", "boxer" => "Boxer",
    "brabancon" => "Petit brabançon", "briard" => "Berger de Brie", "buhund" => "Buhund norvégien", "bulldog" => "Bouledogue",
    "bullterrier" => "Bull Terrier", "cairn" => "Cairn terrier", "cattledog" => "Bouvier australien", "chihuahua" => "Chihuahua",
    "chow" => "Chow-chow", "clumber" => "Clumber Spaniel", "cockapoo" => "Cockapoo", "collie" => "Colley à poil long",
    "coonhound" => "Coonhound", "corgi" => "Welsh Corgi Pembroke", "cotondetulear" => "Coton de Tuléar", "dachshund" => "Teckel",
    "dalmatian" => "Dalmatien", "dane" => "Dogue allemand", "deerhound" => "Deerhound", "dhole" => "Dhole", "dingo" => "Dingo",
    "doberman" => "Dobermann", "elkhound" => "Chien d\'élan norvégien gris", "entlebucher" => "Bouvier de l\'Entlebuch",
    "eskimo" => "Esquimau américain", "finnish" => "Chien finnois de Laponie", "frise" => "Bichon à poil frisé", 
    "germanshepherd" => "Berger allemand", "greyhound" => "Lévrier greyhound", "groenendael" => "Berger belge Groenendael",
    "havanese" => "Bichon havanais", "hound" => "Basset hound", "husky" => "Husky de Sibérie", "keeshond" => "Spitz Loup",
    "kelpie" => "Australian Kelpie", "komondor" => "Komondor", "kuvasz" => "Kuvasz", "labrador" => "Labrador retriever",
    "leonberg" => "Leonberg", "lhasa" => "Lhassa Apso", "malamute" => "Malamute de l\'Alaska", "malinois" => "Malinois",
    "maltese" => "Bichon maltais", "mastiff" => "Mastiff", "mexicanhairless" => "Nu Mexicain", "mix" => "Croisé",
    "mountain" => "Bouvier bernois", "newfoundland" => "Terre-neuve", "otterhound" => "Loutre", "ovcharka" => "Berger du Caucase",
    "papillon" => "Epagneul nain continental papillon", "pekinese" => "Pékinois", "pembroke" => "Welsh Corgi Pembroke",
    "pinscher" => "Pinscher nain", "pitbull" => "Pitbull", "pointer" => "Pointer anglais", "pomeranian" => "Spitz nain",
    "poodle" => "Caniche", "pug" => "Carlin", "puggle" => "Puggle", "pyrenees" => "Chien de montagne des Pyrénées",
    "redbone" => "Redbone Coonhound", "retriever" => "Retriever", "ridgeback" => "Rhodesian ridgeback",
    "rottweiler" => "Rottweiler", "saluki" => "Lévrier persan", "samoyed" => "Samoyède", "schipperke" => "Schipperke",
    "schnauzer" => "Schnauzer nain", "setter" => "Setter Llewellin", "sheepdog" => "Bobtail", "shiba" => "Shiba",
    "shihtzu" => "Shih tzu", "spaniel" => "Cocker spaniel anglais", "springer" => "Springer anglais", "stbernard" => "Saint-Bernard", 
    "terrier" => "Terrier", "vizsla" => "Vizsla", "waterdog" => "Chien d\'eau portugais", "weimaraner" => "Braque de Weimar",
    "whippet" => "Lévrier whippet", "wolfhound" => "Irish wolfhound"
];

$passworduser = hash('sha256', 'testtest');

$genders = array('male', 'female');

$orientations = array(
    0 => 'other_breed',
    1 => 'same_breed',
    2 => 'all_breed'
);

$dogs_name = 
array(

    'loulou', 'milou', 'scoobidou', 'choupette', 'Baqui', 'José', 'Fibi', 'Flynn', 'Gummy', 'Hazia', 
    'Juna', 'Java', 'Laki', 'Maca', 'Mika', 'Nada', 'Nix', 'Nokia', 'Anfield', 'Angel', 'Angie', 'Angus',
    'Anis', 'Anka', 'Apple', 'Aqua', 'Archi', 'Ares', 'Argos', 'Sacha', 'Sethi', 'Sparta', 'Taz', 'Titi', 
    'Tosca', 'Touki', 'Twist', 'Vani', 'Black', 'Blake', 'Blog', 'Blue', 'Bo', 'Bob', 'Bolt', 'Bones', 
    'Boss', 'Boule', 'Braun', 'Brook', 'Bruce', 'Brume', 'Diégo', 'Diesel', 'Dino', 'Dipsy', 'Diva', 'Divine', 
    'Dixie', 'Django', 'Djorka', 'Dodue', 'Doggy', 'Doky', 'Doline', 'Dolly', 'Donald', 'Donuts', 'Doodle', 
    'Dora', 'Doubi', 'Douchka', 'Douglas', 'Elvis', 'Elza', 'Email', 'Émile', 'Emir', 'Emmett', 'Emmy', 'Énigme',
    'Enjoy', 'Enzo', 'Épice', 'Érable', 'Ermann', 'Falcon', 'Fanfan', 'Ficelle', 'Fidji', 'Fila', 'Filat', 'Filou',
    'Fiona', 'Flacky', 'Folie', 'Foly', 'Fonzy', 'Forrest', 'Foster', 'Foufi', 'Foufie', 'Foufou', 'Foxy', 'Fuji', 
    'Funky', 'Gaufre', 'Gee', 'Geek', 'Georges', 'Ghost', 'Gibbs', 'Gift', 'Gin', 'Gips', 'Glace', 'Glee', 'Glenn', 
    'Gold', 'Goo', 'Grace', 'Gray', 'Green', 'Greg', 'Grim', 'Grinch', 'Hulk', 'Harley', 'Harper', 'Hank', 'Henry', 
    'Hawk', 'Hawaï', 'Husky', 'Helper', 'Honey', 'Happy', 'Hudson', 'Harlow', 'Hercules', 'Hamilton', 'Hipster', 
    'Hunter', 'Hudson', 'Iggins', 'Igloo', 'Ignace', 'Igor', 'Ika', 'Ikea', 'Iki', 'Iliane', 'Ilou', 'Image', 'Impact',
    'Ina', 'India', 'Indian', 'Indien', 'Indo', 'Indra', 'Indy', 'Ines', 'Ingall', 'Injy', 'Inka', 'Inky', 'Inna', 'Inos', 
    'Inouk', 'Inox', 'Into', 'Inzo', 'Ioda', 'Iodie', 'Ioko', 'Iola', 'Iona', 'Ionie', 'Ionix', 'Ioshi', 'Iota', 'Iouka', 
    'Iouki', 'Iowa', 'Ipad', 'Iphone', 'Ipnos', 'Ipod', 'Ippy', 'Ipso', 'Ipsy', 'Irane', 'Iris', 'Irish', 'Irka', 'Irlande', 
    'Irma', 'Irnos', 'Iroise', 'Iron', 'Irus', 'Irvin', 'Irya', 'Isak', 'Isie', 'Isis', 'Iska', 'Isko','Cacao', 'Cachou', 
    'Café', 'Carbone', 'Caviar', 'Charbon', 'Chita', 'Choco', 'Daffy', 'Darko', 'Domino', 'Drakkar', 'Ébene', 'Éclipse', 
    'Énigme', 'Gadou', 'Gadoue', 'Ganache', 'Gangster', 'Gasoil', 'Godzilla', 'Gondole', 'Gothic', 'Grim', 'Hercule', 
    'Hitchcock', 'Horror', 'Mad', 'Maki', 'Mikado', 'Mistik', 'Manhattan', 'Memphis', 'Miami', 'Milan', 
    'Munich', 'Nebraska', 'Nevada', 'Ontario', 'Oslo', 'Pékin', 'Phoénix', 'Québec', 'Sahara', 'Savane', 'Shangaï', 
    'Sidney', 'Soho', 'Tahiti', 'Tennessee', 'Texane', 'Texas', 'Tibet', 'Tokyo', 'Ice', 'Iceberg', 'Igloo', 'Jonquille', 
    'Jungle', 'Lilas', 'Maple', 'Montagne', 'Mountain', 'Muget', 'Neige', 'Printemps', 'Rivière', 'Soleil', 'Spring', 
    'Summer', 'Sunny', 'Tornade', 'Toundra', 'Volcano', 'Winter'
);

$dogs_name_count = 0;

$tag_options_1 =
array(
    array(
        'obéissant',
        'énergique',
        'gourmand'
    ),
    array(
        'câlineur',
        'protecteur',
        'joueur'
    ),
    array(
        'paresseux',
        'casanier'
    )
);

$tag_options_2 =
array(
    array(
        'joueur',
        'casanier',
        'câlineur',
    ),
    array(
        'énergique',
        'paresseux',
        'gourmand'
    ),
    array(
        'protecteur',
        'obéissant'
    )
);

$tag_options_3 =
array(
    array(
        'gourmand',
        'paresseux',
        'joueur',
    ),
    array(
        'protecteur',
        'casanier',
        'obéissant'
    ),
    array(
        'énergique',
        'câlineur'
    )
);

$ages = 
array(
    array(1,2,3,4,5,6,7,8,9,10,11,12,24,36,48,60,72),
    array(84,96,108,120,132,144,156,168,180,192,204,216,228,240)
);
    
$country = "france";
$tag_array_random = 1;
$age_count = 0;
$id_user = 1;

$likes_array = 
array(
    mt_rand(0, 100),
    mt_rand(100, 250),
    mt_rand(250, 500),
    mt_rand(500, 600)
);



for($i = 0; $i < 1/*15*/ ; $i++)
{
    foreach($dogsBreed as $dogsBreedEn => $dogsBreedFr)
    {
        // Insertion d'un utilisateur dans la table users
        $gender = $genders[mt_rand(0, 1)];
        $orientation = $orientations[mt_rand(0, 2)];
        $firstname = $faker->firstName;

        if ($dogs_name_count >= sizeof($dogs_name)) $dogs_name_count = 0;
        $dog_name = $dogs_name[$dogs_name_count];
        $dogs_name_count++;

        $picture_path = generate_pictures($dogsBreedEn);
        $online = mt_rand(0, 1);

        $age_count++;
        if ($age_count === 4) {
            $age = $ages[1][mt_rand(0,13)];
            $age_count = 0;
        } else $age = $ages[0][mt_rand(0,16)];





        $bdd->exec("INSERT INTO users SET
        lastname = '{$faker->lastName}', 
        firstname = '$firstname',
        username = '$firstname',
        email = '{$faker->email}',
        password_user = '$passworduser',
        key_email = '{$faker->md5}',
        key_password_reset = '{$faker->md5}',
        active_account = '2',
        profile_picture_path = '$picture_path',
        gender = '$gender',
        orientation = '$orientation',
        dog_name = '$dog_name',
        age = '$age',
        breed = '$dogsBreedEn',
        address = '{$faker->address}',
        city = '{$faker->city}',
        country = '$country',
        zipcode = '{$faker->postcode}',
        biography = '{$faker->text($maxNbChars = 250)}',
        logout_date = '{$faker->date} {$faker->time}',
        latitude = '{$faker->latitude}',
        longitude = '{$faker->longitude}',
        online = '$online'
        ");
    
    


        // Insertion de 3 Tags pour chaque user
        $tag_array_random++;
        if ($tag_array_random === 4) $tag_array_random = 1;
        for ($tag_count = 0; $tag_count < 3; $tag_count++)
        {
            if ($tag_array_random === 1) $tag = $tag_options_1;
            else if ($tag_array_random === 2) $tag = $tag_options_2;
            else $tag = $tag_options_3;
    
            if ($tag_count == 2) $tag_insert = $tag[$tag_count][mt_rand(0, 1)];
            else $tag_insert = $tag[$tag_count][mt_rand(0, 2)];




    
            $bdd->exec("INSERT INTO tags SET
            id_user = '$id_user', 
            tag = '$tag_insert'
            ");



        }
    
    
        // Insertion des likes
        $like_number = $likes_array[mt_rand(0, 3)];
        
        for ($like_count = 0; $like_count < $like_number; $like_count++)
        {
            $id_liker = mt_rand(1, 751);
            if ($id_liker === $id_user) $id_liker += 1;



    
            $bdd->exec("INSERT INTO likes SET
                id_liker = '$id_liker', 
                id_liked = '$id_user'
            ");
        }



        $id_user++;
    }
}
echo "Fake seeds have been made.";
die();

?>