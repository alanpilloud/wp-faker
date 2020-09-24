# WP-Faker IS UNMAINTAINED

_Unfortunately, WP-Faker is unmaintenained and may not work anymore._

WP-Faker is a small tool to generate WordPress content really quickly ! More than that, you can add Acf fields too !

It's based on François Zaninotto's [Faker PHP library](https://github.com/fzaninotto/Faker).

## Why?

I needed a small tool to seed my WordPress installations when developping new themes for customers. Having to manually write into each fields of some posts containing a lot of acf fields is such a pain...

That's why I did this tool which allows me to specify which post type I want to populate and assign dummy values to each fields.

By the way, WP-Faker works without Acf too.

## Installation

Just clone the project at the root of your wordpress install and install Faker with composer :

```
git clone https://github.com/alanpilloud/wp-faker.git
cd wp-faker
composer install
```

## Usage

All you have to do is editing the file config-sample.php to suit your needs and browse the wp-faker folder.

If you are curious about what type of data you can generate, visit the [Faker PHP library](https://github.com/fzaninotto/Faker), there is a full documentation on this page.

Then, browse to `http://yourdomain.com/wp-faker`. A preview of what you are going to generate is displayed. If you are satisfied with your configuration, then click on __Ok, let's go!__ to generate your first post.

The new post has been created, you can now create a new one or go back to the preview.

### Multiple configurations

If you need to run more than one configuration, make a new config file at the root
of your WP-Faker installation. It must be named after this pattern :

```
config-__name__.php
```

Then copy paste the content of config-sample.php and start editing. You will be able
to choose which file to use in the footer of the "ready" page.

### Working with images and files

The best way to work with images is to have a few of them already in your media library and assign their IDs in your configuration file :

```
$images = [241, 240, 239, 157, 156, 153];
shuffle($images);

$this->acf_values = array(
    'document_file' => 238
    'document_gallery' => $images
);

```

You can still use Faker to get an image, but you will have to handle the upload of files and retrieve the ID of each uploaded file.

### Repeater fields

This exemple will create to rows in a repeater field :

```
$this->acf_values = array(
    'my_repeater' => array(
        array(
            'title' => $faker->catchPhrase(),
            'url' => $faker->url(),
        ),
        array(
            'title' => $faker->catchPhrase(),
            'url' => $faker->url(),
        ),
    )
);
```

### Flex content fields

To use flex content fields, you must specify layout names. Here's an example that will insert two fields in a content :

```
$this->acf_values = array(
    'my_flex_content' => array(
        array(
            'video_url' => 'https://www.youtube.com/watch?v=8fiPgir_gFY',
            'acf_fc_layout' => 'video'
        ),
        array(
            'text' => $faker->text(),
            'acf_fc_layout' => 'title'
        ),
    )
);
```

### Cleaning the website of the dummy content

Wp-Faker adds a user named WpFakerUser. To remove all the dummy content (images too), simply delete the user and specify that you don't want to keep its content.

## Don't use it on your production server

This tool is meant to be used only for development, don't let anybody pollute your website !

## Todo
WP-Faker is still under developement and here is what I will have to do :
 - Pre-generate the $value array regarding the actual Acf config.
 - Being more userproof.
