<?php
return [
    'test' => "SELECT * FROM user WHERE id= #id",
    'select_gender' => "SELECT * FROM user WHERE gender= '#gender'",
    'update_age_by_id' => "UPDATE user SET age='#age' WHERE id=#id"
];