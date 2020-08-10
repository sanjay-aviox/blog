<?php
/*
 Plugin Name: Internal Link Builder
 Plugin URI: http://www.sablab.it
 Description: Automate your internal link building strategy with this plugin!
 Version: 1.0
 Author: Alessandro Piconi - SabLab
 Author URI: http://www.sablab.it
 */

/*
 * se una parola è contenuta all'interno del link che viene 
 * pubblicato nella ricorsione della ricerca viene lavorato anche l'interno del tag
 * es la -> _b"la"nk - risolvere e poi decommentare ilb_maxuse
 * 
 * Se due parole uguali sono una di seguito all'altra commuta solo la prima.
 * 
 */

//Get article content and insert links
function ilb_insert_links( $text, $is_content = false ){
    $dictionary = get_option('ilb_dictionary');
    if( !is_array($dictionary) ) return $text;
    
    $ilb_maxuse = get_option('ilb_maxuse');
    $ilb_casesensitive = get_option('ilb_casesensitive');
    $ilb_target_blank = get_option('ilb_target_blank');
    $ilb_backlink = get_option('ilb_backlink');
    
    
    //$anysign = '([ \.,;:\'"\-_@#°=&%£!\^\\\\\$\?\+\*\|\(\[\{\}\]\)]|\A|\Z)';
    $anysign = ( $ilb_maxuse != 'si' ) ? '(\W|\A|\Z)' : '(.|\A|\Z|)';
 
    $target = ( $ilb_target_blank == 'si' ) ? ' target="_blank"' : '';
    
    foreach($dictionary as $lbl => $val){
        
        if( substr($val, 0, 7) != 'http://' )
            $val = 'http://'.$val;
        
        $reg = '/'.$anysign.'('.$lbl.')'.$anysign.'/';
        $expr_from[] = ( $ilb_casesensitive != 'si' ) ? $reg.'i' : $reg;
        $expr_to[] = '$1<a href="'.$val.'"'.$target.'>$2</a>$3';
    }
   
########### 
    $html = '';
    $tmp = array();
    //ESPLODO IL TESTO PER "<"
    $frammenti = explode( '<', $text );
  
    //CICLO I FRAMMENTI
    foreach( $frammenti as $cont => $frammento )
    {
        //se c'è un segno di chiusura potrebbe essere un tag, quindi elimino la parte compresa dalla lavorazione
        if( strpos( $frammento, '>' ) ){
            // SE NEL FRAMMENTO è PRESENTE UN ">" TRANCIO E LAVORO SOLO SULLE PARTI CHE NON SONO LA PRIMA
            $tmp = explode( '>', $frammento );
            $html .= $tmp[0].'>';
            unset( $tmp[0] );
            foreach( $tmp as $parte )
                $html .= preg_replace($expr_from, $expr_to, $parte);
                //$html .= strtr($parte, $dictionary);
            
        }else{
            $html .= preg_replace($expr_from, $expr_to, $frammento);
        }
        
        if( $cont+1 != count($frammenti) ) 
            $html .= '<';
    }
    
    if( $ilb_backlink == 'si' && $is_content )
        $html .= '<div style="clear: both; float: right; font-size: 0.8em;"><a href="http://www.sablab.it">Sablab Development</a></div>';
###########    
    return $html;
}

function ilb_config(){
    $selects = array(
        'ilb_maxuse' => array('no','si'),
        'ilb_casesensitive' => array('no','si'),
        'ilb_target_blank' => array('no','si'),
        'ilb_backlink' => array('no','si'),
    );    
    
    if( isset($_POST['submitted']) ){
        $n = ceil( count( $_POST ) / 2 );
        for( $k = 0; $k < $n; $k++ ){
            if( $_POST['key_'.$k] != '' ){
                $tmp[$_POST['key_'.$k]] = $_POST['url_'.$k];
            }
        }
        update_option('ilb_dictionary', $tmp);
        foreach( $selects as $lbl => $val )
            update_option($lbl, $_POST[$lbl]);
    }
    
    foreach( $selects as $field => $select ){
        $var_value = get_option($field);
        $sel[$field] = '<select name="'.$field.'">';
        foreach( $select as $option ){
            $selected = ( $option == $var_value ) ? ' selected="selected"' : '';
            $sel[$field] .= '<option value="'.$option.'"'.$selected.'>'.$option.'</option>';
        }
        $sel[$field] .= '</select>';
    }   
    
    $dictionary = get_option('ilb_dictionary');
    
    $html = '
        <div class="wrap">
            <h2>Internal Link Building</h2>
            <form name="example" method="post">
            
            <table>
                <!--<tr><td style="width: 120px;">Max use</td>
                <td style="width: 60px;">'.$sel['ilb_maxuse'].'</td><td>Extend search of keys inside any word</td></tr>
                -->
                <tr><td>Case Sensitive</td><td>'.$sel['ilb_casesensitive'].'</td><td>Enable "case sensitive rule" on the plugin</td></tr>
                <tr><td>Link Target Blank</td><td>'.$sel['ilb_target_blank'].'</td><td>Enable target="_blank" on links</td></tr>
                <tr><td>Back Link to developer\'s site</td><td>'.$sel['ilb_backlink'].'</td><td>Give us a backlink :)</td></tr>
            </table>

            <table class="wp-list-table widefat plugins" style="margin-bottom: 10px;">
            <tr><th class="column-name" style="width: 30px;">n</th>
                <th class="column-name" style="width: 250px;">Key</th>
                <th class="column-name">Url</th></tr>';
    $cont = 1;
    if( is_array($dictionary) && count( $dictionary ) > 0 ){
        foreach( $dictionary as $key => $url ){
            $html .= '<tr><td class="row-title">'.$cont.'</td>
                <td><input type="text" name="key_'.$cont.'" value="'.$key.'" /></td>
                <td><input type="text" name="url_'.$cont.'" value="'.$url.'" style="width: 450px;" /></td></tr>';
            $cont++;
        }
    }
    $html .= '  
                <tr><td class="row-title">'.$cont.'</td>
                    <td><input type="text" name="key_'.$cont.'" value="" /></td>
                    <td><input type="text" name="url_'.$cont.'" value=""  style="width: 450px;" /></td></tr>
                <input type="hidden" name="submitted" />
                </table>
                <input type="submit" value="Save Keys!" class="button-secondary action" />
            <form>
        </div>
        ';
    $html .= ilb_instructions();
    
    echo $html;    
}

function ilb_insert_links_comment( $text ){
    return ilb_insert_links( $text );
}

function ilb_insert_links_content( $text ){
    return ilb_insert_links( $text, true );
}

function ilb_instructions(){
    $html = '<p style="margin-top: 10px;">In order to delete a key/url couple, you must empty the url field and then save</p>';
    return $html;
}

function ilb_addlink(){
    add_menu_page('Internal Link Builder', 'Int. Link Builder', 'administrator', 'ilb_menu', 'ilb_config');
}

add_filter('the_content', 'ilb_insert_links_content');
add_filter('comment_text', 'ilb_insert_links_comment');
add_action('admin_menu', 'ilb_addlink');

?>
