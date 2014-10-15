<?php
/***************************************************************/
/**
 * Wordpress Markdown Generator
 * Authored by Derry Livenski
 * description: Uses wordpress backend to generate markdown files
 * Site: http://derryspann.com
 * For more information https://github.com/hyperking/Wordpress-Markdown-Generator-for-Static-Sites
 */

function markdownGenerator(){

  // SPECIFY ACTION TO EXECUTE FILE GENERATION
  add_action('save_post', "create_markdown_files");
  add_action('publish_post', "create_markdown_files");
  add_action("publish_page", "create_markdown_files"); 
  // SPECIFY ACTION TO EXECUTE FILE GENERATION

  function create_markdown_files() { 

  // DEFINE THE DIRECTORY NAME TO CONTAIN THE GENERATED FILES, BASED ON URL  
  $sitename = $GLOBALS['sitename'];
  $dir = ABSPATH.'/sites/'.$sitename.'/content/';
    if(!function_exists('deleteDirectory')){
    function deleteDirectory($dir) { 
        
        if (!file_exists($dir)) return true; 
        if (!is_dir($dir) || is_link($dir)) return unlink($dir); 
            foreach (scandir($dir) as $item) { 
                if ($item == '.' || $item == '..') continue; 
                if (!deleteDirectory($dir . "/" . $item)) { 
                    chmod($dir . "/" . $item, 0777); 
                    if (!deleteDirectory($dir . "/" . $item)) return false; 
                }; 
            } 
            return rmdir($dir); 
        } 
    }
    deleteDirectory($dir);

    // BEGIN QUERY FOR POSTS AND PAGES
    // DEFINE YOUR CUSTOM POST TYPES HERE TO HAVE THEM OUTPUT INTO MARKDOWN
    $postsForMarkdown = get_posts(array(
         'numberposts' => -1,   
         'meta-key' => '',
         'meta-value'  => '',
         'post_status' => array( 'future','publish','draft','private'),
         'post_type'  => array('post','page'),
    ));   


    foreach($postsForMarkdown as $post) {
        setup_postdata( $post );

// GET ALL POSTS CATEGORIES
        $categories = wp_get_post_categories( $post->ID, array( 'fields' => 'names' ) );
        $cats = implode('/', str_replace('_', '', $categories));
// GET POST TAGS
        $t = wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) );
        $tags = implode(',', $t);
        $tax_args = array(
        'public' => true,
        '_builtin' => false
          );
        $taxes = get_taxonomies( $tax_args, 'names' );
        $taxonomies = $taxes;


// BELOW ARE FUNCTIONS FOR PROCESSING EACH POSTS/PAGE
if(!function_exists('getStatus')){
    function getStatus($foo){
        if (get_post_status($foo) == 'draft'){
            return 'Status:draft'. "\n";
        }elseif(get_post_status($foo) == 'private'){
            return 'Status: hidden'."\n";
        }else{
            return false;
        }

    }
}

if(!function_exists('getParent')){
    function getParent($foo){
        if($foo->post_parent){
          $poststr = get_the_title($foo->post_parent);
            return 'childof:'.str_replace(' ','-',strtolower($poststr))."\n";
        }else {
            return 'isparent:'."\n";
        }
    }
}

if(!function_exists('gettheDate')){
  function gettheDate($foo){
  if( get_post_type($foo) == 'events' ){
    // USE THE EVENT DATE VALUE TO SHOW EVENTS IN PROPER ORDER
    return 'Date:'.get_post_meta( $foo->ID,'start_date')[0]."\n";
  }else{
    return 'Date:'. $foo->post_date."\n";
  }
  }
}


if(!function_exists('saveAs')){
    function saveAs($foo){
        if(get_post_meta( $foo,'save_as')[0] == true){
            return 'Save_as:'.get_post_meta( $foo,'save_as')[0]."\n";
        }else{
            return false;
        }
    }   
}
if(!function_exists('getTheTerms')){
function getTheTerms( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {
  $terms = get_the_terms( $id, $taxonomy );
  if ( is_wp_error( $terms ) )
    return $terms;
  if ( empty( $terms ) )
    return false;

  foreach ( $terms as $term ) {
    $link = get_term_link( $term, $taxonomy );
    if ( is_wp_error( $link ) )
      return $link;
    $term_links[] = $term->name ;
  }

  $term_links = apply_filters( "term_links-$taxonomy", $term_links );
  return join( $sep, str_replace('_','',$term_links) );
} 

}



// Currenty not sure how to retrieve all custom taxonomies for custom post types.
// This is a hard coded array of types
$thumburl = 'http://cloud.'.$GLOBALS['sitename'].'.'.$GLOBALS['extension'].'/gallery/'.$post->post_name;
$data =       
'Title:'. $post->post_title."\n".      
gettheDate($post).
'id:'.$post->ID."\n".
getStatus($post->ID).
saveAs($post->ID). 
'Category:'.$cats."\n".
'Tags:'.$tags."\n".
// 'Category:'.$cats."\n".
'Author:'.get_the_author($post->ID, 'page')."\n".
getParent($post).
'summary:'.str_replace(array("\r", "\n", "\r\n"), "",get_the_excerpt())."\n".
'--------MEDIA_DATA----------:'."\n".
'featuredimage:'.wp_get_attachment_url( get_post_thumbnail_id($post->ID) )."\n".
// 'featuredthumb:'.str_replace('150x150','thumbnail',wp_get_attachment_thumb_url( get_post_thumbnail_id($post->ID),'thumbnail' ))."\n".
'featuredthumb:'.str_replace($thumburl,$thumburl.'/thumbnail',wp_get_attachment_thumb_url( get_post_thumbnail_id($post->ID) ))."\n".

'<!-- CONTENT BELOW THIS POINT -->'."\n".     
$post->post_content."\n";

// END MARKDOWN OUTPUT




        
         
    if ($post->post_type == 'page'){
        $directoryPath = ABSPATH.'/sites/'.$sitename.'/content/pages/'.getParent($post);
    }elseif ($post->post_type == 'post') {
        $directoryPath = ABSPATH.'/sites/'.$sitename.'/content/posts/'.$cats.'/';
    }
    $mdPath = $directoryPath.'/'.str_replace(' ','-',$post->post_title).".md";
    // $textPath = $directoryPath.'/'.str_replace(' ','-',$post->post_title).".txt";

    if(!is_dir($directoryPath)){
        mkdir($directoryPath,0777,true);
    }
    if(!file_exists($mdPath)){
        fopen( $mdPath, 'w');
    }
    if(file_exists($mdPath)){
        $fp = fopen( $mdPath, 'w');
        fwrite($fp, $data);
        fclose($fp);
    }

    }
    } 
}
markdownGenerator();
