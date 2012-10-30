Swift
=====

Swift is a module for [Kohana](http://github.com/kohana/), that integrates [Swiftmailer](http://github.com/swiftmailer/swiftmailer).

Example Usage
-------------

Just create an instance of the Email class, using its factory method and create a message instance.

```php
<?php
class Controller_Welcome extends Controller {
	public function action_index()
	{
		Email::factory()->send(Email::message('Hello','me@example.com','you@example.com','Hello there!'));
	}
}
```

The Email factory method by default loads the configuration for the SwiftMailer's Transport and Mailer classes from a config file's 'default' config group (the name can be configured), but can be customized either by a config group in swift.php config file, or by using a configuration array. Apart from the static factory method, also a static message method acts as a helper method that returns an instance of Swift_Message, in order to set the message's options (the native Swift_Message methods can be used, but the message method accepts also 4 optional parameters: the subject, from, to and body of the email). Additional options can be set by chaining (since message returns an instance of Swift_Message).

```php
<?php
	Email::message('Hello')->setFrom('me@example.com')->setTo('you@example.com');
```


