<?php
/****************************************************
 * Lean mean web machine
 *
 * Basic mailer library
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-22
 *
 ****************************************************/

require_once FRAMEWORK_PATH.'libraries/Swift-4.0.6/lib/swift_required.php';
class MailerBase extends Swift
{

	/**
	 * Prepare message using array with options
	 *
	 * @param array $options
	 *		to: string or email => name pairs with receivers (required)
	 *		subject: string with email subject (required)
	 *		template: path to html template (required)
	 *		bcc: string or email => name pairs with BCC receivers
	 *		from: string or email => name pair with sender
	 *		data: template data
	 *		replyTo: string or email => name pair with reply to address
	 *
	 * @return Swift_Message
	 */
	public static function prepareMessage($options){
		
		// Create message
		$message = Swift_Message::newInstance();
		
		// Set to address
		$message->setTo($options['to']);
		
		// Set subject
		$message->setSubject($options['subject']);
		
		// Parse template
		$view = new View();
		$data = isset($options['data'])? $options['data'] : array();
		$data['templatePath'] = APPLICATION_PATH.'views/mails/';
		$data['config'] = get_class_vars('ApplicationConfig');
		$template = $view->get(APPLICATION_PATH.'views/mails/'.$options['template'], $data);
		$message->setBody($template, 'text/html');
		
		// Set bcc
		if(isset($options['bcc'])){
			$message->setBcc($options['bcc']);
		}
		
		// Set from address
		if(isset($options['from'])){
			$message->setFrom($options['from']);
		}else{
			$message->setFrom(array(ApplicationConfig::$mailSenderEmail => ApplicationConfig::$mailSenderName));
		}
		
		// Set reply to address
		if(isset($options['replyTo'])){
			$message->setReplyTo($options['replyTo']);
		}
		
		return $message;		
	}
	
	/**
	 * Send message using PHP's native mail() function
	 *
	 * @param Swift_Message $message
	 * @return int Number of successful recipients
	 */
	public static function sendMessage($message){
		$transport = Swift_MailTransport::newInstance();
		$mailer = Swift_Mailer::newInstance($transport);
		return $mailer->send($message);
	}

}