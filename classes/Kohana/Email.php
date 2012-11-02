<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Email library that uses Swiftmailer to send emails.
 *
 * @author Pantelis Vratsalis
 * @copyright 2012 Pantelis Vratsalis
 * @licence MIT
 */
abstract class Kohana_Email {

	const TRANSPORT_SMTP     = 10;
	const TRANSPORT_SENDMAIL = 20;
	const TRANSPORT_MAIL     = 30;

	// holds the default config group	
	const DEFAULT_CONFIG_GROUP = 'default';

	// holds the transport instance
	protected $transport;

	// holds the Swift_Mailer instance
	protected $mailer;

	// holds the Swift_Message instance
	protected $message;

	/**
     * The constructor accepts a configuration (either a config array or a string that
     * loads configuration from a config group). It sets the $transport and $mailer properties.
     *
     * @param $config mixed array or string (string holds the config group name)
     * @access public
     */
	public function __construct($config)
	{			
		$this->set_transport($config);
		$this->mailer = Swift_Mailer::newInstance($this->transport);
	}

	/**
	 * Sets the transport instance.
	 *
	 * @param $config mixed array or string (that holds the config group name)
	 * @access public
	 * @return void 
	 */
	public function set_transport($config)
	{
		if (!isset($config['transport']))
		{
			$config['transport'] = array('type' => self::TRANSPORT_MAIL);
		}

		switch ($config['transport']['type'])
		{
			case self::TRANSPORT_SENDMAIL:
				$this->transport = isset($config['transport']['options']['command']) 
					                     ? Swift_SendmailTransport::newInstance($config['transport']['options']['command'])
					                     : Swift_SendmailTransport::newInstance();				
				break;
			case self::TRANSPORT_MAIL:
				$this->transport = isset($config['transport']['options']['extra_params']) 
					                     ? Swift_MailTransport::newInstance($config['transport']['options']['extra_params'])
					                     : Swift_MailTransport::newInstance();
				break;
			case self::TRANSPORT_SMTP:
			default:
				$this->transport = Swift_SmtpTransport::newInstance();
				foreach ($config['transport']['options'] as $key => $value)
				{
					$func = 'set' . ucfirst($key);
					$this->transport->$func($value);
				}
				break;				
		}	
	}

	/**
	 * The factory method creates a new Email instance, based on a configuration array or
	 * string that holds the name of a config group in the config file.
	 *
	 * @param $config mixed array or string holding the config group name (default if none provided)
	 * @access public
	 * @static
	 * @return Email instance
	 */
	public static function factory($config = NULL)
	{
		if ($config === NULL)
		{
			$config_group = self::DEFAULT_CONFIG_GROUP;
		}
		elseif (!is_array($config))
		{
			$config_group = $config;
		}

		$config = isset($config_group) ? Kohana::$config->load('swift')->get($config_group) : $config;

		return new Email($config);
	}

	/**
	 * Creates and populates (optionally) a Swift_Message instance. It returns a
	 * Swift_Message instance, in order to allow chaining (in order to use the
	 * original methods of Swift_Message).
	 *
	 * @param $subject string (optional) the message's subject
	 * @param $from string (optional) the from header
	 * @param $to string (optional) the to header
	 * @param $body string (optional) the email body
	 * @access public
	 * @return Swift_Message instance
	 * @static
	 *
	 */
	public static function message($subject = NULL, $from = NULL, $to = NULL, $body = NULL)
	{		
		$message = Swift_Message::newInstance();

		if ($subject !== NULL)
		{
			$message->setSubject($subject);
		}

		if ($from !== NULL)
		{
			$message->setSubject($from);
		}

		if ($to !== NULL)
		{
			$message->setTo($to);
		}

		if ($body !== NULL)
		{
			$message->setBody($body);
		}

		return $message;
	}

	/**
	 * Sends the message that was previously created.
	 *
	 * @param $message Swift_Message the message
	 * @access public
	 * @return integer number of e-mails sent
	 */
	public function send(Swift_Message $message, &$failures = NULL)
	{
		return $this->mailer->send($message, $failures);
	}

}