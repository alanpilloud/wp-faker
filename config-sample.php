<?php

class MyConfig extends Config
{
    public function __construct()
    {
        $faker = Faker\Factory::create('en_GB');

        $this->post_type = 'post';
        $this->post_title = $faker->name();
        $this->post_content = $faker->text();
        $this->post_thumbnail = $faker->image(UPLOAD_DIR,1200,900,'city',false);

        /**
         * Acf values are specified in an array of key/values, the key being the name of the field
         */
        $this->acf_values = array(
            'text' => $faker->text()
        );
    }
}
