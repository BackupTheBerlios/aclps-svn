<?php

class DataAccess_InputSanitizer
{
  public static function SanitizeInput($input)
  {
    return strip_tags($input);
  }
}
