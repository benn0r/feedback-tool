<?php

namespace Feedback;

abstract class Remote
{
	
	abstract public function put($title, $body, array $options);
	
}