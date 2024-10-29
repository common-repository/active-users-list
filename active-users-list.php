<?php
/**
 * Plugin Name: Active Users List
 * Description: This is a plugin which lists all the active/Logged in users.
 * Version:     1.0
 * Author:      Ambarish Chatterjee
 * Author URI:  https://github.com/ambarishchatterjee
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: active-users-list
 */

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I am just a plugin';
	exit;
}

    function aul_loggedInUsers(){
	
        if(is_admin_bar_showing()){
            
            global $wpdb;
            $users=$wpdb->get_results( 'SELECT * FROM wp_users where user_status="1"', 'ARRAY_A');
            echo '<h3>Active Users</h3>';
            echo '<ul class="aul_activeUsers">';
            foreach( $users as $user )
        { 
            echo '<li>'.$user['display_name'].'</li>';
        }
            echo '</ul>';
        }
    }    

add_shortcode( 'Available-Users', 'aul_loggedInUsers' );

    function aul_loggedInUsers_session()
    {
        global $wpdb;
        if(is_user_logged_in())
        {
            $user_id = get_current_user_id();
            $wpdb->query("UPDATE wp_users SET `user_status` = '1' WHERE `wp_users`.`ID` = $user_id;");
        }
         $user_id;
    }    
add_action('init','aul_loggedInUsers_session');

    function aul_loggedOutUsers_session() {
        global $wpdb;
            if(is_user_logged_in())
            {
                $user_id = get_current_user_id();
                $wpdb->query("UPDATE wp_users SET `user_status` = '0' WHERE `wp_users`.`ID` = $user_id;");
            }
         $user_id;
        }        
add_action('wp_logout', 'aul_loggedOutUsers_session');


// We need some CSS
function aul_active_users_css() {
	echo "
    <style type='text/css'>
    ul.aul_activeUsers {
        padding: 0;
    }
	ul.aul_activeUsers li {
        list-style: none;
        text-transform: capitalize;
    }
    ul.aul_activeUsers li:before {
        content: '';
        display: inline-block;
        width: 15px;
        height: 15px;
        -moz-border-radius: 7.5px;
        -webkit-border-radius: 7.5px;
        border-radius: 7.5px;
        background-color: #31ef31;
        margin-right: 10px;
    }

    /*chat*/
    {box-sizing: border-box;}

/* Button used to open the chat form - fixed at the bottom of the page */
.open-button {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

/* The popup chat - hidden by default */
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

/* Full-width textarea */
.form-container textarea {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 200px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container .btn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}

    </style>
	";
}

add_action( 'wp_head', 'aul_active_users_css' );

function aul_chatbox_html(){
    echo '<div class="chat-popup" id="myForm">
    <form action="/action_page.php" class="form-container">
      <h1>Chat</h1>
  
      <label for="msg"><b>Message</b></label>
      <textarea placeholder="Type message.." name="msg" required></textarea>
  
      <button type="submit" class="btn">Send</button>
      <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
    </form>
  </div>
  <script>
  function openForm() {
    document.getElementById("myForm").style.display = "block";
  }
  
  function closeForm() {
    document.getElementById("myForm").style.display = "none";
  }
      </script>
      ';
}
add_action( 'wp_footer', 'aul_chatbox_html' );