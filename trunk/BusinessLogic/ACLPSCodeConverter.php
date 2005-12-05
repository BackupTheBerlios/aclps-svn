<?php

class BusinessLogic_ACLPSCodeConverter
{
    private static $simpleConversions = array(  '[b]'           => '<b>',
                                                '[/b]'          => '</b>',
                                                '[i]'           => '<i>',
                                                '[/i]'          => '</i>',
                                                '[u]'           => '<u>',
                                                '[/u]'          => '</u>',
                                                "\n"            => '<br />',
                                                '[title]'       => '<h1>',
                                                '[/title]'      => '</h1>',
                                                '[subtitle]'    => '<h2>',
                                                '[/subtitle]'   => '</h2>');
                                                
    public static function NewLineToBreak($content)
    {
        return str_replace("\n", '<br />', $content);
    }
    public static function ACLPSCodeToHTML($content)
    {

        //simple cases
        foreach(self::$simpleConversions as $search=>$replace)
        {
            $content = str_replace($search, $replace, $content);
        }
        
        //[img]URL[/img] => <img src=URL>
        while(strstr($content, '[img]'))
        {
        	$sub = strstr($content, '[img]');
        	$offset = strpos($sub, '[/img]');
        	if ($offset)
        	{
        		$URL = substr($sub, 5, $offset - 5);
        		$content = str_replace('[img]' . $URL . '[/img]', '<img src="http://' . $URL . '">' , $content);
        	}
        	else
        	{
        		$content = str_replace('[img]', '', $content);
        	}
        }
        
        //[link=URL]name[/link] => <a href=URL>name</a>
        while(strstr($content, '[link='))
        {
        	$sub = strstr($content, '[link=');
        	$offset = strpos($sub, ']');

        	if ($offset)
        	{
        		$URL = substr($sub, 6, $offset - 6);
        		$length = strlen('link=' . $URL . ']') + 1;

            	$offset = strpos($sub, '[/link]');

            	if ($offset)
            	{
                    $name = substr($sub, $length, $offset - $length);
                    $needle = '[link=' . $URL . ']' . $name . '[/link]';
                    $new_needle = '<a href=http://' . $URL . '>' . $name . '</a>';
                    $content = str_replace($needle, $new_needle, $content);
                }
        		else
        		{
                    $content = str_replace('[link=' . $URL .']', '', $content);
                }
        	}
        	else
        	{
        		$content = str_replace('[link=', '', $content);
        	}
        }
        
        return $content;
    }
}

?>
