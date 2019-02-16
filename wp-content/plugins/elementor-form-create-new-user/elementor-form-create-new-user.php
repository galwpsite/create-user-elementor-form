<?php
/**
 * Plugin Name: Elementor Form Create New User
 * Description: Create a new user using elementor form
 * Author:      Gal Hadad
 * Author URI:  https://wpsite.co.il
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

add_action( 'elementor_pro/forms/new_record',  'thewpchannel_elementor_form_create_new_user' , 10, 2 );

function thewpchannel_elementor_form_create_new_user($record,$ajax_handler)
{
    $form_name = $record->get_form_settings('form_name');
    //Check that the form is the "create new user form" if not - stop and return;
    if ('Create New User' !== $form_name) {
        return;
    }
    $form_data = $record->get_formatted_data();
    $username=$form_data['User Name']; //Get tne value of the input with the label "User Name"
    $password = $form_data['Password']; //Get tne value of the input with the label "Password"
    $email=$form_data['Email'];  //Get tne value of the input with the label "Email"
    $user = wp_create_user($username,$password,$email); // Create a new user, on success return the user_id no failure return an error object
    if (is_wp_error($user)){ // if there was an error creating a new user
        $ajax_handler->add_error_message("Failed to create new user: ".$user->get_error_message()); //add the message
        $ajax_handler->is_success = false;
        return;
    }
    $first_name=$form_data["First Name"]; //Get tne value of the input with the label "First Name"
    $last_name=$form_data["Last Name"]; //Get tne value of the input with the label "Last Name"
    wp_update_user(array("ID"=>$user,"first_name"=>$first_name,"last_name"=>$last_name)); // Update the user with the first name and last name
}