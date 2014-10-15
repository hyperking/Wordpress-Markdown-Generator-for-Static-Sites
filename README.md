Wordpress-Markdown-Generator-for-Static-Sites
===========

Generate markdown files with every post to your current wordpress site local or live.

The Benefits
------------

 - Data is always stored in a database in case you wish to make your site dynamic from static.
 - Easy to use GUI to update, edit, and delete posts used in your static website
 - Generating static sites use less resources when served to client
 - Generated markdown files can be easily read by humans

Markdown Format
-----------------------------
Markdown is a simple flat file containing information for pages on a website. More can be learned at this link
http://whatismarkdown.com/

    Title:Hello World
    Date:2014-10-08 19:24:47
    id:1146
    Category: Category Name/Sub-Category Name
    Tags: tag1, tag2, tag3
    Author: wpadmin
    isparent:
    summary:Lorem ipsum dolor sit amet, consectetur 
    --------MEDIA_DATA----------:
    featuredimage:
    featuredthumb:
    <!-- CONTENT BELOW THIS POINT -->
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi ab minus et nihil eveniet facilis temporibus nobis nulla, repudiandae, molestias ducimus quidem architecto, aliquam quis ullam perspiciatis omnis voluptatum vero.


Why use this Generator?
-----------------------------
There are several online authoring tools used to create and update markdown files. However, this small script will help keep your data organized in a persistent manner to a database.

Not only that, but using wordpress backend to author your posts without the hassle of knowing markdown short codes are helpful.

**Note: You can also use the flexibility of wordpress custom fields and post types to format you markdown meta data.**

Installation Instructions
=====================

1.Copy/Paste file into your active theme folder
----------
> -copy HyperWPMarkdown.php into your wp-content/themes/my-theme-name/ directory


2.Include file
---------
open your theme's 'function.php' and paste the line below 
>  require_once locate_template('HyperWPMarkdown.php');   

3. COMPLETED!!
---------
You can now log into wordpress backend and start adding new posts and pages. A folder will be generated in your root wordpress directory labeled 'sites'.

This folder will include a content directory containing 'posts' and 'pages' with posts organized and nested according to hierarchy and category.

