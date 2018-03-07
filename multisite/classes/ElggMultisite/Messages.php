<?php

namespace ElggMultisite {
    
    class Messages {
	
	
	public static function addMessage($message) {
	    
	    if (empty($_SESSION['messages']))
		$_SESSION['messages'] = [];
	    
	    if (!in_array($message, $_SESSION['messages']))
		$_SESSION['messages'][] = $message;
	}
	
	public static function getMessages() {
	    
	    
	    // Get messages
	    $messages = $_SESSION['messages'];
	    
	    // Clear messages
	    unset($_SESSION['messages']);
	    $_SESSION['messages'] = [];
	    
	    return $messages;
	}
    }
}